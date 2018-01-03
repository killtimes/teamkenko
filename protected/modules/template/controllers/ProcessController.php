<?php

class ProcessController extends RController {

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

        $this->checkAccess('ProcessTemplate_Create', array(), true);

        $model = new ProcessTemplate;
        $shopId = Profile::model()->getShopId(Yii::app()->user->id);
        if (!empty($shopId)) {
            $model->shop_id = $shopId;
        }

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['ProcessTemplate'])) {
            $model->attributes = $_POST['ProcessTemplate'];
            if ($model->save()) {
                Yii::app()->user->setFlash('successMessage', 'Created Template successful');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function checkUpdatePermission($model, $throw = true) {

        $hasGlobalPermission = $this->checkAccess('ProcessTemplate_Update', array());

        if ($hasGlobalPermission) {
            return true;
        }

        $hasShopPermission = $this->checkAccess('ProcessTemplate_OwnUpdate', array());
        $shopId = Profile::model()->getShopId(Yii::app()->user->id);
        if ($hasShopPermission && $model->shop_id == $shopId) {
            return true;
        }

        if ($throw) {
            $this->accessDenied();
        }

        return FALSE;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $model = $this->loadModel($id);

        $this->checkViewPermission($model);


        $modelTaskProcess = new TaskProcessTemplate();
        $modelTaskProcess->process_id = $model->id;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['ProcessTemplate'])) {

            $this->checkUpdatePermission($model);

            $model->attributes = $_POST['ProcessTemplate'];

            if (!isset($_POST['ProcessTemplate']['arr_start_dayofweek'])) {
                $model->arr_start_dayofweek = false;
            }

            if ($model->validate()) {
                
                $tran = Yii::app()->db->beginTransaction();

                try {

                    if ($model->save(false)) {

                        $tran->commit();

                        Yii::app()->user->setFlash('successMessage', 'Updated Template successful');

                        $this->redirect(array('update', 'id' => $model->id));
                    } else {
                        throw new CDbException('Save template failed:' . CVarDumper::dumpAsString($model->errors));
                    }
                } catch (Exception $e) {
                    $tran->rollback();
                    throw $e;
                }
            }
        }

        if (Yii::app()->request->isAjaxRequest) {

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'add-task-form') {
                echo CActiveForm::validate($modelTaskProcess);
                Yii::app()->end();
            }

            if (isset($_POST['TaskProcessTemplate'])) {

                $modelTaskProcess->attributes = $_POST['TaskProcessTemplate'];
                $modelTaskProcess->process_id = $model->id;
                if ($modelTaskProcess->save()) {

                    header('Content-type: application/json');
                    echo CJSON::encode(array('result' => 1));
                }

                Yii::app()->end();
            }
        }

        $this->render('update', array(
            'model' => $model,
            'modelTaskProcess' => $modelTaskProcess
        ));
    }

    public function checkDeletePermission($model, $throwException = TRUE) {

        $hasGlobalPermission = $this->checkAccess('ProcessTemplate_Delete', array());

        if ($hasGlobalPermission) {
            return true;
        }

        $hasShopPermission = $this->checkAccess('ProcessTemplate_OwnDelete', array());
        $shopId = Profile::model()->getShopId(Yii::app()->user->id);
        if ($hasShopPermission && $model->shop_id == $shopId) {
            return true;
        }

        if ($throwException) {
            $this->accessDenied();
        }

        return false;
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {

            // we only allow deletion via POST request
            $model = $this->loadModel($id);
            $this->checkDeletePermission($model);

            $model->delete();

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
//        $dataProvider = new CActiveDataProvider('ProcessTemplate');
//        $this->render('index', array(
//            'dataProvider' => $dataProvider,
//        ));
//    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {

        $model = new ProcessTemplate('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ProcessTemplate'])) {
            $model->attributes = $_GET['ProcessTemplate'];
        }

        $this->checkAccess('ProcessTemplate_List', array(), true);
