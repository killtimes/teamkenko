<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Profile");
$this->breadcrumbs = array(
    UserModule::t("Profile") => array('/user/profile'),
    UserModule::t("Edit"),
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => UserModule::t('Change password'),
        'url' => array('/user/profile/changepassword'),
        'icon' => TbHtml::ICON_LOCK,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT
    ),
    array(
        'label' => UserModule::t('Edit profile'),
        'url' => array('/user/profile/edit'),
        'icon' => TbHtml::ICON_USER,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT
    )
));
?>

<?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
    <div class="alert alert-success panel-container">
        <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
    </div>
<?php endif; ?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <legend><?php echo UserModule::t('Your profile'); ?></legend>

        <div class="col-sm-12">
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'profile-form',
                'enableAjaxValidation' => true,
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
                'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                'labelWidthClass' => 'col-sm-3',
                'controlWidthClass' => 'col-sm-6',
            ));
            ?>


            <?php echo $form->errorSummary(array($model, $profile)); ?>

            <?php
            echo $form->textFieldControlGroup($model, 'username', array('size' => 20, 'maxlength' => 20));
            echo $form->emailFieldControlGroup($model, 'email', array('size' => 60, 'maxlength' => 128));
            echo $form->textFieldControlGroup($profile, 'firstname', array('size' => 60, 'maxlength' => 50));
            echo $form->textFieldControlGroup($profile, 'lastname', array('size' => 60, 'maxlength' => 50));
            echo $form->textFieldControlGroup($profile, 'address', array('size' => 60, 'maxlength' => 255));
            echo $form->textFieldControlGroup($profile, 'mobile_phone', array('size' => 60, 'maxlength' => 30));
            echo $form->dropdownListControlGroup($model, 'tz', User::itemAlias('TimeZone'));
            ?>

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

        </div><!-- form -->
    </div>
</div>




