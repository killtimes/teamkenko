<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Restore");
$this->breadcrumbs = array(
    UserModule::t("Login") => array('/user/login'),
    UserModule::t("Restore"),
);
?>
<div class="login-form">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-12">
                <?php if (Yii::app()->user->hasFlash('recoveryMessage')): ?>
                    <h1><p class="text-success">Success</p></h1>
                    <div class="alert alert-success" role="alert">
                        <?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <div class="btn-group btn-block">
                            <a class="btn btn-default btn-block" href="<?php echo Yii::app()->createUrl('/user/login'); ?>">Login</a>
                        </div>
                    </div>
                <?php else: ?>

                    <h1><?php echo UserModule::t("Forgot password"); ?></h1>
                    <hr>
                    <div class="form-group">
                        <p class="text-info"><span class="label label-info">Info</span> <?php echo UserModule::t("Please enter your username or email addres."); ?></p>
                    </div>

                    <?php echo TbHtml::beginForm(); ?>

                    <?php echo TbHtml::errorSummary($form); ?>

                    <div class="form-group">
                        <?php echo TbHtml::activeLabel($form, 'login_or_email'); ?>
                        <?php echo TbHtml::activeTextField($form, 'login_or_email') ?>
                    </div>

                    <div class="form-group">
                        <?php
                        echo TbHtml::submitButton(UserModule::t("Recovery"), array(
                            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                            'block' => true,
                            'icon' => TbHtml::ICON_SEARCH
                        ));
                        ?>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <div class="btn-group btn-block">
                            <a class="btn btn-default btn-block" href="<?php echo Yii::app()->createUrl('/user/login'); ?>">Login</a>
                        </div>
                    </div>

                    <?php echo TbHtml::endForm(); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>