<?php
/* @var $this TaskgroupController */
/* @var $model TaskGroup */
/* @var $form CActiveForm */
?>

<div class="well">

    <?php $form=$this->beginWidget('\TbActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass'=>'col-sm-4',
        'controlWidthClass'=> 'col-sm-4'
)); ?>

                    <?php echo $form->textFieldControlGroup($model,'id',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'name',array('span'=>5,'maxlength'=>100)); ?>

                    <?php echo $form->textFieldControlGroup($model,'create_date',array('span'=>5)); ?>

                    <?php echo $form->textFieldControlGroup($model,'update_date',array('span'=>5)); ?>

        <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <?php echo TbHtml::formActions(array(
                TbHtml::submitButton('Search',
                    array('color' => TbHtml::BUTTON_COLOR_PRIMARY,
                    'size'=>TbHtml::BUTTON_SIZE_SMALL)
                )
            )); ?>
                </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->