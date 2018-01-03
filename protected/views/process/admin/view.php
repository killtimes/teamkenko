<?php
/* @var $this AdminController */
/* @var $model Process */
?>

<?php
$this->breadcrumbs=array(
	'Processes'=>array('index'),
	$model->name,
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Process',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'List Process',
        'url' => array('index'),
        'icon' => TbHtml::ICON_CHECK,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'Manage Process',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_COG,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
));
?>

<div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <legend>View Process #<?php echo $model->id; ?></legend>

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
		'start_date',
		'progress',
		'stage',
		'status',
		'create_date',
		'update_date',
	),
)); ?>
            </fieldset>
        </div>
</div>
