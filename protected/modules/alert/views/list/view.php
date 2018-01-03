<?php

/* @var $form TbActiveForm */

if (Yii::app()->user->getIsSuperuser()) {
    $this->breadcrumbs = array(
        'Alerts' => array('/alert/admin/list'),
        '#' . $model->id,
    );

    echo TbHtml::buttonGroup(array(
        array(
            'label' => 'List Alert',
            'url' => array('/alert/admin/list', 'type' => $this->type),
        ),
    ));
} else {
    $this->breadcrumbs = array(
        'Alerts' => array('/alert/list'),
        '#' . $model->id,
    );

    echo TbHtml::buttonGroup(array(
        array(
            'label' => 'List Alert',
            'url' => array('/alert/list', 'type' => $this->type),
        ),
    ));
}
$taskProcess = TaskProcess::model()->findByPk($model["related_task_id"]);

echo $this->renderPartial('webroot.themes.theme1.views.includes._flash');

?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Alert #<?php echo $model->id; ?></legend>
            <?php
            $form = $this->beginWidget('\TbActiveForm', array(
                'id' => 'alert-form',
                'enableAjaxValidation' => false,
                'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                'labelWidthClass' => 'col-md-3',
                'controlWidthClass' => 'col-md-8'));
            ?>


            <div class="col-xs-12 col-md-6">
                <div class="list-group">
                    <div class="list-group-item list-group-item-danger">Alert information</div>
                    <div class="list-group-item">
                        Type: <strong><?php echo Alert::itemAlias('Type', $model->alert_type); ?></strong><br>
                        Sent to:<br>
                        <?php

                        if (empty($model->to_users)) {
                            $model->to_users = $model->getAlertRecipient();
                        }

                        if (is_array($model->to_users)) {
                            $model->to_users = implode(',', $model->to_users);
                        }

                        $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_user', array(
                            'model' => $model,
                            'keyName' => 'to_users',
                            'placeHolder' => 'Select User',
                            'shop_id' => 0,
                            'extra' => array(
                                'pluginOptions' => array(
                                    'containerCssClass' => 'view-only',
                                    'multiple' => true
                                ),
                                'readonly' => true
                            )
                        ));
                        ?>

                        Critical status: <?php echo Alert::statusAlias($model["status"]); ?><br>
                        Status: <?php echo Alert::stageAlias($model["stage"]); ?><br>
                        Note: <p><?php echo nl2br(CHtml::encode($model->note)); ?></p>
                        Created: <?php echo "<abbr title=\"" . Yii::app()->localTime->fromUTC($model["create_date"]) . "\">" . Yii::app()->format->timeAgo($model["create_date"]) . "</abbr>"; ?>
                        <?php echo 'by ' . Profile::model()->getById($model["create_by"])->getFullName(); ?><br>

                        <?php if (!empty($model->update_date)) { ?>
                            Updated:
                            <?php echo "<abbr title=\"" . Yii::app()->localTime->fromUTC($model["update_date"]) . "\">" . Yii::app()->format->timeAgo($model["update_date"]) . "</abbr>"; ?>
                            <?php echo 'by ' . Profile::model()->getById($model["update_by"])->getFullName(); ?>

                        <?php } ?>
                    </div>

                    <?php if ($model->stage == Alert::STAGE_ACTIVE && $model->alert_type == Alert::TYPE_TASK_REJECTED) { ?>
                        <div class="list-group-item">
                            <div class="form-inline">
                                <div class="input-group">Reassign task:</div>
                                <div class="input-group">
                                    <?php
                                    echo TbHtml::hiddenField('TaskProcess[id]', $taskProcess->id);
                                    $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_user', array(
                                        'model' => new TaskProcess(),
                                        'keyName' => 'assign_id',
                                        'placeHolder' => 'Select Staff',
                                        'shop_id' => 0,
                                        'extra' => array(
                                            'pluginOptions' => array(
                                                'width' => '250px',
                                            ),
                                        )
                                    ));
                                    ?>
                                </div>
                                <div class="input-group">
                                    <?php echo TbHtml::submitButton('Reassign', array(
                                        'name' => 'reassign',
                                        'size' => TbHtml::BUTTON_SIZE_SM,
                                        'color' => TbHtml::BUTTON_COLOR_INFO
                                    )); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($model->stage != Alert::STAGE_RESOLVED && ($this->checkAccess('Alert_DownCriticalStatus') || $this->checkAccess('Alert_UpCriticalStatus'))) { ?>
                        <div class="list-group-item">
                            <div class="form-inline">
                                <div class="input-group">Change critical status:</div>
                                <div class="input-group">
                                    <?php echo $form->dropDownList($model, 'status', Alert::itemAlias('Status')); ?>

                                </div>
                                <div class="input-group">
                                    <?php echo TbHtml::submitButton('Change', array(
                                        'name' => 'changecriticalstatus',
                                        'size' => TbHtml::BUTTON_SIZE_SM,
                                        'color' => TbHtml::BUTTON_COLOR_DANGER
                                    )); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($this->checkAccess('Alert_Resolve')) { ?>
                        <div class="list-group-item">
                            <div class="form-inline">
                                <div class="input-group">Resolve alert:</div>
                                <div class="input-group">
                                    <?php if ($model->stage == Alert::STAGE_RESOLVED) { ?>
                                        <label class="label label-success">Resolved</label>
                                        <?php echo TbHtml::submitButton('Undo', array(
                                            'name' => 'unresolve',
                                            'class' => 'btn-link',
                                            'size' => TbHtml::BUTTON_SIZE_SM,
                                            'color' => TbHtml::BUTTON_COLOR_DEFAULT
                                        )); ?>
                                    <?php } else { ?>
                                        <?php echo TbHtml::submitButton('Mark as Resolved', array(
                                            'name' => 'resolve',
                                            'size' => TbHtml::BUTTON_SIZE_SM,
                                            'color' => TbHtml::BUTTON_COLOR_SUCCESS
                                        )); ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="list-group">
                    <div class="list-group-item list-group-item-info">Task information</div>
                    <div class="list-group-item">
                        <strong><?php

                            if ($taskProcess != null) {
                                $task = Task::model()->getById($taskProcess->task_id);
                                echo $task->name;

                                echo ' ' . TaskProcess::stageAlias($taskProcess->stage);

                                $process = $taskProcess->process;
                            }
                            ?>
                        </strong>
                    </div>
                    <div class="list-group-item">
                        <?php if (!empty($process)) { ?>
                            * Job: <a href="javascript:;" data-container="#mdListTask" class="view-process"
                                      data-proccess-id="<?php echo $process->id; ?>"
                                      data-url="<?php echo $this->createUrl('/process/overview', array('id' => $process->id, 'task_id' => $taskProcess->id)); ?>"><?php echo $process->name; ?></a>
                            <br>
                        <?php } ?>
                        <?php if (!empty($taskProcess) && !empty($taskProcess->assign_date)) { ?>
                            * Assigned to
                            <?php echo ($taskProcess) ? Profile::model()->getById($taskProcess->assign_id)->getFullName() : 'n/a'; ?>
                            <i><?php echo TbHtml::abbr(Yii::app()->format->timeAgo($taskProcess["assign_date"]), Yii::app()->localTime->fromUTC($taskProcess->assign_date)); ?></i>
                        <?php } ?>
                        <?php if (!empty($taskProcess) && !empty($taskProcess->accept_date)) { ?>
                            <br>
                            * Accepted
                            <i><?php echo TbHtml::abbr(Yii::app()->format->timeAgo($taskProcess["accept_date"]), Yii::app()->localTime->fromUTC($taskProcess->accept_date)); ?></i>
                        <?php } ?>

                        <?php if (!empty($taskProcess) && !empty($taskProcess->reject_date)) { ?>
                            <br>
                            * Rejected
                            <i><?php echo TbHtml::abbr(Yii::app()->format->timeAgo($taskProcess["reject_date"]), Yii::app()->localTime->fromUTC($taskProcess->reject_date)); ?></i>
                        <?php } ?>

                        <?php if (!empty($taskProcess) && !empty($taskProcess->due_date)) { ?>
                            <br>
                            * Due date:
                            <i><?php echo TbHtml::abbr(Yii::app()->format->timeAgo($taskProcess["due_date"]), Yii::app()->localTime->fromUTC($taskProcess->due_date)); ?></i>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php $this->endWidget(); ?>

        </fieldset>
    </div>
</div>

<?php $this->renderPartial('process.views.task._task_activity'); ?>

