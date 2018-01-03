<?php

class DailyGroupSummary extends CFormModel
{

    public function getSummary()
    {
        $data = Yii::app()->db->createCommand()
            ->select('t.task_group as task_group, tp.stage as stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Task t', 'tp.task_id=t.id')
            ->where('tp.status=1 and tp.stage<>0')
            ->group(array('t.task_group', 'tp.stage'))
            ->order('task_group desc')
            ->queryAll();

        $result = array();

        foreach ($data as $d) {

            if (!isset($result[$d['task_group']])) {
                $result[$d['task_group']] = array(
                    'task_group' => $d['task_group'],
                    'assigned' => 0,
                    'wait_for_accept' => 0,
                    'completed' => 0,
                );
            }

            if ($d['stage'] == TaskProcess::STAGE_ASSIGNED) {
                $result[$d['task_group']]['wait_for_accept'] += $d['total'];
                $result[$d['task_group']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_COMPLETED) {
                $result[$d['task_group']]['completed'] += $d['total'];
                $result[$d['task_group']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_INPROGRESS) {
                $result[$d['task_group']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_REJECTED) {
                $result[$d['task_group']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_WAIRFORCONFIRM) {
                $result[$d['task_group']]['assigned'] += $d['total'];
            }
        }

        return array_values($result);
    }

    public function getToday(&$date)
    {
        $today = Yii::app()->localTime->getLocalDateTimeNow();

//        $today = DateTime::createFromFormat('d-m-Y', '23-11-2016');

        $today->setTime(0, 0, 0);
        $date = $today->format('j M Y');

        $r1 = $today->format('Y-m-d 00:00:00');
        $r2 = $today->modify('+1 day')->format('Y-m-d 00:00:00');

        $data = Yii::app()->db->createCommand()
            ->select('t.task_group as task_group, tp.stage as stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Task t', 'tp.task_id=t.id')
            ->where('tp.status=1 and tp.stage<>0 and tp.assign_date>=:r1 and tp.assign_date<:r2', array(
                ':r1' => $r1,
                ':r2' => $r2
            ))
            ->group(array('t.task_group', 'tp.stage'))
            ->order('task_group desc')
            ->queryAll();

        $result = array();

        foreach ($data as $d) {

            if (!isset($result[$d['task_group']])) {
                $result[$d['task_group']] = array(
                    'task_group' => $d['task_group'],
                    'assigned' => 0,
                    'completed' => 0,
                    'wait_for_accept' => 0,

                );
            }

            if ($d['stage'] == TaskProcess::STAGE_ASSIGNED) {
                $result[$d['task_group']]['wait_for_accept'] += $d['total'];
                $result[$d['task_group']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_COMPLETED) {
                $result[$d['task_group']]['completed'] += $d['total'];
                $result[$d['task_group']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_INPROGRESS) {
                $result[$d['task_group']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_REJECTED) {
                $result[$d['task_group']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_WAIRFORCONFIRM) {
                $result[$d['task_group']]['assigned'] += $d['total'];
            }
        }

        return array_values($result);
    }

    public function getDailyByGroup($group, &$date)
    {

        $today = Yii::app()->localTime->getLocalDateTimeNow();

//        $today = DateTime::createFromFormat('d-m-Y', '23-11-2016');

        $today->setTime(0, 0, 0);
        $date = $today->format('j M Y');

        $r1 = $today->format('Y-m-d 00:00:00');
        $r2 = $today->modify('+1 day')->format('Y-m-d 00:00:00');

        $data = Yii::app()->db->createCommand()
            ->select('tp.assign_id, tp.stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Task t', 'tp.task_id=t.id')
            ->where('tp.status=1 and tp.stage<>0 and t.task_group=:group and tp.assign_date>=:r1 and tp.assign_date<:r2', array(
                ':group' => $group,
                ':r1' => $r1,
                ':r2' => $r2
            ))
            ->group(array('tp.assign_id', 'tp.stage'))
            ->order('task_group desc')
            ->queryAll();

        $result = array();
        foreach ($data as $d) {
            if (!isset($result[$d['assign_id']])) {
                $result[$d['assign_id']] = array(
                    'assign_id' => $d['assign_id'],
                    'assigned' => 0,
                    'completed' => 0,
                    'wait_for_accept' => 0,

                );
            }

            if ($d['stage'] == TaskProcess::STAGE_ASSIGNED) {
                $result[$d['assign_id']]['wait_for_accept'] += $d['total'];
                $result[$d['assign_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_COMPLETED) {
                $result[$d['assign_id']]['completed'] += $d['total'];
                $result[$d['assign_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_INPROGRESS) {
                $result[$d['assign_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_REJECTED) {
                $result[$d['assign_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_WAIRFORCONFIRM) {
                $result[$d['assign_id']]['assigned'] += $d['total'];
            }
        }

        return array_values($result);

    }

    public function getByGroup($group)
    {

        $data = Yii::app()->db->createCommand()
            ->select('tp.assign_id, tp.stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Task t', 'tp.task_id=t.id')
            ->where('tp.status=1 and tp.stage<>0 and t.task_group=:group', array(
                ':group' => $group
            ))
            ->group(array('tp.assign_id', 'tp.stage'))
            ->order('task_group desc')
            ->queryAll();

        $result = array();
        foreach ($data as $d) {
            if (!isset($result[$d['assign_id']])) {
                $result[$d['assign_id']] = array(
                    'assign_id' => $d['assign_id'],
                    'assigned' => 0,
                    'completed' => 0,
                    'wait_for_accept' => 0,
                );
            }

            if ($d['stage'] == TaskProcess::STAGE_ASSIGNED) {
                $result[$d['assign_id']]['wait_for_accept'] += $d['total'];
                $result[$d['assign_id']]['assigned'] += $d['total'];

            } else if ($d['stage'] == TaskProcess::STAGE_COMPLETED) {
                $result[$d['assign_id']]['completed'] += $d['total'];
                $result[$d['assign_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_INPROGRESS) {
                $result[$d['assign_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_REJECTED) {
                $result[$d['assign_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_WAIRFORCONFIRM) {
                $result[$d['assign_id']]['assigned'] += $d['total'];
            }
        }

        return array_values($result);

    }

    public function byStaff($group, $staff)
    {
        $data = Yii::app()->db->createCommand()
            ->select('tp.*')
            ->from('TaskProcess tp')
            ->join('Task t', 'tp.task_id=t.id')
            ->where('tp.status=1 and tp.stage<>0 and t.task_group=:group and tp.assign_id=:assign_id', array(
                ':group' => $group,
                ':assign_id' => $staff,
            ))
            ->order('tp.stage asc')
            ->queryAll();

        $criteria = new CDbCriteria();
        $criteria->alias = 't';
        $criteria->join = 'inner join  Task as tt on tt.id=t.task_id';
        $criteria->compare('t.status', 1);
        $criteria->compare('t.stage', '<>0');
        $criteria->compare('tt.task_group', $group);
        $criteria->compare('t.assign_id', $staff);
        $criteria->order = 't.stage asc';

        return new CActiveDataProvider('TaskProcess', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25
            )
        ));

    }

    public function byDailyStaff($group, $staff, &$date)
    {

        $today = Yii::app()->localTime->getLocalDateTimeNow();

//        $today = DateTime::createFromFormat('d-m-Y', '23-11-2016');

        $today->setTime(0, 0, 0);
        $date = $today->format('j M Y');

        $r1 = $today->format('Y-m-d 00:00:00');
        $r2 = $today->modify('+1 day')->format('Y-m-d 00:00:00');

        $criteria = new CDbCriteria();
        $criteria->alias = 't';
        $criteria->join = 'inner join  Task as tt on tt.id=t.task_id';
        $criteria->compare('t.status', 1);
        $criteria->compare('t.stage', '<>0');
        $criteria->compare('tt.task_group', $group);
        $criteria->compare('t.assign_id', $staff);
        $criteria->addCondition("t.assign_date>='$r1' and t.assign_date<'$r2'");
        $criteria->order = 't.stage asc';

        return new CActiveDataProvider('TaskProcess', array(
            'criteria' => $criteria,
            'pagination'=>false
        ));

    }
}