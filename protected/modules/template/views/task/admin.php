<?php
/* @var $this TaskController */
/* @var $model Task */


$this->breadcrumbs = array(
    'Tasks' => array('admin'),
    'Manage',
);


echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Task Template',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('TaskTemplate_Create', array())
    ),
    array(
        'label' => 'List Task Templates',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('TaskTemplate_List', array())
    ),
));

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#task-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
TemplatePage.initList();
$(document).tooltip({selector:'abbr',trigger:'hover'});
",  ClientScript::POS_READY);
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>List Task Templates</legend>

            <?php
//            echo TbHtml::button('Advanced Search', array('size' => TbHtml::BUTTON_SIZE_SMALL,
//                'color' => TbHtml::BUTTON_COLOR_DEFAULT,
//                'icon' => TbHtml::ICON_SEARCH,
//                'class' => 'search-button'));
            ?>
            <div class="search-form">
                <?php
                $this->renderPartial('_search', array(
                    'model' => $model,
                ));
                ?>
            </div><!-- search-form -->
            <div class="panel-container"></div>
            <?php
            $this->widget('\TbGridView', array(
                'id' => 'task-grid',
                'dataProvider' => $model->search(),
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
                        'value' => 'CHtml::link(CHtml::encode($data->name),array("update","id"=>$data->id))'
                    ),
                    array(
                        'name'=>'taskGroup.name',
                        'header'=>'Group'
                    ),
                    'duration',
                     array(
                        'name'=>'create_date',
                        'type'=>'raw',
                        'value'=> 'TbHtml::abbr( Yii::app()->format->timeAgo($data->create_date), Yii::app()->localTime->fromUTC($data->create_date))'
                    ),
                     array(
                        'name'=>'update_date',
                        'type'=>'raw',
                        'value'=> 'TbHtml::abbr( Yii::app()->format->timeAgo($data->update_date), Yii::app()->localTime->fromUTC($data->update_date))'
                    ),
                    array(
                        'value' => array($this, 'renderActionButton')
                    ),
                ),
            ));
            ?>
        </fieldset>
    </div>
</div>

