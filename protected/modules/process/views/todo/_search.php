
<div class="search-form">

    <?php
    $form = $this->beginWidget('\TbActiveForm', array(
        'id' => 'search-task',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => TbHtml::FORM_LAYOUT_INLINE
    ));
    ?>

    <?php
    $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_process', array(
        'model' => $model,
        'keyName' => 'process_id',
        'placeHolder' => 'Process',
        'extra' => array(
            'pluginOptions' => array(
                'width' => '25%',
            ),
            'htmlOptions' => array(
                'class' => 'form-control',
                'style' => 'display:inline-block'
            )
        )
    ));
    ?>

    <?php
    $this->renderPartial('webroot.themes.' . Yii::app()->theme->name . '.views.includes._search_user', array(
        'model' => $model,
        'keyName' => 'assign_id',
        'placeHolder' => 'Staff',
        'shop_id' => '',
        'extra' => array(
            'pluginOptions' => array(
                'placeholderOption' => 'first',
                'width' => '25%',
            ),
            'htmlOptions' => array(
                'class' => 'form-control',
                'style' => 'display:inline-block'
            )
        )
    ));
    ?>
    

    <?php
    echo TbHtml::submitButton('Search', array(
        'size' => TbHtml:: BUTTON_SIZE_SMALL)
    );
    ?> 
    <?php
    echo TbHtml::resetButton('Clear', array(
        'size' => TbHtml::BUTTON_SIZE_SMALL
    ));
    ?> 

    <?php $this->endWidget(); ?>

</div>