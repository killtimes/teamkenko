<?php
/* @var $this AdminController */
/* @var $model Alert */


$this->breadcrumbs = array(
    'Alerts' => array('admin'),
    'Manage',
);
?>

<div class="panel with-nav-tabs panel-default panel-container">
    <div class="panel-heading">
        <ul class="nav nav-tabs">
            <li class="<?php echo(($this->type == '') ? 'active' : '') ?>"><a
                    href="<?php echo Yii::app()->createUrl('/alert/admin/list', array('type' => '')); ?>">
                    All alerts
                </a>
            </li>
            <li class="<?php echo(($this->type == 'noncritical') ? 'active' : '') ?>"><a
                    href="<?php echo Yii::app()->createUrl('/alert/admin/list', array('type' => 'noncritical')); ?>">
                    Non-Critical
                </a>
            </li>

            <li class="<?php echo(($this->type == 'critical') ? 'active' : '') ?>"><a
                    href="<?php echo Yii::app()->createUrl('/alert/admin/list', array('type' => 'critical')); ?>">
                    Critical
                </a>
            </li>
            <li class="<?php echo(($this->type == 'highlycritical') ? 'active' : '') ?>"><a
                    href="<?php echo Yii::app()->createUrl('/alert/admin/list', array('type' => 'highlycritical')); ?>">
                    Highly Critical
                </a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <fieldset>
            <legend>List Alerts</legend>

            <?php $this->widget('\TbGridView', array(
                'id' => 'alert-grid',
                'dataProvider' => $model->search(),
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    'id',
                    array(
                        'header' => 'Alert Type',
                        'type' => 'raw',
                        'value' => 'Alert::itemAlias("Type",$data->alert_type)'
                    ),
                    array(
                        'name' => 'status',
                        'header' => 'Critical status',
                        'type' => 'raw',
                        'value' => 'Alert::statusAlias($data["status"])',
                    ),
                    array(
                        'name' => 'stage',
                        'header' => 'Status',
                        'type' => 'raw',
                        'value' => 'Alert::stageAlias($data["stage"])',
                    ),
                    array(
                        'header' => 'Created Date',
                        'type' => 'raw',
                        'value' => '($data["create_date"])?"<abbr title=\"".Yii::app()->localTime->fromUTC($data["create_date"])."\">".Yii::app()->format->timeAgo($data["create_date"])."</abbr>":""',
                    ),
                    array(
                        'class' => 'AlertActionColumn',
                        'value' => '',
                        'atype' => $this->type
                    )
                ),
            )); ?>
        </fieldset>
    </div>
</div>

