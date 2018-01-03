<?php
$this->breadcrumbs = array(
    'Dashboard' => array('/dashboard'),
    'Test',
);
?>
<div class="panel panel-default panel-container">
    <div class="panel-body">
        <fieldset>
            <?php
            $form = $this->beginWidget('\TbActiveForm', array(
                'id' => 'document-form',
                'enableAjaxValidation' => false,
                'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                //This is very important when uploading files
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
            ));
            ?>    
            <div class="form-group">
                <label class="col-sm-3 control-label required">Name <span class="required">*</span></label>
                <div class="col-sm-7"> 
                    <?php echo $form->textField($model, 'title'); ?>                    
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label required"></label>
                <div class="col-sm-7"> 
                    <span class="btn btn-success btn-xs fileinput-button">
                        <i class="icon-plus icon-white"></i>
                        <span>Select files...</span>
                        <?php
                        $this->widget('yiiwheels.widgets.fileupload.WhBasicFileUpload', array(
                            'model' => $uploadForm,
                            'attribute' => 'file',
                            'uploadAction' => $this->createUrl('task/upload'),
                            'htmlOptions' => array('id' => 'document-form'),
                            'pluginOptions' => array(
                                'autoUpload' => true,
                                'multiple' => false,
                                'showForm' => false,
                                'dataType' => 'json',
                                'start' => 'js:function(){ 
                                    $("#bprogress").show();
                                    $(".fileinput-button").hide();
                                }',
                                'done' => 'js:function(e, data){ 
                                    $(".fileinput-button").hide();
                                    $.each(data.result, function(i, file){
                                        console.log(file);
                                        $("<strong/>").html(file.name+" ("+file.size+")"+" <a title=\"Delete\" data-url=\""+file.delete_url+"\" href=\"javascript:;\" class=\"delete-file\"> <span class=\"glyphicon glyphicon-remove\"></span></a>").appendTo("#bfiles");
                                    });                                    
                                    $("#bprogress .progress-bar").css("width","0%").html("0%");
                                    $("#bprogress").hide();
                                }',
                                'progressall' => "js:function (e, data) {
                                    var progress = parseInt(data.loaded / data.total * 100, 10);
                                    console.log(progress);
                                    $('#bprogress .progress-bar').css(
                                        'width',
                                        progress + '%'
                                    ).html(progress + '%');
                                }"
                            )
                        ));
                        ?>
                    </span>
                    <!-- The global progress bar -->
                    <div id="bprogress" class="progress" style="display: none">
                        <div class="progress-bar"></div>
                    </div>
                    <!-- The container for the uploaded files -->
                    <div id="bfiles" class="files text-success"></div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-center">
                    <?php
                    echo TbHtml::submitButton('Save', array(
                        'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                        'icon' => TbHtml::ICON_OK
                    ));
                    ?>
                </div> 
            </div>
            <?php $this->endWidget(); ?>
        </fieldset>
    </div>
</div>