<?php

class DailyDepartmentSummary extends CFormModel
{

    public function getSummary()
    {
        $data = Yii::app()->db->createCommand()
            ->select('pf.department, tp.stage as stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Process p', 'tp.process_id=p.id')
            ->join('Profile pf', 'pf.user_id=tp.assign_id')
            ->where('p.status=1 and tp.status=1 and tp.stage<>0')
            ->group(array('pf.department', 'tp.stage'))
            ->order('pf.department desc')
            ->queryAll();

        $result = array();

        $department = Profile::itemAlias('Department');
        unset($department['']);
        foreach ($department as $k => $v) {
            $result[$k] = array(
                'department' => $k,
                'assigned' => 0,
                'wait_for_accept' => 0,
                'completed' => 0,
            );
        }

        foreach ($data as $d) {

            if (!isset($result[$d['department']])) {
                continue;
            }

            if ($d['stage'] == TaskProcess::STAGE_ASSIGNED) {
                $result[$d['department']]['wait_for_accept'] += $d['total'];
                $result[$d['department']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_COMPLETED) {
                $result[$d['department']]['completed'] += $d['total'];
                $result[$d['department']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_INPROGRESS) {
                $result[$d['department']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_REJECTED) {
                $result[$d['department']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_WAIRFORCONFIRM) {
                $result[$d['department']]['assigned'] += $d['total'];
            }
        }
        return array_values($result);
    }

    public function getByDepartment($department)
    {

        $data = Yii::app()->db->createCommand()
            ->select('tp.assign_id, tp.stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Process t', 'tp.process_id=t.id')
            ->join('Profile pf', 'tp.assign_id=pf.user_id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and pf.department=:department', array(
                ':department' => $department
            ))
            ->group(array('tp.assign_id', 'tp.stage'))
            ->order('tp.assign_id desc')
            ->queryAll();

        $result = array();
        $allStaff = Yii::app()->db->createCommand()
            ->select('p.user_id')
            ->from('Profile p')
            ->where('p.department=:department', array(
                ':department' => $department
            ))->queryAll();

        foreach ($allStaff as $s) {
            $result[$s['user_id']] = array(
                'assign_id' => $s['user_id'],
                'assigned' => 0,
                'completed' => 0,
                'wait_for_accept' => 0,
            );
        }

        foreach ($data as $d) {
            if (!isset($result[$d['assign_id']])) {
                continue;
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

    public function getByDepartment2($department)
    {

        $data = Yii::app()->db->createCommand()
            ->select('t.shop_id, tp.stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Process t', 'tp.process_id=t.id')
            ->join('Profile pf', 'tp.assign_id=pf.user_id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and pf.department=:department', array(
                ':department' => $department
            ))
            ->group(array('t.shop_id', 'tp.stage'))
            ->order('tp.assign_id desc')
            ->queryAll();

        $result = array();
        $shops = Shop::model()->findAll();

        foreach ($shops as $s) {
            $result[$s->id] = array(
                'shop_id' => $s->id,
                'assigned' => 0,
                'completed' => 0,
                'wait_for_accept' => 0,
            );
        }
        foreach ($data as $d) {
            if (!isset($result[$d['shop_id']])) {
                continue;
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

    public function getByDepartmentShop($department, $shop)
    {

        $data = Yii::app()->db->createCommand()
            ->select('tp.assign_id, tp.stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Process t', 'tp.process_id=t.id')
            ->join('Profile pf', 'tp.assign_id=pf.user_id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and pf.department=:department and t.shop_id=:shop_id', array(
                ':department' => $department,
                ':shop_id' => $shop
            ))
            ->group(array('tp.assign_id', 'tp.stage'))
            ->order('tp.assign_id desc')
            ->queryAll();

        $result = array();
        $allStaff = Yii::app()->db->createCommand()
            ->select('p.user_id')
            ->from('Profile p')
            ->leftJoin('UserShop us', 'us.user_id=p.user_id')
            ->where('us.shop_id=:shop', array(
                ':shop' => $shop
            ))->queryAll();

        foreach ($allStaff as $st) {
            $result[$st['user_id']] = array(
                'assign_id' => $st['user_id'],
                'assigned' => 0,
                'completed' => 0,
                'wait_for_accept' => 0,
            );
        }


        foreach ($data as $d) {
            if (!isset($result[$d['assign_id']])) {
                continue;
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

    public function getDailyDepartmentShop($department, $shop, &$date)
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
            ->join('Profile pf', 'tp.assign_id=pf.user_id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and pf.department=:department and t.shop_id=:shop_id and tp.assign_date>=:r1 and tp.assign_date<:r2', array(
                ':department' => $department,
                ':shop_id' => $shop,
                ':r1' => $r1,
                ':r2' => $r2
            ))
            ->group(array('tp.assign_id', 'tp.stage'))
            ->order('tp.assign_id desc')
            ->queryAll();

        $result = array();

        $allStaff = Yii::app()->db->createCommand()
            ->select('p.user_id')
            ->from('Profile p')
            ->leftJoin('UserShop us', 'us.user_id=p.user_id')
            ->where('us.shop_id=:shop', array(
                ':shop' => $shop
            ))->queryAll();

        foreach ($allStaff as $st) {
            $result[$st['user_id']] = array(
                'assign_id' => $st['user_id'],
                'assigned' => 0,
                'completed' => 0,
                'wait_for_accept' => 0,
            );
        }

        foreach ($data as $d) {
            if (!isset($result[$d['assign_id']])) {
                continue;
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

    public function getToday(&$date)
    {
        $today = Yii::app()->localTime->getLocalDateTimeNow();

//        $today = DateTime::createFromFormat('d-m-Y', '23-11-2016');

        $today->setTime(0, 0, 0);
        $date = $today->format('j M Y');

        $r1 = $today->format('Y-m-d 00:00:00');
        $r2 = $today->modify('+1 day')->format('Y-m-d 00:00:00');

        $data = Yii::app()->db->createCommand()
            ->select('pf.department, tp.stage as stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Process t', 'tp.process_id=t.id')
            ->join('Profile pf', 'pf.user_id=tp.assign_id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and tp.assign_date>=:r1 and tp.assign_date<:r2', array(
                ':r1' => $r1,
                ':r2' => $r2
            ))
            ->group(array('pf.department', 'tp.stage'))
            ->order('pf.department desc')
            ->queryAll();

        $result = array();
        $department = Profile::itemAlias('Department');
        unset($department['']);
        foreach ($department as $k => $v) {
            $result[$k] = array(
                'department' => $k,
                'assigned' => 0,
                'wait_for_accept' => 0,
                'completed' => 0,
            );
        }

        foreach ($data as $d) {

            if ($d['stage'] == TaskProcess::STAGE_ASSIGNED) {
                $result[$d['department']]['wait_for_accept'] += $d['total'];
                $result[$d['department']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_COMPLETED) {
                $result[$d['department']]['completed'] += $d['total'];
                $result[$d['department']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_INPROGRESS) {
                $result[$d['department']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_REJECTED) {
                $result[$d['department']]['assigned'] += $d['total'];
            } else if ($d['stage'] == TaskProcess::STAGE_WAIRFORCONFIRM) {
                $result[$d['department']]['assigned'] += $d['total'];
            }
        }

        return array_values($result);
    }

    public function getDailyByDepartment($department, &$date)
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
            ->join('Profile pf', 'tp.assign_id=pf.user_id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and pf.department=:department and tp.assign_date>=:r1 and tp.assign_date<:r2', array(
                ':department' => $department,
                ':r1' => $r1,
                ':r2' => $r2
            ))
            ->group(array('tp.assign_id', 'tp.stage'))
            ->order('tp.assign_id desc')
            ->queryAll();

        $result = array();

        $allStaff = Yii::app()->db->createCommand()
            ->select('p.user_id')
            ->from('Profile p')
            ->where('p.department=:department', array(
                ':department' => $department
            ))->queryAll();

        foreach ($allStaff as $s) {
            $result[$s['user_id']] = array(
                'assign_id' => $s['user_id'],
                'assigned' => 0,
                'completed' => 0,
                'wait_for_accept' => 0,
            );
        }

        foreach ($data as $d) {
            if (!isset($result[$d['assign_id']])) {
                continue;
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

    public function getDailyByDepartment2($department, &$date)
    {

        $today = Yii::app()->localTime->getLocalDateTimeNow();

//        $today = DateTime::createFromFormat('d-m-Y', '23-11-2016');

        $today->setTime(0, 0, 0);
        $date = $today->format('j M Y');

        $r1 = $today->format('Y-m-d 00:00:00');
        $r2 = $today->modify('+1 day')->format('Y-m-d 00:00:00');

        $data = Yii::app()->db->createCommand()
            ->select('t.shop_id, tp.stage, count(tp.id) as total')
            ->from('TaskProcess tp')
            ->join('Process t', 'tp.process_id=t.id')
            ->join('Profile pf', 'tp.assign_id=pf.user_id')
            ->where('t.status=1 and tp.status=1 and tp.stage<>0 and pf.department=:department and tp.assign_date>=:r1 and tp.assign_date<:r2', array(
                ':department' => $department,
                ':r1' => $r1,
                ':r2' => $r2
            ))
            ->group(array('t.shop_id', 'tp.stage'))
            ->order('tp.shop_id desc')
            ->queryAll();

        $result = array();

        $shops = Shop::model()->findAll();

        foreach ($shops as $s) {
            $result[$s->id] = array(
                'shop_id' => $s->id,
                'assigned' => 0,
                'completed' => 0,
                'wait_for_accept' => 0,
            );
        }

        foreach ($data as $d) {

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

    public function byStaff($department, $staff)
    {

        $criteria = new CDbCriteria();
        $criteria->alias = 't';
        $criteria->join = 'inner join Process as tt on tt.id=t.process_id inner join Profile pf on pf.user_id=t.assign_id';
        $criteria->compare('tt.status', 1);
        $criteria->compare('t.stage', '<>0');
        $criteria->compare('pf.department', $department);
        $criteria->compare('t.assign_id', $staff);
        $criteria->order = 't.stage asc';

        return new CActiveDataProvider('TaskProcess', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25
            )
        ));

    }

    public function byStaffDepartmentShop($shop, $department, $staff)
    {

        $criteria = new CDbCriteria();
        $criteria->alias = 't';
        $criteria->join = 'inner join Process as tt on tt.id=t.process_id inner join Profile pf on pf.user_id=t.assign_id';
        $criteria->compare('tt.status', 1);
        $criteria->compare('t.stage', '<>0');
        $criteria->compare('pf.department', $department);
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

    public function dailyStaffDepartmentShop($shop, $department, $staff, &$date)
    {

        $today = Yii::app()->localTime->getLocalDateTimeNow();

//        $today = DateTime::createFromFormat('d-m-Y', '23-11-2016');

        $today->setTime(0, 0, 0);
        $date = $today->format('j M Y');

        $r1 = $today->format('Y-m-d 00:00:00');
        $r2 = $today->modify('+1 day')->format('Y-m-d 00:00:00');

        $criteria = new CDbCriteria();
        $criteria->alias = 't';
        $criteria->join = 'inner join Process as tt on tt.id=t.process_id inner join Profile pf on pf.user_id=t.assign_id';
        $criteria->compare('tt.status', 1);
        $criteria->compare('t.stage', '<>0');
        $criteria->compare('pf.department', $department);
        $criteria->compare('tt.shop_id', $shop);
        $criteria->compare('t.assign_id', $staff);
        $criteria->addCondition("t.assign_date>='$r1' and t.assign_date<'$r2'");
        $criteria->order = 't.stage asc';

        return new CActiveDataProvider('TaskProcess', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25
            )
        ));

    }

    public function byDailyStaff($department, $staff, &$date)
    {

        $today = Yii::app()->localTime->getLocalDateTimeNow();

//        $today = DateTime::createFromFormat('d-m-Y', '23-11-2016');

        $today->setTime(0, 0, 0);
        $date = $today->format('j M Y');

        $r1 = $today->format('Y-m-d 00:00:00');
        $r2 = $today->modify('+1 day')->format('Y-m-d 00:00:00');

        $criteria = new CDbCriteria();
        $criteria->alias = 't';
        $criteria->join = 'inner join Task as tt on tt.id=t.task_id inner join Profile pf on pf.user_id=t.assign_id';
        $criteria->compare('t.status', 1);
        $criteria->compare('t.stage', '<>0');
        $criteria->compare('pf.department', $department);
        $criteria->compare('t.assign_id', $staff);
        $criteria->addCondition("t.assign_date>='$r1' and t.assign_date<'$r2'");
        $criteria->order = 't.stage asc';

        return new CActiveDataProvider('TaskProcess', array(
            'criteria' => $criteria,
            'pagination' => false
        ));

    }
}