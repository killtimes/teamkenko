<?php
/* @var $this AdminController */
/* @var $model Process */
?>

<?php
$this->breadcrumbs = array(
    'Jobs' => array('admin'),
    $model->name => array('update', 'id' => $model->id),
    'Update',
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

echo $this->renderPartial('webroot.themes.theme1.views.includes._flash');
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <legend>Job #<?php echo $model->id; ?>
            <?php if ($this->checkDeletePermission($model, false)) { ?>
                <a href="javascript:;"
                   data-url="<?php echo $this->createUrl('/process/admin/delete', array('id' => $model->id)); ?>"
                   data-token="<?php echo Yii::app()->request->csrfToken; ?>"
                   class="btn btn-default pull-right btn-sm delete-job">Delete this
                    job</a>
            <?php } ?>
        </legend>
        <div class="col-md-5">
            <?php $this->renderPartial('_form', array('model' => $model)); ?>
        </div>
        <div class="col-md-7">
            <legend>List Tasks</legend>

            <?php $this->renderPartial('_process_tasks', array('model' => $modelTaskProcess, 'modelProcess' => $model)); ?>

            <legend>Attachments</legend>

            <?php $this->renderPartial('_attachments', array('dataProvider' => $attachments, 'model' => $modelTaskProcess, 'modelProcess' => $model)); ?>
        </div>


    </div>
</div>
