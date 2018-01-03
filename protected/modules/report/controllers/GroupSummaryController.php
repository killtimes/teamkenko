<?php
Yii::import('report.models.*');

class GroupSummaryController extends RController
{

    public $group;

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

    public function init()
    {
        parent::init();
        $this->checkAccess('Report_TaskSummary', array(), true);
    }

    public function actionIndex($full = false)
    {
        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $formModel = new DailyGroupSummary();
        $data = $formModel->getSummary();
        $this->render('index', array(
            'full' => $full,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'task_group',
                'pagination' => false
            ))
        ));
    }

    public function actionDaily($full = false)
    {
        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $formModel = new DailyGroupSummary();
        $data = $formModel->getToday($date);
        $this->render('daily', array(
            'full' => $full,
            'date' => $date,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'task_group',
                'pagination' => false
            ))
        ));
    }

    public function actionDailyGroup($group, $full = false)
    {
        $groupModel = null;
        if ($group > 0) {
            $groupModel = TaskGroup::model()->findByPk((int)$group);
        }

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->group = $group;

        $formModel = new DailyGroupSummary();
        $data = $formModel->getDailyByGroup($group, $date);
        $this->render('daily_group', array(
            'full' => $full,
            'group' => $group,
            'date' => $date,
            'groupModel' => $groupModel,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'assign_id',
                'pagination' => false
            ))
        ));
    }

    public function actionGroup($group, $full = false)
    {
        $groupModel = null;
        if ($group > 0) {
            $groupModel = TaskGroup::model()->findByPk((int)$group);
        }

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->group = $group;

        $formModel = new DailyGroupSummary();
        $data = $formModel->getByGroup($group);
        $this->render('by_group', array(
            'full' => $full,
            'group' => $group,
            'groupModel' => $groupModel,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'assign_id',
                'pagination' => false
            ))
        ));
    }

    public function actionStaff($group, $staff, $full = false)
    {
        $groupModel = null;
        if ($group > 0) {
            $groupModel = TaskGroup::model()->findByPk((int)$group);
        }

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->group = $group;
        $formModel = new DailyGroupSummary();
        $data = $formModel->byStaff($group, $staff);

        $this->render('by_staff', array(
            'full' => $full,
            'staff' => $staff,
            'groupModel' => $groupModel,
            'dataProvider' => $data
        ));
    }

    public function actionDailyStaff($group, $staff, $full = false)
    {
        $groupModel = null;
        if ($group > 0) {
            $groupModel = TaskGroup::model()->findByPk((int)$group);
        }

        $staffModel = null;
        if ($staff > 0) {
            $staffModel = Profile::model()->getById((int)$staff);
        }

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }
        $this->group = $group;
        $formModel = new DailyGroupSummary();
        $data = $formModel->byDailyStaff($group, $staff, $date);
        $this->render('daily_staff', array(
            'full' => $full,
            'staff' => $staff,
            'date' => $date,
            'groupModel' => $groupModel,
            'staffModel' => $staffModel,
            'dataProvider' => $data
        ));
    }
}