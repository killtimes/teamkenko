<legend class="fontsmaller">
    <?php if (Yii::app()->user->id == $userId) { ?>
        Total of <?php echo $dataProvider->totalItemCount; ?> task(s) assigned to you on <?php echo Yii::app()->localTime->fromUTC($date,'d M Y'); ?> 
    <?php } else { ?>
        Total of <?php echo $dataProvider->totalItemCount; ?> task(s) assigned to staff on <?php echo Yii::app()->localTime->fromUTC($date,'d M Y'); ?> 

    <?php } ?>
</legend>
<?php
if ($this->checkAccess('ToDo_Create')) {
    echo TbHtml::button('Add Task', array(
        'color' => TbHtml::BUTTON_COLOR_PRIMARY,
        'icon' => TbHtml::ICON_PLUS_SIGN,
        'class' => 'add-button',
        'id' => 'addTask',
        'size' => TbHtml::BUTTON_SIZE_SMALL,
        'data-url' => CController::createUrl('/dashboard/calendar/addTask', array('date' => $date))
    ));
}

$this->widget('\TbGridView', array(
    'id' => 'taskbydate-grid',
    'dataProvider' => $dataProvider,
    'type' => array(
        TbHtml::GRID_TYPE_HOVER,
        TbHtml::GRID_TYPE_CONDENSED,
    ),
    'columns' => array(
        array(
            'name' => 'id',
            'header' => '#'
        ),
        array(
            'header' => '',
            'name' => 'process_id',
            'class' => 'TaskProcessNameAndTipColumn'
        ),
        array(
            'name' => 'task_name',
            'header' => 'Name',
            'type' => 'raw',
            'class' => 'TaskProcessNameColumn'
        ),
        array(
            'name' => 'shop_name',
            'header' => 'Shop',
            'class' => 'TaskShopNameColumn'
        ),
        array(
            'name' => 'supplier_name',
            'header' => 'Contact',
            'class' => 'TaskSupplierNameColumn'
        ),
        array(
            'header' => 'Assigned To',
            'value' => 'Profile::model()->getById($data["assign_id"])->getFullName()',
            'visible' => $userId == 0
        ),
        array(
            'name' => 'due_date',
            'header' => 'Due Date',
            'type' => 'raw',
            'class' => 'TaskStatusDueDateColumn'
        ),
    ),
));
?> 

