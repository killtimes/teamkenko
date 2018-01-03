<?php Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/lightbox2/css/lightbox.css', 'screen', 99); ?>
<?php Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/lightbox2/js/lightbox.js', CClientScript::POS_END); ?>
<?php
/* @var $this DocumentController */
/* @var $model Document */


$this->breadcrumbs = array(
    'Documents' => array('list'),
    'Manage',
);


echo TbHtml::buttonGroup(array(
    array(
        'label' => 'Create Document',
        'url' => array('create'),
        'icon' => TbHtml::ICON_PLUS,
        'visible' => false
    ),
    array(
        'label' => 'List Documents',
        'url' => array('list'),
        'icon' => TbHtml::ICON_LIST,
    ),
));

Yii::app()->clientScript->registerScript('search', "
lightbox.option({
      'resizeDuration': 200,
      'fadeDuration':200,
      'wrapAround': true
    });
$('.search-form form').submit(function(){
    $('#document-grid').yiiGridView('update', {
            data: $(this).serialize()
    });
    return false;
});
TaskPage.taskActivities();
$(document).tooltip({
    selector:'abbr',
    trigger:'hover'
});
", CClientScript::POS_READY);
?>

<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend>List Documents</legend>

            <?php
//            echo TbHtml::button('Advanced Search', array('size' => TbHtml::BUTTON_SIZE_SMALL,
//                'color' => TbHtml::BUTTON_COLOR_DEFAULT,
//                'icon' => TbHtml::ICON_SEARCH,
//                'class' => 'search-button'));
            ?>
            <div class="search-form">
                <?php
                $this->renderPartial('_search', array(
                    'model' => $formFilter,
                ));
                ?>
            </div><!-- search-form -->

            <?php
            $this->widget('\TbGridView', array(
                'id' => 'document-grid',
                'dataProvider' => $dataProvider,
//                'filter' => $model,
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    'id',
                    array(
                        'name' => 'title',
                        'header' => 'Name',
                        'type' => 'raw',
                        'value' => array($this, 'renderDocName')
                    ),
                    'doc_code',
                    'doc_date',
                    array(
                        'name' => 'task_id',
                        'type' => 'raw',
                        'value' => 'CHtml::link("#".$data->task_id,"javascript:;",array("class"=>"view-activity", "data-token"=> Yii::app()->request->csrfToken,"data-url"=>Yii::app()->createUrl("/process/admin/activities", array("id"=>$data->task_id))))' 
                    ),
                    array(
                        'name' => 'shop_id',
                        'value' => '($data->shop_id>0)?Shop::model()->getById($data->shop_id)->name:""'
                    ),
                    array(
                        'name' => 'supplier_id',
                        'value' => '($data->supplier_id>0)?Supplier::model()->getById($data->supplier_id)->name:""'
                    ),
                    array(
                        'header' => 'Type',
                        'name' => 'doc_type',
                        'value' => 'Document::itemAlias("Type", $data->doc_type)'
                    ),
                    array(
                        'name'=>'create_date',
                        'type'=>'raw',
                        'value'=> 'TbHtml::abbr( Yii::app()->format->timeAgo($data->create_date), Yii::app()->localTime->fromUTC($data->create_date))'
                    ),
                    array(
                        'value' => array($this, 'renderActionButton'),
                    ),
                /*
                  'doc_date',
                  'title',
                  'file_name',
                  'file_type',
                  'source_type',
                  'create_date',
                  'update_date',
                 */
//                    array(
//                        'class' => 'bootstrap.widgets.TbButtonColumn',
//                        'template' => '{update}{delete}'
//                    ),
                ),
            ));
            ?>
        </fieldset>
    </div>
</div>

<?php $this->renderPartial('process.views.task._task_activity'); ?>

