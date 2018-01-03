<?php

class ShopController extends RController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    public $defaultAction = 'admin';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
//    public function actionView($id) {
//        $this->render('view', array(
//            'model' => $this->loadModel($id),
//        ));
//    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {

        $this->checkAccess('Shop_Create', array(), true);

        $model = new Shop;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Shop'])) {
            $model->attributes = $_POST['Shop'];
            if ($model->save()) {
                Yii::app()->user->setFlash('successMessage', 'Created Shop successful');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $this->checkAccess('Shop_View', array(), true);

        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Shop'])) {

            $this->checkAccess('Shop_Update', array(), true);

            $model->attributes = $_POST['Shop'];
            if ($model->save()) {
                Yii::app()->user->setFlash('successMessage', 'Updated Shop successful');

                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {

        $this->checkAccess('Shop_Delete', array(), true);

        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Lists all models.
     */
//    public function actionIndex() {
//        $dataProvider = new CActiveDataProvider('Shop');
//        $this->render('index', array(
//            'dataProvider' => $dataProvider,
//        ));
//    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {

        $this->checkAccess('Shop_List', array(), true);

        $model = new Shop('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Shop'])) {
            $model->attributes = $_GET['Shop'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Shop the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Shop::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Shop $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'shop-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function renderActionButton($data, $row, $dataColumn) {
        $buttons = array();

        if ($this->checkAccess("Shop_Delete", array())) {
            $buttons[] = array(
                'label' => 'Delete',
                'url' => Yii::app()->createUrl("/shop/shop/delete", array("id" => $data->id)),
                'icon' => TbHtml::ICON_TRASH,
                'htmlOptions' => array('linkOptions' => array('class' => 'delete', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if (count($buttons) > 0) {
            echo TbHtml::buttonDropdown('', $buttons, array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        }
    }

}
