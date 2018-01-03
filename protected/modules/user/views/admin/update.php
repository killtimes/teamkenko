<?php
$this->breadcrumbs = array(
    (UserModule::t('Users')) => array('/user/admin'),
    $model->username => array('/user/admin/update', 'id' => $model->id),
    (UserModule::t('Update')),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.user_operationbar', array('controller' => $this));
?>

<?php $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._flash'); ?>

<div class="panel panel-default panel-container">
    <div class="panel-body">

        <fieldset>
            
            <legend><?php echo UserModule::t('User') . " #" . $model->id; ?></legend>

            <?php
            echo $this->renderPartial('_form', array('model' => $model, 'profile' => $profile));
            ?>
        </fieldset>
    </div>
</div>
