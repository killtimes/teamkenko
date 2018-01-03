<?php
/* @var $this TaskgroupController */
/* @var $model TaskGroup */
?>

<?php
$this->breadcrumbs = array(
    'Task Groups' => array('index'),
    'Create',
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Task Group',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT
    ),
    array(
        'label' => 'List Task Groups',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT
    ),
));
?>
<?php $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._flash'); ?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Create Task Group</legend>
            <div class="col-md-6">
            <?php $this->renderPartial('_form', array('model' => $model)); ?>  </div>      </fieldset>
    </div>
</div>
