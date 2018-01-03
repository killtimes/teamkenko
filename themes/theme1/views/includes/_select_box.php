<?php

$this->widget('yiiwheels.widgets.select2.WhSelect2', CMap::mergeArray(
                array(
                    'asDropDownList' => true,
                    'model' => $model,
                    'attribute' => $keyName,
                    'data' => $data,
                    'pluginOptions' => array(
                        'placeholder' => $placeHolder,
                        'width' => '60%',
                        //        'placeholderOption' => 'first',
                        'allowClear' => true,
                        'containerCssClass' => 'select2-tp'
                    )
                ), $extra)
);
