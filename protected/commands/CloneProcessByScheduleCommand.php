<?php

class CloneProcessByScheduleCommand extends CConsoleCommand {

    public function run($args) {

        Yii::beginProfile('test');

        Yii::import('application.modules.template.models.Task');
        Yii::import('application.modules.template.models.CloneTask');
        Yii::import('application.modules.template.models.ProcessTemplate');
        Yii::import('application.modules.template.models.TemplateSchedule');
        Yii::import('application.modules.template.models.TaskProcessTemplate');
        Yii::import('application.modules.process.models.Process');
        Yii::import('application.modules.process.models.TaskProcess');
        Yii::import('application.modules.process.models.TaskActivity');
        $now = date('Y-m-d H:i:s');

        //get all task
        $cloneTasks = CloneTask::model()->findAll(' `when`<:now', array(
            ':now' => $now
        ));

        $err = '';
        $totalAdd = 0;
        $w = Yii::app()->dateFormatter->format('w', $now);

        if ($cloneTasks !== null) {
            foreach ($cloneTasks as $ctask) {

                $templateId = $ctask->template_id;

                //get template info
                $template = ProcessTemplate::model()->findByPk($templateId);

                if ($template === null) {
                    $err.="\nTemplate NULL #" . $templateId;
                    continue;
                }
                
                $template->isNewRecord = true;
                unset($template->id);
                
                $criteria = new CDbCriteria;
                $criteria->compare('process_id', $templateId);
                $criteria->order = 'sort_order asc';
                $tasks = TaskProcessTemplate::model()->findAll($criteria);
                //get task data
//                $tasks = TaskProcessTemplate::model()->findAll('process_id=:process_id', array(':process_id' => $templateId));

                if ($tasks === null || count($templateId) == 0) {
                    $err.="\nTemplate tasks NULL #" . $templateId;
                    continue;
                }

                //transaction for job and task
                $transaction = Yii::app()->db->beginTransaction();
                try {

                    $modelProcess = new Process('insert');
                    $modelProcess->attributes = $template->attributes;
                    $modelProcess->name = sprintf("%s - %s, %s", $modelProcess->name, 'Week '. ProcessTemplate::formatOrdinal($w), date('D d M Y'));

                    if ($template->update_by > 0) {
                        $modelProcess->create_by = $template->update_by;
                        $modelProcess->update_by = $template->update_by;
                    }

                    //save job
                    if (!$modelProcess->save()) {
                        throw new CDbException('Save job failed:' . CVarDumper::dumpAsString($modelProcess->errors));
                    }

                    foreach ($tasks as $task) {

                        $recipientsConfig = $task->getAlertRecipientTemplate2();
                        $emailRecipients = array();
                        foreach ($recipientsConfig as $k => $v) {
                            if ((bool)$v) {
                                $emailRecipients[] = $k;
                            }
                        }
                        $recipients = implode(',', array_keys($recipientsConfig)) ;

                        $task->process_id = $modelProcess->id;
                        unset($task->id);

                        $modelTask = new TaskProcess('insert');
                        $modelTask->attributes = $task->attributes;
                        $modelTask->alert_conditions = $task->alert_conditions;
                        $modelTask->alert_recipients = $recipients;
                        $modelTask->send_mail_recipients = implode(',', $emailRecipients);

                        if (!$modelTask->save()) {
                            throw new CDbException('Save task failed:' . CVarDumper::dumpAsString($modelTask->errors));
                        }
                    }

                    //start process
                    $modelProcess->stage = Process::STAGE_STARTED;
                    $modelProcess->scenario = "startProcess";

                    if (!$modelProcess->save()) {
                        throw new CDbException("Start process failed:" . CVarDumper::dumpAsString($modelTask->errors));
                    }

                    //delete task
                    $ctask->delete();

                    //commit transaction
                    $transaction->commit();
                    $totalAdd++;
                    $err.= "\nClone and started Process #" . $modelProcess->id . " at " . date('Y-m-d H:i:s');
                } catch (Exception $e) {
                    $transaction->rollback();
                    $err.="\nInsert Process failed #" . $templateId . ":" . $e->getMessage();
                }
            }
        }

        Yii::endProfile('test');
        $logger = Yii::getLogger();
        $tpl = "Clone process in schedule "
                . "\nTime: %s"
                . "\nTotal Clone Task: %s"
                . "\nCloned: %s"
                . "\n==="
                . "%s"
                . "\n==="
                . "\nExecution time: %s ";
        $result = sprintf($tpl, $now, count($cloneTasks), $totalAdd, $err, $logger->executionTime);
        Yii::log($result);
        exit;
    }

}
