<?php
$this->breadcrumbs = array(
    'Daily Group Summary',
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
            <legend>Daily Group Summary (<?php echo $date; ?>)
                <a title="Fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/groupSummary/daily', array('full' => 1)); ?>"><i
                        class="glyphicon glyphicon-fullscreen"></i></a>
            </legend>
        <?php } else { ?>
            <head>
                <meta http-equiv="refresh" content="60">
                <title><?php echo Yii::app()->name; ?> - Daily Group Summary (<?php echo $date; ?>)</title>

            </head>
            <legend class="text-primary">Teamkenko: Daily Group Summary (<?php echo $date; ?>)
                <a title="Exit fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/groupSummary/daily') ?>"><i
                        class="glyphicon glyphicon-resize-small"></i></a>
            </legend>
        <?php } ?>

        <div class="table-responsive">
            <?php
            $this->widget('\TbGridView', array(
                'id' => 'report-grid',
                'template' => '{items}',
                'dataProvider' => $dataProvider,
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    array(
                        'header' => 'Group',
                        'name' => 'task_group',
                        'type' => 'raw',
                        'value' => function ($data) {
                            if ($data['task_group'] < 0) {
                                return CHtml::link('Non-group', Yii::app()->createUrl('/report/groupSummary/dailyGroup', array('group' => $data['task_group'])));
                            }
                            $group = TaskGroup::model()->findByPk($data['task_group']);
                            if ($group) {
                                return CHtml::link($group->name, Yii::app()->createUrl('/report/groupSummary/dailyGroup', array('group' => $data['task_group'])));
                            }
                            return '';
                        }
                    ),
                    array(
                        'header' => 'Wait for accept',
                        'name' => 'wait_for_accept',
                        'headerHtmlOptions' => array(
                            'class' => 'assigned'
                        ),
                        'cssClassExpression' => "'assigned'"
                    ),
                    array(
                        'header' => 'Completed',
                        'value' => function ($data) {
                            return $data['completed'] . '/' . $data['assigned'];
                        },
                    )


//                    array(
//                        'header' => 'Status',
//                        'name' => 'stage',
//                        'type' => 'raw',
//                        'value' => 'TaskProcess::stageAlias($data["stage"])'
//                    ),
//                    'total'
                ),
            ));
            ?>
        </div>
    </div>
</div>

