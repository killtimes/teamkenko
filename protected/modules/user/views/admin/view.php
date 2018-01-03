<?php
$this->breadcrumbs = array(
    UserModule::t('Users') => array('/user/admin'),
    $model->username,
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.user_operationbar', array('controller' => $this));
?>
<div class="panel panel-default panel-container">
    <div class="panel-body">

        <fieldset>
            <legend><?php echo UserModule::t('View User') . ' "' . $model->username . '"'; ?></legend>

            <?php
            $attributes = array(
                'id',
                'username',
            );

            $profileFields = ProfileField::model()->forOwner()->sort()->findAll();
            if ($profileFields) {
                foreach ($profileFields as $field) {
                    array_push($attributes, array(
                        'label' => UserModule::t($field->title),
                        'name' => $field->varname,
                        'type' => 'raw',
                        'value' => (($field->widgetView($model->profile)) ? $field->widgetView($model->profile) : (($field->range) ? Profile::range($field->range, $model->profile->getAttribute($field->varname)) : $model->profile->getAttribute($field->varname))),
                    ));
                }
            }

            array_push($attributes, 'password', 'email', 'activkey', 'create_at', 'lastvisit_at', array(
                'name' => 'superuser',
                'value' => User::itemAlias("AdminStatus", $model->superuser),
                    ), array(
                'name' => 'status',
                'value' => User::itemAlias("UserStatus", $model->status),
                    )
            );

            $this->widget('bootstrap.widgets.TbDetailView', array(
                'data' => $model,
                'attributes' => $attributes,
            ));
            ?>
        </fieldset>
    </div>
</div>

