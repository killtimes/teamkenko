<?php
/**
 * The following variables are available in this template:
 * - $this: the BootstrapCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
<?php echo "?>\n"; ?>

<div class="well">

    <?php echo "<?php \$form=\$this->beginWidget('\\TbActiveForm', array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass'=>'col-sm-4',
        'controlWidthClass'=> 'col-sm-4'
)); ?>\n"; ?>

    <?php foreach ($this->tableSchema->columns as $column): ?>
        <?php
        $field = $this->generateInputField($this->modelClass, $column);
        if (strpos($field, 'password') !== false) {
            continue;
        }
        ?>
        <?php echo "<?php echo " . $this->generateActiveControlGroup($this->modelClass, $column) . "; ?>\n"; ?>

    <?php endforeach; ?>
    <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <?php echo "<?php echo TbHtml::formActions(array(
                TbHtml::submitButton('Search',
                    array('color' => TbHtml::BUTTON_COLOR_PRIMARY,
                    'size'=>TbHtml::BUTTON_SIZE_SMALL)
                )
            )); ?>\n"; ?>
                </div>
    </div>

    <?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- search-form -->