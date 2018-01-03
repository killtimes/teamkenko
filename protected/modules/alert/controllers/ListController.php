<?php

class ListController extends RController
{
    public $type = '';

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

    public function actionIndex($type = '')
    {
        $this->type = $type;

        $model = new Alert('search');
        $model->unsetAttributes();
        $model->to_user_id = Yii::app()->user->id;
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


        $this->render('index', array(
            'dataProvider' => $model->searchByUser()
        ));
    }

    public function actionView($id, $type = '')
    {
        $this->type = $type;

        $model = Alert::model()->findByPk($id);

        if ($model == null || $model->stage == Alert::STAGE_DELETED) {
            $this->notfoundException();
        }

        if (isset($_POST['reassign']) && isset($_POST['TaskProcess'])) {

            if ($model->to_user_id != Yii::app()->user->id && !$this->checkAccess('Alert_ReassignTask')) {
                $this->accessDenied();
            }

            $taskProcessId = $_POST['TaskProcess']['id'];
            $newAssignId = $_POST['TaskProcess']['assign_id'];

            $taskProcess = TaskProcess::model()->findByPk($taskProcessId);
            if ($taskProcess == null) {
                $this->notfoundException();
            }

            $user = Profile::model()->getById($newAssignId);

            if ($user == null) {
                $this->notfoundException();
            }

            $taskProcess->assign_id = $newAssignId;
            $taskProcess->assign_date = Yii::app()->localTime->getUTCNow('Y-m-d H:i:s');

            if ($taskProcess->save(false, array('assign_id', 'assign_date'))) {
                Yii::app()->user->setFlash('successMessage', 'Task has been re-assigned');
                $this->refresh();
            }

        } else if (isset($_POST['resolve'])) {

            if (!$this->checkAccess('Alert_Resolve')) {
                $this->accessDenied();
            }

            $model->stage = Alert::STAGE_RESOLVED;

            if ($model->save(false, array('stage'))) {
                Yii::app()->user->setFlash('successMessage', 'Alert has been resolved');
                $this->refresh();
            }
        } else if (isset($_POST['unresolve'])) {

            if (!$this->checkAccess('Alert_Resolve')) {
                $this->accessDenied();
            }

            $model->stage = Alert::STAGE_ACTIVE;

            if ($model->save(false, array('stage'))) {
                Yii::app()->user->setFlash('successMessage', 'Alert stage has been changed back to Active');
                $this->refresh();
            }

        } else if (isset($_POST['changecriticalstatus']) && isset($_POST['Alert'])) {

            $newStatus = $_POST['Alert']['status'];

            if ($newStatus > $model->status) {

                if (!$this->checkAccess('Alert_UpCriticalStatus')) {
                    $this->accessDenied('Permission denied: Upgrade critical status');
                }

                $model->status = $newStatus;

                if ($model->save(false, array('status'))) {
                    Yii::app()->user->setFlash('successMessage', 'Critical status has been upgraded');
                    $this->refresh();
                }

            } else if ($newStatus < $model->status) {

                if ($model->to_user_id != Yii::app()->user->id && !$this->checkAccess('Alert_DownCriticalStatus')) {
                    $this->accessDenied('Permission denied: Downgrade critical status');
                }

                $model->status = $newStatus;

                if ($model->save(false, array('status'))) {
                    Yii::app()->user->setFlash('successMessage', 'Critical status has been downgraded');
                    $this->refresh();
                }
            }

        }

        $this->render('view', array(
            'model' => $model
        ));
    }
}