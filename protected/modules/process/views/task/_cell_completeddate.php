<?php
$time = strtotime($data['complete_date']);
$dueTime = strtotime($data['due_date']);
?>
<?php if ($dueTime < $time) { ?>
    <abbr class="text-danger" title="<?php echo Yii::app()->localTime->fromUTC($data['complete_date']); ?>"><?php echo Yii::app()->format->timeago($data['complete_date']); ?> (overdue)</abbr>
<?php } else { ?>
    <abbr class="text-info" title="<?php echo Yii::app()->localTime->fromUTC($data['complete_date']); ?>"><?php echo Yii::app()->format->timeago($data['complete_date']); ?></abbr>
<?php } ?>
