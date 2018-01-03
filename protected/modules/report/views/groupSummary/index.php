<?php
$this->breadcrumbs = array(
    'Group Summary',
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
            <legend>Group Summary
                <a title="Fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/groupSummary', array('full' => 1)); ?>"><i
                        class="glyphicon glyphicon-fullscreen"></i></a>
            </legend>
        <?php } else { ?>
            <head>
                <meta http-equiv="refresh" content="60">
                <title><?php echo Yii::app()->name; ?> - Group Summary</title>

            </head>
            <legend class="text-primary">Teamkenko: Group Summary
                <a title="Exit fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/groupSummary') ?>"><i
                        class="glyphicon glyphicon-resize-small"></i></a>
            </legend>
        <?php } ?>

        <div class="table-responsive">
            <?php
            $this->widget('\TbGridView', array(
                'id' => 'report-grid',
                'template' => '{items}',
                'dataProvider' => $dataProvider,
                'columns' => array(
                    array(
                        'header' => 'Group',
                        'name' => 'task_group',
                        'type' => 'raw',
                        'value' => function ($data) {
                            if ($data['task_group'] < 0) {
                                return CHtml::link('Non-group', Yii::app()->createUrl('/report/groupSummary/group', array('group' => $data['task_group'])));
                            }
                            $group = TaskGroup::model()->findByPk($data['task_group']);
                            if ($group) {
                                return CHtml::link($group->name, Yii::app()->createUrl('/report/groupSummary/group', array('group' => $data['task_group'])));
                            }
                            return '';
                        },
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
                ),
            ));
            ?>
        </div>
    </div>
</div>

