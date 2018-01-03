<?php

class AdminController extends RController {

    public $defaultAction = 'index';
    public $layout = '//layouts/main';
    private $_model;

    /**
     * @return array action filters
     */
    public function filters() {
        return CMap::mergeArray(parent::filters(), array(
                    'accessControl', // perform access control for CRUD operations
        ));
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('refreshschema'),
                'users' => array('*')
            ),
            array('allow',
                'users' => array('@')
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('impersonate','impersonateback'),
                'users' => Yii::app()->getModule('rights')->getAuthorizer()->getSuperusers(),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('index', array(
            'model' => $model,
        ));
        /* $dataProvider=new CActiveDataProvider('User', array(
          'pagination'=>array(
          'pageSize'=>Yii::app()->controller->module->user_page_size,
          ),
          ));

          $this->render('index',array(
          'dataProvider'=>$dataProvider,
          ));// */
    }

    /**
     * Displays a particular model.
     */
//    public function actionView() {
//        $model = $this->loadModel();
//        $this->render('view', array(
//            'model' => $model,
//        ));
//    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new User;
        $profile = new Profile;
//        $this->performAjaxValidation(array($model, $profile));
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->activkey = Yii::app()->controller->module->encrypting(microtime() . $model->password);
            $profile->attributes = $_POST['Profile'];

            $profile->user_id = 0;
            if ($model->validate() && $profile->validate()) {
                $model->password = Yii::app()->controller->module->encrypting($model->password);
                if ($model->save()) {
                    $profile->user_id = $model->id;
                    $profile->save();
                }
                Yii::app()->user->setFlash('successMessage', 'Created User successful');
                $this->redirect(array('update', 'id' => $model->id));
            } else {
                $profile->validate();
            }
        }

        $this->render('create', array(
            'model' => $model,
            'profile' => $profile,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate() {
        $model = $this->loadModel();
        $profile = $model->profile;
        $this->performAjaxValidation(array($model, $profile));
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $profile->attributes = $_POST['Profile'];

            if (!isset($_POST['Profile']['arr_department'])) {
                $profile->arr_department = 0;
            }

            if (!isset($_POST['Profile']['arr_shop_id'])) {
                $profile->arr_shop_id = array();
            }

            if ($model->validate() && $profile->validate()) {
                $old_password = User::model()->notsafe()->findByPk($model->id);
                if ($old_password->password != $model->password) {
                    $model->password = Yii::app()->controller->module->encrypting($model->password);
                    $model->activkey = Yii::app()->controller->module->encrypting(microtime() . $model->password);
                }
                $model->save();
                $profile->save();
                if($model->id == Yii::app()->user->id){
                    Yii::app()->user->updateSession();
                }
                Yii::app()->user->setFlash('successMessage', 'Updated User successful');

                $this->redirect(array('update', 'id' => $model->id));
            } else {
                $profile->validate();
            }
        }

        $this->render('update', array(
            'model' => $model,
            'profile' => $profile,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete() {

        if (Yii::app()->request->isPostRequest) {

            // we only allow deletion via POST request
            $model = $this->loadModel();

            $transaction = Yii::app()->db->beginTransaction();

            try {

                $userId = $model->id;
                $model->delete();

                $profile = Profile::model()->findByPk($userId);
                $profile->delete();

                $transaction->commit();
            } catch (Exception $e) {

                $transaction->rollback();

                $model->status = User::STATUS_DELETED;
                $model->save();
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax']))
                $this->redirect(array('/user/admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($validate) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($validate);
            Yii::app()->end();
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel() {
        if ($this->_model === null) {
            if (isset($_GET['id']))
                $this->_model = User::model()->notsafe()->findbyPk($_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }

    public function actionOnline() {

        $dataProvider = new CActiveDataProvider('User', array(
            'criteria' => array(
                'select' => 'user.*, ss.last_activity',
                'join' => 'INNER JOIN `Session` AS ss ON user.id=ss.user_id',
                'condition' => 'user.status>:status AND ss.expire>:current_time',
                'params' => array(
                    ':status' => User::STATUS_NOACTIVE,
                    ':current_time' => new CDbExpression('NOW()')
                )
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->getModule('user')->user_page_size,
            ),
            'sort' => array(
                'defaultOrder' => 'ss.last_activity desc'
            ),
        ));

        $this->render('online', array(
            'dataProvider' => $dataProvider
        ));
    }

    public function actionImpersonate($id) {

        $user = User::model()->findByPk($id);

        if ($user !== null) {

            Yii::app()->user->changeIdentity($user->id, $user->email, array(
                'originId' => Yii::app()->user->id,
                'originName' => Yii::app()->user->name
            ));
        }

        $this->redirect('/dashboard');
    }

    public function actionImpersonateBack() {

        $originId = Yii::app()->user->getState('originId');
        $originName = Yii::app()->user->getState('originName');

        if (!empty($originId) && !empty($originName)) {

            Yii::app()->user->changeIdentity($originId, $originName, array());

            $this->redirect('/dashboard');
        } else {
            Yii::app()->user->logout();
            $this->redirect(Yii::app()->controller->module->returnLogoutUrl);
        }
    }

//    public function actionTest(){
//        Yii::app()->session->clearOldSessions();
//    }

    public function actionRefreshSchema() {
        Yii::app()->db->schema->getTables();
        Yii::app()->db->schema->refresh();
        echo 'cache refreshed.';
        Yii::app()->end();
    }

}
