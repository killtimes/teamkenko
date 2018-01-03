<div class="well">
    <p><?php echo UserModule::t("You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done."); ?></p>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        'labelWidthClass' => 'col-sm-4',
        'controlWidthClass' => 'col-sm-4'
    ));
    ?>

    <?php echo $form->numberFieldControlGroup($model, 'id'); ?>
    <?php echo $form->textFieldControlGroup($model, 'username'); ?>
    <?php echo $form->emailFieldControlGroup($model, 'email'); ?>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <?php
            echo TbHtml::formActions(array(
                TbHtml::submitButton('Search', array('color' => TbHtml::BUTTON_COLOR_PRIMARY,
                    'size' => TbHtml::BUTTON_SIZE_SMALL)
                )
            ));
            ?>
        </div>
    </div>


    <!--    <div class="row">-->
    <!--        --><?php //echo $form->label($model,'id');   ?>
    <!--        --><?php //echo $form->textField($model,'id');   ?>
    <!--    </div>-->
    <!---->
    <!--    <div class="row">-->
    <!--        --><?php //echo $form->label($model,'username');   ?>
    <!--        --><?php //echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20));   ?>
    <!--    </div>-->
    <!---->
    <!--    <div class="row">-->
    <!--        --><?php //echo $form->label($model,'email');   ?>
    <!--        --><?php //echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128));   ?>
    <!--    </div>-->
    <!---->
    <!--    <div class="row">-->
    <!--        --><?php //echo $form->label($model,'activkey');   ?>
    <!--        --><?php //echo $form->textField($model,'activkey',array('size'=>60,'maxlength'=>128));   ?>
    <!--    </div>-->
    <!---->
    <!--    <div class="row">-->
    <!--        --><?php //echo $form->label($model,'create_at');   ?>
    <!--        --><?php //echo $form->textField($model,'create_at');   ?>
    <!--    </div>-->
    <!---->
    <!--    <div class="row">-->
    <!--        --><?php //echo $form->label($model,'lastvisit_at');   ?>
    <!--        --><?php //echo $form->textField($model,'lastvisit_at');   ?>
    <!--    </div>-->
    <!---->
    <!--    <div class="row">-->
    <!--        --><?php //echo $form->label($model,'superuser');   ?>
    <!--        --><?php //echo $form->dropDownList($model,'superuser',$model->itemAlias('AdminStatus'));   ?>
    <!--    </div>-->
    <!---->
    <!--    <div class="row">-->
    <!--        --><?php //echo $form->label($model,'status');   ?>
    <!--        --><?php //echo $form->dropDownList($model,'status',$model->itemAlias('UserStatus'));   ?>
    <!--    </div>-->
    <!---->
    <!--    <div class="row buttons">-->
    <!--        --><?php //echo CHtml::submitButton(UserModule::t('Search'));  ?>
    <!--    </div>-->

    <?php $this->endWidget(); ?>

</div><!-- search-form -->