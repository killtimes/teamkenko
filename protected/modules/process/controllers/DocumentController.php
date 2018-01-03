<?php

class DocumentController extends RController
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    public $defaultAction = 'list';

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

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
//    public function actionCreate() {
//        $model = new Document;
//
//        // Uncomment the following line if AJAX validation is needed
//        // $this->performAjaxValidation($model);
//
//        if (isset($_POST['Document'])) {
//            $model->attributes = $_POST['Document'];
//            if ($model->save()) {
//                $this->redirect(array('view', 'id' => $model->id));
//            }
//        }
//
//        $this->render('create', array(
//            'model' => $model,
//        ));
//    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
//    public function actionUpdate($id) {
//        $model = $this->loadModel($id);
//
//        // Uncomment the following line if AJAX validation is needed
//        // $this->performAjaxValidation($model);
//
//        if (isset($_POST['Document'])) {
//            $model->attributes = $_POST['Document'];
//            if ($model->save()) {
//                $this->redirect(array('view', 'id' => $model->id));
//            }
//        }
//
//        $this->render('update', array(
//            'model' => $model,
//        ));
//    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
//    public function actionDelete($id) {
//        if (Yii::app()->request->isPostRequest) {
//            // we only allow deletion via POST request
//            $this->loadModel($id)->delete();
//
//            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//            if (!isset($_GET['ajax'])) {
//                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//            }
//        } else {
//            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
//        }
//    }

    /**
     * Lists all models.
     */
//    public function actionIndex() {
//        $dataProvider = new CActiveDataProvider('Document');
//        $this->render('index', array(
//            'dataProvider' => $dataProvider,
//        ));
//    }

    /**
     * Manages all models.
     */
    public function actionList()
    {


        $this->checkAccess('Document_List', array(), true);

        $form = new DocumentFilterForm();

        if (isset($_GET['DocumentFilterForm'])) {

            $form->attributes = $_GET['DocumentFilterForm'];
            $form->parseDateRange();

            if (!$form->validate(array('filter_by'))) {
                $form->filter_by = '';
                $form->date_range = '';
            } else {
                //cache search filter
                $form->setCacheQuery($_GET['DocumentFilterForm']);
            }
        } else {
            $cached = $form->getCacheQuery();
            if ($cached !== false) {
                $form->attributes = $cached;
            }
            $form->parseDateRange();
        }

        $criteria = $form->search();

        $dataProvider = new CActiveDataProvider('Document', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25
            ),
            'sort' => array(
                'defaultOrder' => 'create_date desc'
            )
        ));

        $this->render('list', array(
            'formFilter' => $form,
            'dataProvider' => $dataProvider
        ));

    }

    public function checkDeletePermission($model, $throwException = TRUE)
    {
        return $this->checkAccess('Document_Delete');
    }

    public function actionDelete($id)
    {
        $this->layout = false;
        if (Yii::app()->request->isAjaxRequest) {
            if ($this->checkDeletePermission(null, false)) {
                $document = Document::model()->findByPk($id);

                if ($document != null) {
                    $document->delete();
                } else {
                    $this->notfoundException();
                }

            } else {
                $this->accessDenied();
            }
        }
        Yii::app()->end();
    }

    protected function renderActionButton($data, $row, $dataColumn)
    {
        $buttons = array();

        if ($this->checkDeletePermission($data, false)) {
            $buttons[] = array(
                'label' => 'Delete',
                'url' => 'javascript:;',
                'icon' => TbHtml::ICON_TRASH,
                'htmlOptions' => array('linkOptions' => array('data-url' => Yii::app()->createUrl("/process/document/delete", array("id" => $data["id"])), 'class' => 'delete-item', 'data-token' => Yii::app()->request->csrfToken))
            );
        }

        if (count($buttons) > 0) {
            echo TbHtml::buttonDropdown('', $buttons, array('groupOptions' => array('pull' => TbHtml::PULL_RIGHT), 'icon' => TbHtml::ICON_COG, 'size' => TbHtml::BUTTON_SIZE_SMALL, 'menuOptions' => array('pull' => TbHtml::PULL_RIGHT)));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Document the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Document::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Document $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'document-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function renderDocName($data, $row, $dataColumn)
    {
        return $this->renderPartial('_cellname', $data, TRUE);
    }

}
