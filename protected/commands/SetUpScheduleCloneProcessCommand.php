<?php

class SetUpScheduleCloneProcessCommand extends CConsoleCommand {

    public function run($args) {

        Yii::beginProfile('test');
        Yii::import('application.modules.template.models.Task');
        Yii::import('application.modules.template.models.ProcessTemplate');
        Yii::import('application.modules.template.models.CloneTask');
        Yii::import('application.modules.template.models.TemplateSchedule');

        //today
        $now = time();
        //index weekday
        $todayDoW = date('w', $now);
        $date = date('Y-m-d', $now);
        $time = date('H:i:s', $now);

        //convert to bitwise
        $todayBW = ProcessTemplate::mappingDayOfWeek($todayDoW);

        $w = Yii::app()->dateFormatter->format('w', $date);

        //get process template with auto start ON and scheduled on today, in future, not set up for a schedule in the pass
        $criteria = new CDbCriteria;
        $criteria->join = ' left join TemplateSchedule on t.id=TemplateSchedule.process_id';
        $criteria->compare('status', ProcessTemplate::STATUS_ACTIVE);
        $criteria->compare('is_auto_start', 1);
        $criteria->compare('start_time', '>' . $time);
        
        $criteria->addCondition('start_dayofweek&' . $todayBW);
        $criteria->addCondition('week is null OR week='.$w);


//        $templates = ProcessTemplate::model()->findAll('status=:status AND is_auto_start=1 AND start_dayofweek&:dayofweek AND start_time>:current_time', array(
//            ':status' => ProcessTemplate::STATUS_ACTIVE,
//            ':dayofweek' => $todayBW,
//            ':current_time' => $time
//        ));

        $templates = ProcessTemplate::model()->findAll($criteria);

        $msg = '';
        $totalAdd = 0;
        $totalUpdate = 0;

        if ($templates !== null) {

            foreach ($templates as $t) {

                //check in schedule task if exist
                $model = CloneTask::model()->find('template_id=:template_id', array(
                    ':template_id' => $t->id
                ));

                $dateTime = sprintf('%s %s', $date, $t->start_time);

                if ($model === null) {
                    $model = new CloneTask;
                    $model->template_id = $t->id;
                    $model->when = $dateTime;
                    if (!$model->save()) {
                        $msg.="\nAdd failed:" . CVarDumper::dumpAsString($model->errors);
                    } else {
                        $totalAdd++;
                        $msg.="\nAdd OK [#" . $t->id . "] time: " . $model->when;
                    }
                } else {
                    //update task if the time get changed in the day
                    if ($dateTime != $model->when) {
                        $model->when = $dateTime;
                        if (!$model->save()) {
                            $msg.="\nUpdate failed #" . $t->id . " : " . CVarDumper::dumpAsString($model->errors);
                        } else {
                            $totalUpdate++;
                            $msg.="\nUpdate OK [#" . $t->id . "] time: " . $model->when;
                        }
                    }
                }
            }
        }

        Yii::endProfile('test');
        $logger = Yii::getLogger();
        $tpl = "Set up process for cloning today "
                . "\nDate: %s"
                . "\nTotal Templates: %s"
                . "\nAdd: %s"
                . "\nUpdate: %s"
                . "\n==="
                . "%s"
                . "\n==="
                . "\nExecution time: %s ";
        $result = sprintf($tpl, date('Y-m-d H:i:s', $now), count($templates), $totalAdd, $totalUpdate, $msg, $logger->executionTime);
        Yii::log($result);
        exit;
    }

}
