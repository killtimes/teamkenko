<?php
/* @var $this AdminController */
/* @var $model Process */
/* @var $form TbActiveForm */
?>

<div class="form-group">

    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'process-form',
        'enableAjaxValidation' => false,
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-md-3',
        'controlWidthClass' => 'col-md-9'
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 7, 'maxlength' => 150)); ?>

    <?php echo $form->textAreaControlGroup($model, 'description', array('rows' => 3, 'span' =>7 , 'maxlength' => 255)); ?>

    <div class="<?php echo ($model->hasErrors('shop_id')) ? 'has-error' : '' ?> form-group">        
        <label class="col-md-3 control-label required">Shop <span class="required">*</span></label>
        <div class="col-md-9"> 
            <?php $shopId = Profile::model()->getShopId(Yii::app()->user->id);
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $model,
                'keyName' => 'shop_id',
                'placeHolder' => 'Select Shop',
                'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Shop::model()->findAll(), 'id', 'name')),
                'extra' => array(
//                    'readonly' => !empty($shopId),
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                        'width' => '70%',
                    ),
                )
            ));
            ?>
            <?php echo $form->error($model, 'shop_id'); ?>

        </div>
    </div> 

    <div class="<?php echo ($model->hasErrors('supplier_id')) ? 'has-error' : '' ?> form-group">        
        <label class="col-md-3 control-label required">Contact <span class="required">*</span></label>
        <div class="col-md-9"> 
            <?php
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $model,
                'keyName' => 'supplier_id',
                'placeHolder' => 'Select Contact',
                'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(Supplier::model()->findAll(), 'id', 'name')),
                'extra' => array(
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                        'width' => '70%',
                    ),
                )
            ));
            ?>
            <?php echo $form->error($model, 'supplier_id'); ?>

        </div>
    </div> 

    <div class="<?php echo ($model->hasErrors('task_group')) ? 'has-error' : '' ?> form-group">        
        <label class="col-md-3 control-label required">Task Group<span class="required">*</span></label>
        <div class="col-md-9"> 
            <?php
            $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._select_box', array(
                'model' => $model,
                'keyName' => 'task_group',
                'placeHolder' => 'Select task group',
                'data' => CMap::mergeArray(array('' => ''), Tbhtml::listData(TaskGroup::model()->findAll(), 'id', 'name')),
                'extra' => array(
                    'pluginOptions' => array(
                        'placeholderOption' => 'first',
                        'width' => '70%',

                    ),
                )
            ));
            ?>
            <?php echo $form->error($model, 'task_group'); ?>

        </div>
    </div>

    <?php if ($model->stage != Process::STAGE_NOTSET) { ?>
        <div class="form-group">        
            <label class="col-md-3 control-label ">Job</label>
            <div class="col-md-7"> 
                <div class="progress pad5">
                    <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $model->progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $model->progress; ?>%;">
                        <span><?php echo $model->progress; ?>%</span>
                    </div>
                </div>
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