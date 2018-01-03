<?php

class TodoController extends RController
{

    public $defaultAction = 'list';
    public $stage = 'all';

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

    public function actionList($stage='all')
    {
        $this->stage = $stage;
        $this->checkAccess('ToDo_List', array(), true);

        $model = new TaskProcess;

        if (isset($_GET['TaskProcess'])) {
            $model->attributes = $_GET['TaskProcess'];
        }

        if($this->stage != 'all'){
            $model->stage = Yii::app()->request->getParam('stage', TaskProcess::STAGE_ASSIGNED);
        }

        $dataProvider = $model->getTasksByStage();

        $this->render('list', array('model' => $model, 'dataProvider' => $dataProvider, 'stage' => $this->stage));
    }

    public function actionCreate()
    {

        $this->checkAccess('ToDo_Create', array(), true);

        $model = new TaskProcess('insertTodo');

        if (isset($_POST['TaskProcess'])) {

            $model->attributes = $_POST['TaskProcess'];

            if ($model->validate()) {

                if ($model->save(false)) {
                    Yii::app()->user->setFlash('successMessage', 'Created Task successful');
                    $this->redirect(array('/process/todo/update', 'id' => $model->id));
                }
            }
        }

        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id)
    {

        $model = $this->loadModel($id);
        $model->scenario = 'updateTodo';
        if (isset($_POST['TaskProcess'])) {

            $this->checkAccess('ToDo_Update', array(), true);

            $model->attributes = $_POST['TaskProcess'];

            if ($model->save()) {
                Yii::app()->user->setFlash('successMessage', 'Update Task successful');
                $this->refresh();
            }
        }

        $this->render('update', array('model' => $model));
    }

    public function loadModel($id)
    {
        $model = TaskProcess::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    protected function renderTaskName($data, $row, $dataColumn)
    {
        $this->renderPartial('application.views._cell_taskname', array('data' => $data));
    }

    public function checkDeletePermission($model, $throwException = TRUE)
    {

        return $this->checkAccess('ToDo_Delete', array());

    }

    protected function renderTaskActionButton($data, $row, $dataColumn)
    {
        $buttons = array();

        $buttons[] = array(
            'label' => 'View activities',
            'url' => 'javascript:;',
            'icon' => TbHtml::ICON_EYE_OPEN,
            'htmlOptions' => array('linkOptions' => array('data-url' => Yii::app()->createUrl("/process/admin/activities", array("id" => $data["id"])), 'class' => 'view-activity', 'data-token' => Yii::app()->request->csrfToken))
        );

        if ($this->checkDeletePermission($data, false) && empty($data['process_id'])) {
            $buttons[] = array(
                'label' => 'Delete',
                'url' => 'javascript:;',
                'icon' => TbHtml::ICON_TRASH,
                'htmlOptions' => array('linkOptions' => array('data-url' => Yii::app()->createUrl("/process/todo/delete", array("id" => $data["id"])), 'class' => 'delete-item', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if (count($buttons) > 0) {
            echo TbHtml::buttonDropdown('', $buttons, array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        }
    }

    protected function renderProcessName($data, $row, $dataColumn)
    {
        if (!empty($data["process_name"])) {
            return TbHtml::link(Yii::app()->format->formatShortText($data["process_name"], array('length' => 40)), array('/process/admin/update', 'id' => $data["process_id"]));
        }
    }

    public function actionCount()
    {
        header('Content-Type: application/json');

        $model = new TaskProcess;

        echo CJSON::encode(array(
            'all'=> $model->getTotalTaskByStage(null,true),
            TaskProcess::STAGE_ASSIGNED => $model->getTotalTaskByStage(TaskProcess::STAGE_ASSIGNED),
            TaskProcess::STAGE_COMPLETED => $model->getTotalTaskByStage(TaskProcess::STAGE_COMPLETED),
            TaskProcess::STAGE_INPROGRESS => $model->getTotalTaskByStage(TaskProcess::STAGE_INPROGRESS),
            TaskProcess::STAGE_NOTSET => $model->getTotalTaskByStage(TaskProcess::STAGE_NOTSET),
            TaskProcess::STAGE_REJECTED => $model->getTotalTaskByStage(TaskProcess::STAGE_REJECTED),
            TaskProcess::STAGE_WAIRFORCONFIRM => $model->getTotalTaskByStage(TaskProcess::STAGE_WAIRFORCONFIRM),
        ));

        Yii::app()->end();
    }

    public function actionDelete($id)
    {
        $this->layout = false;
        if (Yii::app()->request->isAjaxRequest) {

            if ($this->checkAccess('ToDo_Delete')) {
                $task = TaskProcess::model()->findByPk($id);
                if ($task != null && empty($task->process_id)) {
                    $task->delete();
                }
            } else {
                $this->accessDenied();
            }
        }

        Yii::app()->end();
    }

}
