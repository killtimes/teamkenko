<div class="">
    <h4>Informations</h4>
    <div class="">
        <label class="col-md-2 control-label text-right">Name</label>
        <div class="col-md-7">
            <label class="control-label text-primary"><?php echo CHtml::encode($process->name); ?></label>
        </div>
        <div class="clearfix"></div>

    </div>
    <div class="">
        <label class=" col-md-2 control-label text-right">Shop</label>
        <div class="col-md-7">
            <label class="control-label text-primary"><?php echo CHtml::encode(Shop::model()->getById($process->shop_id)->name); ?></label>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="">
        <label class=" col-md-2 control-label text-right">Contact</label>
        <div class="col-md-7">
            <label class="control-label text-primary"><?php echo CHtml::encode(Supplier::model()->getById($process->supplier_id)->name); ?></label>
        </div>
        <div class="clearfix"></div>

    </div>
    <div class="">
        <label class=" col-md-2 control-label text-right">Started</label>
        <div class="col-md-7">
            <label class="control-label text-primary"><?php echo Yii::app()->format->timeAgo($process->start_date) ?></label>
        </div>
        <div class="clearfix"></div>

    </div>
    <div class="">
        <label class=" col-md-2 control-label text-right">Job</label>
        <div class="col-md-7">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $process->progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $process->progress; ?>%;">
                    <span><?php echo $process->progress; ?>%</span>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

    </div>
    <div class="clearfix"></div>
    <h4>Attachments</h4>
    <ul>
        <?php foreach ($documents as $att) { ?>
            <?php if ($att->status == Document::STATUS_ACTIVE) { ?>
                <li>
                    <?php if ($att->isImage()) { ?>
                        <a data-lightbox="photos" data-title="<?php echo CHtml::encode($att->title); ?>" href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $att->id)) ?>" > <?php echo $att->renderIcon(); ?> <?php echo CHtml::encode($att->title); ?></a> <em class="small">added <?php echo $att->create_date; ?></em>
                    <?php } else if ($att->source_type == Document::SOURCE_TYPE_LOCAL) { ?>
                        <a target="_blank"  href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $att->id)) ?>"> <?php echo $att->renderIcon(); ?> <?php echo CHtml::encode($att->title); ?> </a> <em>added <?php echo $att->create_date; ?></em> 
                    <?php } else { ?>
                        <a target="_blank" href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $att->id)) ?>"><?php echo $att->renderIcon(); ?> <?php echo CHtml::encode($att->title); ?></a> <em class="small">added <?php echo $att->create_date; ?> </em>
                    <?php } ?>
                </li>
            <?php } else { ?>
                <li class="text-muted deleted">
                    <?php echo $att->renderIcon(); ?> <?php echo CHtml::encode($att->title); ?> <em class="smallfont text-muted">added <?php echo $att->create_date; ?></em>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>
<h4>Tasks</h4>

<?php
$this->widget('\TbGridView', array(
    'id' => 'task-process-grid',
    'dataProvider' => $modelTask->search(),
    'enableSorting' => false,
    'type' => array(
        TbHtml::GRID_TYPE_HOVER,
        TbHtml::GRID_TYPE_CONDENSED,
    ),
    'rowHtmlOptionsExpression' => 'array("class"=> (($data->id==' . $task_id . ')?"success":""))',
    'columns' => array(
        'id',
        array(
            'class' => 'TaskProcessNameColumn',
            'name' => 'task_id'
        ),
        array(
            'class' => 'TaskProcessAssigneeColumn',
            'name' => 'assign_id',
            'header' => 'Staff'
        ),
        array(
            'class' => 'TaskProcessStageColumn',
            'name' => 'stage',
            'header' => 'Stage'
        ),
        array(
            'header' => 'Est time',
            'value' => '$data->duration. " hours(s)"'
        ),
        array(
            'class' => 'TaskProcessDueDateColumn',
            'header' => 'Due',
            'value' => '($data["due_date"]!==null)?Yii::app()->format->timeAgo($data["due_date"]):""'
        ),
    ),
));
?>
