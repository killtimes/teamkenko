<?php
$this->widget('\TbGridView', array(
    'id' => 'documents-grid',
    'dataProvider' => $dataProvider,
    'enableSorting'=>false,
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
            'header' => 'Upload by',
            'type' => 'raw',
            'value' => 'Profile::model()->getById($data["upload_by"])->getFullName()'
        ),
//        array(
//            'value' => array($this, 'renderActionButton'),
//        ),
    ),
));
?>