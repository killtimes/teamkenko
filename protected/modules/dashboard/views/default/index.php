<?php
/* @var $this DefaultController */

$this->breadcrumbs = array(
    'Dashboard',
);
?>
<div class="col-sm-12 col-md-6 col-lg-7">
    <div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <?php if (in_array('Staff', $this->roles)) { ?>
                    <legend>Task Summary</legend>
                    <div class="col-sm-8 col-md-8 col-lg-6">
                        <div class="list-group">
                            <a href="<?php echo Yii::app()->createUrl('process/task/request'); ?>"
                               class="list-group-item">
                                Tasks Request
                                <span class="badge badge-info"><?php echo $model->getTotalTaskRequests(); ?></span>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('process/task/dueToday'); ?>"
                               class="list-group-item">Tasks Due Today
                                <span class="badge badge-success"><?php echo $model->getTotalTaskDueToday(); ?></span>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('process/task/dueTomorrow'); ?>"
                               class="list-group-item">Tasks Due Tomorrow
                                <span
                                    class="badge badge-success"><?php echo $model->getTotalTaskDueTomorrow(); ?></span>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('process/task/dueOver2days'); ?>"
                               class="list-group-item">Tasks Due Over 2 Days
                                <span
                                    class="badge badge-success"><?php echo $model->getTotalTaskDueOver2Days(); ?></span>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('process/task/overdue'); ?>"
                               class="list-group-item">Overdue Tasks
                                <span class="badge badge-danger"><?php echo $model->getTotalTaskOverdue(); ?></span>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('process/task/assigned'); ?>"
                               class="list-group-item">Wait For Accept
                                <span class="badge badge-warning"><?php echo $model->getTotalTaskAssigned(); ?></span>
                            </a>
                        </div>
                    </div>
                <?php } else { ?>
                    <legend>Dashboard</legend>
                <?php } ?>

                <?php if (Yii::app()->user->getIsSuperuser()) { ?>
                    <div class="col-sm-8 col-md-8 col-lg-6">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-info"><strong>Unresolved alerts</strong></div>
                            <a href="<?php echo Yii::app()->createUrl('/alert/admin/list', array('type' => 'noncritical')); ?>"
                               class="list-group-item">Non-Critical Alerts
                                <span
                                    class="badge badge-danger"><?php echo Alert::model()->totalNonCriticalAlert(); ?></span>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('/alert/admin/list', array('type' => 'critical')); ?>"
                               class="list-group-item">Critical Alerts
                                <span
                                    class="badge badge-danger"><?php echo Alert::model()->totalCriticalAlert(); ?></span>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('/alert/admin/list', array('type' => 'highlycritical')); ?>"
                               class="list-group-item">Highly Critical Alerts
                                <span
                                    class="badge badge-danger"><?php echo Alert::model()->totalHighlyCriticalAlert(); ?></span>
                            </a>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-8 col-md-8 col-lg-6">
                        <div class="list-group">
                            <a href="<?php echo Yii::app()->createUrl('/alert/list', array('type' => 'noncritical')); ?>"
                               class="list-group-item">Non-Critical Alerts
                                <span
                                    class="badge badge-danger"><?php echo Alert::model()->countNonCriticalAlert(Yii::app()->user->id); ?></span>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('/alert/list', array('type' => 'critical')); ?>"
                               class="list-group-item">Critical Alerts
                                <span
                                    class="badge badge-danger"><?php echo Alert::model()->countCriticalAlert(Yii::app()->user->id); ?></span>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('/alert/list', array('type' => 'highlycritical')); ?>"
                               class="list-group-item">Highly Critical Alerts
                                <span
                                    class="badge badge-danger"><?php echo Alert::model()->countHighlyCriticalAlert(Yii::app()->user->id); ?></span>
                            </a>
                        </div>
                    </div>
                <?php } ?>

            </fieldset>
        </div>
    </div>
</div>
<div class="col-sm-12 col-md-6 col-lg-5">
    <div class="panel panel-default panel-container panel-calendar">
        <div class="panel-heading"><span class="glyphicon glyphicon-calendar"></span> Task Schedule
            <button id="btnRefresh" data-loading-text="Loading..." class="btn btn-default btn-xs pull-right">Refresh
            </button>
        </div>
        <div class="panel-body">
            <div id="calendarLoader" style="padding: 20px">
                <div class="progress ">
                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    </div>
                </div>
            </div>

            <div id="dCalendar">
                <div class="dashboard-calendar"></div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<!--
<?php
$this->widget('\TbGridView', array(
    'dataProvider' => new CArrayDataProvider(array()),
    'id' => 'dummy',
    'columns' => array('a')
));

$this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
    'name' => 'dummytime',
    'pluginOptions' => array(
        'showMeridian' => false,
        'defaultTime' => 'value'
    )
));
Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/js/jquery.counter.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('handle-calendar', 'DashboardPage.initCalendar("' . Yii::app()->createUrl('/dashboard/calendar/parseTask') . '")', CClientScript::POS_READY);
?>

-->

<?php
$this->beginWidget('\TbModal', array(
    'id' => 'mdListTask',
    'header' => false,
    'size' => ' modal-lg modal-slg',
    'backdrop' => true,
    'footer' => false,
    'fade' => false,
    'htmlOptions' => array(
        'data-url' => Yii::app()->createUrl('/dashboard/calendar/listTask')
    )
));
?>
<div class="content-placeholder">
    <div class="placeholder">
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar"
                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
</div>

<?php
$this->beginWidget('\TbModal', array(
    'id' => 'mdAddTask',
    'size' => TbHtml::MODAL_SIZE_LARGE,
    'header' => 'Add Task',
    'backdrop' => true,
    'fade' => false,
    'footer' => array(
        TbHtml::button('Save', array('id' => 'btnSave', 'color' => TbHtml::BUTTON_COLOR_PRIMARY)),
        TbHtml::button('Close', array('id' => 'btnCancel')),
    )
));
?>
<div class="content-placeholder">
    <div class="placeholder">
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar"
                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">

            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
</div>

<?php $this->renderPartial('process.views.task._task_activity'); ?>

<!--
<?php
$this->widget('yiiwheels.widgets.typeahead.WhTypeAhead', array(
    'name' => 'dummyLoader',
    'pluginOptions' => array(
        'local' => array(
            'abc'
        )
    )
));
?>
-->