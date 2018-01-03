<?php
$g = Profile::itemAlias('Department',$department);

$name = '';
$profile = Profile::model()->getById($staff);
if ($profile != null) {
    $name = $profile->getFullName();
}

$shopName = '';
$shop = Shop::model()->getById($this->shop);
if ($shop != null) {
    $shopName = $shop->name;
}


$this->breadcrumbs = array(
    'Department Summary' => array('/report/departmentSummary'),
    ' '.$g => array('/report/departmentSummary/department','staff'=>$staff, 'department' => $this->department),
    $shopName => array('/report/departmentSummary/departmentShop','shop'=>$this->shop,'department'=>$this->department),
    $name
);


Yii::app()->getClientScript()->registerScript('tasks', "
        $(document).tooltip({
            selector:'abbr',
            trigger:'hover'
        });
", CClientScript::POS_READY);
?>
<div class="panel panel-default padding-10">
    <div class="panel-body">
        <?php if (!$full) { ?>
            <legend>Department Summary: <?php echo $g; ?> - <?php echo $shopName; ?> - <?php echo $name; ?>
                <a title="Fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/departmentSummary/staff', array('full' => 1,'staff'=>$staff, 'department' => $this->department)); ?>"><i
                        class="glyphicon glyphicon-fullscreen"></i></a>
            </legend>
        <?php } else { ?>
            <head>
                <meta http-equiv="refresh" content="60">
                <title><?php echo Yii::app()->name; ?> - <?php echo $g; ?> - <?php echo $name; ?></title>
            </head>
            <legend class="text-primary">Teamkenko Department Summary
                - <?php echo $g; ?> - <?php echo $name; ?>
                <a title="Exit fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/departmentSummary/staff', array('staff'=>$staff,'department' => $this->department)); ?>"><i
                        class="glyphicon glyphicon-resize-small"></i></a>
            </legend>
        <?php } ?>

        <div class="table-responsive">
            <?php
            $this->widget('\TbGridView', array(
                'id' => 'report-grid',
                'dataProvider' => $dataProvider,
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    array(
                        'header' => 'ID',
                        'name' => 'id'
                    ),
                    array(
                        'header' => 'Task',
                        'type' => 'raw',
                        'value' => function ($data) {
                            $task = Task::model()->getById($data['task_id']);

                            if ($task != null) {
                                return CHtml::encode(Task::model()->getById($data['task_id'])->name);
                            }

                            return '';
                        }
                    ),
                    array(
                        'header' => 'Job',
                        'type' => 'raw',
                        'value' => function ($data) {
                            $job = Process::model()->findByPk($data['process_id']);

                            if ($job) {
                                return $job->name;
                            }

                            return '';
                        }
                    ),
                    array(
                        'name' => 'stage',
                        'header' => 'Status',
                        'type' => 'raw',
                        'value' => 'TaskProcess::stageAlias($data["stage"])',
                    ),
                ),
            ));
            ?>
        </div>
    </div>
</div>

