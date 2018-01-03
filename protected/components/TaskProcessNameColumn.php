<?php

Yii::import('template.models.Task');

class TaskProcessNameColumn extends CDataColumn {

    public function getDataCellContent($row) {

        $data = $this->grid->dataProvider->data[$row];
        
        return TbHtml::link(CHtml::encode(Task::model()->getById($data['task_id'])->name), 'javascript:;', array('class' => 'view-activity', 'data-url' => Yii::app()->createUrl('/process/task/activities', array('id' => $data['id'])))) . '&nbsp; <span data-href="' . Yii::app()->createUrl('/process/admin/instructions', array('id' => $data['task_id'])) . '" class="glyphicon glyphicon-question-sign text-muted show-instructions"></span>';

    }

}
