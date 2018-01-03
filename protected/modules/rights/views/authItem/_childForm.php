<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'enableAjaxValidation' => false,
//        'htmlOptions' => array('enctype' => 'multipart/form-data'),
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-sm-3',
        'controlWidthClass' => 'col-sm-7'
    ));
    ?>
    <div class="row">
        <?php echo $form->dropdownListControlGroup($model, 'itemname', $itemnameSelectOptions); ?>
    </div>

    <div class="col-sm-12 text-center">
        <?php
        echo TbHtml::formActions(array(
            TbHtml::submitButton(Rights::t('core', 'Add'), array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'icon' => TbHtml::ICON_OK)),
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div>