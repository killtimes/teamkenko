<?php
/* @var $this SupplierController */
/* @var $model Supplier */


$this->breadcrumbs = array(
    'Contacts' => array('admin'),
    'List',
);


echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Contact',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Supplier_Create', array())
    ),
    array(
        'label' => 'List Contacts',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Supplier_List', array())
    ),
));

Yii::app()->clientScript->registerScript('search', "
SupplierPage.initList();
", CClientScript::POS_END);
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>List Contacts</legend>
            <?php
            $this->widget('\TbGridView', array(
                'id' => 'supplier-grid',
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
                    'industry',
                    array(
                        'value' => array($this, 'renderActionButton')
                    ),
                ),
            ));
            ?>
        </fieldset>
    </div>
</div>

