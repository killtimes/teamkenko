<?php
$this->breadcrumbs=array(
	'Users',
);

$this->menu=array(
array('label'=>'Create User','url'=>array('create')),
array('label'=>'Manage User','url'=>array('admin')),
);
?>

<h1>Users</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'user.views.user._view',
)); ?>
