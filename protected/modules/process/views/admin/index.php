<?php
/* @var $this AdminController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs=array(
	'Processes',
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
        'icon' => TbHtml::ICON_LIST,
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
        <legend>Processes</legend>

<?php $this->widget('\TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
    </div>
</div>
