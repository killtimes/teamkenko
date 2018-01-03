
<?php
$this->breadcrumbs = array(
    'Dashboard' => array('/dashboard'),
    'Overdue Tasks',
);

Yii::app()->clientScript->registerScript('search', "
//    TaskPage.initRequestPage();
    TaskPage.taskActivities();
    ProcessPage.initPopover();
$(document).tooltip({
            selector:'abbr',
            trigger:'hover'
        });
", CClientScript::POS_READY);


//if ($this->checkAccess('ToDo_Create')) {
//    echo TbHtml::buttonGroup(array(
//        array(
//            'label' => 'Create Task',
//            'url' => array('/process/task/create'),
//            'icon' => TbHtml::ICON_PLUS,
//            'color' => TBHtml::BUTTON_COLOR_DEFAULT,
//        )
//    ));
//}
?>


<div id="mdListTask" class="panel with-nav-tabs panel-default panel-container">
    <div class="panel-heading">
        <?php $this->renderPartial('_tabs'); ?>        
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade in active">
                <?php
                $this->widget('\TbGridView', array(
                    'id' => 'task-grid',
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
                            'value' => array($this, 'renderTaskName')
                        ),
//                        array(
//                            'header' => 'Request By',
//                            'type' => 'raw',
//                            'value' => array($this, 'renderRequestOwner')
//                        ),
                        array(
                            'name' => 'shop_name',
                            'header' => 'Shop',
                            'value' => array($this, 'renderShopName')
                        ),
                        array(
                            'name' => 'supplier_name',
                            'header' => 'Contact',
                            'value' => array($this, 'renderSupplierName')
                        ),
                        array(
                            'header' => 'Status',
                            'name' => 'stage',
                            'type' => 'raw',
                            'value' => 'TaskProcess::stageAlias($data["stage"])'
                        ),
                        array(
                            'name' => 'complete_date',
                            'header' => 'Complete Date',
                            'type' => 'raw',
                            'value' => array($this, 'renderCompleteDate')
                        )
                    ),
                ));
                ?>    
            </div>
        </div>
    </div>
</div>

<?php $this->renderPartial('_task_activity'); ?>

