<?php

class TaskController extends RController {

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations            
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {

        $userId = Yii::app()->user->id;

        $model = new TaskProcess('search');
        $model->assign_id = $userId;

        $this->render('index', array(
            'dataProvider' => $model->search()
        ));
    }

    public function actionNotify() {
        $model = new TaskProcess;
        header('Content-Type: application/json');

        $tasks = $model->taskRequests();

        $now = time();

        $totalTaskAssign1HourAgo = 0;

        foreach ($tasks->getData() as $t) {
            if ($now - strtotime($t['assign_date']) > 3600) {
                $totalTaskAssign1HourAgo++;
            }
        }

        echo CJSON::encode(array(
            'tasks' => (int) $model->getTotalTaskRequests(),
            'tasksAssignedOver1Hour' => $totalTaskAssign1HourAgo
        ));

        Yii::app()->end();
    }

}
