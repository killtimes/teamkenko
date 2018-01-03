<?php
/* @var $this ShopController */
/* @var $model Shop */


$this->breadcrumbs = array(
    'Shops' => array('admin'),
    'Manage',
);


echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Shop',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Shop_Create', array())
    ),
    array(
        'label' => 'List Shops',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Shop_List', array())
    ),
));

Yii::app()->clientScript->registerScript('search', "
ShopPage.initList();
", CClientScript::POS_END);
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>List Shops</legend>

<?php
$this->widget('\TbGridView', array(
    'id' => 'shop-grid',
    'dataProvider' => $model->search(),
    //'filter'=>$model,
    'type' => array(
        TbHtml::GRID_TYPE_HOVER,
        TbHtml::GRID_TYPE_CONDENSED,
    ),
    'columns' => array(
        'id',
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->name),array("update","id"=>$data->id))'
        ),
        'employees',
        'address',
        'phone',
        'fax',
        array(
            'value' => array($this, 'renderActionButton')
        )
    ),
));
?>
        </fieldset>
    </div>
</div>

