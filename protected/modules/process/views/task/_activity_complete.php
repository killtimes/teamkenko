<li class="list-group-item">
    <span class="pull-right text-muted time-line">
        <?php echo TbHtml::abbr(Yii::app()->format->timeago($model->action_date), Yii::app()->localTime->fromUTC($model->action_date)); ?>  <span class="glyphicon glyphicon-time timestamp"></span>
                <span class="glyphicon glyphicon-remove invisible"></span>

    </span> 
    <i class="glyphicon glyphicon-check icon-activity text-muted"></i> 
    <a><strong>
    <?php echo Profile::model()->getById($model->action_source)->getFullName(); ?></strong></a> 

    <?php if($model->action_source == Yii::app()->user->id){ ?>

    accepted next task. This task marked as Completed.

    <?php }else{ ?>
   	completed this task.
    <?php } ?>

</li>
