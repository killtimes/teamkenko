<?php
Yii::import('report.models.*');

class ShopSummaryController extends RController
{

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

        $formModel = new DailyShopSummary();
        $data = $formModel->getSummary();
        $this->render('index', array(
            'full' => $full,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'shop_id',
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

        $formModel = new DailyShopSummary();
        $data = $formModel->getToday($date);
        $this->render('daily', array(
            'full' => $full,
            'date' => $date,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'shop_id',
                'pagination' => false
            ))
        ));
    }

    public function actionDailyShop($shop, $full = false)
    {
        $shopModel = null;
        if ($shop > 0) {
            $shopModel = Shop::model()->findByPk((int)$shop);
        }

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->shop = $shop;

        $formModel = new DailyShopSummary();
        $data = $formModel->getDailyByShop($shop, $date);
        $this->render('daily_group', array(
            'full' => $full,
            'date' => $date,
            'shopModel' => $shopModel,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'assign_id',
                'pagination' => false
            ))
        ));
    }

    public function actionShop($shop, $full = false)
    {
        $shopModel = null;
        if ($shop > 0) {
            $shopModel = Shop::model()->findByPk((int)$shop);
        }

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->shop = $shop;

        $formModel = new DailyShopSummary();
        $data = $formModel->getByShop($shop);
        $this->render('by_group', array(
            'full' => $full,
            'shopModel' => $shopModel,
            'dataProvider' => new CArrayDataProvider($data, array(
                'keyField' => 'assign_id',
                'pagination' => false
            ))
        ));
    }

    public function actionStaff($shop, $staff, $full = false)
    {
        $shopModel = null;
        if ($shop > 0) {
            $shopModel = Shop::model()->findByPk((int)$shop);
        }

        if ($full) {
            $this->layout = false;
            Yii::app()->bootstrap->register();
            Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999);
        }

        $this->shop = $shop;
        $formModel = new DailyShopSummary();
        $data = $formModel->byStaff($shop, $staff);

        $this->render('by_staff', array(
            'full' => $full,
            'staff' => $staff,
            'shopModel' => $shopModel,
            'dataProvider' => $data
        ));
    }

    public function actionDailyStaff($shop, $staff, $full = false)
    {
        $shopModel = null;
        if ($shop > 0) {
            $shopModel = Shop::model()->findByPk((int)$shop);
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
        $this->shop = $shop;
        $formModel = new DailyShopSummary();
        $data = $formModel->byDailyStaff($shop, $staff, $date);
        $this->render('daily_staff', array(
            'full' => $full,
            'staff' => $staff,
            'date' => $date,
            'shopModel' => $shopModel,
            'staffModel' => $staffModel,
            'dataProvider' => $data
        ));
    }
}