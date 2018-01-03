<?php
class TaskProcessAssigneeColumn extends CDataColumn{
    public function getDataCellContent($row) {

        $data = $this->grid->dataProvider->data[$row];
        
        return Profile::model()->getById($data['assign_id'])->getFullName();
    }
}