<?php

$g = Profile::itemAlias('Department',$this->department);

$this->breadcrumbs = array(
    'Daily Department Summary' => array('/report/departmentSummary/daily'),
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
            <legend>Department Summary: <?php echo $g; ?> (<?php echo $date; ?>)
                <a title="Fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/departmentSummary/dailyDepartment', array('full' => 1, 'department' => $this->department)); ?>"><i
                        class="glyphicon glyphicon-fullscreen"></i></a>
            </legend>
        <?php } else { ?>
            <head>
                <meta http-equiv="refresh" content="60">
                <title><?php echo Yii::app()->name; ?> - <?php echo $g; ?> (<?php echo $date; ?>)</title>

            </head>
            <legend class="text-primary">Teamkenko Department Summary
                - <?php echo $g; ?> (<?php echo $date; ?>)
                <a title="Exit fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/departmentSummary/dailyDepartment', array('department' => $this->department)) ?>"><i
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
                        'header' => 'Shop',
                        'type' => 'raw',
                        'value' => function ($data) {
                            $shop = Shop::model()->getById($data["shop_id"]);
                            if ($shop != null) {
                                return CHtml::link($shop->name, Yii::app()->createUrl('/report/departmentSummary/dailyDepartmentShop', array('shop' => $data['shop_id'], 'department' => Yii::app()->getController()->department)));
                            }
                            return '';
                        },
                        'visible' => Yii::app()->getController()->department == Profile::DEPARTMENT_SHOP
                    ),
                    array(
                        'header' => 'Staff',
                        'type' => 'raw',
                        'value' => function ($data) {
                            $profile = Profile::model()->getById($data["assign_id"]);
                            if ($profile != null) {
                                return CHtml::link($profile->getFullName(),Yii::app()->createUrl('/report/departmentSummary/dailyStaff',array('staff'=>$data['assign_id'],'department'=>Yii::app()->getController()->department)));
                            }
                            return '';
                        },
                        'visible' => Yii::app()->getController()->department != Profile::DEPARTMENT_SHOP

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

