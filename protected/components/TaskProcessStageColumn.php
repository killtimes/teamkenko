<?php
class TaskProcessStageColumn extends CDataColumn{
    public function getDataCellContent($row) {

        $data = $this->grid->dataProvider->data[$row];
        
        return TaskProcess::stageAlias($data['stage']);
    }
}