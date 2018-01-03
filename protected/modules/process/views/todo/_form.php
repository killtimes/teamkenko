<div class="form-group">

    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'process-form',
        'enableAjaxValidation' => false,
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-md-3',
        'controlWidthClass' => 'col-md-8'));
    ?>

    <?php echo $form->errorSummary($model); ?> 

    <div class="<?php echo ($model->hasErrors('customTaskName')) ? 'has-error' : '' ?> form-group">        
        <label class="col-md-3 control-label required">Task <span class="required">*</span></label>
        <div class="col-md-8"> 
            <?php
            $this->widget('yiiwheels.widgets.typeahead.WhTypeAhead', array(
                'model' => $model,
                'attribute' => 'customTaskName',
                'htmlOptions' => array(
                    'value' => $model->customTaskName,
                ),
                'pluginOptions' => array(
                    'local' => array_keys(CHtml::listData(Task::model()->scopeActive()->findAll(), 'name', 'name'))
                )
            ));
            ?>
            <?php echo $form->error($model, 'customTaskName'); ?>
        </div>
    </div>

    <?php echo $form->textFieldControlGroup($model, 'duration', array('label' => 'Duration (hours)', 'placeholder' => 'Estimate time')); ?>

    <div class="<?php echo ($model->hasErrors('assign_id')) ? 'has-error' : '' ?> form-group">        
        <label class="col-md-3 control-label required">Assign To <span class="required">*</span></label>
        <div class="col-md-8"> 
            <?php
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_user', array(
                'model' => $model,
                'keyName' => 'assign_id',
                'placeHolder' => 'Select User',
                'shop_id' => 0
            ));
            ?>
            <?php echo $form->error($model, 'assign_id'); ?>
        </div>
    </div>

    <?php if (empty($model->process_id)) { ?>

        <div class="<?php echo ($model->hasErrors('shop_id')) ? 'has-error' : '' ?> form-group">        
            <label class="col-sm-3 control-label">Shop</label>
            <div class="col-sm-8"> 
                <?php
                $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                    'model' => $model,
                    'keyName' => 'shop_id',
                    'placeHolder' => 'Select Shop',
                    'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Shop::model()->findAll(), 'id', 'name')),
                    'extra' => array(
                        'pluginOptions' => array(
                            'placeholderOption' => 'first',
                            'width' => '70%',
                        ),
                    )
                ));
                ?>
                <?php echo $form->error($model, 'shop_id'); ?>

            </div>
        </div> 

        <div class="<?php echo ($model->hasErrors('supplier_id')) ? 'has-error' : '' ?> form-group">        
            <label class="col-sm-3 control-label">Contact</label>
            <div class="col-sm-8"> 
                <?php
                $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                    'model' => $model,
                    'keyName' => 'supplier_id',
                    'placeHolder' => 'Select Contact',
                    'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Supplier::model()->findAll(), 'id', 'name')),
                    'extra' => array(
                        'pluginOptions' => array(
                            'placeholderOption' => 'first',
                            'width' => '70%',
                        ),
                    )
                ));
                ?>
                <?php echo $form->error($model, 'supplier_id'); ?>

            </div>
        </div> 

    <?php } ?>

    <?php echo $form->textAreaControlGroup($model, 'description', array('rows' => 5)); ?>
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

            if (empty($model->alert_recipients)) {
                $model->alert_recipients = $model->getAlertRecipient();
            }

            if(is_array($model->alert_recipients)){
                $model->alert_recipients = implode(',', $model->alert_recipients);
            }

            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_user', array(
                'model' => $model,
                'keyName' => 'alert_recipients',
                'placeHolder' => 'Select User',
                'shop_id' => 0,
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
    <div class="<?php echo ($model->hasErrors('alert_enable')) ? 'has-error' : '' ?> form-group">
        <label class="col-md-3 control-label required">Enable <span class="required">*</span></label>
        <div class="col-md-7 form-inline">
            <?php echo $form->radioButtonList($model, 'alert_enable', array('1' => 'Yes', '0' => 'No')); ?>
            <?php echo $form->error($model, 'alert_enable'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 text-center">
            <?php
            echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'icon' => TbHtml::ICON_OK
            ));
            ?>
        </div> 
    </div>

    <?php $this->endWidget(); ?>

</div>
<?php
Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/js/jquery.counter.min.js', CClientScript::POS_END);

Yii::app()->clientScript->registerScript('js-counter', '
    $("#TaskProcess_description").counter({
                goal: "sky",
                type: "word",
                msg: "word(s)"
            });
    ', CClientScript::POS_READY);
