<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form TbActiveForm */
<?php echo "?>\n"; ?>

<div class="form-group">

    <?php echo "<?php \$form=\$this->beginWidget('\\TbActiveForm', array(
	'id'=>'" . $this->class2id($this->modelClass) . "-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-sm-3',
        'controlWidthClass' => 'col-sm-8'

)); ?>\n"; ?>

    <p class="text-info"><span class="label label-info">Info</span> Fields with <span class="required">*</span> are required.</p>

    <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

    <?php
    foreach ($this->tableSchema->columns as $column) {
        if ($column->autoIncrement) {
            continue;
        }
        ?>
        <?php echo "<?php echo " . $this->generateActiveControlGroup($this->modelClass, $column) . "; ?>\n"; ?>

    <?php
    }
    ?>
    
    
    <div class="row">
        <div class="col-sm-12 text-center">
            <?php echo "<?php echo TbHtml::submitButton(\$model->isNewRecord ? 'Create' : 'Save',array(
		    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
                    'icon' => TbHtml::ICON_OK
		)); ?>\n"; ?>
        </div> 
    </div>

    <?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->