<?php
$task = Task::model()->getById($data->task_id);
echo CHtml::link(CHtml::encode($task->name), array("/process/admin/updateTask", "id" => $data->id), array("class" => "update-task"));
?>
&nbsp; 
<a href="javascript:;" class="text-muted">
<span data-href="<?php echo CController::createUrl('admin/instructions', array('id' => $data->task_id)) ?>" class="glyphicon glyphicon-question-sign show-instructions"></span>
</a>
&nbsp;
<a href="javascript:;" class="text-muted"><span data-url="<?php echo Yii::app()->createUrl("/process/admin/activities", array("id" => $data["id"])); ?>"  data-token="<?php echo Yii::app()->request->csrfToken; ?>" class="glyphicon glyphicon-eye-open view-activity"></span></a>