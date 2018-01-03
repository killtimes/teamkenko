<?php
/* @var $this AdminController */
/* @var $model Process */


$this->breadcrumbs=array(
	'Processes'=>array('admin'),
	'Manage',
);


echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Process',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => 'Manage Process',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_COG,
        'color' => ( ($this->id == '') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
));

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#process-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Manage Processes</legend>

            
            
            <p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
        &lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
            echo TbHtml::button('Advanced Search', array('size' => TbHtml::BUTTON_SIZE_SMALL,
                'color' => TbHtml::BUTTON_COLOR_DEFAULT,
                'icon' => TbHtml::ICON_SEARCH,
                'class' => 'search-button'));
            ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('\TbGridView',array(
	'id'=>'process-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
	'columns'=>array(
		'id',
		'name',
		'description',
		'shop_id',
		'supplier_id',
		'start_date',
		/*
		'progress',
		'stage',
		'status',
		'create_date',
		'update_date',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update}{delete}'

		),
	),
)); ?>
        </fieldset>
    </div>
</div>

