<?php
/* @var $this DocumentController */
/* @var $model Document */
?>

<?php
$this->breadcrumbs=array(
	'Documents'=>array('index'),
	$model->title,
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
        'icon' => TbHtml::ICON_CHECK,
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
                <legend>View Document #<?php echo $model->id; ?></legend>

<?php $this->widget('\\TbDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'task_id',
		'shop_id',
		'supplier_id',
		'doc_type',
		'doc_code',
		'doc_date',
		'title',
		'file_name',
		'file_type',
		'source_type',
		'create_date',
		'update_date',
	),
)); ?>
            </fieldset>
        </div>
</div>
