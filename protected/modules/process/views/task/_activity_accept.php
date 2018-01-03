<li class="list-group-item">
    <span class="pull-right text-muted time-line">
        <?php echo TbHtml::abbr(Yii::app()->format->timeago($model->action_date), Yii::app()->localTime->fromUTC($model->action_date)); ?> <span class="glyphicon glyphicon-time timestamp"></span>
        <span class="glyphicon glyphicon-remove invisible"></span>
    </span> 
    <i class="glyphicon glyphicon-thumbs-up text-muted icon-activity"></i> <a><strong><?php echo Profile::model()->getById($model->action_source)->getFullName(); ?></strong></a> accepted this task.



</li>
