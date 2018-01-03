<?php if ($model->status == TaskActivity::STATUS_ACTIVE) { ?>
    <li class="list-group-item">
        <span class="pull-right text-muted time-line">
            <?php echo TbHtml::abbr(Yii::app()->format->timeago($model->action_date), Yii::app()->localTime->fromUTC($model->action_date)); ?>  <span class="glyphicon glyphicon-time timestamp"></span>
            
            <?php if($this->checkDeleteMessagePermission($model, $task)){ ?>
            <a class="delete-activity" data-token="<?php echo Yii::app()->request->csrfToken; ?>" href="javascript:;" data-url="<?php echo Yii::app()->createUrl('/process/task/deleteActivity', array('id' => $model->id)) ?>" data-loading-text="Deleting..."><span class="glyphicon glyphicon-remove"></span></a>
            <?php }else{ ?>
                <span class="glyphicon glyphicon-remove invisible"></span>
            <?php } ?>
        </span> 
        <i class="glyphicon glyphicon-comment icon-activity text-muted"></i> <a><strong><?php echo Profile::model()->getById($model->action_source)->getFullName(); ?></strong></a> <?php echo nl2br(CHtml::encode($model->action_message)); ?>

    </li>
<?php } else { ?>
    <li class=" list-group-item text-muted deleted">
        <span class="pull-right text-muted time-line">
            <?php echo Yii::app()->format->timeago($model->action_date); ?> <span class="glyphicon glyphicon-time timestamp"></span>
            <span class="glyphicon glyphicon-remove invisible"></span>
        </span> 
        <i class="glyphicon glyphicon-comment icon-activity text-muted"></i> <strong><?php echo Profile::model()->getById($model->action_source)->getFullName(); ?></strong> <?php echo nl2br(CHtml::encode($model->action_message)); ?>


    </li>
<?php } ?>
