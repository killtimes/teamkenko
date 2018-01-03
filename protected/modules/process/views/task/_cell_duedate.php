<?php
$time = strtotime($data['due_date']);
?>
<?php if (time() > $time) { ?>
    <abbr class="text-danger" title="<?php echo Yii::app()->localTime->fromUTC($data['due_date']); ?>"><?php echo Yii::app()->format->timeago($data['due_date']); ?></abbr>
<?php } else { ?>
    <abbr class="text-info" title="<?php echo Yii::app()->localTime->fromUTC($data['due_date']); ?>"><?php echo Yii::app()->format->timeago($data['due_date']); ?></abbr>
<?php } ?>
