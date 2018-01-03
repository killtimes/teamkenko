<?php
$this->breadcrumbs = array(
    UserModule::t('Users') => array('admin'),
    UserModule::t('Create'),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.user_operationbar', array('controller' => $this));
?>
<?php $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._flash'); ?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend><?php echo UserModule::t("Create User"); ?></legend>

            <?php
            echo $this->renderPartial('_form', array('model' => $model, 'profile' => $profile));
            ?>
        </fieldset>
    </div>
</div>
