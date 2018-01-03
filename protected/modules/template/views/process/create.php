<?php
/* @var $this ProcessController */
/* @var $model ProcessTemplate */
?>

<?php
$this->breadcrumbs = array(
    'Job Templates' => array('admin'),
    'Create',
);

echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Job Template',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('ProcessTemplate_Create', array())
    ),
    array(
        'label' => 'List Job Templates',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('ProcessTemplate_List', array())
    ),
));

echo $this->renderPartial('webroot.themes.theme1.views.includes._flash');
?>



<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>Create Job Template</legend>
            
             <div class="col-md-6">
            <?php $this->renderPartial('_form', array('model' => $model)); ?> 
             </div>
        </fieldset>
    </div>
</div>
