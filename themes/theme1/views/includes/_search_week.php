<?php

if (!isset($extra)) {
    $extra = array();
}

$this->widget('yiiwheels.widgets.select2.WhSelect2', CMap::mergeArray(array(
            'asDropDownList' => false,
            'model' => $model,
            'attribute' => $keyName,
            'pluginOptions' => array(
                'multiple'=>true,
                'minimumInputLength' => 0,
                'width' => '100%',
                'placeholder' => $placeHolder,
                'allowClear' => true,
                'ajax' => array(
                    'url' => CController::createUrl('/template/process/listweek'),
                    'dataType' => 'json',
                    'quietMillis' => 500,
                    'data' => 'js:function (term,page) {
                            return {
                                q: term,
                                page: page
                            };
                        }',
                    'results' => 'js:function (data,page) { 
                            var more = (page * 100) < data.total;
                            return {results: data.results, more: more};
                        }'
                ),
                'initSelection' => 'js:function(element, callback) {
                        var id= $(element).val();
                        if (id!=="") {
                            $.ajax("' . CController::createUrl('/template/process/initweek') . '", {
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
                    return item.text + "   <i class=\'text-muted success\'>" + item.date + "</i>";
                }'
            )), $extra));
