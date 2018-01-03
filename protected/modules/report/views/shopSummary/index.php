<?php
$this->breadcrumbs = array(
    'Shop Summary',
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
            <legend>Shop Summary
                <a title="Fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/taskSummary', array('full' => 1)); ?>"><i
                        class="glyphicon glyphicon-fullscreen"></i></a>
            </legend>
        <?php } else { ?>
            <head>
                <meta http-equiv="refresh" content="60">
                <title><?php echo Yii::app()->name; ?> - Shop Summary</title>
            </head>
            <legend class="text-primary">Teamkenko: Shop Summary
                <a title="Exit fullscreen" class="btn btn-link btn-sm pull-right"
                   href="<?php echo $this->createUrl('/report/taskSummary') ?>"><i
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
                        'header' => 'Shop',
                        'name' => 'shop_id',
                        'type' => 'raw',
                        'value' => function ($data) {
                            if ($data['shop_id'] < 0) {
                                return CHtml::link('Non-group', Yii::app()->createUrl('/report/shopSummary/shop', array('shop' => $data['shop_id'])));
                            }
                            $group = Shop::model()->findByPk($data['shop_id']);
                            if ($group) {
                                return CHtml::link($group->name, Yii::app()->createUrl('/report/shopSummary/shop', array('shop' => $data['shop_id'])));
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

