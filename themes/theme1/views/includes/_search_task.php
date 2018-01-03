<?php

$this->widget('yiiwheels.widgets.select2.WhSelect2', array(
    'model' => $model,
    'asDropDownList' => false,
    'attribute' => $keyName,
    'events' => array("select2-selecting" => 'js:ProcessPage.setTaskDuration'),
    'pluginOptions' => array(
        'minimumInputLength' => 0,
        'width' => '100%',
        'placeholder' => 'Select Task',
        'allowClear' => true,
        'ajax' => array(
            'url' => CController::createUrl('/template/task/list', array('g'=>$taskGroup)),
            'dataType' => 'json',
            'quietMillis' => 500,
            'data' => 'js:function (term,page) {
                            return {
                                q: term,
                                page: page
                            };
                        }',
            'results' => 'js:function (data,page) { 
                            var more = (page * 10) < data.total;
                            return {results: data.results, more: more};
                        }'
        ),
        'initSelection' => 'js:function(element, callback) {
                        var id=$(element).val();
                        if (id!=="") {
                            $.ajax("' . CController::createUrl('/template/task/load') . '", {
                                data: {
                                    qid: id
                                },
                                dataType: "json"
                            }).done(function(data) {                                     
                                callback(data); 
                            });
                        }
                    }',
        'formatSelection'=>'js:function(item, data){
            if(item.group.length != " "){
                    return item.text + " <i class=\'text-muted success\'>" + item.group + "</i>";
                }else{
                    return item.text;
                } 
            }',
        'formatResult' => 'js:function(item){
                if(item.group.length != " "){
                    return item.text + " <i class=\'text-muted success\'>" + item.group + "</i>";
                }else{
                    return item.text;
                }
            
            }',
        
)));
?>