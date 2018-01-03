<?php

class DefaultController extends RController
{


    public function filters()
    {
        return CMap::mergeArray(parent::filters(), array(
//            'rights',
            'accessControl', // perform access control for CRUD operations
        ));
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('test'),
                'users' => array('*')
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'delete', 'create', 'update', 'view', 'abc'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {

        $model = new TaskProcess;

        $this->render('index', array(
            'model' => $model
        ));
    }

    public function actionTest()
    {

//        $alert = Alert::model()->findByPk(476);
//
//        $taskId = $alert->related_task_id;
//
//        $task = TaskProcess::model()->findByPk($taskId);
//
////        $recipients = $task->getEmailsAlertRecipients();
//        $recipients = array('tamhv87@gmail.com');
//
//        $a = Yii::app()->emailManager->notify($recipients, $alert, $task);
//        var_dump($a);
    }

}
