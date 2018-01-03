<?php
/* @var $this SupplierController */
/* @var $model Supplier */
?>

<?php
$this->breadcrumbs = array(
    'Contacts' => array('admin'),
    'Create',
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
?>

<?php if (Yii::app()->user->hasFlash('successMessage')) { ?>
    <div class="alert alert-success panel-container">
        <span class="glyphicon glyphicon-ok"></span> <strong><?php echo Yii::app()->user->getFlash('successMessage'); ?></strong>
    </div>
<?php } ?>

<?php if (Yii::app()->user->hasFlash('errorMessage')) { ?>
    <div class="alert alert-danger panel-container">
        <?php echo Yii::app()->user->getFlash('errorMessage'); ?>
    </div>
<?php } ?>
<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Create Contact</legend>
            <div class="col-md-6">
            <?php $this->renderPartial('_form', array('model' => $model)); ?> </div>       </fieldset>
    </div>
</div>
