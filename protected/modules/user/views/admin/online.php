
<?php
$this->breadcrumbs = array(
    UserModule::t('Users') => array('/user/admin'),
    UserModule::t('List Users'),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.user_operationbar', array('controller' => $this));
Yii::app()->clientScript->registerScript('tt', "
    $(document).tooltip({selector:'abbr',trigger:'hover'});

    ");
?>
<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <legend><?php echo UserModule::t("Online Users"); ?></legend>

            
           

            <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id' => 'user-grid',
                'dataProvider' => $dataProvider,
//    'filter' => $model,//disable filter column
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    array(
                        'name' => 'id',
                        'type' => 'raw',
                        'value' => 'CHtml::link(CHtml::encode($data->id),array("admin/update","id"=>$data->id))',
                    ),
                    array(
                        'header'=>'Online',
                        'type'=>'raw',
                        'value'=>'(Yii::app()->session->isUserOnline($data->id))?TbHtml::badge("&nbsp;", array("class"=>"online badge-success")):TbHtml::badge("&nbsp;",array("class"=>"online"))',
                        'htmlOptions'=>array('style'=>'text-align:center'),
                        'headerHtmlOptions'=>array('style'=>'text-align:center')
                    ),
                    array(
                        'name' => 'username',
                        'type' => 'raw',
                        'value' => 'CHtml::link(UHtml::markSearch($data,"username"),array("admin/update","id"=>$data->id))',
                    ),
                    array(
                        'name' => 'email',
                        'type' => 'raw',
                        'value' => 'CHtml::link(UHtml::markSearch($data,"email"), "mailto:".$data->email)',
                    ),
                    array(
                        'name'=>'department',
                        'value'=>'Profile::itemAlias("Department", Profile::model()->getById($data->id)->department)'
                    ),
                    'mobile_phone',
//                    'create_at',
                    array(
                        'name'=>'last_activity',
                        'type'=>'raw',
                        'value'=>'($data->last_activity)?TbHtml::abbr(Yii::app()->format->timeAgo($data->last_activity), Yii::app()->localTime->fromUTC($data->last_activity)):""'
                    ),
//                    array(
//                        'header'=>'Shop',
//                        'type'=>'raw',
//                        'value'=>'($data->shop !== null)?$data->shop->name:""'
//                    ),
//                    array(
//                        'name' => 'status',
//                        'type' => 'raw',
//                        'value' => '($data->status == 1)?TbHtml::labelTb(User::itemAlias("UserStatus",$data->status), array("color" => TbHtml::LABEL_COLOR_SUCCESS)):TbHtml::labelTb(User::itemAlias("UserStatus",$data->status))',
//                        'filter' => User::itemAlias("UserStatus"),
//                    ),
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