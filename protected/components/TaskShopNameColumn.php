<?php

class TaskShopNameColumn extends CDataColumn {

    public function getDataCellContent($row) {

        $data = $this->grid->dataProvider->data[$row];
        $cid = 0;
        if ($data['shop_id'] > 0) {
            $cid = $data['shop_id'];
        } else if ($data['todo_shop_id'] > 0) {
            $cid = $data['todo_shop_id'];
        }

        if ($cid > 0) {
            return Shop::model()->getById($cid)->name;
        }
    }

}
