
<div class="panel panel-default panel-container">
    <div class="panel-body">
        <?php /*
          $form = $this->beginWidget('\TbActiveForm', array(
          'id' => 'document-form',
          'enableAjaxValidation' => false,
          'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
          //This is very important when uploading files
          'htmlOptions' => array('enctype' => 'multipart/form-data'),
          ));
          ?>
          <?php
          $this->widget('xupload.XUpload', array(
          'url' => Yii::app()->createUrl("process/task/upload"),
          'model' => $model,
          'attribute' => 'file',
          'autoUpload' => true,
          'multiple' => false,
          'showForm' => false,
          'htmlOptions' => array('id' => 'document-form'),
          'uploadTemplate' => null,
          'downloadTemplate' => null,
          'options' => array(
          'acceptFileTypes' => 'js:/(\.|\/)(gif|jpe?g|png)$/i',
          'dataType' => 'json',
          'done' => 'js:function(e, data){
          $(".fileinput-button").hide();
          $.each(data.result, function(i, file){
          console.log(file);
          $("<strong/>").html(file.name+" ("+file.size+")"+" <a title=\"Delete\" data-url=\""+file.delete_url+"\" href=\"javascript:;\" class=\"delete-file\"> <span class=\"glyphicon glyphicon-remove\"></span></a>").appendTo("#bfiles");
          });
          //$("#bprogress .progress-bar").css("width","0%").html("0%");
          //$("#bprogress").hide();
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
          //            'showForm' => false,
          ));
          ?>

          <!-- The global progress bar -->
          <div id="bprogress" class="progress">
          <div class="progress-bar"></div>
          </div>
          <!-- The container for the uploaded files -->
          <div id="bfiles" class="files text-success"></div>
          <?php $this->endWidget(); */ ?>


        <?php
        $form = $this->beginWidget('\TbActiveForm', array(
            'id' => 'document-form',
            'enableAjaxValidation' => false,
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            //This is very important when uploading files
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
        ?>

        <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Select files...</span>
            <!-- The file input field used as target for the file upload widget -->
            <?php
            $this->widget('xupload.BasicXUpload', array(
                'model' => $model,
                'attribute' => 'file',
                'url' => Yii::app()->createUrl("process/task/upload"),
                'htmlOptions' => array('id' => 'document-form'),
                'options' => array(
                    'dataType' => 'json',
                    'acceptFileTypes' => 'js:/(\.|\/)(gif|jpe?g|png|pdf)$/i',
                    'added'=>'js:function(e,data){
                        console.log("validate",data.isValidated);
                        }',
                    'done' => 'js:function(e, data){
                        console.log("done",data);
                    }',
                    'progressall' => "js:function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        console.log(progress);
                    }",
                    'fail'=>'js:function(e, data){  
                        console.log("fail");
                    }'
                )
            ));
            ?>
        </span>

        <?php $this->endWidget();
        ?>

    </div>
</div>
