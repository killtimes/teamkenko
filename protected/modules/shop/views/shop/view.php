<?php
/* @var $this ShopController */
/* @var $model Shop */
?>

<?php
$this->breadcrumbs=array(
	'Shops'=>array('index'),
	$model->name,
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Shop',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'List Shop',
        'url' => array('index'),
        'icon' => TbHtml::ICON_CHECK,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'Manage Shop',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_COG,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
));
?>

<div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <legend>View Shop #<?php echo $model->id; ?></legend>

<?php $this->widget('\\TbDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'name',
		'employees',
		'address',
		'phone',
		'fax',
	),
)); ?>
            </fieldset>
        </div>
</div>
