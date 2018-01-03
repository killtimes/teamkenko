<?php
class TaskProcessNameAndTipColumn extends CDataColumn{
    public function getDataCellContent($row) {

        $data = $this->grid->dataProvider->data[$row];
        
        if($data['process_id'] > 0){
            return "<a data-url=\"".Yii::app()->createUrl("/process/overview", array("id"=>$data["process_id"], "task_id"=>$data["id"]) )."\" rel=\"tooltip\" data-container=\"#mdListTask\" title=\"".CHtml::encode($data["process_name"])."\" class=\"view-process\" data-process-id=\"".$data["process_id"]."\" href=\"javascript:;\"><span class=\"badge badge-default\">Job detail</span></a>";
        }
        
    }
}