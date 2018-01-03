
<?php
echo TbHtml::button('Add Task', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'icon' => TbHtml::ICON_OK_SIGN,
    'class' => 'add-button',
    'id' => 'addTask',
    'size' => TbHtml::BUTTON_SIZE_SMALL,
    'data-url' => CController::createUrl('/template/process/createTask', array('id' => $modelProcess->id))
));

Yii::app()->clientScript->registerScript('ajax-add-task', 'ProcessPage.initialize()', CClientScript::POS_READY);

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery.ui');
Yii::app()->getClientScript()->registerScript('sort-grid', '
    ProcessPage.initSortableGridview("' . CController::createUrl('process/sortTask', array('id'=>$modelProcess->id)) . '","' . Yii::app()->request->csrfToken . '");               
    ProcessPage.initPopover();
    $(document).tooltip({
        selector:"abbr",
        trigger:"hover"
    });
', CClientScript::POS_READY);
?>

<?php
$this->widget('\TbGridView', array(
    'id' => 'task-process-grid',
    'dataProvider' => $model->search(),
    'type' => array(
        TbHtml::GRID_TYPE_HOVER,
        TbHtml::GRID_TYPE_CONDENSED,
    ),
    'rowHtmlOptionsExpression' => 'array("id"=>$data->id,"class"=> "sort-enable")',
    'afterAjaxUpdate' => 'js:function(id, data){
        ProcessPage.initialize();
        ProcessPage.initSortableGridview("' . CController::createUrl('process/sortTask', array('id'=>$modelProcess->id)) . '","' . Yii::app()->request->csrfToken . '");        
    }',
    'columns' => array(
        'id',
        array(
            'name' => 'task',
            'type' => 'raw',
            'value' => array($this, 'renderTaskName')
        ),
        array(
            'name' => 'assigned_to',
            'header' => 'Assigned To',
            'type' => 'raw',
            'value' => 'Profile::model()->getById($data->assign_id)->getFullName()'
        ),
        'duration',
        array(
            'name'=>'create_date',
            'type'=>'raw',
            'value'=> 'TbHtml::abbr(Yii::app()->format->timeAgo($data->create_date),Yii::app()->localTime->fromUTC($data->create_date))'
        ),
        array(
            'value' => array($this, 'renderActionTaskButton')
        ),
    ),
));
?>

<?php
$this->beginWidget('\TbModal', array(
    'id' => 'editTask',
    'header' => 'Add/Edit Task',
    'size'=>  TbHtml::MODAL_SIZE_LARGE,
    'footer' => array(
        TbHtml::button('Save', array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'onclick' => 'js:$("#edit-task-form").submit();')),
        TbHtml::button('Close', array('data-dismiss' => 'modal')),
    )
));
?>
<div class="progress">
    <div class="progress-bar progress-bar-striped active" role="progressbar"
         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">

    </div>
</div>
<?php $this->endWidget(); ?>