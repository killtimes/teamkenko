
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

<?php echo $form->errorSummary(array($model, $profile)); ?>

<div class="col-sm-6"> 
    <?php echo $form->textFieldControlGroup($model, 'username', array('size' => 20, 'maxlength' => 20)); ?>
    <?php echo $form->passwordFieldControlGroup($model, 'password', array('size' => 60, 'maxlength' => 128)); ?>
    <?php echo $form->emailFieldControlGroup($model, 'email', array('size' => 60, 'maxlength' => 128)); ?>
    <?php echo $form->dropdownListControlGroup($model, 'tz', User::itemAlias('TimeZone')); ?>
    <?php echo $form->dropdownListControlGroup($model, 'status', User::itemAlias('UserStatus')); ?>
</div>
<div class="col-sm-6"> 

    <?php echo $form->textFieldControlGroup($profile, 'firstname', array('size' => 20, 'maxlength' => 50)); ?>
    <?php echo $form->textFieldControlGroup($profile, 'lastname', array('size' => 20, 'maxlength' => 50)); ?>
    <div class="<?php echo ($profile->hasErrors('arr_department')) ? 'has-error' : '' ?> form-group">        
        <label class="col-sm-3 control-label required">Department <span class="required">*</span></label>
        <div class="col-sm-7"> 
            <?php
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $profile,
                'keyName' => 'arr_department',
                'placeHolder' => 'Select Department',
                'data' => Profile::itemAlias('Department'),
                'extra' => array(
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                        'width' => '100%',
                    ),
                    'htmlOptions' => array(
                        'multiple' => true
                    )
                )
            ));
            ?>
            <?php echo $form->error($profile, 'arr_department'); ?>

        </div>
    </div>
    <div class="<?php echo ($profile->hasErrors('shop_id')) ? 'has-error' : '' ?> form-group">        
        <label class="col-sm-3 control-label">Shop</label>
        <div class="col-sm-7"> 
            <?php
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $profile,
                'keyName' => 'arr_shop_id',
                'placeHolder' => 'Select Shop',
                'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Shop::model()->findAll(), 'id', 'name')),
                'extra' => array(
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                        'width' => '100%',
                    ),
                    'htmlOptions' => array(
                        'multiple' => true
                    )
                )
            ));
            ?>
            <?php echo $form->error($profile, 'arr_shop_id'); ?>

        </div>
    </div>

    <?php echo $form->textFieldControlGroup($profile, 'address', array('size' => 20, 'maxlength' => 255)); ?>
    <?php echo $form->textFieldControlGroup($profile, 'mobile_phone', array('size' => 20, 'maxlength' => 30)); ?>
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

