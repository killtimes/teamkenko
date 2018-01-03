<?php

class TaskStatusDueDateColumn extends CDataColumn {

    public function getDataCellContent($row) {

        $data = $this->grid->dataProvider->data[$row];

        $html = TaskProcess::stageAlias($data["stage"]);

        if ($data['stage'] != TaskProcess::STAGE_COMPLETED && $data['due_date'] !== null) {
            $due = strtotime($data['due_date']);
            if ($due > time()) {
                $html.='<br>' . TbHtml::abbr('due in ' . Yii::app()->format->timeAgo($data['due_date']), $data['due_date'], array('class'=>'text-info'));
            } else {
                $html.='<br>' . TbHtml::abbr('overdue ' . Yii::app()->format->timeAgo($data['due_date']), $data['due_date'], array('class'=>'text-danger'));
            }
        }

        return $html;
    }

}
