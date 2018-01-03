<?php
$this->breadcrumbs = array(
    'Tasks' => array('/process/todo/list'),
    'Listing',
);
echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Task',
        'url' => array('/process/todo/create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Process_Create', array())
    )
));
$url = Yii::app()->createUrl('/process/todo/count');
Yii::app()->getClientScript()->registerScript('tasks', "
	TaskPage.fetchTotalTaskByStage('{$url}');
        ProcessPage.initPopover();
        $(document).tooltip({
            selector:'abbr',
            trigger:'hover'
        });
", CClientScript::POS_READY);


Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#tasks-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="panel with-nav-tabs panel-default panel-container">
    <div class="panel-heading">
        <?php $this->renderPartial('_tabs', array('stage' => $this->stage)); ?>
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade in active">

                <?php
                $this->renderPartial('_search', array('model' => $model));
                $this->widget('\TbGridView', array(
                    'id' => 'tasks-grid',
//                    'htmlOptions' => array('class' => 'grid-view panel-container'),
                    'dataProvider' => $dataProvider,
                    'enableSorting' => false,
                    'type' => array(
                        TbHtml::GRID_TYPE_HOVER,
                        TbHtml::GRID_TYPE_CONDENSED,
                    ),
                    'columns' => array(
                        array(
                            'header' => 'ID',
                            'name' => 'id'
                        ),
                        array(
                            'header' => 'Task',
                            'type' => 'raw',
                            'value' => array($this, 'renderTaskName')
                        ),
                        array(
                            'header' => 'Staff',
                            'type' => 'raw',
                            'value' => 'Profile::model()->getById($data["assign_id"])->getFullName()'
                        ),
                        array(
                            'header' => 'Job',
                            'type' => 'raw',
                            'value' => array($this, 'renderProcessName')
                        ),
                        array(
                            'header' => 'Duration',
                            'value' => '$data["duration"]." hour(s)"'
                        ),
                        array(
                            'name' => 'stage',
                            'header' => 'Status',
                            'type' => 'raw',
                            'value' => 'TaskProcess::stageAlias($data["stage"])',
                            'visible' => $this->stage != TaskProcess::STAGE_NOTSET.''
                        ),
                        array(
                            'header' => 'Create Date',
                            'type' => 'raw',
                            'value' => '($data["create_date"])?"<abbr title=\"".Yii::app()->localTime->fromUTC($data["create_date"])."\">".Yii::app()->format->timeAgo($data["create_date"])."</abbr>":""',
                            'visible' => $this->stage === 'all'
                        ),
                        array(
                            'header' => 'Assign Date',
                            'type' => 'raw',
                            'value' => '($data["assign_date"])?"<abbr title=\"".Yii::app()->localTime->fromUTC($data["assign_date"])."\">".Yii::app()->format->timeAgo($data["assign_date"])."</abbr>":""',
                            'visible' => $this->stage != TaskProcess::STAGE_NOTSET
                        ),
                        array(
                            'header' => 'Reject Date',
                            'type' => 'raw',
                            'value' => '($data["reject_date"])?"<abbr title=\"".Yii::app()->localTime->fromUTC($data["reject_date"])."\">".Yii::app()->format->timeAgo($data["reject_date"])."</abbr>":""',
                            'visible' => in_array($this->stage, array(TaskProcess::STAGE_REJECTED))
                        ),
                        array(
                            'header' => 'Due Date',
                            'type' => 'raw',
                            'value' => '($data["due_date"])?"<abbr title=\"".Yii::app()->localTime->fromUTC($data["due_date"])."\">".Yii::app()->format->timeAgo($data["due_date"])."</abbr>":""',
                            'visible' => $this->stage != TaskProcess::STAGE_NOTSET
                        ),
                        array(
                            'header' => 'Complete Date',
                            'type' => 'raw',
                            'value' => '($data["complete_date"])?"<abbr title=\"".Yii::app()->localTime->fromUTC($data["complete_date"])."\">".Yii::app()->format->timeAgo($data["complete_date"])."</abbr>":""',
                            'visible' => in_array($this->stage, array(TaskProcess::STAGE_COMPLETED))
                        ),
                        array(
                            'value' => array($this, 'renderTaskActionButton'),
                            'visible' => $this->stage != TaskProcess::STAGE_NOTSET
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<?php $this->renderPartial('process.views.task._task_activity'); ?>
<?php $this->renderPartial('alert.views.admin._modal_alerts'); ?>