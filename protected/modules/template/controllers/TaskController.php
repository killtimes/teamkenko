<?php

class TaskController extends RController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    public $defaultAction = 'admin';

    /**
     * @return array action filters
     */
    public function filters() {
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
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
//    public function actionView($id) {
//        $this->render('view', array(
//            'model' => $this->loadModel($id),
//        ));
//    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {

        $this->checkAccess('TaskTemplate_Create', array(), true);

        $model = new Task;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Task'])) {
            $model->attributes = $_POST['Task'];
            if ($model->save()) {
                Yii::app()->user->setFlash('successMessage', 'Created Task successful');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $this->checkAccess('TaskTemplate_View', array(), true);

        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Task'])) {

            $this->checkAccess('TaskTemplate_Update', array(), true);

            $model->attributes = $_POST['Task'];
            if ($model->save()) {
                Yii::app()->user->setFlash('successMessage', 'Updated Task successful');

                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {

            $this->checkAccess('TaskTemplate_Delete', array(), true);

            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Lists all models.
     */
//    public function actionIndex() {
//        $dataProvider = new CActiveDataProvider('Task');
//        $this->render('index', array(
//            'dataProvider' => $dataProvider,
//        ));
//    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $this->checkAccess('TaskTemplate_List', array(), true);

        $model = new Task('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Task'])) {
            $model->attributes = $_GET['Task'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Task the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Task::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Task $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'task-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionList() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $limit = 10;
        $keyword = Yii::app()->getRequest()->getParam('q', '');
//        $task_group = Yii::app()->getRequest()->getParam('g', '');

//        $filterByTaskGroup = '';
//        if (!empty($task_group)) {
//            $filterByTaskGroup .= ' u.task_group=' . intval($task_group) . ' AND ';
//        }

        $page = Yii::app()->getRequest()->getParam('page', 1);
        $offset = ($page - 1) * $limit;

        //search contact
        $connection = Yii::app()->db;
        $sql = "SELECT SQL_CALC_FOUND_ROWS u.id, u.name,u.duration,tg.name as `group` "
                . " FROM `Task` u"
                . " INNER JOIN `TaskGroup` tg ON u.task_group=tg.id "
                . " WHERE u.status=1 && (tg.name LIKE :keyword OR  u.name LIKE :keyword) "
                . " LIMIT :limit "
                . " OFFSET :offset ";

        $command = $connection->createCommand($sql);
        $command->bindValue(':keyword', "%" . $keyword . "%", PDO::PARAM_STR);
        $command->bindValue(':limit', $limit, PDO::PARAM_INT);
        $command->bindValue(':offset', $offset, PDO::PARAM_INT);

        $rows = $command->queryAll();

        //get total for paging
        $total = Yii::app()->db->createCommand('SELECT FOUND_ROWS() AS total')->queryScalar();

        $data = array();
        $data['total'] = $total;
        $data['results'] = array();

        foreach ($rows as $item) {
            $data['results'][] = array(
                'id' => $item['id'],
                'text' => $item['name'],
                'group' => $item['group'],
                'duration' => $item['duration'],
            );
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function actionLoad() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $id = filter_input(INPUT_GET, 'qid');

        if (empty($id)) {
            throw new CHttpException('404', 'Missing "term" GET parameter.');
        }

        $sql = "SELECT u.id, u.name, u.description, u.duration, tg.name as `group` "
                . " FROM `Task` u"
                . " INNER JOIN `TaskGroup` tg ON u.task_group=tg.id "
                . " WHERE u.id=:qid "
                . " LIMIT 1 ";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(':qid', $id, PDO::PARAM_INT);
        $item = $cmd->queryRow();

        $data = array(
            'id' => $item['id'],
            'text' => $item['name'],
            'group' => $item['group'],
            'duration' => $item['duration'],
        );

        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function renderActionButton($data, $row, $dataColumn) {

        $buttons = array();

        if ($this->checkAccess('TaskTemplate_Delete', array())) {
            $buttons[] = array(
                'label' => 'Delete',
                'url' => Yii::app()->createUrl("/template/task/delete", array("id" => $data->id)),
                'visible' => true,
                'icon' => TbHtml::ICON_TRASH,
                'htmlOptions' => array('linkOptions' => array('class' => 'delete', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if (count($buttons) > 0) {
            echo TbHtml::buttonDropdown('', $buttons, array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        }
    }

}
