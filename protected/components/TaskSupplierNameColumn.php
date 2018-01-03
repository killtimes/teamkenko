<?php

class TaskSupplierNameColumn extends CDataColumn {

    public function getDataCellContent($row) {

        $data = $this->grid->dataProvider->data[$row];
        $sid = 0;
        if ($data['supplier_id'] > 0) {
            $sid = $data['supplier_id'];
        } else if ($data['todo_supplier_id'] > 0) {
            $sid = $data['todo_supplier_id'];
        }

        if ($sid > 0) {
            return Supplier::model()->getById($sid)->name;
        }
    }

}
