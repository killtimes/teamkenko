<?php

class AdminController extends RController
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    public $defaultAction = 'admin';
    public $type = 'admin';

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

    public function checkViewPermission($model)
    {

        $hasGlobalPermission = $this->checkAccess('Process_View', array());

        if ($hasGlobalPermission) {
            return true;
        }

        $hasShopPermission = $this->checkAccess('Process_OwnView', array());

        if ($hasShopPermission && $model->shop_id == Profile::model()->getShopId(Yii::app()->user->id)) {
            return true;
        }

        $this->accessDenied();
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {

        $model = $this->loadModel($id);

        $this->checkViewPermission($model);

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {

        $this->checkAccess('Process_Create');

        $model = new Process;

        $shopId = Profile::model()->getShopId(Yii::app()->user->id);

        if (!empty($shopId)) {
            $model->shop_id = $shopId;
        }

//        $this->performAjaxValidation($model);

        if (isset($_POST['Process'])) {

            $model->attributes = $_POST['Process'];

            if (!empty($shopId)) {
                $model->shop_id = $shopId;
            }

            if ($model->validate()) {

                $tran = Yii::app()->db->beginTransaction();

                try {
                    if ($model->save(false)) {
                        $tran->commit();
                        Yii::app()->user->setFlash('successMessage', 'Created Job successful');
                        $this->redirect(array('update', 'id' => $model->id));
                    } else {
                        Yii::log('Save process failed:' . CVarDumper::dumpAsString($model->errors), CLogger::LEVEL_ERROR, 'biz.process');
                        throw new CDbException('Save process failed');
                    }
                } catch (Exception $e) {
                    $tran->rollback();
                    Yii::app()->handleException($e);
                }
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function checkUpdatePermission($model, $throw = true)
    {

        $hasGlobalPermission = $this->checkAccess('Process_Update', array());

        if ($hasGlobalPermission) {
            return true;
        }

        $hasShopPermission = $this->checkAccess('Process_OwnUpdate', array());

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
    public function actionUpdate($id)
    {

        $model = $this->loadModel($id);

        $this->checkViewPermission($model);

        $modelTaskProcess = new TaskProcess();
        $modelTaskProcess->process_id = $model->id;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Process'])) {

            $this->checkUpdatePermission($model);

            $model->attributes = $_POST['Process'];
            if ($model->save()) {
                Yii::app()->user->setFlash('successMessage', 'Updated Job successful');

                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $attachments = Document::model()->getDocumentsByJobId($id);
        $attachmentProvider = new CActiveDataProvider('Document');
        $attachmentProvider->setData($attachments);

        $this->render('update', array(
            'model' => $model,
            'modelTaskProcess' => $modelTaskProcess,
            'attachments' => $attachmentProvider
        ));
    }

    protected function renderDocName($data, $row, $dataColumn)
    {
        return $this->renderPartial('/document/_cellname', $data, TRUE);
    }

    public function checkDeletePermission($model, $throwException = TRUE)
    {

        $hasGlobalPermission = $this->checkAccess('Process_Delete', array());

        if ($hasGlobalPermission) {
            return true;
        }

        $hasShopPermission = $this->checkAccess('Process_OwnDelete', array());

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
    public function actionDelete($id)
    {
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

    public function actionRecover($id){

        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);

            $this->checkDeletePermission($model);

            $model->recover();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function checkListPermission($throw = true)
    {

        return $this->checkAccess('Process_List', array(), !$throw);
    }

    /**
     * Lists all models.
     */
//    public function actionIndex() {
//        $this->checkListPermission();
//        $dataProvider = new CActiveDataProvider('Process');
//        $this->render('index', array(
//            'dataProvider' => $dataProvider,
//        ));
//    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {

        $model = new Process('search');
        $model->unsetAttributes();  // clear any default values


        if (isset($_GET['Process'])) {
            $model->attributes = $_GET['Process'];
        }

        $this->checkAccess('Process_List', array(), true);

//        if (Yii::app()->user->shop_id > 0) {
//            $model->shop_id = Yii::app()->user->shop_id;
//        }

        $dataProvider = $model->search();

        $this->render('admin', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionArchived()
    {
        $this->type = 'archived';

        $model = new Process('search');
        $model->unsetAttributes();  // clear any default values


        if (isset($_GET['Process'])) {
            $model->attributes = $_GET['Process'];
        }

        $this->checkAccess('Process_List', array(), true);

//        if (Yii::app()->user->shop_id > 0) {
//            $model->shop_id = Yii::app()->user->shop_id;
//        }

        $this->render('admin', array(
            'model' => $model,
            'dataProvider' => $model->searchCompletedProcess(),
        ));
    }

    public function actionDeleted()
    {

        $this->type = 'deleted';

        $model = new Process('search');
        $model->unsetAttributes();  // clear any default values


        if (isset($_GET['Process'])) {
            $model->attributes = $_GET['Process'];
        }

        $this->checkAccess('Process_List', array(), true);

//        if (Yii::app()->user->shop_id > 0) {
//            $model->shop_id = Yii::app()->user->shop_id;
//        }

        $this->render('admin', array(
            'model' => $model,
            'dataProvider' => $model->searchDeletedProcess(),
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Process the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Process::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Process $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'process-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function checkCreateTaskPermission($process)
    {

        $this->checkUpdatePermission($process);

        $this->checkAccess('Task_Create');
    }

    public function actionCreateTask($id)
    {

        $process = $this->loadModel($id);

        $this->checkCreateTaskPermission($process);

        $taskProcess = new TaskProcess;
        $taskProcess->process_id = $process->id;

        //ajax validate on submit
        $this->performAjaxValidationCreateTask($taskProcess);

        if (Yii::app()->request->isAjaxRequest && isset($_POST['TaskProcess'])) {

            $taskProcess->attributes = $_POST['TaskProcess'];

            if ($taskProcess->save()) {
                $taskProcess = new TaskProcess;
            } else {
                echo CActiveForm::validate($taskProcess);
                Yii::app()->end();
            }
        }

        $html = $this->renderPartial('_taskprocess_form', array(
            'model' => $taskProcess,
            'modelProcess' => $process
        ), true, false);

        $jsArr = array_values(Yii::app()->clientscript->scripts);

        $js = '';

        foreach ($jsArr as $k => $arr) {

            $js .= CHtml::script(implode("\n", $arr));
        }

        header('Content-type: application/json');

        echo CJSON::encode(array(
            'data' => $html,
            'script' => $js
        ));

        Yii::app()->end();
    }

    public function checkUpdateTaskPermission($process, $task)
    {

        $hasGlobalPermission = $this->checkAccess('Process_Update', array());

        if ($hasGlobalPermission) {
            return true;
        }

        $hasShopPermission = $this->checkAccess('Process_OwnUpdate', array());
        $shopId = Profile::model()->getShopId(Yii::app()->user->id);
        if ($hasShopPermission && $process->shop_id == $shopId) {
            return true;
        }

        $hasTaskManagerPermission = $this->checkAccess('Task_Update', array());

        if ($hasTaskManagerPermission) {
            return true;
        }

        $hasTaskPermission = $this->checkAccess('Task_OwnUpdate', array());

        if ($hasTaskPermission && $task->assign_id == Yii::app()->user->id) {
            return true;
        }

        $this->accessDenied();
    }

    public function checkDeleteTaskPermission($process, $task, $throw = true)
    {

        $hasGlobalPermission = $this->checkAccess('Process_Delete', array());

        if ($hasGlobalPermission) {
            return true;
        }

        $hasShopPermission = $this->checkAccess('Process_OwnDelete', array());
        $shopId = Profile::model()->getShopId(Yii::app()->user->id);
        if ($hasShopPermission && $process->shop_id == $shopId) {
            return true;
        }

        $hasTaskManagerPermission = $this->checkAccess('Task_Delete', array());

        if ($hasTaskManagerPermission) {
            return true;
        }

        $hasTaskPermission = $this->checkAccess('Task_OwnDelete', array());

        if ($hasTaskPermission && $task->create_by == Yii::app()->user->id) {
            return true;
        }

        if ($throw) {
            $this->accessDenied();
        }

        return false;
    }

    public function actionUpdateTask($id)
    {

        $taskProcess = TaskProcess::model()->findByPk($id);

        if ($taskProcess === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $process = $taskProcess->process;

        $this->checkUpdateTaskPermission($process, $taskProcess);

        //ajax validate on submit
        $this->performAjaxValidationCreateTask($taskProcess);

        if (Yii::app()->request->isAjaxRequest && isset($_POST['TaskProcess'])) {

            $taskProcess->attributes = $_POST['TaskProcess'];

            if (!$taskProcess->save()) {
                echo CActiveForm::validate($taskProcess);
                Yii::app()->end();
            }

            $reAssign = (isset($_POST['TaskProcess']['re_assign']) && intval($_POST['TaskProcess']['re_assign']) == 1);

            if ($reAssign) {
                $taskProcess->stage = TaskProcess::STAGE_ASSIGNED;
                $taskProcess->scenario = 'assign';
                $taskProcess->save();
            }
        }

        $html = $this->renderPartial('_taskprocess_form', array(
            'model' => $taskProcess,
            'modelProcess' => $process
        ), true, false);

        $jsArr = array_values(Yii::app()->clientscript->scripts);
        $js = '';
        foreach ($jsArr as $k => $arr) {
            $js .= CHtml::script(implode("\n", $arr));
        }

        header('Content-type: application/json');

        echo CJSON::encode(array(
            'data' => $html,
            'script' => $js
        ));

        Yii::app()->end();
    }

    public function actionSortTask($id)
    {

        if (Yii::app()->request->isAjaxRequest === true && isset($_POST['result'])) {

            $model = Process::model()->findByPk($id);

            if ($model === NULL) {
                throw new CHttpException(404, 'Not found');
            }

            $this->checkUpdatePermission($model);

            //get max order of other unsort task
            $maxOrder = Yii::app()->db->createCommand('SELECT MAX(`sort_order`) AS `max_order` FROM `TaskProcess` WHERE `process_id`=' . $model->id . ' AND `stage`>' . TaskProcess::STAGE_NOTSET)->queryScalar();
            $maxOrder++;
            $sql = 'UPDATE `TaskProcess` SET `sort_order`= CASE `id` ';
            $idArr = array();
            foreach ($_POST['result'] as $order => $idt) {
                $idt = intval($idt);
                $idArr[] = $idt;
                $sql .= ' WHEN ' . $idt . ' THEN ' . (intval($order) + $maxOrder);
            }
            $sql .= ' END ';
            $sql .= ' WHERE `process_id`=' . intval($id) . ' AND `id` IN (' . implode(',', $idArr) . ') AND `stage`=0';

            Yii::app()->db->createCommand($sql)->execute();
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDeleteTask($id)
    {

        if (Yii::app()->request->isPostRequest) {

            $taskProcess = TaskProcess::model()->findByPk($id);

            if ($taskProcess === null) {
                throw new CHttpException(404, 'Not found');
            }

            if ($taskProcess->stage != TaskProcess::STAGE_NOTSET && $taskProcess->stage != TaskProcess::STAGE_REJECTED) {
                $this->accessDenied();
            }

            $process = $taskProcess->process;

            if ($process === null) {
                throw new CHttpException(404, 'Not found');
            }

            $this->checkDeleteTaskPermission($process, $taskProcess);

            $tran = Yii::app()->db->beginTransaction();

            try {

                $tasks = $process->taskProcesses;
                $totalTask = count($tasks);
                $tId = $taskProcess->id;
                if (!$taskProcess->delete()) {
                    Yii::log('Delete task failed:' . CVarDumper::dumpAsString($taskProcess->errors), CLogger::LEVEL_ERROR);
                    throw new CDbException('Delete task failed');
                }

                if ($totalTask > 1) {
                    $maxOrder = Yii::app()->db->createCommand('SELECT MAX(`sort_order`) AS `max_order` FROM `TaskProcess` WHERE `process_id`=' . $process->id . ' AND `stage`>' . TaskProcess::STAGE_NOTSET)->queryScalar();
                    $maxOrder++;

                    $w = '';
                    $found = false;
                    $foundPrevtask = false;
                    $nextTask = null;
                    $preVTask = null;
                    $totalHours = 0;
                    $hoursComplete = 0;
                    $progress = 0;
                    foreach ($tasks as $t) {

                        if ($t->id != $tId) {
                            $totalHours += $t->duration;
                        }

                        if ($t->stage == TaskProcess::STAGE_COMPLETED) {
                            $hoursComplete += $t->duration;
                        }
                        if ($found) {
                            //this is next task
                            $nextTask = $t;
                            $found = false;
                        }

                        if ($t->id != $tId) {
                            $w .= ' WHEN ' . $t->id . ' THEN ' . $maxOrder;
                            $maxOrder++;
                        } else {
                            $found = true;
                            $foundPrevtask = true;
                        }

                        if (!$foundPrevtask) {
                            $prevTask = $t;
                        }
                    }

                    $sql = 'UPDATE `TaskProcess` SET `sort_order`= CASE `id` ';
                    $sql .= $w;
                    $sql .= ' END ';
                    $sql .= ' WHERE `process_id`=' . $process->id . ' AND `stage`=0';

                    Yii::app()->db->createCommand($sql)->execute();

                    if ($process->stage == Process::STAGE_STARTED || $process->stage == Process::STAGE_INPROGRESS) {
                        if ($nextTask != null && $nextTask->stage == TaskProcess::STAGE_NOTSET) {
                            $nextTask->stage = TaskProcess::STAGE_ASSIGNED;
                            $nextTask->scenario = 'assign';
                            $nextTask->save();

                            $progress = floor(($hoursComplete) / $totalHours * 100);

                        } else if ($nextTask == null && $prevTask != null && $prevTask->stage == TaskProcess::STAGE_WAIRFORCONFIRM) {
                            $prevTask->scenario = 'complete';
                            $prevTask->stage = TaskProcess::STAGE_COMPLETED;
                            if (!$prevTask->save()) {
                                throw new CDbException('Complete previous task failed.');
                            }

                            $progress = floor(($hoursComplete + $prevTask->duration) / $totalHours * 100);
                        }

                        //set process in progress and progress
                        $process->stage = Process::STAGE_INPROGRESS;
                        $process->progress = $progress;
                        if (!$process->save()) {
                            throw new CDbException('Set process is in progress failed.');
                        }
                    }

                }

                $tran->commit();
            } catch (Exception $e) {
                $tran->rollback();
                throw $e;
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    protected function performAjaxValidationCreateTask($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'edit-task-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function renderProgress($data, $row, $dataColumn)
    {
        return $this->renderPartial('_cell_progress', $data, true);
    }

    protected function renderDate($data, $row, $dataColumn)
    {
        return $this->renderPartial('_cell_date', $data, true);
    }

    public function actionStartProcess($id)
    {

        if (Yii::app()->request->isPostRequest) {

            $model = Process::model()->findByPk($id);

            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
            }

            $this->checkUpdatePermission($model);

            //check list task
            if (empty($model->taskProcesses)) {
                throw new CHttpException(400, 'No task');
            }

            //check valid process
            if ($model->status != Process::STATUS_ACTIVE || $model->stage != Process::STAGE_NOTSET) {
                $this->accessDenied();
            }

            $model->stage = Process::STAGE_STARTED;
            $model->scenario = "startProcess";

            if ($model->save()) {
                //send notify
            } else {
                throw new CDbException("Start process failed");
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function checkDuplicatePermission($model, $throw = false)
    {

        $canCreate = $this->checkAccess('Process_Create');

        $canView = $this->checkAccess('Process_View');

        if ($canCreate && $canView) {
            return true;
        }

        $canViewOwn = $this->checkAccess('Process_OwnView');
        $shopId = Profile::model()->getShopId(Yii::app()->user->id);
        if ($canCreate && $canViewOwn && $model->shop_id == $shopId) {
            return true;
        }

        if ($throw) {
            $this->accessDenied();
        }
    }

    public function actionDuplicate($id)
    {

        if (Yii::app()->request->isAjaxRequest) {

            $model = Process::model()->findByPk($id);

            if ($model === null) {
                throw new CHttpException(404, 'Not found');
            }

            $this->checkDuplicatePermission($model);

            $tasks = $model->taskProcesses;

            $model->name .= ' copy';
            $model->scenario = 'insert';
            $model->isNewRecord = true;
            $model->stage = Process::STAGE_NOTSET;
            $model->progress = 0;
            $model->start_date = null;
            $model->complete_date = null;
            $model->status = Process::STATUS_ACTIVE;
            unset($model->id);

            $db = Yii::app()->db->beginTransaction();

            try {

                if ($model->save()) {

                    if (!empty($tasks)) {
                        foreach ($tasks as $task) {
                            unset($task->id);
                            $task->status = TaskProcess::STATUS_ACTIVE;
                            $task->isNewRecord = true;
                            $task->scenario = 'insert';
                            $task->process_id = $model->id;
                            $task->stage = TaskProcess::STAGE_NOTSET;
                            $task->assign_date = null;
                            $task->reject_date = null;
                            $task->accept_date = null;
                            $task->due_date = null;
                            $task->complete_date = null;
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

    public function actionClone()
    {

        $model = new CloneProcessForm();

        if (isset($_GET['id'])) {

            $tpId = intval($_GET['id']);

            //get job data
            $template = ProcessTemplate::model()->findByPk($tpId);

            if ($template === NULL) {
                throw new CHttpException(404, 'The requested page does not exist.');
            }

            $model->template_id = $tpId;
            $model->name = $template->name;
        }

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'clone-process-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (Yii::app()->request->isAjaxRequest && isset($_POST['CloneProcessForm'])) {

            $this->checkAccess('Process_Create');

            $model->attributes = $_POST['CloneProcessForm'];

            if ($model->validate()) {

                //get job data
                $template = ProcessTemplate::model()->findByPk($model->template_id);

                if ($template === NULL) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                }

                //overide jobtemplate field
                $template->name = $model->name;
                $template->scenario = 'insert';
                $template->isNewRecord = true;
                unset($template->id);

                //get task data
                $criteria = new CDbCriteria;
                $criteria->compare('process_id', $model->template_id);
                $criteria->order = 'sort_order asc';
//                $tasks = TaskProcessTemplate::model()->findAll('process_id=:process_id', array(':process_id' => $model->template_id));
                $tasks = TaskProcessTemplate::model()->findAll($criteria);
                //transaction for job and task
                $transaction = Yii::app()->db->beginTransaction();

                try {

                    $modelProcess = new Process('insert');
                    $modelProcess->attributes = $template->attributes;

                    //save job
                    if ($modelProcess->save()) {

                        //clone task
                        if (!empty($tasks)) {

                            foreach ($tasks as $task) {

                                $task->process_id = $modelProcess->id;
                                $recipientsConfig = $task->getAlertRecipientTemplate2();
                                $emailRecipients = array();
                                foreach ($recipientsConfig as $k => $v) {
                                    if ((bool)$v) {
                                        $emailRecipients[] = $k;
                                    }
                                }

                                $modelTask = new TaskProcess('insert');
                                $modelTask->attributes = $task->attributes;
                                $modelTask->alert_conditions = $task->alert_conditions;
                                $modelTask->alert_recipients = implode(',', array_keys($recipientsConfig));
                                $modelTask->send_mail_recipients = implode(',', $emailRecipients);
                                $modelTask->task_type = $task->task_type;

                                if (!$modelTask->save()) {
                                    throw new CDbException('Save task failed');
                                }
                            }
                        }

                        //commit transaction
                        $transaction->commit();

                        $model = new CloneProcessForm();
                    } else {
                        throw new CDbException('Save job failed');
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    $model->addError('general', $e->getMessage());


                    $this->refresh();
                }
            }
        }

        $this->renderPartialAjax('_clone_process_form', array(
            'model' => $model
        ));
    }

    protected function renderTaskDate($data, $row, $dataColumn)
    {
        return $this->renderPartial('_cell_taskdate', $data);
    }

    protected function renderTaskActionButton($data, $row, $dataColumn)
    {
        $buttons = array();

        $buttons[] = array(
            'label' => 'View activities',
            'url' => 'javascript:;',
            'icon' => TbHtml::ICON_EYE_OPEN,
            'htmlOptions' => array('linkOptions' => array('data-url' => Yii::app()->createUrl("/process/admin/activities", array("id" => $data->id)), 'class' => 'view-activity', 'data-token' => Yii::app()->request->csrfToken))
        );

        if (($data->stage == TaskProcess::STAGE_NOTSET || $data->stage == TaskProcess::STAGE_REJECTED) && $this->checkDeleteTaskPermission($data->process, $data, false)) {
            $buttons[] = array(
                'label' => 'Delete',
                'url' => 'javascript:;',
                'icon' => TbHtml::ICON_TRASH,
                'htmlOptions' => array('linkOptions' => array('data-url' => Yii::app()->createUrl("/process/admin/deleteTask", array("id" => $data->id)), 'class' => 'delete-task', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if (count($buttons) > 0) {
            echo TbHtml::buttonDropdown('', $buttons, array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        }
    }

    protected function renderActionButton($data, $row, $dataColumn)
    {

        $buttons = array();

        if ($data->stage == Process::STAGE_NOTSET) {

            if ($this->type != 'deleted' && $this->checkUpdatePermission($data, false)) {

                $buttons[] = array(
                    'label' => 'Start',
                    'url' => Yii::app()->createUrl("/process/admin/startProcess", array("id" => $data->id)),
                    'icon' => TbHtml::ICON_OK,
                    'htmlOptions' => array('linkOptions' => array('class' => 'start-process', 'data-token' => Yii::app()->request->csrfToken))
                );
            }
        }

        if ($this->checkDuplicatePermission($data, false)) {

            $buttons[] = array(
                'label' => 'Duplicate',
                'url' => Yii::app()->createUrl("/process/admin/duplicate", array("id" => $data->id)),
                'icon' => TbHtml::ICON_DUPLICATE,
                'htmlOptions' => array('linkOptions' => array('class' => 'duplicate', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if ($this->type != 'deleted' && $this->type != 'archived' && $this->checkDeletePermission($data, false)) {

            $buttons[] = array(
                'label' => 'Delete',
                'url' => Yii::app()->createUrl("/process/admin/delete", array("id" => $data->id)),
                'icon' => TbHtml::ICON_TRASH,
                'htmlOptions' => array('linkOptions' => array('class' => 'delete', 'data-token' => Yii::app()->request->csrfToken))
            );
        } else if ( $this->type = 'deleted' && $this->checkDeletePermission($data, false)) {
            $buttons[] = array(
                'label' => 'Recover',
                'url' => Yii::app()->createUrl("/process/admin/recover", array("id" => $data->id)),
                'icon' => TbHtml::ICON_RETWEET,
                'htmlOptions' => array('linkOptions' => array('class' => 'recover', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if (count($buttons) > 0) {
            echo TbHtml::buttonDropdown('', $buttons, array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        }
    }

    protected function renderName($data, $row, $dataColumn)
    {
        return $this->renderPartial('_cell_name', $data, true);
    }

    protected function renderTaskName($data, $row, $dataColumn)
    {
        return $this->renderPartial('_cell_taskname', $data);
    }

    public function actionInstructions($id)
    {

        $task = Task::model()->getById($id);

        if ($task === null) {
            $this->accessDenied();
        }

        $html = '<strong>' . $task->name . '</strong><br>';

        if (!empty($task->instructions)) {
            $html .= nl2br($task->instructions);
        } else {
            $html .= '<em>No instructions</em>';
        }


        echo $html;

        Yii::app()->end();
    }

    public function actionActivities($id)
    {

        $this->checkAccess('Task_List', array(), true);

        $task = TaskProcess::model()->findByPk($id);

        if ($task === null) {
            throw new CHttpException(404, 'Not found');
        }

//        Yii::import("xupload.models.XUploadForm");
//        Yii::import("process.models.MessageActivityForm");

        $formUpload = new XUploadForm();
        $formMessage = new MessageActivityForm();

        $models = TaskActivity::model()->findAll(array(
            'order' => 'action_date desc',
            'condition' => 'task_id=:task_id',
            'params' => array(':task_id' => $id)
        ));

        $attachments = Document::model()->findAll(array(
            'order' => 'create_date desc',
            'condition' => 'task_id=:task_id',
            'params' => array(':task_id' => $id)
        ));

        $html = $this->renderPartial('process.views.task.activities', array(
            'task' => $task,
            'models' => $models,
            'formMessage' => $formMessage,
            'formUpload' => $formUpload,
            'task_id' => $id,
            'attachments' => $attachments
        ), true, false);

        $jsInline = '';

        foreach (Yii::app()->clientscript->scripts as $k => $js) {
            $jsInline .= implode('', $js);
        }

        echo $html . CHtml::script($jsInline);
        Yii::app()->end();
    }

    public function actionList()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $limit = 10;
        $keyword = Yii::app()->getRequest()->getParam('q', '');
        $page = Yii::app()->getRequest()->getParam('page', 1);
        $offset = ($page - 1) * $limit;

        //search contact
        $connection = Yii::app()->db;
        $sql = "SELECT SQL_CALC_FOUND_ROWS u.id, u.name "
            . " FROM `Process` u"
            . " WHERE u.status=:status "
            . " AND (u.name LIKE :keyword) "
            . " LIMIT :limit "
            . " OFFSET :offset ";

        $command = $connection->createCommand($sql);
        $command->bindValue(':status', Process::STATUS_ACTIVE, PDO::PARAM_INT);
        $command->bindValue(':keyword', "%" . $keyword . "%", PDO::PARAM_STR);
        $command->bindValue(':limit', $limit, PDO::PARAM_INT);
        $command->bindValue(':offset', $offset, PDO::PARAM_INT);

        $rows = $command->queryAll();

        //get total for paging
        $total = Yii::app()->db->createCommand('SELECT FOUND_ROWS() AS total')->queryScalar();

        $data = array();
        $data['total'] = $total;
        $data['results'] = array();

        foreach ($rows as $item) {
            $data['results'][] = array(
                'id' => $item['id'],
                'text' => $item['name']
            );
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function checkDeleteDocumentPermission($document, $task)
    {

        if ($task->stage == TaskProcess::STAGE_COMPLETED) {
            return false;
        }

        $delAny = $this->checkAccess('Document_DeleteAny');

        if ($delAny) {
            return true;
        }

        $delOwn = $this->checkAccess('Document_DeleteOwn');

        if ($delOwn && $document->upload_by == Yii::app()->user->id) {
            return true;
        }

        return false;
    }

    public function checkDeleteMessagePermission($message, $task)
    {

        if ($task->stage == TaskProcess::STAGE_COMPLETED) {
            return false;
        }

        $delAny = $this->checkAccess('Message_DeleteAny');

        if ($delAny) {
            return true;
        }

        $delOwn = $this->checkAccess('Message_DeleteOwn');

        if ($delOwn && $message->action_type == TaskActivity::ACTION_TYPE_ADDMESSAGE && $message->action_source == Yii::app()->user->id) {
            return true;
        }

        return false;
    }

}
