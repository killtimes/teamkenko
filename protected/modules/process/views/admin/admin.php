<?php
/* @var $this AdminController */
/* @var $model Process */
Yii::app()->clientScript->registerScript('tooltip', '
    //$("small.in-progress").tooltip({container:"#tt-inprogress"});
    //$("small.wait-accept").tooltip({container:"#tt-wait"});
    
    $("#view-container").tooltip({
        selector:"small.in-progress",
        container:"#tt-inprogress"
    });
    
    $(".container-fluid small.wait-accept").tooltip({
        container:"#tt-wait",
        trigger:"manual"
    }).tooltip("show");
    
    $("html").tooltip({
        selector:"small.completed",
        container:"#tt-complete"
    });
    
    reInitTooltip();
    

    ', ClientScript::POS_READY);


$this->breadcrumbs = array(
    'Jobs' => array('admin'),
    'Manage',
);


echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Job',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Process_Create', array())
    ),
    array(
        'label' => "Create Job by template",
        'url' => array('clone'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'htmlOptions' => array('class' => 'clone-process'),
        'visible' => $this->checkAccess('Process_Create', array()) && $this->action->id != 'archived'
    ),
    array(
        'label' => 'List Jobs',
        'url' => array('admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
    ),
    array(
        'label' => 'Archived Jobs',
        'url' => array('admin/archived'),
        'icon' => TbHtml::ICON_FLOPPY_SAVED,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Process_List', array())
    ),
    array(
        'label' => 'Deleted Jobs',
        'url' => array('admin/deleted'),
        'icon' => TbHtml::ICON_BAN_CIRCLE,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT,
        'visible' => $this->checkAccess('Process_List', array())
    ),
));

Yii::app()->clientScript->registerScript('search', "
        ProcessPage.initialize();
/*$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});*/
$('.search-form form').submit(function(){
	$('#process-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

Yii::app()->clientScript->registerScript('reinittooltip', 'function reInitTooltip(id, data){
    $("#tt-wait").html("");
    $(".container-fluid small.wait-accept").tooltip({
        container:"#tt-wait",
        trigger:"manual"
    }).tooltip("show");
    
    $("abbr").tooltip();
}');

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('yiiactiveform');
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>List Jobs

                <?php echo ($this->type == 'deleted') ? '(Deleted)' : '' ?>
                <?php echo ($this->type == 'archived') ? '(Completed)' : '' ?>

            </legend>

            <?php
            //            echo TbHtml::button('Advanced Search', array('size' => TbHtml::BUTTON_SIZE_SMALL,
            //                'color' => TbHtml::BUTTON_COLOR_DEFAULT,
            //                'icon' => TbHtml::ICON_SEARCH,
            //                'class' => 'search-button'));
            ?>
            <div class="search-form">
                <?php
                $this->renderPartial('_search', array(
                    'model' => $model,
                ));
                ?>
            </div><!-- search-form -->

            <?php
            $this->widget('\TbGridView', array(
                'htmlOptions' => array('class' => 'grid-view panel-container'),
                'id' => 'process-grid',
                'dataProvider' => $dataProvider,
                'afterAjaxUpdate' => 'reInitTooltip',
//                'filter' => $model,
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    'id',
                    array(
                        'name' => 'name',
                        'type' => 'raw',
                        'value' => array($this, 'renderName'),
//                        'htmlOptions'=>array('style'=>' vertical-align: middle;')
                    ),
                    array(
                        'name' => 'shop',
                        'header' => 'Shop',
                        'value' => 'Shop::model()->getById($data->shop_id)->name'
                    ),
                    array(
                        'name' => 'supplier',
                        'header' => 'Contact',
                        'value' => 'Supplier::model()->getById($data->supplier_id)->name'
                    ),
                    array(
                        'name' => 'progress',
                        'type' => 'raw',
                        'value' => array($this, 'renderProgress'),
                        'htmlOptions' => array('style' => 'width:20%'),
                        'header' => 'Progress'
                    ),
                    array(
                        'name' => 'stage',
                        'type' => 'raw',
                        'value' => 'Process::stageAlias($data->stage)',
                    ),
                    array(
                        'type' => 'raw',
                        'value' => array($this, 'renderDate')
                    ),
                    array(
                        'type' => 'raw',
                        'value' => array($this, 'renderActionButton')
                    )
                ),
            ));
            ?>
        </fieldset>
    </div>
</div>

<?php
$this->beginWidget('\TbModal', array(
    'id' => 'mdCloneProcess',
    'header' => 'Create Job by template',
    'footer' => array(
        TbHtml::button('Save', array('id' => 'btnSave', 'data-loading-text' => 'Please wait...', 'color' => TbHtml::BUTTON_COLOR_PRIMARY, 'onclick' => 'js:ProcessPage.saveProcess(this)')),
        TbHtml::button('Close', array('data-dismiss' => 'modal')),
    ),
));
?>
<div class="progress">
    <div class="progress-bar progress-bar-striped active" role="progressbar"
         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">

    </div>
</div>
<?php $this->endWidget(); ?>

<div id="tt-inprogress">
</div>
<div id="tt-wait">
</div>
<div id="tt-complete">
</div>
