<?php

class DailyShopSummary extends CFormModel
{

    public function getSummary()
    {
        $data = Yii::app()->db->createCommand()
            ->select('p.shop_id as shop_id, tp.stage as stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Process p', 'tp.process_id=p.id')
            ->where('p.status=1 and tp.status=1 and tp.stage<>0')
            ->group(array('p.shop_id', 'tp.stage'))
            ->order('p.shop_id desc')
            ->queryAll();

        $result = array();

        foreach ($data as $d) {

            if (!isset($result[$d['shop_id']])) {
                $result[$d['shop_id']] = array(
                    'shop_id' => $d['shop_id'],
                    'assigned' => 0,
                    'wait_for_accept' => 0,
                    'completed' => 0,
                );
            }

            if ($d['stage'] == TaskProcess::STAGE_ASSIGNED) {
                $result[$d['shop_id']]['wait_for_accept'] += $d['total'];
                $result[$d['shop_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_COMPLETED) {
                $result[$d['shop_id']]['completed'] += $d['total'];
                $result[$d['shop_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_INPROGRESS) {
                $result[$d['shop_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_REJECTED) {
                $result[$d['shop_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_WAIRFORCONFIRM) {
                $result[$d['shop_id']]['assigned'] += $d['total'];
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
            ->select('t.shop_id as shop_id, tp.stage as stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Process t', 'tp.process_id=t.id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and tp.assign_date>=:r1 and tp.assign_date<:r2', array(
                ':r1' => $r1,
                ':r2' => $r2
            ))
            ->group(array('t.shop_id', 'tp.stage'))
            ->order('t.shop_id desc')
            ->queryAll();

        $result = array();

        foreach ($data as $d) {

            if (!isset($result[$d['shop_id']])) {
                $result[$d['shop_id']] = array(
                    'shop_id' => $d['shop_id'],
                    'assigned' => 0,
                    'completed' => 0,
                    'wait_for_accept' => 0,

                );
            }

            if ($d['stage'] == TaskProcess::STAGE_ASSIGNED) {
                $result[$d['shop_id']]['wait_for_accept'] += $d['total'];
                $result[$d['shop_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_COMPLETED) {
                $result[$d['shop_id']]['completed'] += $d['total'];
                $result[$d['shop_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_INPROGRESS) {
                $result[$d['shop_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_REJECTED) {
                $result[$d['shop_id']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_WAIRFORCONFIRM) {
                $result[$d['shop_id']]['assigned'] += $d['total'];
            }
        }

        return array_values($result);
    }

    public function getDailyByShop($shop, &$date)
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
            ->join('Process t', 'tp.process_id=t.id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and t.shop_id=:shop and tp.assign_date>=:r1 and tp.assign_date<:r2', array(
                ':shop' => $shop,
                ':r1' => $r1,
                ':r2' => $r2
            ))
            ->group(array('tp.assign_id', 'tp.stage'))
            ->order('t.shop_id desc')
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

    public function getByShop($group)
    {

        $data = Yii::app()->db->createCommand()
            ->select('tp.assign_id, tp.stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Process t', 'tp.process_id=t.id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and t.shop_id=:shop', array(
                ':shop' => $group
            ))
            ->group(array('tp.assign_id', 'tp.stage'))
            ->order('t.shop_id desc')
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

    public function byStaff($shop, $staff)
    {

        $criteria = new CDbCriteria();
        $criteria->alias = 't';
        $criteria->join = 'inner join Process as tt on tt.id=t.process_id';
        $criteria->compare('tt.status',1);
        $criteria->compare('t.stage', '<>0');
        $criteria->compare('tt.shop_id', $shop);
        $criteria->compare('t.assign_id', $staff);
        $criteria->order = 't.stage asc';

        return new CActiveDataProvider('TaskProcess', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25
            )
        ));

    }

    public function byDailyStaff($shop, $staff, &$date)
    {

        $today = Yii::app()->localTime->getLocalDateTimeNow();

//        $today = DateTime::createFromFormat('d-m-Y', '23-11-2016');

        $today->setTime(0, 0, 0);
        $date = $today->format('j M Y');

        $r1 = $today->format('Y-m-d 00:00:00');
        $r2 = $today->modify('+1 day')->format('Y-m-d 00:00:00');

        $criteria = new CDbCriteria();
        $criteria->alias = 't';
        $criteria->join = 'inner join Process as tt on tt.id=t.process_id';
        $criteria->compare('tt.status',1);
        $criteria->compare('t.stage', '<>0');
        $criteria->compare('tt.shop_id', $shop);
        $criteria->compare('t.assign_id', $staff);
        $criteria->addCondition("t.assign_date>='$r1' and t.assign_date<'$r2'");
        $criteria->order = 't.stage asc';

        return new CActiveDataProvider('TaskProcess', array(
            'criteria' => $criteria,
            'pagination' => false
        ));

    }
}