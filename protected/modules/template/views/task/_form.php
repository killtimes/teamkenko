<?php
Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/js/jquery.counter.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('counter', 'TemplatePage.initFormTask()', CClientScript::POS_READY);
?>


<div class="form-group">

    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'task-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-md-3',
        'controlWidthClass' => 'col-md-9'
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 7, 'maxlength' => 150)); ?>

    <?php echo $form->textAreaControlGroup($model, 'description', array('rows' => '3', 'span' => 7, 'maxlength' => 255)); ?>

    <?php echo $form->textFieldControlGroup($model, 'duration', array('span' => 7)); ?>

    <div class="<?php echo ($model->hasErrors('task_group')) ? 'has-error' : '' ?> form-group">        
        <label class="col-md-3 control-label required">Task Group <span class="required">*</span></label>
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
                    ),
                )
            ));
            ?>
            <?php echo $form->error($model, 'task_group'); ?>

        </div>
    </div> 

    <?php echo $form->textAreaControlGroup($model, 'instructions', array('span' => 7, 'rows' => 6, 'maxlength' => 7000)); ?>


    <div class="row">
        <div class="col-sm-12 text-center">
            <?php
            if (($model->isNewRecord && $this->checkAccess('TaskTemplate_Create', array()) || (!$model->isNewRecord && $this->checkAccess('TaskTemplate_Update', array())))) {
                echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                    'icon' => TbHtml::ICON_OK
                ));
            }
            ?>
        </div> 
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->