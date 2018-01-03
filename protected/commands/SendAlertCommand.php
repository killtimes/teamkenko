<?php
Yii::import('application.modules.process.models.*');
Yii::import('application.modules.template.models.*');
Yii::import('application.modules.alert.models.*');
Yii::import('application.modules.supplier.models.Supplier');
Yii::import('application.modules.shop.models.Shop');

class SendAlertCommand extends CConsoleCommand
{
    public $defaultAction = 'help';

    public function actionHelp()
    {
        echo $this->getHelp();
    }

    public function actionProcessTasks()
    {

        $db = Yii::app()->db;

        $taskAlert = $db->createCommand()
            ->select('t.*')
            ->from('TaskProcess t')
            ->join('Process p', 'p.id=t.process_id')
            ->where('p.status=:p_status and p.stage>=:p_stage1 and p.stage<>:p_stage2  and t.status=:t_status and t.stage>=:t_stage1 and t.stage<>:t_stage2 and t.alert_enable=1', array(
                ':p_status' => Process::STATUS_ACTIVE,
                ':p_stage1' => Process::STAGE_STARTED,
                ':p_stage2' => Process::STAGE_DONE,
                ':t_stage1' => TaskProcess::STAGE_ASSIGNED,
                ':t_stage2' => TaskProcess::STAGE_COMPLETED,
                ':t_status' => TaskProcess::STATUS_ACTIVE,
            ))->queryAll();

        //get tasks
        $c = count($taskAlert);
        $this->log('Total scheduled alerts:' . $c);
        if ($c == 0) {
            $this->log('No task with alert. STOP.');
            return;
        }

        $taskAlertFmt = array();
        $taskIds = array();
        foreach ($taskAlert as $r) {
            $taskAlertFmt[$r['id']] = $r;

            if (!in_array($r['id'], $taskIds)) {
                $taskIds[] = $r['id'];
            }
        }

        if (count($taskIds) <= 0) {
            $this->log('No task id, STOP.');
            return;
        }

        //get alert recepient by task id
        $alertUser = $db->createCommand()
            ->select('task_id,user_id')
            ->from('AlertRecipientConfig')
            ->where('task_id in (' . implode(',', $taskIds) . ')')->queryAll();

        $alertUserFmt = array();
        foreach ($alertUser as $r) {
            $alertUserFmt[$r['task_id']][] = $r['user_id'];
            $taskAlertFmt[$r['task_id']]['alert_recipients'] = $alertUserFmt[$r['task_id']];
        }


        //start process sending alert
        foreach ($taskAlertFmt as $task_id => $task) {
            $this->processAlerts($task);
        }
    }

    private function processAlerts($task)
    {
        //check condition 1: alert if task not accept in 1 hour
        if ($this->isTaskNotAcceptIn1Hour($task)) {
            $this->log('match rule NotAccept #' . $task['id']);
            $this->createAlert(Alert::TYPE_TASK_NOT_ACCEPT, $task);
        }

        if ($this->isTaskOverdue($task)) {
            $this->log('match rule Overdue #' . $task['id']);
            $this->createAlert(Alert::TYPE_TASK_OVERDUE, $task);
        }
    }

    private function createAlert($type, $task, $status = Alert::STATUS_NORMAL)
    {

        $alert = new Alert();
        $alert->alert_type = $type;
        $alert->create_by = Yii::app()->user->id;
        $alert->to_user_id = $task['alert_recipient'];
        $alert->stage = Alert::STAGE_ACTIVE;
        $alert->status = $status;
        $alert->related_task_id = $task['id'];
        $alert->note = '';

        if (isset($task['alert_recipients']) && is_array($task['alert_recipients']) && count($task['alert_recipients']) > 0) {
            $alert->to_users = implode(',', $task['alert_recipients']);
            $alert->to_user_id = null;
        } else {
            $alert->to_users = $task['alert_recipient'];
        }

        if (!$alert->save()) {
            $this->log(CVarDumper::dumpAsString($alert->errors));
            return;
        }

        //spool send mail
        $task = TaskProcess::model()->findByPk($alert->related_task_id);
        if($task != null){

            $recipients = $task->getEmailsAlertRecipients();

            Yii::app()->emailManager->notify($recipients, $alert, $task);
        }

        $this->log('created #' . $alert->id);
    }

    private function isTaskOverdue($task)
    {
        if ((bool)($task['alert_condition'] & TaskProcessTemplate::ALERT_COND_OVER_DUE) == false) {
            return false;
        }

        if (empty($task['due_date'])) {
            return false;
        }

        $duration = round($task['duration'] * 3600 * 0.2);
        $dueTime = Yii::app()->localTime->getTimestamp($task['due_date']);
        $now = Yii::app()->localTime->getTimestamp();

        $remainSecondToMatch = ($dueTime + $duration) - $now;
        $this->log('task #' . $task['id'] . ' match rule Overdue in ' . $remainSecondToMatch);

        if ($this->checkExistingAlert($task['id'], Alert::TYPE_TASK_OVERDUE)) {
            $this->log('task #' . $task['id'] . ' alert existing');
            return false;
        }

        return ($dueTime + $duration < $now);
    }

    private function isTaskNotAcceptIn1Hour($task)
    {

        if ((bool)($task['alert_condition'] & TaskProcessTemplate::ALERT_COND_NOT_ACCEPT) == false) {
            return false;
        }

        if ($task['stage'] != TaskProcess::STAGE_ASSIGNED || empty($task['assign_date'])) {
            return false;
        }

        //20% time => send alert
        $duration = round($task['duration'] * 3600 * 0.2);
        $assignTime = Yii::app()->localTime->getTimestamp($task['assign_date']);
        $now = Yii::app()->localTime->getTimestamp();

        $remainSecondToMatch = ($assignTime + $duration) - $now;
        $this->log('task #' . $task['id'] . ' match rule NotAcceptIn1Hour in ' . $remainSecondToMatch);

        //check existing alert
        if ($this->checkExistingAlert($task['id'], Alert::TYPE_TASK_NOT_ACCEPT)) {
            $this->log('task #' . $task['id'] . ' alert existing');
            return false;
        }

        return ($assignTime + $duration < $now);
    }

    private function checkExistingAlert($task_id, $type)
    {
        //check existing alert
        $existAlert = Alert::model()->exists('related_task_id=:task_id and alert_type=:alert_type and stage=:stage', array(
            ':task_id' => $task_id,
            ':alert_type' => $type,
            ':stage' => Alert::STAGE_ACTIVE
        ));

        return $existAlert;
    }

    private function log($s)
    {
        echo $s . PHP_EOL;
    }
}