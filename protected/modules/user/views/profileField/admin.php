<?php
$this->breadcrumbs = array(
    UserModule::t('Profile Fields') => array('/user/profileField/admin'),
    UserModule::t('Manage'),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.user_operationbar', array('controller' => $this));


//Yii::app()->clientScript->registerScript('search', "
//$('.search-button').click(function(){
//    $('.search-form').toggle();
//    return false;
//});
//$('.search-form form').submit(function(){
//    $.fn.yiiGridView.update('profile-field-grid', {
//        data: $(this).serialize()
//    });
//    return false;
//});
//");
?>


<?php // echo CHtml::link(UserModule::t('Advanced Search'), '#', array('class' => 'search-button')); ?>
<!--<div class="search-form" style="display:none">
<?php
//    $this->renderPartial('_search', array(
//        'model' => $model,
//    ));
?>
</div> search-form -->
<div class="panel panel-default panel-container">
    <div class="panel-body">

        <fieldset>
            <legend><?php echo UserModule::t('Manage Profile Fields'); ?></legend>



            <p><?php echo UserModule::t("You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done."); ?></p>

            <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'dataProvider' => $model->search(),
                'filter' => $model,
                'type' => array(
                    TbHtml::GRID_TYPE_HOVER,
                    TbHtml::GRID_TYPE_CONDENSED,
                ),
                'columns' => array(
                    'id',
                    array(
                        'name' => 'varname',
                        'type' => 'raw',
                        'value' => 'UHtml::markSearch($data,"varname")',
                    ),
                    array(
                        'name' => 'title',
                        'value' => 'UserModule::t($data->title)',
                    ),
                    array(
                        'name' => 'field_type',
                        'value' => '$data->field_type',
                        'filter' => ProfileField::itemAlias("field_type"),
                    ),
                    'field_size',
                    //'field_size_min',
                    array(
                        'name' => 'required',
                        'value' => 'ProfileField::itemAlias("required",$data->required)',
                        'filter' => ProfileField::itemAlias("required"),
                    ),
                    //'match',
                    //'range',
                    //'error_message',
                    //'other_validator',
                    //'default',
                    'position',
                    array(
                        'name' => 'visible',
                        'value' => 'ProfileField::itemAlias("visible",$data->visible)',
                        'filter' => ProfileField::itemAlias("visible"),
                    ),
                    //*/
                    array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update}{delete}'
                    ),
                )
            ));
            ?>
        </fieldset>
    </div>
</div>
