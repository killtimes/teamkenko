<?php

class AlertActionColumn extends TbDataColumn
{
    public $atype='';
    public function getDataCellContent($row) {

        $data = $this->grid->dataProvider->data[$row];

        echo TbHtml::link('Action',array('/alert/list/view','id'=>$data->id,'type'=>$this->atype),array(
            'class'=>'btn btn-default btn-xs',
        ));

    }
}