<?php
Yii::import('report.models.*');

class DepartmentSummaryController extends RController
{

    public $staff;
    public $department;
    public $shop;

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

        $formModel = new DailyDepartmentSummary();
        $data = $formModel->getSummary();

        $this->render('index', array(
            'full' => $full,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'department',
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

        $formModel = new DailyDepartmentSummary();
        $data = $formModel->getToday($date);
        $this->render('daily', array(
            'full' => $full,
            'date' => $date,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'department',
                'pagination' => false
            ))
        ));
    }


    public function actionDepartment($department, $full = false)
    {

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->department = $department;

        $formModel = new DailyDepartmentSummary();

        if ($department == Profile::DEPARTMENT_SHOP) {
            $data = $formModel->getByDepartment2($department);

            $this->render('by_department', array(
                'full' => $full,
                'department' => $department,
                'dataProvider' => new CArrayDataProvider($data, array(
                    'keyField' => 'shop_id',
                    'pagination' => false
                ))
            ));
        } else {
            $data = $formModel->getByDepartment($department);

            $this->render('by_department', array(
                'full' => $full,
                'department' => $department,
                'dataProvider' => new CArrayDataProvider($data, array(
                    'keyField' => 'assign_id',
                    'pagination' => false
                ))
            ));
        }

    }

    public function actionDepartmentShop($shop, $department, $full = false)
    {

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->department = $department;
        $this->shop = $shop;

        $formModel = new DailyDepartmentSummary();
        $data = $formModel->getByDepartmentShop($department, $shop);

        $this->render('by_department_shop', array(
            'full' => $full,
            'department' => $department,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'assign_id',
                'pagination' => false
            ))
        ));

    }

    public function actionDailyDepartmentShop($shop, $department, $full = false)
    {

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->department = $department;
        $this->shop = $shop;

        $formModel = new DailyDepartmentSummary();
        $data = $formModel->getDailyDepartmentShop($department, $shop, $date);

        $this->render('daily_department_shop', array(
            'full' => $full,
            'department' => $department,
            'date' => $date,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'assign_id',
                'pagination' => false
            ))
        ));

    }

    public function actionStaff($department, $staff, $full = false)
    {

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->department = $department;
        $formModel = new DailyDepartmentSummary();
        $data = $formModel->byStaff($department, $staff);

        $this->render('by_staff', array(
            'full' => $full,
            'staff' => $staff,
            'department' => $department,
            'dataProvider' => $data
        ));
    }

    public function actionStaffDepartmentShop($shop, $department, $staff, $full = false)
    {

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->department = $department;
        $this->shop = $shop;
        $formModel = new DailyDepartmentSummary();
        $data = $formModel->byStaffDepartmentShop($shop, $department, $staff);

        $this->render('by_staff_department_shop', array(
            'full' => $full,
            'staff' => $staff,
            'department' => $department,
            'dataProvider' => $data
        ));
    }

    public function actionDailyStaffDepartmentShop($shop, $department, $staff, $full = false)
    {

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->department = $department;
        $this->shop = $shop;
        $formModel = new DailyDepartmentSummary();
        $data = $formModel->dailyStaffDepartmentShop($shop, $department, $staff, $date);

        $this->render('daily_staff_department_shop', array(
            'full' => $full,
            'staff' => $staff,
            'date' => $date,
            'department' => $department,
            'dataProvider' => $data
        ));
    }

    public function actionDailyDepartment($department, $full = false)
    {

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->department = $department;

        $formModel = new DailyDepartmentSummary();

        if ($department == Profile::DEPARTMENT_SHOP) {
            $data = $formModel->getDailyByDepartment2($department, $date);
            $this->render('daily_department', array(
                'full' => $full,
                'date' => $date,
                'dataProvider' => new CArrayDataProvider($data, array(
                    'keyField' => 'shop_id',
                    'pagination' => false
                ))
            ));
        } else {
            $data = $formModel->getDailyByDepartment($department, $date);
            $this->render('daily_department', array(
                'full' => $full,
                'date' => $date,
                'dataProvider' => new CArrayDataProvider($data, array(
                    'keyField' => 'assign_id',
                    'pagination' => false
                ))
            ));
        }

    }

    public function actionDailyStaff($department, $staff, $full = false)
    {

        $staffModel = null;
        if ($staff > 0) {
            $staffModel = Profile::model()->getById((int)$staff);
        }

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }
        $this->department = $department;
        $formModel = new DailyDepartmentSummary();
        $data = $formModel->byDailyStaff($department, $staff, $date);
        $this->render('daily_staff', array(
            'full' => $full,
            'staff' => $staff,
            'date' => $date,
            'staffModel' => $staffModel,
            'dataProvider' => $data
        ));
    }
}