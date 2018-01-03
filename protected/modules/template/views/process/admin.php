<?php
/* @var $this ProcessController */
/* @var $model ProcessTemplate */

$this->breadcrumbs = array(
    'Process Templates' => array('admin'),
    'Manage',
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Job Template',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('ProcessTemplate_Create', array())
    ),
    array(
        'label' => 'List Job Templates',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('ProcessTemplate_List', array())
    ),
));

Yii::app()->clientScript->registerScript('search', "
    ProcessPage.initialize();
    
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('#search-process').submit(function(){
    $('#process-grid').yiiGridView('update', {
            data: $(this).serialize()
    });
    return false;
});
$(document).tooltip({selector:'abbr',trigger:'hover'});
", ClientScript::POS_READY);
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>List Job Templates</legend>

            <?php
//            echo TbHtml::button('Advanced Search', array('size' => TbHtml::BUTTON_SIZE_SMALL,
//                'color' => TbHtml::BUTTON_COLOR_DEFAULT,
//                'icon' => TbHtml::ICON_SEARCH,
//                'class' => 'search-button'));
//            ?>
            <div class="search-form">
                <?php
                $this->renderPartial('_search', array(
                    'model' => $model,
                ));
                ?>
            </div><!-- search-form -->

            <?php
            $this->widget('\TbGridView', array(
                'id' => 'process-grid',
                'dataProvider' => $model->search(),
                'htmlOptions'=>array(
                    'class'=>'grid-view panel-container'
                ),
                //'filter'=>$model,
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    'id',
                    array(
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => 'CHtml::link(CHtml::encode($data->name),array("update","id"=>$data->id)).(($data->update_by>0)?"<br> <i><small class=\"text-muted\">updated by <strong>".Profile::model()->getById($data->update_by)->getFullName()."</strong></small></i>":"")'
                    ),
                    array(
                        'name' => 'shop',
                        'header'=>'Shop',
                        'type' => 'raw',
                        'value' => 'Shop::model()->getById($data->shop_id)->name'
                    ),
                    array(
                        'name' => 'supplier',
                        'header'=>'Supplier',
                        'type' => 'raw',
                        'value' => 'Supplier::model()->getById($data->supplier_id)->name'
                    ),
                    array(
                        'name'=>'is_auto_start',
                        'type'=>'raw',
                        'header'=>'Auto start',
                        'value'=> array($this, 'renderSchedule')
                    ),
//                    'start_dayofweek',
                    /*
                      'start_time',

                      'progress',
                      'stage',
                      'status',
                     */
                     array(
                        'name'=>'create_date',
                        'type'=>'raw',
                        'value'=> 'TbHtml::abbr( Yii::app()->format->timeAgo($data->create_date), Yii::app()->localTime->fromUTC($data->create_date))'
                    ),
                    array(
                        'name' => 'status',
                        'type' => 'raw',
                        'value' => '($data->status == 1)?TbHtml::labelTb(ProcessTemplate::itemAlias("Status",$data->status), array("color" => TbHtml::LABEL_COLOR_SUCCESS)):TbHtml::labelTb(ProcessTemplate::itemAlias("Status",$data->status))',
                        'filter' => ProcessTemplate::itemAlias("Status"),
                    ),
                    array(
                        'type' => 'raw',
                        'value' => array($this, 'renderActionButton')
                    ),
                ),
            ));
            ?>
        </fieldset>
    </div>
</div>

