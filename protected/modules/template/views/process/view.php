<?php
/* @var $this ProcessController */
/* @var $model ProcessTemplate */
?>

<?php
$this->breadcrumbs=array(
	'Process Templates'=>array('index'),
	$model->name,
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create ProcessTemplate',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'List ProcessTemplate',
        'url' => array('index'),
        'icon' => TbHtml::ICON_CHECK,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'Manage ProcessTemplate',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_COG,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
));
?>

<div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <legend>View ProcessTemplate #<?php echo $model->id; ?></legend>

<?php $this->widget('\\TbDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'name',
		'description',
		'shop_id',
		'supplier_id',
		'start_dayofweek',
		'start_time',
		'is_auto_start',
		'progress',
		'stage',
		'status',
	),
)); ?>
            </fieldset>
        </div>
</div>
