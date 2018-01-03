<?php

class OverviewController extends RController {

    public function actionIndex($id, $task_id) {
        $this->layout = false;

        $process = Process::model()->findByPk($id);

        if ($process === null) {
            $this->notfoundException();
        }

        //get list task
        $taskProcessModel = new TaskProcess();
        $taskProcessModel->process_id = $process->id;

        //get document
        $criteria = new CDbCriteria();
        $criteria->join = " inner join `TaskProcess` tp on tp.id=t.task_id inner join `Process` p on p.id=tp.process_id ";
        $criteria->condition = " t.status=".Document::STATUS_ACTIVE." AND p.id= " . $taskProcessModel->process_id;
        $criteria->order = "create_date desc";
        
        $documents = Document::model()->findAll($criteria);
        
        $html = $this->renderPartial('index', array(
            'process' => $process,
            'modelTask' => $taskProcessModel,
            'documents'=>$documents,
            'task_id' => $task_id
                ), true, false);

        $jsInline = '';

        foreach (Yii::app()->clientscript->scripts as $k => $js) {
            $jsInline.=implode('', $js);
        }

        echo $html . CHtml::script($jsInline);
        Yii::app()->end();
    }

}
