<?php
/* @var $this TaskgroupController */
/* @var $model TaskGroup */


$this->breadcrumbs = array(
    'Task Groups' => array('admin'),
    'Manage',
);


echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Task Group',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT
    ),
    array(
        'label' => 'List Task Groups',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_COG,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT
    ),
));
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>List Task Groups</legend>

            <?php
            $this->widget('\TbGridView', array(
                'id' => 'task-group-grid',
                'dataProvider' => $model->search(),
//                'filter' => $model,
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    'id',
                    'name',
                    'create_date',
                    'update_date',
                    array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update}{delete}'
                    ),
                ),
            ));
            ?>
        </fieldset>
    </div>
</div>

