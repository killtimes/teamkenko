<?php
/* @var $this ProcessController */
/* @var $model ProcessTemplate */
?>

<?php
$this->breadcrumbs = array(
    'Job Templates' => array('admin'),
    $model->name => array('update', 'id' => $model->id),
    'Update',
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Job Template',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('ProcessTemplate_Create', array())
    ),
    array(
        'label' => 'List Job Templates',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('ProcessTemplate_List', array())
    ),
));

echo $this->renderPartial('webroot.themes.theme1.views.includes._flash');
?>
<div class="panel panel-default panel-container">
    <div class="panel-body">
        <legend>Job Template #<?php echo $model->id; ?></legend>

        <div class="col-md-6">
            <?php $this->renderPartial('_form', array('model' => $model)); ?>    
        </div>
        <div class="col-md-6">
            <legend>List Task</legend>

            <?php $this->renderPartial('_process_template_tasks', array('model' => $modelTaskProcess, 'modelProcess' => $model)); ?>    
        </div>
    </div>
</div>
