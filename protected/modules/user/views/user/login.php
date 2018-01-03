<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Login");
$this->breadcrumbs = array(
    UserModule::t("Login"),
);
?>

<div class="login-form">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-12">

                <h1><?php echo UserModule::t("Login"); ?></h1>
                <hr>
                <?php if (Yii::app()->user->hasFlash('loginMessage')): ?>

                    <div class="success">
                        <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
                    </div>

                <?php endif; ?>

                <?php
                $form = $this->beginWidget('bootstrap.widgets.TbActiveForm');
                ?>

                <?php echo $form->textFieldControlGroup($model, 'username'); ?>

                <?php echo $form->passwordFieldControlGroup($model, 'password'); ?>

                <?php echo $form->checkBoxControlGroup($model, 'rememberMe'); ?>

                <div class="form-group">
                    <?php
                    echo TbHtml::formActions(array(
                        TbHtml::submitButton(UserModule::t('Login'), array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'icon' => TbHtml::ICON_LOCK, 'block' => true)),
                    ));
                    ?>
                </div>
                <hr/>
                <div class="form-group">
                    <div class="btn-group btn-block">
                        <a class="btn btn-default btn-block" href="<?php print_r(Yii::app()->getModule('user')->recoveryUrl[0]); ?>">Forgot password</a>
                    </div>
                </div>

                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

