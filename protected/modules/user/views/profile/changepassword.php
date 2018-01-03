<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Change Password");
$this->breadcrumbs = array(
    UserModule::t("Profile") => array('/user/profile'),
    UserModule::t("Change Password"),
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => UserModule::t('Change password'),
        'url' => array('/user/profile/changepassword'),
        'icon' => TbHtml::ICON_LOCK,
        'color' => ( ($this->id == 'profile' && $this->action->id == 'changepassword') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => UserModule::t('Edit profile'),
        'url' => array('/user/profile/edit'),
        'icon' => TbHtml::ICON_USER,
        'color' => ( ($this->id == 'profile' && $this->action->id == 'edit') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    )
));
?>
<div class="panel panel-default panel-container">
    <div class="panel-body">
        <legend><?php echo UserModule::t('Change password'); ?></legend>
        <div class="col-sm-12">
            
            

            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'changepassword-form',
                'enableAjaxValidation' => true,
//                'clientOptions' => array(
//                    'validateOnSubmit' => true
//                ),
                'labelWidthClass' => 'col-sm-3',
                'controlWidthClass' => 'col-sm-6',
                'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            ));
            
            echo $form->errorSummary(array($model));
            
            echo $form->passwordFieldControlGroup($model, 'oldPassword');
            echo $form->passwordFieldControlGroup($model, 'password', array('help' => 'Minimal password length 4 symbols.'));
            echo $form->passwordFieldControlGroup($model, 'verifyPassword');

            ?>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <?php
                    
                    echo TbHtml::formActions(array(
                        TbHtml::submitButton(UserModule::t('Save'), array('color' => TbHtml::BUTTON_COLOR_PRIMARY))
                    ));
                    ?>
                </div> 
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>




