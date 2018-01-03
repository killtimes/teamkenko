
<?php if ($model->scenario === 'update'): ?>

    <h3><?php echo Rights::getAuthItemTypeName($model->type); ?></h3>

<?php endif; ?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'labelWidthClass' => 'col-sm-3',
    'controlWidthClass' => 'col-sm-7'
        ));
?>
<div class="col-sm-12"> 
    <?php echo $form->textFieldControlGroup($model, 'name', array('maxlength' => 255, 'help' => Rights::t('core', 'Do not change the name unless you know what you are doing.'))); ?>
    <?php echo $form->textFieldControlGroup($model, 'description', array('maxlength' => 255, 'help' => Rights::t('core', 'A descriptive name for this item.'))); ?>
    <?php if (Rights::module()->enableBizRule === true): ?>
        <?php echo $form->textFieldControlGroup($model, 'bizRule', array('maxlength' => 255, 'help' => Rights::t('core', 'Code that will be executed when performing access checking.'))); ?>
    <?php endif; ?>
    <?php if (Rights::module()->enableBizRule === true && Rights::module()->enableBizRuleData): ?>
        <?php echo $form->textFieldControlGroup($model, 'data', array('maxlength' => 255, 'help' => Rights::t('core', 'Additional data available when executing the business rule.'))); ?>
    <?php endif; ?>
</div>
<div class="row">
    <div class="col-sm-12 text-center">
        <?php
        echo TbHtml::formActions(array(
            TbHtml::submitButton(Rights::t('core', 'Save'), array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'icon' => TbHtml::ICON_OK)),
            '&nbsp;&nbsp;',
            TbHtml::buttonGroup(array(
                array('label' => Rights::t('core', 'Cancel'), 'url' => Yii::app()->user->rightsReturnUrl),
            ))
        ));
        ?>
    </div> 
</div>

<?php $this->endWidget(); ?>
