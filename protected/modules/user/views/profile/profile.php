<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Profile");
$this->breadcrumbs = array(
    UserModule::t("Profile"),
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

<?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
    <div class="alert alert-success panel-container">
        <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
    </div>
<?php endif; ?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend><?php echo UserModule::t('Your profile'); ?></legend>
            <table class="detail-view table table-striped table-condensed">
                <tbody>

                    <tr>
                        <th><?php echo CHtml::encode($model->getAttributeLabel('username')); ?></th>
                        <td><?php echo CHtml::encode($model->username); ?></td>
                    </tr>

                    <tr>
                        <th><?php echo CHtml::encode($profile->getAttributeLabel('firstname')); ?></th>
                        <td><?php echo CHtml::encode($profile->firstname); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo CHtml::encode($profile->getAttributeLabel('lastname')); ?></th>
                        <td><?php echo CHtml::encode($profile->lastname); ?></td>
                    </tr>

                    <?php if (!empty($profile->department)) { ?>
                        <tr>
                            <th><?php echo CHtml::encode($profile->getAttributeLabel('department')); ?></th>
                            <td><?php echo CHtml::encode(Profile::itemAlias('Department', $profile->department)); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($profile->shop_id)) { ?>
                        <tr>
                            <th><?php echo CHtml::encode($profile->getAttributeLabel('shop')); ?></th>
                            <td><?php echo CHtml::encode(Shop::model()->getById($profile->shop_id)->name); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th><?php echo CHtml::encode($model->getAttributeLabel('email')); ?></th>
                        <td><?php echo CHtml::encode($model->email); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo CHtml::encode($profile->getAttributeLabel('address')); ?></th>
                        <td><?php echo CHtml::encode($profile->address); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo CHtml::encode($profile->getAttributeLabel('mobile_phone')); ?></th>
                        <td><?php echo CHtml::encode($profile->mobile_phone); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo CHtml::encode($model->getAttributeLabel('create_at')); ?></th>
                        <td><?php echo Yii::app()->format->timeAgo($model->create_at); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo CHtml::encode($model->getAttributeLabel('last_activity')); ?></th>
                        <td><?php echo Yii::app()->format->timeAgo($model->last_activity); ?></td>
                    </tr>                   
                </tbody>

            </table>
        </fieldset>
    </div>
</div>



