<?php
Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/js/jquery.counter.min.js', CClientScript::POS_END);

echo TbHtml::button('Add Task', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'icon' => TbHtml::ICON_PLUS_SIGN,
    'class' => 'add-button',
    'id' => 'addTask',
    'size' => TbHtml::BUTTON_SIZE_SMALL,
    'data-url' => CController::createUrl('/process/admin/createTask', array('id' => $modelProcess->id))
));

Yii::app()->clientScript->registerScript('ajax-add-task', 'ProcessPage.initialize()', CClientScript::POS_READY);

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery.ui');
$cs->registerCoreScript('yiiactiveform');

Yii::app()->getClientScript()->registerScript('sort-grid', '
    ProcessPage.initSortableGridview("' . CController::createUrl('admin/sortTask', array('id' => $modelProcess->id)) . '","' . Yii::app()->request->csrfToken . '"); 
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
    'enableSorting' => false,
    'type' => array(
        TbHtml::GRID_TYPE_HOVER,
        TbHtml::GRID_TYPE_CONDENSED,
    ),
    'rowHtmlOptionsExpression' => 'array("id"=>$data->id,"class"=> (($data->stage==0)?"sort-enable":""))',
    'afterAjaxUpdate' => 'js:function(id, data){
        ProcessPage.initialize();
        ProcessPage.initSortableGridview("' . CController::createUrl('admin/sortTask',array('id' => $modelProcess->id)) . '","' . Yii::app()->request->csrfToken . '");        
    }',
    'columns' => array(
        'id',
        array(
            'name' => 'task',
            'type' => 'raw',
            'value' => array($this, 'renderTaskName')
        ),
        array(
            'header' => 'Assigned To',
            'type' => 'raw',
            'value' => 'Profile::model()->getById($data->assign_id)->getFullName()'
        ),
        'duration',
        array(
            'name' => 'stage',
            'header' => 'Status',
            'type' => 'raw',
            'value' => 'TaskProcess::stageAlias($data->stage)'
        ),
        array(
            'header' => 'Assigned',
            'type' => 'raw',
            'value' => '($data->assign_date)?"<abbr title=\"".Yii::app()->localTime->fromUTC($data->assign_date)."\">".Yii::app()->format->timeAgo($data->assign_date)."</abbr>":""'
        ),
        array(
            'header' => 'Due',
            'type' => 'raw',
            'value' => '($data->due_date)?"<abbr title=\"".Yii::app()->localTime->fromUTC($data->due_date)."\">".Yii::app()->format->timeAgo($data->due_date)."</abbr>":""'
        ),
        array(
            'value' => array($this, 'renderTaskActionButton')
        ),
    ),
));
?>

<?php
$this->beginWidget('\TbModal', array(
    'id' => 'editTask',
    'size'=> TbHtml::MODAL_SIZE_LARGE,
    'header' => 'Add/Edit Task',
    'footer' => array(
        TbHtml::button('Save', array('id'=>'btnSave', 'color' => TbHtml::BUTTON_COLOR_PRIMARY, 'onclick' => 'js:$("#edit-task-form").submit();')),
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

<?php $this->renderPartial('process.views.task._task_activity'); ?>

<!--
<?php
$this->widget('yiiwheels.widgets.switch.WhSwitch', array(
    'name' => 'not-use'
));
?>
-->
