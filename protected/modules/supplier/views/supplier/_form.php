<?php
/* @var $this SupplierController */
/* @var $model Supplier */
/* @var $form TbActiveForm */
?>

<div class="form-group">

    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'supplier-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-md-3',
        'controlWidthClass' => 'col-md-9'
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 7, 'maxlength' => 200)); ?>

    <?php echo $form->textFieldControlGroup($model, 'industry', array('span' => 7, 'maxlength' => 200)); ?>

    <?php echo $form->textFieldControlGroup($model, 'address', array('span' => 7, 'maxlength' => 255)); ?>

    <div class="form-group">
        <label class="col-md-3 control-label">Country</label>
        <div class="col-md-9">
            <?php
            $this->widget('yiiwheels.widgets.formhelpers.WhCountries', array(
                'name' => 'Supplier[country]',
                'value' => $model->country,
                'useHelperSelectBox' => true,
                'pluginOptions' => array(
                    'language' => 'es_ES',
                    'flags' => true,
                    'filter' => true
                ),
                'htmlOptions' => array(
                    'class' => 'input-tp',
                    'style' => 'width:33%'
                )
            ));
            ?>
        </div>
    </div>


    <?php echo $form->textFieldControlGroup($model, 'mobile_phone', array('span' => 5, 'maxlength' => 30)); ?>

    <?php echo $form->textFieldControlGroup($model, 'office_phone', array('span' => 5, 'maxlength' => 30)); ?>

    <?php echo $form->textFieldControlGroup($model, 'office_fax', array('span' => 5, 'maxlength' => 30)); ?>


    <div class="row">
        <div class="col-sm-12 text-center">
            <?php

            if (($model->isNewRecord && $this->checkAccess('Supplier_Create' , array())) || (!$model->isNewRecord && $this->checkAccess('Supplier_Update', array()))) {
                echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                    'icon' => TbHtml::ICON_OK
                ));
            }
            ?>
        </div> 
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->