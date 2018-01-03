<?php
/* @var $this AdminController */
/* @var $model Alert */
/* @var $form TbActiveForm */
?>

<div class="panel-container ">

    <div class="row">
        <div class="col-xs-12">
            <?php
            $urlParams = array('id' => $task->id);
            if(!$model->isNewRecord){
                $urlParams['alert_id']=$model->id;
            }
            $form = $this->beginWidget('\TbActiveForm', array(
                'id' => 'alert-form',
                'action' => $this->createUrl('/alert/admin/bytask', $urlParams),
                'enableAjaxValidation' => false,
                'layout' => TbHtml::FORM_LAYOUT_VERTICAL,
                'htmlOptions' => array(
                    'onsubmit' => 'return false;'
                )

            )); ?>
            <div class="col-xs-12 col-sm-3">
                <?php echo $form->dropDownListControlGroup($model, 'alert_type', Alert::itemAlias('Type')); ?>
            </div>
            <div class="col-xs-12 col-sm-3">
                <?php echo $form->dropDownListControlGroup($model, 'status', Alert::itemAlias('Status')); ?>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="<?php echo ($model->hasErrors('to_user_id')) ? 'has-error' : '' ?> form-group">
                    <label class="control-label required">To <span class="required">*</span></label>
                    <div class="">
                        <?php

                        if (empty($model->to_users)) {
                            $model->to_users = $model->getAlertRecipient();
                        }

                        if(is_array($model->to_users)){
                            $model->to_users = implode(',', $model->to_users);
                        }

                        $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_user', array(
                            'model' => $model,
                            'keyName' => 'to_users',
                            'placeHolder' => 'Select User',
                            'shop_id' => 0,
                            'extra' => array(
                                'pluginOptions' => array(
                                    'multiple' => true
                                )
                            )
                        ));
                        ?>
                        <?php echo $form->error($model, 'to_user_id'); ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12">
                <?php echo $form->textAreaControlGroup($model, 'note', array('rows' => 3, 'placeholder' => 'Enter note if any')); ?>
            </div>
            <div class="col-xs-12 col-sm-12 text-center">
                <?php echo TbHtml::submitButton('Save alert', array(
                    'id' => 'btnSaveAlert',
                    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                    'size' => TbHtml::BUTTON_SIZE_SM,
                    'data-loading-text' => 'Saving...'
                ));

                if (!$model->isNewRecord) {
                    echo ' ' . TbHtml::link('Cancel', 'javascript:;', array(
                            'id' => 'btnCancel',
                            'color' => TbHtml::BUTTON_COLOR_DEFAULT,
                            'size' => TbHtml::BUTTON_SIZE_SM,
                            'data-url' => $this->createUrl('/alert/admin/bytask', array('id' => $task->id))
                        ));
                }
                ?>
            </div>
            <?php $this->endWidget(); ?>

        </div><!-- form -->

    </div>
    <div class="clearfix"></div>
</div>
