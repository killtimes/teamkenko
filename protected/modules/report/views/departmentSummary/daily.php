<?php
$this->breadcrumbs = array(
    'Daily Department Summary',
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
            <legend>Daily Department Summary (<?php echo $date; ?>)
                <a title="Fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/departmentSummary/daily', array('full' => 1)); ?>"><i
                        class="glyphicon glyphicon-fullscreen"></i></a>
            </legend>
        <?php } else { ?>
            <head>
                <meta http-equiv="refresh" content="60">
                <title><?php echo Yii::app()->name; ?> - Daily Department Summary (<?php echo $date; ?>)</title>
            </head>
            <legend class="text-primary">Teamkenko: Daily Department Summary (<?php echo $date; ?>)
                <a title="Exit fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/departmentSummary/daily') ?>"><i
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
                        'header' => 'Department',
                        'name' => 'department',
                        'type'=>'raw',
                        'value'=>function($data){
                            return CHtml::link(Profile::itemAlias('Department',$data['department']),Yii::app()->createUrl('/report/departmentSummary/dailyDepartment',array('department'=>$data['department'])));
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

