<?php
/* @var $this DocumentController */
/* @var $model Document */
/* @var $form CActiveForm */
?>

<div class="well">

    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => TbHtml::FORM_LAYOUT_INLINE,
        'labelWidthClass' => 'col-sm-4',
        'controlWidthClass' => 'col-sm-4'
    ));
    ?>
    <?php
    $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
        'model' => $model,
        'keyName' => 'filter_by',
        'placeHolder' => 'Date Filter Type',
        'data' => array('' => '', Document::FILTERTYPE_UPLOADDATE => 'Upload date', Document::FILTERTYPE_BIZDATE => 'Business date'),
        'extra' => array(
            'pluginOptions' => array(
                'allowClear' => true,
                'minimumResultsForSearch' => -1,
                'width' => '10%',
            ),
            'htmlOptions' => array(
                'style' => 'display:inline-block;min-width:150px;margin-right:3px',
            )
        )
    ));
    ?>
    
    <?php
    $this->widget('yiiwheels.widgets.daterangepicker.WhDateRangePicker', array(
        'id' => 'txtDateRange',
        'model' => $model,
        'attribute' => 'date_range',
        'htmlOptions' => array(
            'placeholder' => 'Date range',
            'style'=>'min-width:190px;'
        ),
        'pluginOptions' => array(
            'format' => 'DD/MM/YYYY',
            'ranges' => array(
                'Today' => 'js:[moment(), moment()]',
                'Yesterday' => 'js:[moment().subtract(1, "days"), moment().subtract(1, "days")]',
                'Last 7 Days' => 'js:[moment().subtract(6, "days"), moment()]',
                'Last 30 Days' => 'js:[moment().subtract(29, "days"), moment()]',
                'This Month' => 'js:[moment().startOf("month"), moment().endOf("month")]',
                'Last Month' => 'js:[moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]'
            )
        )
    ));
    ?>
    <?php echo $form->textFieldControlGroup($model, 'title', array('placeholder' => 'Name', 'span' => 5, 'maxlength' => 200)); ?>


    <?php //echo $form->textFieldControlGroup($model, 'task_id', array('span' => 5)); ?>

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
                'width' => '15%',
            ),
            'htmlOptions' => array(
                'class' => 'form-control',
                'style' => 'display:inline-block;min-width:250px'
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
                'width' => '15%',
            ),
            'htmlOptions' => array(
                'class' => 'form-control',
                'style' => 'display:inline-block;min-width:250px'
            )
        )
    ));
    ?> 

    <?php
    $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
        'model' => $model,
        'keyName' => 'doc_type',
        'placeHolder' => 'Document Type',
        'data' => CMap::mergeArray(array(''=>''), Document::itemAlias('Type')),
        'extra' => array(
            'pluginOptions' => array(
                'allowClear' => true,
                'minimumResultsForSearch' => -1,
//                'placeholderOption' => 'first',
                'width' => '10%',
            ),
            'htmlOptions' => array(
                'style' => 'display:inline-block;min-width:150px',
            )
        )
    ));
    ?>

    <?php echo $form->textFieldControlGroup($model, 'doc_code', array('placeholder' => 'Document Code', 'span' => 5, 'maxlength' => 30)); ?>


    <div class="form-group">
        <?php
        echo TbHtml::formActions(array(
            TbHtml::submitButton('Search', array('color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'size' => TbHtml::BUTTON_SIZE_SMALL)
            )
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->