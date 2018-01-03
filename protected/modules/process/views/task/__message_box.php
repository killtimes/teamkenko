<?php
Yii::app()->clientScript->registerScript('message', "
    TaskPage.initMessageBox();
    $('abbr').tooltip();
", CClientScript::POS_READY);

$form = $this->beginWidget('\TbActiveForm', array(
    'action' => Yii::app()->createUrl('process/task/postMessage', array('id' => $task_id)),
    'id' => 'message-form',
    'layout' => TbHtml::FORM_LAYOUT_VERTICAL,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'onsubmit' => "return false;"),
        ));
?>
<div>    
    <h4>Messages</h4>
    <div class="error-container alert alert-dismissible alert-danger " style="display: none"></div>
    <div class="form-group">
        <?php echo $form->textArea($formMessage, 'message', array('style' => 'width:60%', 'rows' => 3, 'placeholder' => 'Write your comment...', 'max' => 500)); ?>
        <div class="clearfix pad5"></div>
        
        <span class="btn btn-default btn-xs fileinput-button pull-left">
            <i class="glyphicon glyphicon-paperclip"></i>
            <span>Attach file</span>
        </span>
        <div class="hidden">
            <?php
            $uploadModel = new XUploadForm();
            $this->widget('jupload.JUpload', array(
                'template' => 'application.views._tpl_upload',
                'showForm' => false,
                'model' => $uploadModel,
                'attribute' => 'file',
                'url' => Yii::app()->createUrl("process/task/upload"),
                'multiple' => true,
                'autoUpload' => true,
                'htmlOptions' => array(
                    'id' => 'message-form',
                ),
                'options' => array(
                    'acceptFileTypes' => 'js:/(\.|\/)(jpg|png|bmp|xls|7z|zip|rar|doc|pdf|docx|xlsx|xls|txt)$/i',
                    'maxFileSize' => 6000000, // 6 MB
                    'maxNumberOfFiles' => 10,
                    'filesContainer' => '#fileContainer',
                    'sequentialUploads' => false,
                    'singleFileUploads' => true
                )
            ));
            ?>
        </div>
        <span class="help-block small">Note: If you're uploading Invoice, please enter Invoice code/Invoice number... to Document Code.<br>
        If you're uploading Purchase Order, please enter Delivery Date to Document Date</span>
        <table role="presentation" class="table  no-margin pad5">
            <tbody id="fileContainer" class="files">

            </tbody>
        </table>
    </div>
    <div class="clearfix"></div>
    <button id="btnPost" data-loading-text="Loading..." class="btn btn-primary btn-sm pull-right">Post</button>
    <div class="clearfix"></div>
</div>

<?php $this->endWidget(); ?>