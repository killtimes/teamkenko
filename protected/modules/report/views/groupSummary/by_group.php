<?php

$g = 'Non-group';
if ($group > 0) {
    $g = $groupModel->name;
}
$this->breadcrumbs = array(
    'Group Summary' => array('/report/groupSummary'),
    $g
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
            <legend>Group Summary: <?php echo $g; ?>
                <a title="Fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/groupSummary/group', array('full' => 1, 'group' => ($group > 0) ? $groupModel->id : -1)); ?>"><i
                        class="glyphicon glyphicon-fullscreen"></i></a>
            </legend>
        <?php } else { ?>
            <head>
                <meta http-equiv="refresh" content="60">
                <title><?php echo Yii::app()->name; ?> - <?php echo $g; ?></title>

            </head>
            <legend class="text-primary">Teamkenko Group Summary
                - <?php echo $g; ?>
                <a title="Exit fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/groupSummary/group', array('group' => ($group > 0) ? $groupModel->id : -1)) ?>"><i
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
                        'header' => 'Staff',
                        'type' => 'raw',
                        'value' => function ($data) {
                            $profile = Profile::model()->getById($data["assign_id"]);
                            if ($profile != null) {
                                    return CHtml::link($profile->getFullName(),Yii::app()->createUrl('/report/groupSummary/staff',array('staff'=>$data['assign_id'], 'group'=>Yii::app()->getController()->group)));
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
                ),
            ));
            ?>
        </div>
    </div>
</div>

