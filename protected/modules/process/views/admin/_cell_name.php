<?php
echo CHtml::link(CHtml::encode($data->name),array("update","id"=>$data->id));
?>
<div class="small text-muted">
    <em>created by <?php echo Profile::model()->getById($data->create_by)->getFullName(); ?>, <?php echo TbHtml::abbr(Yii::app()->format->timeAgo($data->create_date),Yii::app()->localTime->fromUTC($data->create_date)); ?></em>
</div>