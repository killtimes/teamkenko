<?php
$this->breadcrumbs = array(
    'Tasks' => array('/process/todo/list'),
    'Create Task',
);
echo TbHtml::buttonGroup(array(
    array(
        'label' => 'List Tasks',
        'url' => array('/process/todo/list', array('stage' => TaskProcess::STAGE_ASSIGNED)),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('ToDo_List')
    )
));
?>
<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Create Task</legend>
            <div class="col-md-5">
                <?php $this->renderPartial('_form', array('model' => $model)); ?>
            </div>
        </fieldset>
    </div>
</div>