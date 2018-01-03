<?php
/* @var $this TaskController */
/* @var $model Task */
/* @var $form CActiveForm */
?>

<div>
    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => TbHtml::FORM_LAYOUT_INLINE
    ));
    ?>

    <?php echo $form->textField($model, 'name', array('style'=>'width:15%', 'placeholder' => 'Name', 'maxlength' => 150)); ?>

    <?php
    $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
        'model' => $model,
        'keyName' => 'task_group',
        'placeHolder' => 'Select task group',
        'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(TaskGroup::model()->findAll(), 'id', 'name')),
        'extra' => array(
            'pluginOptions' => array(
                'placeholderOption' => 'first',
                'width' => '15%'
            ),
        ),
        'htmlOptions' => array(
            'class' => 'form-control',
            'style' => 'display:inline-block'
        )
    ));
    ?>
    <?php
    echo TbHtml::submitButton('Search', array('color' => TbHtml::BUTTON_COLOR_PRIMARY,
        'size' => TbHtml::BUTTON_SIZE_SMALL)
    )
    ?>


    <?php $this->endWidget(); ?>

</div><!-- search-form -->