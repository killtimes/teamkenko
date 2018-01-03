<?php
/* @var $this AdminController */
/* @var $model Alert */
?>

<?php
$this->breadcrumbs=array(
	'Alerts'=>array('/alert/admin'),
	'Create',
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Alert',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'Manage Alert',
        'url' => array('list'),
        'icon' => TbHtml::ICON_COG,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
));
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
        <legend>Create Alert</legend>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>        </fieldset>
    </div>
</div>
