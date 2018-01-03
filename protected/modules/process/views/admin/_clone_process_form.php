<?php
$form = $this->beginWidget('\TbActiveForm', array(
    'id' => 'clone-process-form',
    'enableAjaxValidation' => true,
    'htmlOptions' => array(
        'onsubmit' => "return false;"
    ),
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'afterValidate' => 'js:ProcessPage.submitProcess'
    ),
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'labelWidthClass' => 'col-sm-3',
    'controlWidthClass' => 'col-sm-7'
        ));
?>

<?php echo $form->errorSummary($model); ?>

<div class="<?php echo ($model->hasErrors('template_id')) ? 'has-error' : '' ?> form-group">        
    <label class="col-sm-3 control-label">Template <span class="required">*</span></label>
    <div class="col-sm-7"> 
        <?php
        $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_template', array(
            'model' => $model,
            'keyName' => 'template_id',
            'placeHolder' => 'Select Template',
            'extra' => array(
                'events' => array("select2-selecting" => 'js:ProcessPage.setProcessName'),
            )
        ));
        ?>
        <?php echo $form->error($model, 'template_id'); ?>
    </div>
</div>

<?php echo $form->textFieldControlGroup($model, 'name', array('controlWidthClass' => 'col-sm-7', 'maxlength' => 255)); ?>

<?php $this->endWidget(); ?>
