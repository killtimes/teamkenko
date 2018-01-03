<?php
$this->breadcrumbs = array(
        'Dashboard' => array('/dashboard'),
    'Create Task',
);
echo TbHtml::buttonGroup(array(
    array(
        'label' => 'List Tasks',
        'url' => array('/process/task/request'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
    )
));
?>
<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Create Task</legend>
            <div class="col-md-5">
                <?php $this->renderPartial('_form', array('model' => $model, 'department'=>$department)); ?>
            </div>
        </fieldset>
    </div>
</div>