<?php

class AdminController extends RController
{
    public $type = '';
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    public $defaultAction = 'list';

    /**
     * @return array action filters
     */
    public function filters()
    {
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
    public function accessRules()
    {
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
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Alert;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Alert'])) {
            $model->attributes = $_POST['Alert'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
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
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Alert'])) {
            $model->attributes = $_POST['Alert'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
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
    public function actionDelete($id)
    {
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
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Alert');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionList($type = '')
    {
        $this->type = $type;
        $model = new Alert('search');
        $model->unsetAttributes();  // clear any default values
        switch ($type) {
            case 'noncritical':
                $model->status = Alert::STATUS_NORMAL;
                $model->stage = Alert::STAGE_ACTIVE;
                break;
            case 'critical':
                $model->status = Alert::STATUS_CRITICAL;
                $model->stage = Alert::STAGE_ACTIVE;
                break;
            case 'highlycritical':
                $model->status = Alert::STATUS_HIGH_CRITICAL;
                $model->stage = Alert::STAGE_ACTIVE;
                break;
            default:

                break;
        }
        $this->render('list', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Alert the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Alert::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Alert $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'alert-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionBytask($id, $alert_id = null)
    {

        $this->checkAccess('Alert_Create', array(), true);

        $task = TaskProcess::model()->findByPk($id);

        if ($task === null) {
            throw new CHttpException(404, 'Not found');
        }


        if (isset($_POST['delete']) && isset($_GET['alert_id'])) {

            $alert = Alert::model()->findByPk((int)$_GET['alert_id']);

            if ($alert == null) {
                $this->notfoundException();
            }

            $alert->delete();

            $alert = new Alert();

        } else if (isset($_GET['alert_id'])) {
            $alert = Alert::model()->findByPk((int)$_GET['alert_id']);
            if ($alert == null) {
                $this->notfoundException();
            }
        } else {
            $alert = new Alert();
        }

        if (isset($_POST['Alert']) && !isset($_POST['delete'])) {
            $alert->to_users = $_POST['Alert']['to_users'];
            $alert->alert_type = $_POST['Alert']['alert_type'];
            $alert->note = $_POST['Alert']['note'];
            $alert->status = $_POST['Alert']['status'];

            if ($alert->isNewRecord) {
                $alert->create_by = Yii::app()->user->id;
                $alert->stage = Alert::STAGE_ACTIVE;
                $alert->related_task_id = $task->id;
            }

            if ($alert->save()) {
                Yii::app()->user->setFlash('successMessage', 'Alert has been saved.');
                $alert = new Alert();

                Yii::app()->clientScript->registerScript('changeTab', "$('#alertTabs a[href=\"#alerts\"]').tab('show');", CClientScript::POS_READY);
            }
        }

        $dataProvider = Alert::model()->findAllByTaskId($id);

        $id = 'alert-grid';
        if (!isset($_GET['ajax'])) {
            $id .= '-' . time();
        } else {
            $id = $_GET['ajax'];
        }

        $html = $this->renderPartial('alert.views.admin._bytask', array(
            'dataProvider' => $dataProvider,
            'task' => $task,
            'alert' => $alert,
            'idGridview' => $id
        ), true, false);


        $jsInline = '';

        foreach (Yii::app()->clientscript->scripts as $k => $js) {
            if (array_key_exists('changeTab', $js)) {
                $jsInline .= "setTimeout(function(){" . $js['changeTab'] . "},100);";
                unset($js['changeTab']);
            }

            $jsInline .= implode('', $js);

        }

        echo $html . CHtml::script($jsInline);


        Yii::app()->end();
    }
}