<?php

class CalendarController extends RController
{

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations            
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionParseTask($month, $year)
    {

        $result = array();

        if (!checkdate($month, 1, $year)) {
            $month = date('m');
            $year = date('Y');
        }
        $filterUser = '';
        $params = array();
        if (!Yii::app()->user->hasRole('Manager') && !Yii::app()->user->hasRole('Admin')) {
            $filterUser = ' AND assign_id=:assign_id ';
            $params[':assign_id'] = Yii::app()->user->id;
        }

        $params[':first_day'] = $year . '-' . $month . '-01 00:00:00';
        $params[':status'] = Process::STATUS_ACTIVE;

        $tasks = TaskProcess::model()->findAll("status=:status $filterUser AND assign_date>=:first_day AND assign_date<(DATE(:first_day) + INTERVAL 1 MONTH)", $params);

        foreach ($tasks as $t) {
            $day = date('j-n', strtotime($t->assign_date));

            if (!isset($result[$day])) {
                $result[$day] = array(
                    'total' => 0,
                    'completed' => 0
                );
            }

            $result[$day]['total']++;

            if ($t->stage == TaskProcess::STAGE_COMPLETED) {
                $result[$day]['completed']++;
            }
        }

        header('Content-Type: application/json');
        echo CJSON::encode($result);
    }

    public function actionListTask($date)
    {
        list($year, $month, $day) = explode('-', $date);
        if (!checkdate($month, $day, $year)) {
            $this->notfoundException();
        }
        $userId = ' ';
        if (!Yii::app()->user->hasRole('Manager') && !Yii::app()->user->hasRole('Admin')) {
            $userId = Yii::app()->user->id;
        }

        $dataProvider = TaskProcess::model()->getUserTaskByDate($date, $userId);

        $html = $this->renderPartial('listtask', array(
            'dataProvider' => $dataProvider,
            'date' => $date,
            'userId' => $userId
        ), true, false);

        $jsInline = '';

        foreach (Yii::app()->clientscript->scripts as $k => $js) {
            $jsInline .= implode('', $js);
        }

        echo $html . CHtml::script($jsInline);
        Yii::app()->end();
    }

    public function actionAddTask($date)
    {

        $this->checkAccess('ToDo_Create', array(), true);

        $model = new TaskProcess('insertTodo');
        if (!Yii::app()->user->hasRole('Manager') && !Yii::app()->user->hasRole('Admin')) {
            $model->assign_id = Yii::app()->user->id;
        }

        if (isset($_POST['TaskProcess'])) {

            list($y, $m, $d) = explode('-', $date);

            if (!checkdate($m, $d, $y)) {
                $model->addError('general', 'An error occurred. Please try again');
            }

            $dateWithTime = $date . ' ' . $_POST['TaskProcess']['start_time'] . ':00';

            $datet = strtotime($dateWithTime);
            $dateFormated = date('Y-m-d h:i:s', $datet);

            $model->attributes = $_POST['TaskProcess'];
            if (!Yii::app()->user->hasRole('Manager') && !Yii::app()->user->hasRole('Admin')) {
                $model->assign_id = Yii::app()->user->id;
            }

            if (!$model->hasErrors() && $model->validate()) {

                $tran = Yii::app()->db->beginTransaction();
                try {

                    if (!$model->save(false)) {
                        throw new CDbException('Save task failed');
                    }
                    $model->scenario = 'updateAssignDate';
                    $model->assign_date = $dateFormated;
                    $dueDate = $datet + $model->duration * 3600;
                    $model->due_date = date('Y-m-d h:i:s', $dueDate);
                    if (!$model->update(array('assign_date', 'due_date'))) {
                        throw new CDbException('Update assign date failed');
                    }

                    $tran->commit();
                    $model = new TaskProcess;
                    if (!Yii::app()->user->hasRole('Manager') && !Yii::app()->user->hasRole('Admin')) {
                        $model->assign_id = Yii::app()->user->id;
                    }

                    Yii::app()->user->setFlash('successMessage', 'Added new task successful');
                } catch (Exception $e) {
                    $tran->rollback();
                    $model->addError('general', 'An error occurred. Please try again');
                }
            }
        }

        $department = array();
        if (!Yii::app()->user->hasRole('Manager') && !Yii::app()->user->hasRole('Admin')) {
            $department = Profile::model()->getDepartment(Yii::app()->user->id);
        }


        $html = $this->renderPartial('_add_task_form', array(
            'model' => $model,
            'date' => $date,
            'department' => $department
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
    }

}
