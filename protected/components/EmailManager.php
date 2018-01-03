<?php
Yii::import('email.components.EEmailManager');

class EmailManager extends EEmailManager
{
    const PRIORITY = 1;

    const ALERT_TASK_NOT_ACCEPT = 'task_not_accept';
    const ALERT_TASK_OVERDUE_IN_1HOUR = 'task_overdue_in_1hour';
    const ALERT_TASK_REASSIGNED = 'task_assigned';
    const ALERT_TASK_REJECTED = 'task_rejected';


    public function notify($to, Alert $alert, $task)
    {
        switch ($alert->alert_type) {
            case Alert::TYPE_TASK_NOT_ACCEPT:
                $template = self::ALERT_TASK_NOT_ACCEPT;
                break;
            case Alert::TYPE_TASK_OVERDUE:
                $template = self::ALERT_TASK_OVERDUE_IN_1HOUR;
                break;
            case Alert::TYPE_TASK_REASSIGN:
                $template = self::ALERT_TASK_REASSIGNED;
                break;
            case Alert::TYPE_TASK_REJECTED:
                $template = self::ALERT_TASK_REJECTED;
                break;
            default:
                return false;
        }

        try {

            $templateParams = array(
                'alert' => $alert,
                'task' => $task,
                'baseUrl' => Yii::app()->params['domain']
            );

            $message = $this->buildTemplateMessage($template, $templateParams, $this->defaultLayout);
            return $this->sendNotify($to, $message);
        } catch (Exception $e) {

            Yii::log('EmailManager:_notify exception:' . $e->getMessage(), CLogger::LEVEL_ERROR);
            return false;
        }
    }

    private function sendNotify($emails, $message)
    {
        // get the message
        $swiftMessage = Swift_Message::newInstance($message['subject']);
        $swiftMessage->setBody($message['message'], 'text/html');
        $swiftMessage->setFrom($this->fromEmail, $this->fromName);
        $swiftMessage->setTo($emails);

        // spool the email
        $emailSpool = $this->getEmailSpool($swiftMessage);
        $emailSpool->priority = self::PRIORITY;
        $emailSpool->transport = $this->defaultTransport;
        return $emailSpool->save(false);
    }


    protected function buildTemplateMessage_php($template, $viewParams = array(), $layout = null)
    {
        $message = array();
        $controller = Yii::app()->controller;
        if($controller == null){
            $controller = new CController('YiiMail');
        }
        foreach ($this->templateFields as $field) {

            $viewParams['contents'] = $controller->renderInternal(Yii::getPathOfAlias($this->templatePath . '.' . $template . '.' . $field) . '.php', $viewParams, true);

            if (!$layout)
                $viewParams[$field] = $message[$field] = $viewParams['contents'];
            else
                $viewParams[$field] = $message[$field] = $controller->renderInternal(Yii::getPathOfAlias($this->templatePath . '.' . $layout . '.' . $field) . '.php', $viewParams, true);

            unset($viewParams['contents']);
        }
        return $message;
    }

    public function buildTemplateMessage($template, $viewParams = array(), $layout = null)
    {
        if ($layout === null)
            $layout = $this->defaultLayout;
        $method = 'buildTemplateMessage_' . $this->templateType;
        if (!method_exists($this, $method))
            $this->templateType = 'php';
        return call_user_func_array(array($this, $method), array($template, $viewParams, $layout));
    }
}