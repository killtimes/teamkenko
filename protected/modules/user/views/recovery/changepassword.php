<?php $this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Change Password");
$this->breadcrumbs = array(
    UserModule::t("Login") => array('/user/login'),
    UserModule::t("Change Password"),
);
?>
<div class="login-form">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-12">
                <h1><?php echo UserModule::t("Change Password"); ?></h1>


                <div class="form">
                    <?php echo TbHtml::beginForm(); ?>

                    <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
                    <?php echo TbHtml::errorSummary($form); ?>

                    <div class="">
                        <?php echo TbHtml::activeLabelEx($form, 'password'); ?>
                        <?php echo TbHtml::activePasswordField($form, 'password'); ?>
                        <p class="hint">
                            <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
                        </p>
                    </div>

                    <div class="">
                        <?php echo TbHtml::activeLabelEx($form, 'verifyPassword'); ?>
                        <?php echo TbHtml::activePasswordField($form, 'verifyPassword'); ?>
                    </div>


                    <div class=" submit panel-container text-center">
                        <?php echo TbHtml::submitButton(UserModule::t("Save")); ?>
                    </div>

                    <?php echo TbHtml::endForm(); ?>
                </div>
                <!-- form -->
            </div>
        </div>
    </div>
</div>
