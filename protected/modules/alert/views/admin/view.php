<?php
/* @var $this AdminController */
/* @var $model Alert */
?>

<?php
$this->breadcrumbs=array(
	'Alerts'=>array('/alert/admin'),
	$model->id,
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Alert',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'List Alert',
        'url' => array('index'),
        'icon' => TbHtml::ICON_CHECK,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'Manage Alert',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_COG,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
));
?>

<div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <legend>View Alert #<?php echo $model->id; ?></legend>

<?php $this->widget('\\TbDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'alert_type',
		'note',
		'create_date',
		'update_date',
		'create_by',
		'update_by',
		'to_user_id',
		'stage',
		'related_task_id',
	),
)); ?>
            </fieldset>
        </div>
</div>
