<script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="<?php echo Yii::app()->params['dropboxKey']; ?>"></script>
<?php
Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/js/autosize.min.js', CClientScript::POS_END);
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('yiiactiveform');
?>

<?php Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/lightbox2/css/lightbox.css', 'screen', 99); ?>
<?php Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/lightbox2/js/lightbox.js', CClientScript::POS_END); ?>
<?php
Yii::app()->clientScript->registerScript('configlightbox', "
lightbox.option({
      'resizeDuration': 200,
      'fadeDuration':200,
      'wrapAround': true
    });
    TaskPage.taskActivities();
    ProcessPage.initPopover();
", CClientScript::POS_READY);
?>
<?php
$this->beginWidget('\TbModal', array(
    'id' => 'taskActivities',
    'header' => 'Task Activities',
    'size' => TbHtml::MODAL_SIZE_LARGE,
    'backdrop' => false,
    'footer' => false,
    'fade' => false
));
?>
<div class="content-placeholder">
    <div class="placeholder">
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar"
                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
</div>


<!--
<?php
$this->widget('jupload.JUpload', array());

$this->widget('yiiwheels.widgets.select2.WhSelect2', array(
    'name' => 'preloadSelect',
    'data' => array('' => '')
));
?>
-->

<?php
$this->beginWidget('\TbModal', array(
    'id' => 'mdProcess',
    'header' => 'Job',
    'size' => TbHtml::MODAL_SIZE_LARGE,
    'backdrop' => false,
    'footer' => false,
    'fade' => false
));
?>
<div class="content-placeholder">
    <div class="placeholder">
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar"
                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
</div>

<?php
$this->beginWidget('\TbModal', array(
    'id' => 'mdConfirmComplete',
    'header' => 'Complete Task',
    'size' => TbHtml::MODAL_SIZE_DEFAULT,
    'backdrop' => true,
    'footer' => array(
        TbHtml::button('Complete', array('data-loading-text' => 'Loading...', 'disabled' => 'disabled', 'class' => 'complete disabled', 'color' => TbHtml::BUTTON_COLOR_PRIMARY)),
        TbHtml::button('Cancel', array('data-dismiss' => 'modal')),
    ),
    'fade' => true
));
?>
<h4>Are all the notes scanned in and saved for this task?</h4>
<div class="well">
    <div class="content-placeholder">
        <div class="placeholder">
            <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar"
                     aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
</div>
<!--
<?php
$this->widget('yiiwheels.widgets.datepicker.WhDatePicker', array(
    'name' => 'loaddatepicker',
    'pluginOptions' => array(
        'format' => 'dd-mm-yyyy',
        'disableTouchKeyboard' => true,
        'todayHighlight' => true
    ),
    'htmlOptions' => array(
        'placeholder' => 'dd-mm-yyyy'
    )
));
?>
-->

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <ol class="indicator"></ol>
</div>