<?php
/* @var $this DefaultController */

$this->breadcrumbs = array(
    'Dashboard' => array('/dashboard'),
    'Tasks'
);
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Request Tasks</legend>    
            <?php
            $this->widget('\TbGridView', array(
                'id' => 'task-grid',
                'dataProvider' => $dataProvider,
//                'filter' => $model,
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    array(
                        'name' => 'priority',
                        'type' => 'raw',
                        'value' => 'TaskProcess::priorityAlias($data->priority)'
                    ),
                    array(
                        'name'=>'process.name',
                        'header'=>'Process'
                    ),
                    array(
                        'name'=>'process.shop.name',
                        'header'=>'Shop'
                    ),
                    array(
                        'name'=>'process.supplier.name',
                        'header'=>'Supplier'
                    ),
                    array(
                        'name' => 'task.name',
                        'header'=>'Task',
                        'type' => 'raw',
                        'value' => 'CHtml::link(CHtml::encode($data->task->name),array("/process/admin/updateTask","id"=>$data->id),array("class"=>"update-task"))'
                    ),
                    array(
                        'header' => 'Assigned To',
                        'type' => 'raw',
                        'value' => '$data->assign->profile->firstname." ".$data->assign->profile->lastname'
                    ),
                    'duration',
                    array(
                        'name' => 'stage',
                        'header' => 'Status',
                        'type' => 'raw',
                        'value' => 'TaskProcess::stageAlias($data->stage)'
                    ),
                    'create_date',
                    array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '',                        
                    ),
                ),
            ));
            ?>
        </fieldset>
    </div>
</div>