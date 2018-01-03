<?php
Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/js/alert.js', CClientScript::POS_END);

Yii::app()->clientScript->registerScript('initjs', "
AlertModule.list.init();
", CClientScript::POS_READY);

$this->beginWidget('\TbModal', array(
    'id' => 'taskAlerts',
    'header' => 'Task Alerts',
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
