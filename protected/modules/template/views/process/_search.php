<?php
/* @var $this AdminController */
/* @var $model Process */
/* @var $form CActiveForm */
?>

<div class=" search">

    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'search-process',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => TbHtml::FORM_LAYOUT_INLINE,
    ));
    ?>

    <?php echo $form->textField($model, 'name', array('style'=>'width:20%', 'title' => 'Name', 'placeholder' => 'Name', 'class' => 'input-tp')); ?>
    <?php /*$shopId = Profile::model()->getShopId(Yii::app()->user->id);*/
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
                'style' => 'display:inline-block'
            )
        )
    ));
    ?>
    <?php $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
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
     
    <?php echo TbHtml::submitButton('Search', array(
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

    <?php
    Yii::app()->bootstrap->registerTooltip('body', array(
        'trigger' => 'focus',
        'selector' => 'input.input-tp'
    ));

/*
    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'search-process',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-sm-4',
        'controlWidthClass' => 'col-sm-4'
    ));
    ?>

    <div class="form-group">
        <div class="col-md-3">
            <?php echo $form->textField($model, 'id', array('title' => 'ID', 'placeholder' => 'ID', 'class' => 'input-tp')); ?>
        </div>

        <div class="col-md-3">
            <?php echo $form->textField($model, 'name', array('title' => 'Name', 'placeholder' => 'Name', 'class' => 'input-tp')); ?>
        </div>

        <div class="col-md-3">

            <?php
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $model,
                'keyName' => 'shop_id',
                'placeHolder' => 'Shop',
                'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Shop::model()->findAll(), 'id', 'name')),
                'extra' => array(
                    'readonly' => !empty(Profile::model()->getShopId(Yii::app()->user->id)),
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                        'width' => '100%',
                    ),
                )
            ));
            ?>

        </div>
        <div class="col-md-3">
            <?php
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $model,
                'keyName' => 'supplier_id',
                'placeHolder' => 'Supplier',
                'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Supplier::model()->findAll(), 'id', 'name')),
                'extra' => array(
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                        'width' => '100%',
                    ),
                )
            ));
            ?>
        </div>


        <div class="clearfix"></div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <?php
            echo TbHtml::formActions(array(
                TbHtml::submitButton('Search', array(
                    'size' => TbHtml:: BUTTON_SIZE_SMALL)
                ),
                TbHtml::resetButton('Clear', array(
                    'size' => TbHtml::BUTTON_SIZE_SMALL
                ))
            ));
            ?>
        </div>
    </div>

    <?php $this->endWidget();*/ ?>
</div>