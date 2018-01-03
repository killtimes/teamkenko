<?php

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
                    'url' => CController::createUrl('/process/default/list'),
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
                            $.ajax("' . CController::createUrl('/process/default/load') . '", {
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
                return "<i class=\"glyphicon glyphicon-bookmark gray\"> </i> "+item.text;
            
            }'
            )), $extra));
