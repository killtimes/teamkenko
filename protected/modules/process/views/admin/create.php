<?php
/* @var $this AdminController */
/* @var $model Process */
?>

<?php
$this->breadcrumbs = array(
    'Jobs' => array('admin'),
    'Create',
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Job',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Process_Create', array())
    ),
    array(
        'label' => 'List Jobs',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Process_List', array())
    ),
    array(
        'label' => 'Archived Jobs',
        'url' => array('admin/archived'),
        'icon' => TbHtml::ICON_FLOPPY_SAVED,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Process_List', array())
    ),
));
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Create Job</legend>
            <div class="col-md-5">
                <?php $this->renderPartial('_form', array('model' => $model)); ?>  
            </div>
        </fieldset>
    </div>
</div>
