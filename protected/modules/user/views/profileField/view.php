<?php
$this->breadcrumbs=array(
	UserModule::t('Profile Fields')=>array('/user/profileField/admin'),
	UserModule::t($model->title),
);  

echo $this->renderPartial('webroot.themes.theme1.views.includes.user_operationbar', array('controller'=>$this));


?>
<h1><?php echo UserModule::t('View Profile Field #').$model->varname; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'varname',
		'title',
		'field_type',
		'field_size',
		'field_size_min',
		'required',
		'match',
		'range',
		'error_message',
		'other_validator',
		'widget',
		'widgetparams',
		'default',
		'position',
		'visible',
	),
)); ?>
