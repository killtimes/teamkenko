<?php
$task = Task::model()->getById($data["task_id"]);
echo CHtml::link(CHtml::encode($task["name"]), array("/process/todo/update", "id" => $data["id"]), array("class" => "update-task"));
?>
&nbsp; 
<a rel="tooltip" title="Intructions" href="javascript:;" class="text-muted"><span data-href="<?php echo CController::createUrl('/process/admin/instructions', array('id' => $data["task_id"])) ?>" class="glyphicon glyphicon-question-sign show-instructions"></span></a>
&nbsp;
<a  rel="tooltip" title="Activities" href="javascript:;" class="text-muted"><span data-url="<?php echo Yii::app()->createUrl("/process/admin/activities", array("id" => $data["id"])); ?>"  data-token="<?php echo Yii::app()->request->csrfToken; ?>" class="glyphicon glyphicon-eye-open view-activity"></span></a>
&nbsp;
<?php if($this->checkAccess('Alert_Create',array())){ ?>
    <a  rel="tooltip" title="Alerts" href="javascript:;" class="text-muted"><span data-url="<?php echo Yii::app()->createUrl("/alert/admin/bytask", array("id" => $data["id"])); ?>"  data-token="<?php echo Yii::app()->request->csrfToken; ?>" class="glyphicon glyphicon-alert view-alerts"></span></a>
<?php } ?>