//        $shopId = Profile::model()->getShopId(Yii::app()->user->id);
//        if ($shopId > 0) {
//            $model->shop_id = $shopId;
//        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function checkListPermission($throw = true) {

        return $this->checkAccess('ProcessTemplate_List', array(), !$throw);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ProcessTemplate the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = ProcessTemplate::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ProcessTemplate $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'process-template-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionDeleteTask($id) {
        if (Yii::app()->request->isPostRequest) {
            $taskProcess = TaskProcessTemplate::model()->findByPk($id);
            if ($taskProcess !== null) {

                $process = $taskProcess->process;

                if ($process === null) {
                    throw new CHttpException(404, 'Not found');
                }

                $this->checkUpdatePermission($process);

                $taskProcess->delete();
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    protected function performAjaxValidationCreateTask($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'edit-task-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionCreateTask($id) {

        $process = $this->loadModel($id);
        $this->checkUpdatePermission($process);

        $taskProcess = new TaskProcessTemplate;
        $taskProcess->process_id = $process->id;

        //ajax validate on submit
        $this->performAjaxValidationCreateTask($taskProcess);

        if (Yii::app()->request->isAjaxRequest && isset($_POST['TaskProcessTemplate'])) {

            $taskProcess->attributes = $_POST['TaskProcessTemplate'];

            if ($taskProcess->save()) {
                $taskProcess = new TaskProcessTemplate;
            } else {
                echo CActiveForm::validate($taskProcess);
                Yii::app()->end();
            }
        }

        $html = $this->renderPartial('update_taskprocess', array(
            'model' => $taskProcess,
            'modelProcess' => $process
                ), true, false);

        $jsArr = array_values(Yii::app()->clientscript->scripts);

        $js = '';

        foreach ($jsArr as $k => $arr) {

            $js.= CHtml::script(implode("\n", $arr));
        }

        header('Content-type: application/json');

        echo CJSON::encode(array(
            'data' => $html,
            'script' => $js
        ));

        Yii::app()->end();
    }

    public function actionUpdateTask($id) {

        $taskProcess = TaskProcessTemplate::model()->findByPk($id);

        if ($taskProcess === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $process = $taskProcess->process;

        $this->checkUpdatePermission($process);

        //ajax validate on submit
        $this->performAjaxValidationCreateTask($taskProcess);

        if (Yii::app()->request->isAjaxRequest && isset($_POST['TaskProcessTemplate'])) {

            $taskProcess->attributes = $_POST['TaskProcessTemplate'];

            if (!$taskProcess->save()) {
                echo CActiveForm::validate($taskProcess);
                Yii::app()->end();
            }
        }

        $html = $this->renderPartial('update_taskprocess', array(
            'model' => $taskProcess,
            'modelProcess' => $taskProcess->process
                ), true, false);

        $jsArr = array_values(Yii::app()->clientscript->scripts);
        $js = '';
        foreach ($jsArr as $k => $arr) {
            $js.= CHtml::script(implode("\n", $arr));
        }

        header('Content-type: application/json');

        echo CJSON::encode(array(
            'data' => $html,
            'script' => $js
        ));

        Yii::app()->end();
    }

    public function actionSortTask($id) {
        if (Yii::app()->request->isAjaxRequest === true && isset($_POST['result'])) {

            $model = ProcessTemplate::model()->findByPk($id);

            if ($model === NULL) {
                throw new CHttpException(404, 'Not found');
            }

            $this->checkUpdatePermission($model);


            $sql = 'UPDATE `TaskProcessTemplate` SET `sort_order`= CASE `id` ';
            $idArr = array();
            foreach ($_POST['result'] as $order => $idt) {
                $idt = intval($idt);
                $idArr[] = $idt;
                $sql.=' WHEN ' . $idt . ' THEN ' . intval($order);
            }
            $sql.=' END ';
            $sql.=' WHERE `process_id`=' . intval($id) . ' AND `id` IN (' . implode(',', $idArr) . ')';

            Yii::app()->db->createCommand($sql)->execute();
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDuplicate($id) {

        if (Yii::app()->request->isAjaxRequest) {

            $model = ProcessTemplate::model()->findByPk($id);

            if ($model === null) {
                throw new CHttpException(404, 'Not found');
            }

            $tasks = $model->taskProcessTemplates;

            $model->name .= ' copy';
            $model->scenario = 'insert';
            $model->isNewRecord = true;
            unset($model->id);

            $db = Yii::app()->db->beginTransaction();

            try {

                if ($model->save()) {

                    if (!empty($tasks)) {
                        foreach ($tasks as $task) {

                            $alertRecipients = $task->getAlertRecipientTemplate();
                            unset($task->id);
                            $task->scenario = 'insert';
                            $task->isNewRecord = true;
                            $task->process_id = $model->id;
                            $task->alert_recipients = implode(',', $alertRecipients);
                            if (!$task->save()) {

                                throw new CDbException('Save task failed');
                            }
                        }
                    }

                    $db->commit();
                } else {
                    throw new CDbException('Save template failed');
                }
            } catch (Exception $e) {
                $db->rollback();

                throw $e;
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    protected function renderActionButton($data, $row, $dataColumn) {

        $buttons = array();

        if ($this->checkDuplicatePermission($data)) {
            $buttons[] = array(
                'label' => 'Duplicate',
                'url' => Yii::app()->createUrl("/template/process/duplicate", array("id" => $data->id)),
                'icon' => TbHtml::ICON_DUPLICATE,
                'htmlOptions' => array('linkOptions' => array('class' => 'duplicate', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if ($this->checkDeletePermission($data, false)) {
            $buttons[] = array(
                'label' => 'Delete',
                'url' => Yii::app()->createUrl("/template/process/delete", array("id" => $data->id)),
                'icon' => TbHtml::ICON_TRASH,
                'htmlOptions' => array('linkOptions' => array('class' => 'delete', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if (count($buttons) > 0) {
            echo TbHtml::buttonDropdown('', $buttons, array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        }
    }

    public function checkViewPermission($model) {

        $hasGlobalPermission = $this->checkAccess('ProcessTemplate_View', array());

        if ($hasGlobalPermission) {
            return true;
        }

        $hasShopPermission = $this->checkAccess('ProcessTemplate_OwnView', array());
        $shopId = Profile::model()->getShopId(Yii::app()->user->id);
        if ($hasShopPermission && $model->shop_id == $shopId) {
            return true;
        }

        $this->accessDenied();
    }

    public function checkDuplicatePermission($model, $throw = false) {

        $hasGlobalPermission = $this->checkAccess('ProcessTemplate_View', array());

        if ($hasGlobalPermission) {
            return true;
        }

        $hasShopPermission = $this->checkAccess('ProcessTemplate_OwnView', array());
        $shopId = Profile::model()->getShopId(Yii::app()->user->id);
        if ($hasShopPermission && $model->shop_id == $shopId) {
            return true;
        }

        if ($throw) {
            $this->accessDenied();
        }

        return false;
    }

    protected function renderTaskName($data, $row, $dataColumn) {
        $this->renderPartial('_cell_taskname', $data);
    }

    protected function renderActionTaskButton($data, $row, $dataColumn) {

        $buttons = array();

        if ($this->checkUpdatePermission($data->process, false)) {

            $buttons[] = array(
                'label' => 'Delete',
                'url' => 'javascript:;',
                'icon' => TbHtml::ICON_TRASH,
                'htmlOptions' => array('linkOptions' => array('data-url' => Yii::app()->createUrl("/template/process/deleteTask", array("id" => $data->id)), 'class' => 'delete-task', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if (count($buttons) > 0) {
            echo TbHtml::buttonDropdown('', $buttons, array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        }
    }

    protected function renderSchedule($data, $row, $dataColumn) {
        if ($data->is_auto_start == 1 && is_array($data->arr_start_dayofweek)) {

            $days = array();
            foreach ($data->arr_start_dayofweek as $day) {
                if ($day == ProcessTemplate::DAYS_SUN) {
                    $days[] = 'Sunday';
                } elseif ($day == ProcessTemplate::DAYS_MON) {
                    $days[] = 'Monday';
                } elseif ($day == ProcessTemplate::DAYS_TUE) {
                    $days[] = 'Tuesday';
                } elseif ($day == ProcessTemplate::DAYS_WED) {
                    $days[] = 'Wednesday';
                } elseif ($day == ProcessTemplate::DAYS_THU) {
                    $days[] = 'Thursday';
                } elseif ($day == ProcessTemplate::DAYS_FRI) {
                    $days[] = 'Friday';
                } elseif ($day == ProcessTemplate::DAYS_SAT) {
                    $days[] = 'Saturday';
                }
            }

            return TbHtml::labelTb(ProcessTemplate::itemAlias("AutoStart", $data->is_auto_start), array("color" => TbHtml::LABEL_COLOR_PRIMARY)) . " <small>" . ' <strong>' . CHtml::encode($data->start_time) . "</strong> " . implode(', ', $days) . "</small>";
        } else {
            return TbHtml::labelTb(ProcessTemplate::itemAlias("AutoStart", $data->is_auto_start));
        }
    }

    public function actionListweek() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $keyword = Yii::app()->getRequest()->getParam('q', '');

        $weeks = ProcessTemplate::weeksInYears();

        $data['total'] = count($weeks);
        $data['results'] = array();

        if (!empty($keyword)) {
            foreach ($weeks as $w) {
                $t = strtoupper($w['text']);
                $d = strtoupper($w['date']);
                $k = strtoupper($keyword);
                if (strpos($t, $k) !== false || strpos($d, $k) !== false) {
                    $data['results'][] = $w;
                }
            }
        } else {
            $data['results'] = $weeks;
        }


        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function actionInitweek() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $ids = filter_input(INPUT_GET, 'qid');

        $arr_id = explode(',', $ids);

        if (empty($arr_id)) {
            throw new CHttpException('404', 'Missing "term" GET parameter.');
        }

        $weeks = ProcessTemplate::weeksInYears();

        $result = array();
        foreach ($arr_id as $id) {

            if (isset($weeks[$id])) {
                $result[] = $weeks[$id];
            } else {
                $result[] = array(
                    'id' => '',
                    'text' => '',
                    'date' => ''
                );
            }
        }

        echo CJSON::encode($result);
        Yii::app()->end();
    }

}
