<?php
/* @var $this DocumentController */
/* @var $model Document */
/* @var $form TbActiveForm */
?>

<div class="form-group">

    <?php $form=$this->beginWidget('\TbActiveForm', array(
	'id'=>'document-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-sm-3',
        'controlWidthClass' => 'col-sm-8'

)); ?>

    <p class="text-info"><span class="label label-info">Info</span> Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

            <?php echo $form->textFieldControlGroup($model,'task_id',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'shop_id',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'supplier_id',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'doc_type',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'doc_code',array('span'=>5,'maxlength'=>30)); ?>

            <?php echo $form->textFieldControlGroup($model,'doc_date',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'title',array('span'=>5,'maxlength'=>200)); ?>

            <?php echo $form->textFieldControlGroup($model,'file_name',array('span'=>5,'maxlength'=>255)); ?>

            <?php echo $form->textFieldControlGroup($model,'file_type',array('span'=>5,'maxlength'=>30)); ?>

            <?php echo $form->textFieldControlGroup($model,'source_type',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'create_date',array('span'=>5)); ?>

            <?php echo $form->textFieldControlGroup($model,'update_date',array('span'=>5)); ?>

        
    
    <div class="row">
        <div class="col-sm-12 text-center">
            <?php echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
                    'icon' => TbHtml::ICON_OK
		)); ?>
        </div> 
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->