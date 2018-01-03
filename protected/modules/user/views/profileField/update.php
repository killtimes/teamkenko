<?php
$this->breadcrumbs = array(
    UserModule::t('Profile Fields') => array('/user/profileField'),
    $model->title => array('/user/profileField/view', 'id' => $model->id),
    UserModule::t('Update'),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.user_operationbar', array('controller' => $this));
?>
<div class="panel panel-default panel-container">
    <div class="panel-body">

        <fieldset>
            <legend><?php echo UserModule::t('Update Profile Field ') . $model->id; ?></legend>
            <?php echo $this->renderPartial('_form', array('model' => $model)); ?>

        </fieldset>
    </div>
</div>

