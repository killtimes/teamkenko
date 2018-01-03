<?php
/* @var $this ProcessController */
/* @var $model ProcessTemplate */
/* @var $form TbActiveForm */
?>

<div class="form-group">

    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'process-template-form',
        'enableAjaxValidation' => true,
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-sm-3',
        'controlWidthClass' => 'col-sm-8'
    ));
    ?>


    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 5, 'maxlength' => 150)); ?>

    <?php echo $form->textAreaControlGroup($model, 'description', array('rows' => 3, 'span' => 5, 'maxlength' => 255)); ?>

    <div class="<?php echo ($model->hasErrors('shop_id')) ? 'has-error' : '' ?> form-group">
        <label class="col-sm-3 control-label required">Shop <span class="required">*</span></label>

        <div class="col-sm-7">
            <?php
            $shopId = Profile::model()->getShopId(Yii::app()->user->id);
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $model,
                'keyName' => 'shop_id',
                'placeHolder' => 'Select Shop',
                'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Shop::model()->findAll(), 'id', 'name')),
                'extra' => array(
                    'readonly' => !empty($shopId),
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                    ),
                )
            ));
            ?>
            <?php echo $form->error($model, 'shop_id'); ?>

        </div>
    </div>

    <div class="<?php echo ($model->hasErrors('supplier_id')) ? 'has-error' : '' ?> form-group">
        <label class="col-sm-3 control-label required">Contact <span class="required">*</span></label>

        <div class="col-sm-7">
            <?php
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $model,
                'keyName' => 'supplier_id',
                'placeHolder' => 'Select Supplier',
                'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Supplier::model()->findAll(), 'id', 'name')),
                'extra' => array(
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                    ),
                )
            ));
            ?>
            <?php echo $form->error($model, 'supplier_id'); ?>

        </div>
    </div>

    <div class="<?php echo ($model->hasErrors('task_group')) ? 'has-error' : '' ?> form-group">
        <label class="col-sm-3 control-label required">Task Group<span class="required">*</span></label>

        <div class="col-sm-8">
            <?php
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $model,
                'keyName' => 'task_group',
                'placeHolder' => 'Select task group',
                'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(TaskGroup::model()->findAll(), 'id', 'name')),
                'extra' => array(
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                    ),
                )
            ));
            ?>
            <?php echo $form->error($model, 'task_group'); ?>

        </div>
    </div>

    <?php if (!$model->isNewRecord) { ?>

        <div class="form-group">
            <label class="col-sm-3 control-label">Auto start</label>

            <div class="col-md-7">
                <?php
                $this->widget('yiiwheels.widgets.switch.WhSwitch', array(
                    'model' => $model,
                    'attribute' => 'is_auto_start',
                ));
                ?>
                <span class="help-block">Clone process base on this template. Please define when it should be cloned with Start week, Start day and Start time</span>
            </div>
        </div>

        <div class="<?php echo ($model->hasErrors('str_weeks')) ? 'has-error' : '' ?> form-group">
            <label class="col-sm-3 control-label required">Start week </label>

            <div class="col-sm-7">
                <?php
                //                $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_week', array(
                //                    'model' => $model,
                //                    'keyName' => 'str_weeks',
                //                    'placeHolder' => 'Select week'
                //                ));
                ?>
                <?php
                $weekData = ProcessTemplate::weeksInYears();
                $oddEvenWeeks = ProcessTemplate::getOddEvenWeeks($weekData);
                Yii::app()->clientScript->registerScript('week', '
                    window.preload_data = ' . CJavaScript::encode($weekData) . ';
                    ', CClientScript::POS_HEAD);
                $this->widget('yiiwheels.widgets.select2.WhSelect2', array(
                    'asDropDownList' => false,
                    'model' => $model,
                    'attribute' => 'str_weeks',
                    'pluginOptions' => array(
                        'multiple' => true,
                        'minimumInputLength' => 0,
                        'width' => '100%',
                        'placeholder' => 'Select week',
                        'allowClear' => true,
                        'query' => 'js:function(query) {
                            
                            var data = {results: []};
                            $.each(window.preload_data, function(){
                                if(query.term.length == 0 || this.text.toUpperCase().indexOf(query.term.toUpperCase()) >= 0 || this.date.toUpperCase().indexOf(query.term.toUpperCase()) >= 0 ){
                                    if(this.year != undefined){
                                        data.results.push({id: this.id, text: this.text ,date: this.date, year: this.year});
                                    }else{
                                        data.results.push({id: this.id, text: this.text ,date: this.date});
                                    }
                                }
                            });
                            query.callback(data); 
                        }',
                        'formatResult' => 'js:function(item){
                            if(item.year != undefined){
                                return "<strong>"+item.text +" (" + item.year +")</strong> <i class=\'text-muted\'>" + item.date + "</i>";
                            }else{
                                return "<strong>"+item.text + "</strong> <i class=\'text-muted\'>" + item.date + "</i>";
                            }
                        }',
                        'formatSelection' => 'js:function(item){
                            return "<strong>"+item.text + "</strong> <i class=\'text-muted\'>" + item.date + "</i>";
                        }',
                        'initSelection' => 'js:function(element, callback){
                            var preselected_ids = [];
                            if(element.val()){
                                $(element.val().split(",")).each(function (index,value) {
                                    preselected_ids.push({id: value});
                                });
                                var pre_selections = [];
                                for(index in window.preload_data){
                                    for(id_index in preselected_ids){
                                        if (window.preload_data[index].id*1 == preselected_ids[id_index].id*1){
                                            pre_selections.push(window.preload_data[index]);
                                        }
                                    }
                                }

                                callback(pre_selections);
                            }
                        }'
                    ),
                ));
                ?>
                <div>
                    <a onclick="js:$('#<?php echo get_class($model) ?>_str_weeks').select2('val',<?php echo CJavaScript::encode($oddEvenWeeks['odd']); ?>);"
                       href="javascript:;">Odd weeks</a> |
                    <a onclick="js:$('#<?php echo get_class($model) ?>_str_weeks').select2('val',<?php echo CJavaScript::encode($oddEvenWeeks['even']); ?>);"
                       href="javascript:;">Even weeks</a> |
                    <a onclick="js:$('#<?php echo get_class($model) ?>_str_weeks').select2('val',null);"
                       href="javascript:;">Clear</a>
                </div>
                <span class="help-block">Weeks for cloning process, only be used with Auto Start = ON, leave blank to Auto Start every weeks</span>
                <?php echo $form->error($model, 'arr_weeks'); ?>

            </div>
        </div>

        <div class="<?php echo ($model->hasErrors('arr_start_dayofweek')) ? 'has-error' : '' ?> form-group">
            <label class="col-sm-3 control-label required">Start day </label>

            <div class="col-sm-7">
                <?php
                $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                    'model' => $model,
                    'keyName' => 'arr_start_dayofweek',
                    'placeHolder' => 'Select day',
                    'data' => CMap::mergeArray(array('' => ''), ProcessTemplate::itemAlias('DayOfWeek')),
                    'extra' => array(
                        'pluginOptions' => array(
                            'placeholderOption' => 'first',
                        ),
                        'htmlOptions' => array(
                            'multiple' => true
                        )
                    )
                ));
                ?>
                <span class="help-block">The day of week to clone process, only be used with Auto Start = ON</span>
                <?php echo $form->error($model, 'arr_start_dayofweek'); ?>

            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Start time</label>

            <div class="col-md-5">
                <?php
                $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                    'model' => $model,
                    'attribute' => 'start_time',
                    'pluginOptions' => array(
                        'showMeridian' => false,
                        'defaultTime' => 'value'
                    )
                ));
                ?>
                <span class="help-block">The time to clone process, only be used with Auto Start = ON (UK time)</span>
            </div>
        </div>

        <div class="<?php echo ($model->hasErrors('status')) ? 'has-error' : '' ?> form-group">
            <label class="col-sm-3 control-label required">Status <span class="required">*</span></label>

            <div class="col-sm-7">
                <?php
                $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                    'model' => $model,
                    'keyName' => 'status',
                    'placeHolder' => 'Select Status',
                    'data' => ProcessTemplate::itemAlias('Status'),
                    'extra' => array(
                        'pluginOptions' => array(
                            'minimumResultsForSearch' => '-1'
                        ),
                    )
                ));
                ?>
                <span
                    class="help-block">Only Active template will be used for cloning Process (Manual and Automatic)</span>
                <?php echo $form->error($model, 'status'); ?>

            </div>
        </div>

    <?php } ?>

    <div class="row">
        <div class="col-sm-12 text-center">
            <?php
            echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'icon' => TbHtml::ICON_OK
            ));
            ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->