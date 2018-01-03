<?php
/* @var $form TbActiveForm */
$form = $this->beginWidget('\TbActiveForm', array(
    'id' => 'edit-task-form',
    'enableAjaxValidation' => true,
    'htmlOptions' => array(
        'onsubmit' => "return false;"
    ),
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'afterValidate' => 'js:ProcessPage.submitTaskProcess'
    ),
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'labelWidthClass' => 'col-md-3',
    'controlWidthClass' => 'col-md-7'
));
?>
<legend class="fontsmaller">Task assignee</legend>


<?php echo $form->errorSummary($model); ?>

<div class="<?php echo ($model->hasErrors('task_id')) ? 'has-error' : '' ?> form-group">
    <label class="col-md-3 control-label required">Task <span class="required">*</span></label>
    <div class="col-md-7">
        <?php
        $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_task', array(
            'model' => $model,
            'keyName' => 'task_id',
            'taskGroup' => $modelProcess->task_group
        ));
        ?>
        <?php echo $form->error($model, 'task_id'); ?>
    </div>
</div>

<?php echo $form->textFieldControlGroup($model, 'duration', array('placeholder' => 'Estimate time')); ?>

<div class="<?php echo ($model->hasErrors('assign_id')) ? 'has-error' : '' ?> form-group">
    <label class="col-md-3 control-label required">Assign To <span class="required">*</span></label>
    <div class="col-md-7">
        <?php
        $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_user', array(
            'model' => $model,
            'keyName' => 'assign_id',
            'placeHolder' => 'Select User',
            'shop_id' => $modelProcess->shop_id
        ));
        ?>
        <?php echo $form->error($model, 'assign_id'); ?>
    </div>
</div>
<div class="<?php echo ($model->hasErrors('is_att_mandatory')) ? 'has-error' : '' ?> form-group">
    <label class="col-md-3 control-label required">Is attachment mandatory ? <span class="required">*</span></label>
    <div class="col-md-7 form-inline">
        <?php echo $form->radioButtonList($model, 'is_att_mandatory', array('1' => 'Yes', '0' => 'No')); ?>
        <?php echo $form->error($model, 'is_att_mandatory'); ?>
        <p class="help-block">If attachment is mandatory, task can't be completed without attachment.</p>
    </div>
</div>
<div class="<?php echo ($model->hasErrors('can_not_reject')) ? 'has-error' : '' ?> form-group">
    <label class="col-md-3 control-label required">Can be rejected ? <span class="required">*</span></label>
    <div class="col-md-7 form-inline">
        <?php echo $form->radioButtonList($model, 'can_not_reject', array('1' => 'Task can\'t be rejected', '0' => 'Task can be rejected')); ?>
        <?php echo $form->error($model, 'can_not_reject'); ?>
    </div>
</div>
<div class="<?php echo ($model->hasErrors('task_type')) ? 'has-error' : '' ?> form-group">
    <label class="col-md-3 control-label required">Task type<span class="required">*</span></label>
    <div class="col-md-7 form-inline">
        <?php echo $form->radioButtonList($model, 'task_type',TaskProcessTemplate::itemAlias('TaskType')); ?>
        <?php echo $form->error($model, 'task_type'); ?>
    </div>
</div>
<legend class="fontsmaller">Task alert configurations</legend>
<div class="<?php echo ($model->hasErrors('alert_conditions')) ? 'has-error' : '' ?> form-group">
    <label class="col-md-3 control-label required">Condition <span class="required">*</span></label>
    <div class="col-md-7">
        <?php
        echo $form->checkBoxList($model, 'alert_conditions', TaskProcessTemplate::itemAlias('AlertCondition'));
        ?>
        <?php echo $form->error($model, 'alert_conditions'); ?>
    </div>
</div>
<div class="<?php echo ($model->hasErrors('alert_recipients')) ? 'has-error' : '' ?> form-group">
    <label class="col-md-3 control-label required">Send to <span class="required">*</span></label>
    <div class="col-md-7">
        <?php
        $data = array();
        $data =  $model->getAlertRecipientTemplate2();
        $model->alert_recipients = array_keys($data);

        if(is_array($model->alert_recipients)){
            $model->alert_recipients = implode(',', $model->alert_recipients);
        }

        $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_user', array(
            'model' => $model,
            'keyName' => 'alert_recipients',
            'placeHolder' => 'Select User',
            'shop_id' => $modelProcess->shop_id,
            'extra' => array(
                'pluginOptions' => array(
                    'multiple' => true
                )
            )
        ));
        ?>
        <?php echo $form->error($model, 'alert_recipients'); ?>
    </div>
</div>
<div class="<?php echo ($model->hasErrors('send_mail_recipients')) ? 'has-error' : '' ?> form-group">
    <label class="col-md-3 control-label required">Send email to</label>
    <div class="col-md-7">
        <?php

        if(count($data) > 0){
            $r = array();
            foreach($data as $k=>$v){
                if((bool)$v){
                    $r[] = $k;
                }
            }

            $model->send_mail_recipients = implode(',', $r);
        }

        $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_user', array(
            'model' => $model,
            'keyName' => 'send_mail_recipients',
            'placeHolder' => 'Select User',
            'shop_id' => $modelProcess->shop_id,
            'extra' => array(
                'pluginOptions' => array(
                    'multiple' => true
                )
            )
        ));
        ?>
        <?php echo $form->error($model, 'send_mail_recipients'); ?>
        <a href="javascript:;" onclick="$('#s2id_TaskProcessTemplate_send_mail_recipients').select2('val',$('#s2id_TaskProcessTemplate_alert_recipients').select2('val'))">List user above</a>
        <?php echo $form->textField($model,'send_mail_recipient_extra',array('placeholder'=>'Or/And enter email address here e.g email1@abc.com,email2@abc.com,...')); ?>

    </div>

</div>
<div class="<?php echo ($model->hasErrors('alert_enable')) ? 'has-error' : '' ?> form-group">
    <label class="col-md-3 control-label required">Enable <span class="required">*</span></label>
    <div class="col-md-7 form-inline">
        <?php echo $form->radioButtonList($model, 'alert_enable', array('1' => 'Yes', '0' => 'No')); ?>
        <?php echo $form->error($model, 'alert_enable'); ?>
    </div>
</div>


<?php /* ?>
<div class="<?php echo ($model->hasErrors('priority')) ? 'has-error' : '' ?> form-group">        
    <label class="col-sm-4 control-label required">Prority <span class="required">*</span></label>
    <div class="col-sm-6"> 
        <?php
        $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
            'model' => $model,
            'keyName' => 'priority',
            'placeHolder' => 'Select priority',
            'data' => CMap::mergeArray(array('' => ''), TaskProcessTemplate::itemAlias('Priority')),
            'extra' => array(
                'pluginOptions' => array(
                    'placeholderOption' => 'first',
                    'minimumResultsForSearch' => '-1'
                ),
            )
        ));
        ?>
        <?php echo $form->error($model, 'priority'); ?>
    </div>
</div>
<?php */ ?>
<?php $this->endWidget(); ?>
