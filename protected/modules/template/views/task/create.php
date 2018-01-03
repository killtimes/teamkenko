<?php
/* @var $this TaskController */
/* @var $model Task */
?>

<?php
$this->breadcrumbs = array(
    'Tasks' => array('admin'),
    'Create',
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Task Template',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('TaskTemplate_Create', array())
    ),
    array(
        'label' => 'List Task Templates',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('TaskTemplate_List', array())
    ),
));
?>
<?php $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._flash'); ?>
<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Create Task Template</legend>
            <div class="col-md-6">
            <?php $this->renderPartial('_form', array('model' => $model)); ?> 
            </div>
        </fieldset>
    </div>
</div>
