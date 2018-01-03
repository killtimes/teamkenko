<?php
/* @var $this DocumentController */
/* @var $model Document */
?>

<?php
$this->breadcrumbs=array(
	'Documents'=>array('index'),
	'Create',
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Document',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'List Document',
        'url' => array('index'),
        'icon' => TbHtml::ICON_LIST,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'Manage Document',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_COG,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
));
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
        <legend>Create Document</legend>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>        </fieldset>
    </div>
</div>
