<?php


$name = '';
$profile = Profile::model()->getById($staff);
if ($profile != null) {
    $name = $profile->getFullName();
}

$this->breadcrumbs = array(
    'Staff Summary' => array('/report/staffSummary'),
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
            <legend>Shop Summary: <?php echo $name; ?>
                <a title="Fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/staffSummary/staff', array('full' => 1, 'staff' => $staff)); ?>"><i
                        class="glyphicon glyphicon-fullscreen"></i></a>
            </legend>
        <?php } else { ?>
            <head>
                <meta http-equiv="refresh" content="60">
                <title><?php echo Yii::app()->name; ?> - <?php echo $name; ?></title>
            </head>
            <legend class="text-primary">Teamkenko Staff Summary
                - <?php echo $name; ?>
                <a title="Exit fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/staffSummary/staff', array('staff' => $staff)) ?>"><i
                        class="glyphicon glyphicon-resize-small"></i></a>
            </legend>
        <?php } ?>

        <div class="table-responsive">
            <?php
            $this->widget('\TbGridView', array(
                'id' => 'report-grid',
//                'template' => '{items}',
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

