<?php
/* @var $this AdminController */
/* @var $model Alert */
?>

<?php
$this->breadcrumbs=array(
	'Alerts'=>array('/alert/admin'),
	'#'.$model->id=>array('view','id'=>$model->id),
	'Update',
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
        'icon' => TbHtml::ICON_LIST,
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
        <legend>Update Alert <?php echo $model->id; ?></legend>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>    </div>
</div>
    