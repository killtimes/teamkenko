<?php
/* @var $this AdminController */
/* @var $model Process */
/* @var $form CActiveForm */
?>

<div class="search">

    <?php
    Yii::app()->bootstrap->registerTooltip('body', array(
        'trigger' => 'focus',
        'selector' => 'input.input-tp'
    ));

    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'search-process',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => TbHtml::FORM_LAYOUT_INLINE
    ));
    ?>
    <?php echo $form->textField($model, 'name', array('style'=>'min-width:20%', 'title' => 'Name', 'placeholder' => 'Name', 'class' => 'input-tp')); ?>
    <?php 
    $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
        'model' => $model,
        'keyName' => 'shop_id',
        'placeHolder' => 'Shop',
        'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Shop::model()->findAll(), 'id', 'name')),
        'extra' => array(
//            'readonly' => !empty($shopId),
            'pluginOptions' => array(
                'placeholderOption' => 'first',
                'width' => '20%',
            ),
            'htmlOptions' => array(
                'class' => 'form-control',
                'style' => 'display:inline-block;'
            )
        )
    ));
    ?>  
    <?php
    $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
        'model' => $model,
        'keyName' => 'supplier_id',
        'placeHolder' => 'Contact',
        'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Supplier::model()->findAll(), 'id', 'name')),
        'extra' => array(
            'pluginOptions' => array(
                'placeholderOption' => 'first',
                'width' => '20%',
            ),
            'htmlOptions' => array(
                'class' => 'form-control',
                'style' => 'display:inline-block'
            )
        )
    ));
    ?> 
    <?php if ($this->action->id != 'archived') { ?>
         <?php
        $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
            'model' => $model,
            'keyName' => 'stage',
            'placeHolder' => 'Stage',
            'data' => CMap::mergeArray(array('' => ''), Process::itemAlias('StageNotCompleted')),
            'extra' => array(
                'pluginOptions' => array(
                    'placeholderOption' => 'first',
                    'width' => '20%',
                ),
                'htmlOptions' => array(
                    'class' => 'form-control',
                    'style' => 'display:inline-block'
                )
            )
        ));
        ?>
    <?php } ?>
    <?php
    echo TbHtml::submitButton('Search', array(
        'size' => TbHtml:: BUTTON_SIZE_SMALL,
        'color'=>  TbHtml::BUTTON_COLOR_PRIMARY)
    );
    ?> 
    <?php
    echo TbHtml::resetButton('Clear', array(
        'size' => TbHtml::BUTTON_SIZE_SMALL
    ));
    ?> 
    

    <?php $this->endWidget(); ?>
</div>