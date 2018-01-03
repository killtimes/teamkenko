
<div class="col-sm-12">
    <div class="alert alert-info">
        <span class="label label-info">Note</span>
        Fields with <span class="required">*</span> are required.
    </div>
</div> 

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'labelWidthClass' => 'col-sm-3',
    'controlWidthClass' => 'col-sm-8'
        ));
?>

<div class="col-sm-12"> 
    <?php echo (($model->id) ? $form->textFieldControlGroup($model, 'varname', array('size' => 20, 'maxlength' => 20, 'readonly' => true)) : $form->textFieldControlGroup($model, 'varname', array('size' => 20, 'maxlength' => 20, 'help' => 'Allowed lowercase letters and digits.'))); ?>  
    <?php echo $form->textFieldControlGroup($model, 'title', array('size' => 60, 'maxlength' => 255)); ?>
    <?php echo (($model->id) ? $form->textFieldControlGroup($model, 'field_type', array('size' => 60, 'maxlength' => 50, 'readonly' => true, 'id' => 'field_type')) : $form->dropdownListControlGroup($model, 'field_type', ProfileField::itemAlias('field_type'), array('id' => 'field_type'))); ?>
    <?php echo (($model->id) ? $form->textFieldControlGroup($model, 'field_size', array('readonly' => true)) : $form->textFieldControlGroup($model, 'field_size')); ?>
    <?php echo $form->textFieldControlGroup($model, 'field_size_min'); ?>
    <?php echo $form->dropdownListControlGroup($model, 'required', ProfileField::itemAlias('required')); ?>
    <?php echo $form->textFieldControlGroup($model, 'match', array('size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->textFieldControlGroup($model, 'range', array('size' => 60, 'maxlength' => 5000)); ?>
    <?php echo $form->textFieldControlGroup($model, 'error_message', array('size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->textFieldControlGroup($model, 'other_validator', array('size' => 60, 'maxlength' => 255)); ?>
    <?php echo (($model->id) ? $form->textFieldControlGroup($model, 'default', array('size' => 60, 'maxlength' => 255, 'readonly' => true)) : $form->textFieldControlGroup($model, 'default', array('size' => 60, 'maxlength' => 255))); ?>
    <?php
    list($widgetsList) = ProfileFieldController::getWidgets($model->field_type);
    echo $form->dropdownListControlGroup($model, 'widget', $widgetsList, array('id' => 'widgetlist', 'disabled' => true));
    ?>
    <?php echo $form->textFieldControlGroup($model, 'widgetparams', array('size' => 60, 'maxlength' => 5000, 'id' => 'widgetparams', 'readonly' => true)); ?>
    <?php echo $form->textFieldControlGroup($model, 'position'); ?>
<?php echo $form->dropdownListControlGroup($model, 'visible', ProfileField::itemAlias('visible')); ?>

</div>
<div class="row">
    <div class="col-sm-12 text-center">
        <?php
        echo TbHtml::formActions(array(
            TbHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), array('color' => TbHtml::BUTTON_COLOR_PRIMARY)
            )
        ));
        ?>
    </div> 
</div>

<?php $this->endWidget(); ?>
