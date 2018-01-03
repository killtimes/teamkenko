<?php
$task = Task::model()->getById($data->task_id);
echo CHtml::link(CHtml::encode($task->name), array("/template/process/updateTask", "id" => $data->id), array("class" => "update-task"));
?>
&nbsp; <span data-href="<?php echo CController::createUrl('/process/admin/instructions', array('id' => $data->task_id)) ?>" class="glyphicon glyphicon-question-sign text-muted show-instructions"></span>