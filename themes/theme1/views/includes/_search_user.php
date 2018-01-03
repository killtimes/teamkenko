<?php

if (!isset($extra)) {
    $extra = array();
}


if(!isset($department)){
    $param = array('sid' => $shop_id);
}else{
    $param = array('did'=>$department,'sid' => $shop_id);
}

$this->widget('yiiwheels.widgets.select2.WhSelect2', CMap::mergeArray(array(
            'asDropDownList' => false,
            'model' => $model,
            'attribute' => $keyName,
            'pluginOptions' => array(
                'minimumInputLength' => 0,
                'width' => '100%',
                'placeholder' => $placeHolder,
                'allowClear' => true,
                'ajax' => array(
                    'url' => CController::createUrl('/user/default/list', $param),
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
                        // the input tag has a value attribute preloaded that points to a preselected ditributor id
                        // this function resolves that id attribute to an object that select2 can render
                        // for updateAction or after a failed form validation
                        var id= $(element).val();
                        if (id!=="") {
                            $.ajax("' . CController::createUrl('/user/default/load') . '", {
                                data: {
                                    qid: id
                                },
                                dataType: "json"
                            }).done(function(data) {                                     
                                callback(data); 
                            });
                        }
                    }',
                'formatResult' => 'js:function(item){
                if(item.email.length != " "){
                    return item.text + "   <i class=\'text-muted success\'>" + item.email + "</i>";
                }else{
                    return item.text;
                }            
            }'
            )), $extra));
