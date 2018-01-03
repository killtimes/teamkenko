<?php

class TaskController extends RController
{

    public $defaultAction = 'index';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array('accessControl',
            // perform access control for CRUD operations
            'postOnly + delete',
            // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(array('allow',
            // allow authenticated user to perform 'create' and 'update' actions
            'users' => array('@'),), array('deny',
            // deny all users
            'users' => array('*'),),);
    }

    public function actionIndex()
    {

    }

    public function actionRequest()
    {

        $this->checkAccess('Task_List', array(), true);

        $model = new TaskProcess;

        $this->render('request', array('dataProvider' => $model->taskRequests(),));
    }

    public function actionAssigned()
    {

        $this->checkAccess('Task_List', array(), true);

        $model = new TaskProcess;

        $this->render('assigned', array('dataProvider' => $model->taskAssign()));
    }

    public function actionDueToday()
    {

        $this->checkAccess('Task_List', array(), true);

        $model = new TaskProcess;

        $this->render('due_today', array('dataProvider' => $model->dueToday()));
    }

    public function actionDueTomorrow()
    {

        $this->checkAccess('Task_List', array(), true);

        $model = new TaskProcess;

        $this->render('due_tomorrow', array('dataProvider' => $model->dueTomorrow()));
    }

    public function actionDueOver2Days()
    {

        $this->checkAccess('Task_List', array(), true);

        $model = new TaskProcess;

        $this->render('due_over2days', array('dataProvider' => $model->dueOver2Days()));
    }

    public function actionOverdue()
    {

        $this->checkAccess('Task_List', array(), true);

        $model = new TaskProcess;

        $this->render('overdue', array('dataProvider' => $model->overdue()));
    }

    public function actionCompleted()
    {
        $this->checkAccess('Task_List', array(), true);

        $model = new TaskProcess;

        $this->render('completed', array('dataProvider' => $model->getCompleted()));
    }

    protected function renderDueDate($data, $row, $dataColumn)
    {
        $this->renderPartial('_cell_duedate', array('data' => $data));
    }

    protected function renderCompleteDate($data, $row, $dataColumn)
    {
        $this->renderPartial('_cell_completeddate', array('data' => $data));
    }

    protected function renderAcceptButton($data, $row, $dataColumn)
    {

        if ($data["stage"] == TaskProcess::STAGE_ASSIGNED) {

            $buttonReject = array(
                'label' => 'Not Accept',
                'url' => 'javascript:;',
                'icon' => TbHtml::ICON_REMOVE,
                'htmlOptions' => array(
                    'linkOptions' => array(
                        'data-href' => Yii::app()->createUrl("/process/task/reject", array("id" => $data["id"])),
                        'class' => 'reject-task disabled',
                        'data-token' => Yii::app()->request->csrfToken)
                )
            );

            if ($data["can_not_reject"]) {
                $buttonReject = array(
                    'label' => '<span class="text-muted">Not Accept (Not available for this task)</span>',
                    'url' => 'javascript:;',
                    'icon' => TbHtml::ICON_REMOVE,
                    'htmlOptions' => array(
                        'linkOptions' => array(
                            'disabled' => 'disabled'
                        )
                    )
                );
            }


            echo TbHtml::buttonDropdown('', array(
                array(
                    'label' => 'Accept',
                    'url' => 'javascript:;',
                    'icon' => TbHtml::ICON_OK,
                    'htmlOptions' => array(
                        'linkOptions' => array(
                            'data-href' => Yii::app()->createUrl("/process/task/accept", array("id" => $data["id"])),
                            'class' => 'accept-task',
                            'data-token' => Yii::app()->request->csrfToken)
                    )
                ),
                $buttonReject
            ), array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        } else if ($data["stage"] == TaskProcess::STAGE_INPROGRESS) {
            echo TbHtml::buttonDropdown('', array(array('label' => 'Complete', 'url' => 'javascript:;', 'icon' => TbHtml::ICON_OK_SIGN, 'htmlOptions' => array('linkOptions' => array('data-url' => Yii::app()->createUrl("/process/task/complete", array("id" => $data["id"])), 'data-instruction-url' => CController::createUrl('admin/instructions', array('id' => $data['task_id'])), 'class' => 'complete-task', 'data-token' => Yii::app()->request->csrfToken))),), array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        }
    }

    protected function renderRemindButton($data)
    {
        echo 123;
    }

    public function actionAccept($id)
    {

        if (Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest) {

            $model = TaskProcess::model()->findByPk($id);

            if ($model === null) {
                throw new CHttpException(404, 'Not found.');
            }

            if ($model->assign_id != Yii::app()->user->id) {
                $this->accessDenied();
                Yii::app()->end();
            }

            //check valid task
            if ($model->stage != TaskProcess::STAGE_ASSIGNED) {
                throw new CHttpException(403, 'Forbidden.');
            }

            if ($model->process !== null) {

                $process = $model->process;

                //check valid process
                if ($process->status != Process::STATUS_ACTIVE || $process->stage == Process::STAGE_NOTSET) {
                    throw new CHttpException(403, 'Forbidden.');
                }

                $transaction = Yii::app()->db->beginTransaction();

                try {

                    $model->accept();
                    $model->onAfterTaskAccepted($model);


//                    $model->stage = TaskProcess::STAGE_INPROGRESS;
//                    $model->scenario = 'accept';
//                    if (!$model->save()) {
//                        throw new CDbException('Accept task failed.');
//                    }
//
//                    //mark previous task = completed
//                    $tasks = TaskProcess::model()->findAll(array('condition' => 'process_id=:process_id and status=1', 'params' => array(':process_id' => $model->process_id), 'order' => 'sort_order asc'));
//
//                    if (empty($tasks)) {
//                        throw new CDbException('Get tasks failed.');
//                    }
//
//                    $preTask = null;
//                    $found = false;
//                    $totalHours = 0;
//                    $hoursComplete = 0;
//                    $progress = 0;
//
//                    foreach ($tasks as $task) {
//
//                        $totalHours += $task->duration;
//
//                        if ($task->stage == TaskProcess::STAGE_COMPLETED) {
//                            $hoursComplete += $task->duration;
//                        }
//
//                        if ($task->id == $model->id) {
//                            $found = true;
//                        }
//
//                        if (!$found) {
//                            $preTask = $task;
//                        }
//                    }
//
//                    if ($preTask !== null) {
//                        $preTask->scenario = 'complete';
//                        $preTask->stage = TaskProcess::STAGE_COMPLETED;
//                        if (!$preTask->save()) {
//                            throw new CDbException('Complete previous task failed.');
//                        }
//
//                        $progress = floor(($hoursComplete + $preTask->duration) / $totalHours * 100);
//                    }
//
//                    //set process in progress and progress
//                    $process->stage = Process::STAGE_INPROGRESS;
//                    $process->progress = $progress;
//                    if (!$process->save()) {
//                        throw new CDbException('Set process is in progress failed.');
//                    }

                    $transaction->commit();
                } catch (Exception $e) {

                    $transaction->rollback();
                    throw $e;
                }
            } else {

                $transaction = Yii::app()->db->beginTransaction();

                try {

                    $model->accept();

//                    $model->stage = TaskProcess::STAGE_INPROGRESS;
//                    $model->scenario = 'accept';
//                    if (!$model->save()) {
//                        throw new CDbException('Accept task failed.');
//                    }

                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw $e;
                }
            }

            Yii::app()->end();
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionReject($id)
    {

        if (Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest) {

            $model = TaskProcess::model()->findByPk($id);

            if ($model === null) {
                throw new CHttpException(404, 'Not found.');
            }

            if ($model->assign_id != Yii::app()->user->id) {
                $this->accessDenied();
                Yii::app()->end();
            }

            $process = $model->process;

            //check valid process
            if ($process != null && ($process->status != Process::STATUS_ACTIVE || $process->stage == Process::STAGE_NOTSET)) {
                throw new CHttpException(403, 'Forbidden.');
            }

            //check valid task
            if ($model->stage != TaskProcess::STAGE_ASSIGNED) {
                throw new CHttpException(403, 'Forbidden.');
            }

            if ($model->can_not_reject) {
                throw new CHttpException(403, 'This task can\'t be rejected.');
            }

            $model->stage = TaskProcess::STAGE_REJECTED;
            $model->scenario = 'reject';
            $model->reason = $_POST['reason'];

            if (!$model->save()) {
                throw new CDbException('Reject task failed.');
            }

            Yii::app()->end();
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionComplete($id)
    {

        if (Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest) {

            $model = TaskProcess::model()->findByPk($id);

            if ($model === null) {
                throw new CHttpException(404, 'Not found.');
            }

            if ($model->assign_id != Yii::app()->user->id) {
                $this->accessDenied();
                Yii::app()->end();
            }

            //check valid task
            if ($model->stage != TaskProcess::STAGE_INPROGRESS) {
                throw new CHttpException(403, 'Forbidden.');
            }

            if ($model->process !== null) {

                $process = $model->process;

                //check valid process
                if ($process->status != Process::STATUS_ACTIVE || $process->stage == Process::STAGE_NOTSET) {
                    throw new CHttpException(403, 'Forbidden.');
                }

                if ($model->is_att_mandatory) {
                    //check attachment
                    $countAttachments = Document::model()->count(array('condition' => 'task_id=:task_id AND status=:status', 'params' => array(':task_id' => $id, ':status' => Document::STATUS_ACTIVE)));

                    if ($countAttachments == 0) {
                        throw new CHttpException(403, 'Attachment is mandatory. Please try again after uploading attachments.');
                    }
                }

                $transaction = Yii::app()->db->beginTransaction();

                try {

                    $model->onAfterTaskPreCompleted($model);

//                    //get list all task in sequece
//                    $tasks = TaskProcess::model()->findAll(array('condition' => 'process_id=:process_id', 'params' => array(':process_id' => $model->process_id), 'order' => 'sort_order asc'));
//
//                    if (empty($tasks)) {
//                        throw new CDbException('Get tasks failed.');
//                    }
//
//                    $found = false;
//                    $nextTask = null;
//
//                    //find next task
//                    foreach ($tasks as $task) {
//
//                        if ($found) {
//
//                            $nextTask = $task;
//                            break;
//                        }
//
//                        if ($task->id == $model->id) {
//
//                            $found = true;
//                        }
//                    }
//
//                    //this is last task
//                    if ($nextTask === null) {
//
//                        $model->stage = TaskProcess::STAGE_COMPLETED;
//                        $model->scenario = 'complete';
//                        if (!$model->save(true,array('stage','complete_date'))) {
//                            throw new CDbException('Complete task failed.');
//                        }
//
//                        //complete process
//                        $process = $model->process;
//                        $process->scenario = 'completeProcess';
//                        $process->stage = Process::STAGE_DONE;
//                        if (!$process->save()) {
//                            throw new CDbException('Complete process failed.');
//                        }
//                    } else {
//
//                        //update task to wait confirm
//                        $model->stage = TaskProcess::STAGE_WAIRFORCONFIRM;
//                        $model->scenario = 'waitConfirm';
//                        if (!$model->save()) {
//                            throw new CDbException('Complete task failed.');
//                        }
//
//                        //update next task to assign
//                        $nextTask->scenario = 'assign';
//                        $nextTask->stage = TaskProcess::STAGE_ASSIGNED;
//                        $nextTask->save();
//                    }

                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw $e;
                }
            } else {
                //complete todo task
                $transaction = Yii::app()->db->beginTransaction();

                try {

                    $model->complete();

//                    $model->stage = TaskProcess::STAGE_COMPLETED;
//                    $model->scenario = 'complete';
//                    if (!$model->save(true, array('stage', 'complete_date'))) {
//                        throw new CDbException('Complete task failed.');
//                    }
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw $e;
                }
            }

            Yii::app()->end();
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    protected function renderTaskName($data, $row, $dataColumn)
    {

        $task = Task::model()->getById($data['task_id']);

        if ($task != null) {
            return TbHtml::link(CHtml::encode(Task::model()->getById($data['task_id'])->name), 'javascript:;', array('class' => 'view-activity', 'data-url' => CController::createUrl('activities', array('id' => $data['id'])))) . '&nbsp; <span data-href="' . CController::createUrl('admin/instructions', array('id' => $data['task_id'])) . '" class="glyphicon glyphicon-question-sign text-muted show-instructions"></span>';
        }

        return '';
    }

    public function actionActivities($id)
    {
        $this->checkAccess('Task_List', array(), true);

        $task = TaskProcess::model()->findByPk($id);

        if ($task === null) {
            throw new CHttpException(404, 'Not found');
        }

        $formUpload = new XUploadForm();
        $formMessage = new MessageActivityForm();

        $models = TaskActivity::model()->findAll(array('order' => 'action_date desc', 'condition' => 'task_id=:task_id AND status=:status', 'params' => array(':task_id' => $id, ':status' => TaskActivity::STATUS_ACTIVE)));

        $attachments = Document::model()->findAll(array('order' => 'create_date desc', 'condition' => 'task_id=:task_id AND status=:status', 'params' => array(':task_id' => $id, ':status' => Document::STATUS_ACTIVE)));

        $html = $this->renderPartial('activities', array('task' => $task, 'models' => $models, 'formMessage' => $formMessage, 'formUpload' => $formUpload, 'task_id' => $id, 'attachments' => $attachments), true, false);

        $jsInline = '';

        foreach (Yii::app()->clientscript->scripts as $k => $js) {
            $jsInline .= implode('', $js);
        }

        echo $html . CHtml::script($jsInline);
        Yii::app()->end();
    }

    public function actionPostMessage($id)
    {

        if (!Yii::app()->request->isAjaxRequest || !Yii::app()->request->isPostRequest) {
            $this->accessDenied();
        }

        $task = TaskProcess::model()->findByPk($id);

        if ($task === null) {
            $this->notfoundException();
        }

        $process = $task->process;
        $ptemp = '';

        $formMessage = new MessageActivityForm();

        if (isset($_POST['MessageActivityForm'])) {

            $formMessage->attributes = $_POST['MessageActivityForm'];

            if ($formMessage->validate()) {

                $transaction = Yii::app()->db->beginTransaction();

                try {

                    $hasFile = false;
                    $documentIds = array();

                    if (!empty($formMessage->listAttachments)) {
                        $hasFile = true;
                        foreach ($formMessage->listAttachments as $hash => $data) {

                            $tmp = XUploadForm::getCacheTempFile($hash);

                            if ($tmp !== false) {

                                $doc = new Document();
                                $doc->tempPath = $tmp['path'];
                                $doc->fileName = $tmp['filename'];
                                $doc->file_type = $tmp['mime'];
                                $doc->task_id = $task->id;
                                $doc->title = $data['file_label'];
                                $doc->source_type = Document::SOURCE_TYPE_LOCAL;
                                if ($process !== null) {
                                    $doc->shop_id = $process->shop_id;
                                    $doc->supplier_id = $process->supplier_id;
                                } else {
                                    $doc->shop_id = $task->shop_id;
                                    $doc->supplier_id = $task->supplier_id;
                                }

                                $doc->doc_type = $data['doc_type'];
                                $doc->doc_code = $data['doc_code'];

                                if (!empty($data['doc_date'])) {
                                    $doc->doc_date = date('Y-m-d', strtotime($data['doc_date']));
                                }

                                if (!$doc->save()) {
                                    Yii::log("Save Document failed: " . CVarDumper::dumpAsString($doc->errors), CLogger::LEVEL_ERROR);
                                }

                                $documentIds[] = $doc->id;
                            }
                        }


//                        if ($formMessage->file_source == MessageActivityForm::SOURCE_TYPE_DROPBOX) {
//                            $doc = new Document();
//                            $doc->file_name = $formMessage->file_name;
//                            $doc->task_id = $task->id;
//                            $doc->title = $formMessage->file_label;
//                            $doc->source_type = Document::SOURCE_TYPE_DROPBOX;
//                            if ($process !== null) {
//                                $doc->shop_id = $process->shop_id;
//                                $doc->supplier_id = $process->supplier_id;
//                            } else {
//                                $doc->shop_id = $task->shop_id;
//                                $doc->supplier_id = $task->supplier_id;
//                            }
//                            $doc->doc_type = $formMessage->doc_type;
//                            $doc->doc_code = $formMessage->doc_code;
//
//                            if (!empty($formMessage->doc_date)) {
//                                $doc->doc_date = date('Y-m-d', strtotime($formMessage->doc_date));
//                            }
//
//                            if (!$doc->save()) {
//                                Yii::log("Save Document failed: " . CVarDumper::dumpAsString($doc->errors), CLogger::LEVEL_ERROR);
//                                throw new CDbException('Save document failed');
//                            }
//
//                            $hasFile = true;
//                            $documentId = $doc->id;
//                        } else if (Yii::app()->user->hasState('attachment')) {
//
//                            $userAttachment = Yii::app()->user->getState('attachment');
//
//                            if ($userAttachment['filename'] == $formMessage->file_name) {
//
//                                $ptemp = $userAttachment['path'];
//
//                                $doc = new Document();
//                                $doc->file_type = $userAttachment['mime'];
//                                $doc->task_id = intval($id);
//                                $doc->title = $formMessage->file_label;
//                                $doc->source_type = Document::SOURCE_TYPE_LOCAL;
//                                if ($process !== null) {
//                                    $doc->shop_id = $process->shop_id;
//                                    $doc->supplier_id = $process->supplier_id;
//                                } else {
//                                    $doc->shop_id = $task->shop_id;
//                                    $doc->supplier_id = $task->supplier_id;
//                                }
//
//                                $doc->doc_type = $formMessage->doc_type;
//                                $doc->doc_code = $formMessage->doc_code;
//
//                                if (!empty($formMessage->doc_date)) {
//                                    $doc->doc_date = date('Y-m-d', strtotime($formMessage->doc_date));
//                                }
//
//                                if (!$doc->save()) {
//                                    Yii::log("Save Document failed: " . CVarDumper::dumpAsString($doc->errors), CLogger::LEVEL_ERROR);
//                                    throw new CDbException('Save document failed');
//                                }
//
//                                $hasFile = true;
//                                $documentId = $doc->id;
//                            }
//                        }
                    }

                    $activity = new TaskActivity();
                    $activity->action_source = Yii::app()->user->id;
                    $activity->task_id = intval($id);

                    if ($hasFile) {
                        $activity->action_type = TaskActivity::ACTION_TYPE_ADDDOCUMENT;
                        $activity->action_object = implode(',', $documentIds);
                    } else {
                        $activity->action_type = TaskActivity::ACTION_TYPE_ADDMESSAGE;
                    }

                    $activity->action_message = $formMessage->message;

                    if (!$activity->save()) {
                        Yii::log("Save task activity failed: " . CVarDumper::dumpAsString($doc->errors), CLogger::LEVEL_ERROR);
                        throw new CDbException('Save task activity failed');
                    }

                    $transaction->commit();

                    //delete temp file
                    if (!empty($ptemp)) {
                        @unlink($ptemp);
                    }

                    echo CJSON::encode(array('hasError' => false));
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw $e;
                }
            } else {
                echo CJSON::encode(array('hasError' => true, 'errorHtml' => CHtml::errorSummary($formMessage)));
            }
        }
    }

    public function actionDeleteFile($f)
    {

        XUploadForm::deleteCacheTempFile($f);

        echo CJSON::encode(array(
            $f => true
        ));

        Yii::app()->end();
    }

    public function actionUpload()
    {


        $model = new XUploadForm();
        $model->secureFileNames = false;

        $model->file = CUploadedFile::getInstance($model, 'file');

        if ($model->file !== null) {
            $model->mime_type = $model->file->getType();
            $model->size = $model->file->getSize();
            $model->name = $model->file->getName();

            $ext = $model->file->getExtensionName();
            $tmp = str_replace($ext, '', $model->name);

            $model->filename = microtime(true) . '_' . XUploadForm::slugify($tmp) . '.' . $ext;  //md5(Yii::app()->user->id . microtime() . $model->name);
            $model->hashname = md5($model->filename);

            if ($model->validate()) {

                if ($model->saveTempFile()) {
                    header('Content-Type: application/json');
                    echo CJSON::encode(array(
                        'files' => array(
                            array(
                                "name" => $model->name,
                                "type" => $model->mime_type,
                                "size" => $model->size, //$model->getReadableFileSize(),
//                                "filename" => $model->filename,
                                'deleteType' => 'GET',
                                'deleteUrl' => $this->createUrl('/process/task/deleteFile', array('f' => $model->hashname)),
                                'hashname' => $model->hashname
                            )
                        )
                    ));
                } else {
                    echo CJSON::encode(array(
                        'files' => array(
                            array(
                                'error' => 'Can not upload file. Please try again',
                                "name" => $model->name,
                            )
                        )
                    ));
                }

//                //hash filename
//                $filename = $model->filename;
//
//                //Move our file to our temporary dir
//                $model->file->saveAs($tmpPath . $filename);
//                chmod($tmpPath . $filename, 0774);
//
//                //Now we need to save this path to the user's session
//                if (Yii::app()->user->hasState('attachment')) {
//                    $userDocument = Yii::app()->user->getState('attachment');
//                } else {
//                    $userDocument = array();
//                }
//
//                $userDocument = array('path' => $tmpPath . $filename,
//                    //the same file or a thumb version that you generated
//                    'filename' => $filename, 'size' => $model->size, 'mime' => $model->mime_type, 'name' => $model->name,);
//
//                Yii::app()->user->setState('attachment', $userDocument);
            } else {
                echo CJSON::encode(array(
                    'files' => array(
                        array(
                            'error' => $model->getError('file'),
                            "name" => $model->name,
                        )
                    )
                ));
            }
        } else {
            echo CJSON::encode(array(
                'files' => array(
                    array(
                        'error' => 'Can not upload file. Please try again',
                    )
                )
            ));
        }

        Yii::app()->end();
    }

    public function actionSummary()
    {

        $model = new TaskProcess;
        header('Content-Type: application/json');
        echo CJSON::encode(array('requests' => $model->getTotalTaskRequests(), 'assigned' => $model->getTotalTaskAssigned(), 'due_today' => $model->getTotalTaskDueToday(), 'due_tomorrow' => $model->getTotalTaskDueTomorrow(), 'due_over2days' => $model->getTotalTaskDueOver2Days(), 'overdue' => $model->getTotalTaskOverdue(), 'completed' => $model->getTotalTaskCompleted()));

        Yii::app()->end();
    }

    protected function renderRequestOwner($data, $row, $dataColumn)
    {
        $requestUserId = 0;
        if ($data['request_by'] > 0) {
            $requestUserId = $data["request_by"];
        } else if (!empty($data['start_by'])) {
            $requestUserId = $data["start_by"];
        } else {
            $requestUserId = $data["update_by"];
        }

        return Profile::model()->getById($data["update_by"])->getFullName();
    }

    protected function renderShopName($data, $row, $dataColumn)
    {
        $cid = 0;

        if ($data['shop_id'] > 0) {
            $cid = $data['shop_id'];
        } else if ($data['todo_shop_id'] > 0) {
            $cid = $data['todo_shop_id'];
        }

        if ($cid > 0) {
            return Shop::model()->getById($cid)->name;
        }
    }

    protected function renderSupplierName($data, $row, $dataColumn)
    {
        $sid = 0;
        if ($data['supplier_id'] > 0) {
            $sid = $data['supplier_id'];
        } else if ($data['todo_supplier_id'] > 0) {
            $sid = $data['todo_supplier_id'];
        }

        if ($sid > 0) {
            return Supplier::model()->getById($sid)->name;
        }
    }

    public function actionCreate()
    {

        $this->checkAccess('ToDo_Create', array(), true);

        $model = new TaskProcess('insertTodo');

        if (isset($_POST['TaskProcess'])) {

            $model->attributes = $_POST['TaskProcess'];

            if ($model->validate()) {

                $transaction = Yii::app()->db->beginTransaction();

                try {
                    if (!$model->save()) {
                        throw new CDbException('Create task failed.');
                    }

                    $model->stage = TaskProcess::STAGE_INPROGRESS;
                    $model->scenario = 'accept';
                    if (!$model->save()) {
                        throw new CDbException('Accept task failed.');
                    }

                    $transaction->commit();

                    Yii::app()->user->setFlash('successMessage', 'Created Task successful');
                    $this->redirect(array('/process/task/update', 'id' => $model->id));
                } catch (Exception $e) {
                    $transaction->rollback();
                }
            }
        }

        $department = Profile::model()->getDepartment(Yii::app()->user->id);

        $this->render('create', array('model' => $model, 'department'=>$department));
    }

    public function actionUpdate($id)
    {

        $model = $this->loadModel($id);

        if (isset($_POST['TaskProcess'])) {

            $this->checkAccess('ToDo_Update', array(), true);

            $model->attributes = $_POST['TaskProcess'];
            $model->scenario = 'updateTodo';

            if ($model->save()) {
                Yii::app()->user->setFlash('successMessage', 'Update Task successful');
                $this->refresh();
            }
        }
        $department = Profile::model()->getDepartment(Yii::app()->user->id);

        $this->render('update', array('model' => $model,'department'=>$department));
    }

    public function loadModel($id)
    {
        $model = TaskProcess::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
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

    public function actionDeleteActivity($id)
    {

        $model = TaskActivity::model()->findByPk((int)$id);

        if ($model === null) {
            $this->notfoundException();
        }

        $task = TaskProcess::model()->findByPk($model->task_id);

        if ($task === null) {
            $this->notfoundException();
        }

        //can only delete message and file
        if ($model->action_type != TaskActivity::ACTION_TYPE_ADDMESSAGE &&
            $model->action_type != TaskActivity::ACTION_TYPE_ADDDOCUMENT
        ) {
            $this->accessDenied();
        }

        if ($model->status == TaskActivity::STATUS_DELETED) {
            $this->accessDenied();
        }

        if ($model->action_type == TaskActivity::ACTION_TYPE_ADDMESSAGE && !$this->checkDeleteMessagePermission($model, $task)) {
            $this->accessDenied();
        }

        //get document
        $document = null;
        $arrDocId = explode(',', $model->action_object);
        if ($model->action_type == TaskActivity::ACTION_TYPE_ADDDOCUMENT && !empty($model->action_object)) {

            $criteria = new CDbCriteria;
            $criteria->addInCondition('id', $arrDocId);
            $documents = Document::model()->findAll($criteria);
        }

        if ($documents !== null && !$this->checkDeleteDocumentPermission($documents[0], $task)) {
            $this->accessDenied();
        }

        $tran = Yii::app()->db->beginTransaction();

        try {

            if (!$model->delete()) {
                $error = 'Delete task activity failed ' . CVarDumper::dumpAsString($model->errors);
                Yii::log($error);
                throw new CDbException('Delete task activity failed');
            }

            if ($documents !== null) {

                foreach ($documents as $doc) {
                    if (!$doc->delete()) {
                        $error = 'Delete document failed ' . CVarDumper::dumpAsString($model->errors);
                        Yii::log($error);
                    }
                }
            }

            //delete physical file
            if ($document !== null && $document->source_type == Document::SOURCE_TYPE_LOCAL) {
                $filePath = $document->getFilePath();
                @unlink($filePath);
            }

            $tran->commit();
        } catch (Exception $e) {
            $tran->rollback();
            throw $e;
        }
    }

}
