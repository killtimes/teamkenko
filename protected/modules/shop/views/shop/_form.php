<?php
/* @var $this ShopController */
/* @var $model Shop */
/* @var $form TbActiveForm */
?>

<div class="form-group">

    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'shop-form',
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

    <?php echo $form->textFieldControlGroup($model, 'employees', array('span' => 7, 'maxlength' => 50)); ?>

    <?php echo $form->textFieldControlGroup($model, 'address', array('span' => 7, 'maxlength' => 200)); ?>

    <?php echo $form->textFieldControlGroup($model, 'phone', array('span' => 7, 'maxlength' => 50)); ?>

    <?php echo $form->textFieldControlGroup($model, 'fax', array('span' => 7, 'maxlength' => 50)); ?>

    <div class="row">
        <div class="col-sm-12 text-center"> 
            <?php
            if (($model->isNewRecord && $this->checkAccess('Shop_Create', array())) || (!$model->isNewRecord && $this->checkAccess('Shop_Update', array())))
                echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                    'icon' => TbHtml::ICON_OK,
                ));
            ?>
        </div> 
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->