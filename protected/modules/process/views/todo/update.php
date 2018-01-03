<?php
$this->breadcrumbs = array(
    'Tasks' => array('/process/todo/list'),
    'Update Task',
);
echo TbHtml::buttonGroup(array(
    array(
        'label' => 'List Tasks',
        'url' => array('/process/todo/list', 'stage' => TaskProcess::STAGE_ASSIGNED),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('ToDo_List')

    )
));
?>

<?php $this->renderPartial('application.views._flash', array('model' => $model)); ?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Task #<?php echo $model->id; ?></legend>
            <?php if ($model->isNewRecord) { ?>
                <?php $this->renderPartial('_form', array('model' => $model)); ?>
            <?php } else { ?>
                <div class="col-md-5">
                    <?php $this->renderPartial('_form', array('model' => $model)); ?>
                </div>
                <div class="col-md-3">
                    <dl class="dl-horizontal">
                        <dt>Assign Date</dt>
                        <dd><?php echo Yii::app()->localTime->fromUTC($model->assign_date); ?></dd>
                        <dt>Accept Date</dt>
                        <dd><?php echo Yii::app()->localTime->fromUTC($model->accept_date); ?></dd>
                        <dt>Due Date</dt>
                        <dd><?php echo Yii::app()->localTime->fromUTC($model->due_date); ?></dd>
                        <dt>Complete Date</dt>
                        <dd><?php echo Yii::app()->localTime->fromUTC($model->complete_date); ?></dd>
                        <dt>Status</dt>
                        <dd><?php echo TaskProcess::stageAlias($model->stage); ?></dd>
                    </dl>     
                </div>
            <?php } ?>
        </fieldset>
    </div>
</div>