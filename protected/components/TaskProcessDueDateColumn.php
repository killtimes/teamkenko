<?php

class TaskProcessDueDateColumn extends CDataColumn {

    public function getDataCellContent($row) {

        $data = $this->grid->dataProvider->data[$row];

        if ($data['stage'] != TaskProcess::STAGE_COMPLETED && $data['due_date'] !== null) {
            return TbHtml::abbr(Yii::app()->format->timeAgo($data['due_date']), $data['due_date']);
        }
    }

}
