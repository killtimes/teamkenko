<?php
$this->breadcrumbs = array(
    UserModule::t('Profile Fields') => array('/user/profileField/admin'),
    UserModule::t('Create'),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.user_operationbar', array('controller' => $this));
?>


<div class="panel panel-default panel-container">
    <div class="panel-body">

        <fieldset>
            <legend><?php echo UserModule::t("Create Profile Field"); ?></legend>


            <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
        </fieldset>
    </div>
</div>
