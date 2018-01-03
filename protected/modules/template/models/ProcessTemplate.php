<?php

/**
 * This is the model class for table "ProcessTemplate".
 *
 * The followings are the available columns in table 'ProcessTemplate':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $shop_id
 * @property integer $supplier_id
 * @property integer $start_dayofweek
 * @property string $start_time
 * @property integer $is_auto_start
 * @property integer $progress
 * @property integer $stage
 * @property integer $status
 * @property integer $task_group
 * @property integer $create_by
 * @property integer $update_by
 *
 * The followings are the available model relations:
 * @property Shop $shop
 * @property Supplier $supplier
 * @property TaskProcessTemplate[] $taskProcessTemplates
 */
class ProcessTemplate extends CActiveRecord
{

    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 1;
    const AUTO_START = 1;
    const NOT_AUTO_START = 0;
    const DAYS_SUN = 1;
    const DAYS_MON = 2;
    const DAYS_TUE = 4;
    const DAYS_WED = 8;
    const DAYS_THU = 16;
    const DAYS_FRI = 32;
    const DAYS_SAT = 64;

    public $oldRecord;
    public $arr_start_dayofweek;
    public $arr_weeks;
    public $str_weeks;

    public static function mappingDayOfWeek($index)
    {
        $arr = array(
            self::DAYS_SUN,
            self::DAYS_MON,
            self::DAYS_TUE,
            self::DAYS_WED,
            self::DAYS_THU,
            self::DAYS_FRI,
            self::DAYS_SAT
        );

        return $arr[$index];
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ProcessTemplate';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('shop_id, supplier_id, name, task_group', 'required'),
            array('status', 'checkListTask', 'on' => 'update'),
            array('status', 'default',
                'value' => 0,
                'setOnEmpty' => false,
                'on' => 'insert'
            ),
            array('shop_id, supplier_id,start_dayofweek, is_auto_start, progress, stage, status, task_group', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 150),
            array('description', 'length', 'max' => 255),
            array('arr_weeks', 'type', 'type' => 'array', 'allowEmpty' => true),
            array('start_time, str_weeks', 'safe'),
            array('update_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'update'
            ),
            array('create_date,update_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'insert'
            ),
            array('arr_start_dayofweek', 'arrayOfDayOfWeek'),
            array('update_by', 'default',
                'value' => Yii::app()->user->id,
                'setOnEmpty' => false,
                'on' => 'update'
            ),
            array('create_by, update_by', 'default',
                'value' => Yii::app()->user->id,
                'setOnEmpty' => false,
                'on' => 'insert'
            ),
            array('id, name, shop_id, supplier_id, stage, status', 'safe', 'on' => 'search'),
        );
    }

    public function checkListTask()
    {
        $totalTask = Yii::app()->db->createCommand('SELECT count(`id`) FROM `TaskProcessTemplate` WHERE `process_id`=' . $this->id)->queryScalar();
        if ($this->status == 1 && $totalTask == 0) {
            $this->addError('status', 'Please define list task of this template before make it active');
        }
    }

    public function arrayOfDayOfWeek($attributeName, $params)
    {

        if ($this->is_auto_start && empty($this->$attributeName)) {
            $this->addError($attributeName, "Day of week can not be blank.");
            $this->arr_start_dayofweek = 0;
            return false;
        }

        if ($this->is_auto_start && !is_array($this->$attributeName)) {
            $this->addError($attributeName, "Day of week is not valid.");
            $this->arr_start_dayofweek = 0;
            return false;
        }

        return true;
    }

    protected function beforeSave()
    {

        if (!empty($this->arr_start_dayofweek)) {
            $bit = 0;
            foreach ($this->arr_start_dayofweek as $day) {
                $bit |= $day;
            }
            $this->start_dayofweek = $bit;
        } else {
            $this->start_dayofweek = 0;
        }

        return parent::beforeSave();
    }

    protected function afterFind()
    {


        $this->arr_start_dayofweek = array();

        if ((bool)($this->start_dayofweek & self::DAYS_SUN)) {
            $this->arr_start_dayofweek[] = self::DAYS_SUN;
        }
        if ((bool)($this->start_dayofweek & self::DAYS_MON)) {
            $this->arr_start_dayofweek[] = self::DAYS_MON;
        }
        if ((bool)($this->start_dayofweek & self::DAYS_TUE)) {
            $this->arr_start_dayofweek[] = self::DAYS_TUE;
        }
        if ((bool)($this->start_dayofweek & self::DAYS_WED)) {
            $this->arr_start_dayofweek[] = self::DAYS_WED;
        }
        if ((bool)($this->start_dayofweek & self::DAYS_THU)) {
            $this->arr_start_dayofweek[] = self::DAYS_THU;
        }
        if ((bool)($this->start_dayofweek & self::DAYS_FRI)) {
            $this->arr_start_dayofweek[] = self::DAYS_FRI;
        }
        if ((bool)($this->start_dayofweek & self::DAYS_SAT)) {
            $this->arr_start_dayofweek[] = self::DAYS_SAT;
        }

        if (count($this->arr_start_dayofweek) <= 0) {
            $this->arr_start_dayofweek = 0;
        }

        $criteria = new CDbCriteria;
        $criteria->compare('process_id', $this->id);
        $criteria->order = 'week asc';
        $schedules = TemplateSchedule::model()->findAll($criteria);

        if (!empty($schedules)) {

            foreach ($schedules as $sch) {
                $this->arr_weeks[] = $sch->week;
            }

            $this->str_weeks = implode(',', $this->arr_weeks);
        } else {
            $this->arr_weeks = '';
        }

        $this->oldRecord = clone $this;

        return parent::afterFind();
    }

    public function beforeValidate()
    {


        $this->arr_weeks = explode(',', $this->str_weeks);

        if (parent::beforeValidate()) {

            if ($this->is_auto_start == 1) {
                $validator = CValidator::createValidator('required', $this, 'start_time');
                $this->getValidatorList()->insertAt(0, $validator);
            }

            return true;
        }

        return false;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
            'supplier' => array(self::BELONGS_TO, 'Supplier', 'supplier_id'),
            'taskProcessTemplates' => array(self::HAS_MANY, 'TaskProcessTemplate', 'process_id'),
            'taskGroup' => array(self::BELONGS_TO, 'TaskGroup', 'task_group'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'shop_id' => 'Shop',
            'supplier_id' => 'Supplier',
            'start_dayofweek' => 'Start day',
            'start_time' => 'Start Time',
            'is_auto_start' => 'Is Auto Start',
            'progress' => 'Progress',
            'stage' => 'Stage',
            'status' => 'Status',
            'task_group' => 'Task Group'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('shop_id', $this->shop_id);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('stage', $this->stage);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'create_date desc'
            ),
            'pagination' => array(
                'pageSize' => 25
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ProcessTemplate the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function itemAlias($type, $code = NULL)
    {
        $_items = array(
            'Status' => array(
                self::STATUS_DISABLE => 'Disable',
                self::STATUS_ACTIVE => 'Active'
            ),
            'AutoStart' => array(
                self::NOT_AUTO_START => 'No',
                self::AUTO_START => 'Yes'
            ),
            'DayOfWeek' => array(
                self::DAYS_SUN => 'Sunday',
                self::DAYS_MON => 'Monday',
                self::DAYS_TUE => 'Tuesday',
                self::DAYS_WED => 'Wednesday',
                self::DAYS_THU => 'Thursday',
                self::DAYS_FRI => 'Friday',
                self::DAYS_SAT => 'Saturday'
            )
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    const KEY_WEEK_OF_YEAR = "weekofyear:%s";
    const KEY_WEEK_OF_YEAR_EXPIRED = 0;

    public static function weeksInYears()
    {

        $year = date('Y');

        $key = sprintf(self::KEY_WEEK_OF_YEAR, $year);
        $result = Yii::app()->cache->get($key);

        if (empty($result)) {

            $result = array();

            $firstDateOfYear = new DateTime("first day of $year-01");
            $lastDateOfYear = new DateTime("last day of $year-12");
            $firstDateOfYearFormatted = $firstDateOfYear->format('Y-m-d');
            $nextWeek = new DateTime("$firstDateOfYearFormatted");

            $pass = false;

            while ($nextWeek <= $lastDateOfYear) {

                $w = (int)Yii::app()->dateFormatter->format('w', $nextWeek->format('Y-m-d'));

                //if last week of previous year
                if ($w > 1 && !$pass) {
                    $y = clone $nextWeek;
                    $y->modify('previous year');
                    $year = $y->format('Y');
                    $pair = ProcessTemplate::getStartAndEndDate($w, $year);
                    $pass = true;
                    $result[] = array(
                        'id' => $w,
                        'text' => self::formatOrdinal($w),
                        'pair' => $pair,
                        'year' => $year,
                        'date' => date('d M Y', strtotime($pair[0])) . ' - ' . date('d M Y', strtotime($pair[1]))
                    );
                } else {
                    $pass = true;
                    $pair = ProcessTemplate::getStartAndEndDate($w, $nextWeek->format('Y'));
                    $result[] = array(
                        'id' => $w,
                        'text' => self::formatOrdinal($w),
                        'pair' => $pair,
                        'date' => date('d M Y', strtotime($pair[0])) . ' - ' . date('d M Y', strtotime($pair[1]))
                    );
                }


                $nextWeek->modify("next Thursday");
            }

            $lastWeekRange = $result[count($result) - 1];
            $endDate = new DateTime($lastWeekRange['pair'][1]);
            if ($endDate < $lastDateOfYear) {

                $w = Yii::app()->dateFormatter->format('w', $nextWeek->format('Y-m-d'));
                $year = $nextWeek->format('Y');
                $pair = ProcessTemplate::getStartAndEndDate($w, $year);
                $result[] = array(
                    'id' => $w,
                    'text' => self::formatOrdinal($w),
                    'pair' => $pair,
                    'year' => $year,
                    'date' => date('d M Y', strtotime($pair[0])) . ' - ' . date('d M Y', strtotime($pair[1]))
                );
            }

            if (count($result) > 0) {
                Yii::app()->cache->set($key, $result, self::KEY_WEEK_OF_YEAR_EXPIRED);
            }

        }

        return $result;
    }


    public static function weeksInYearsOld()
    {
        $year = date('Y');
        $key = sprintf(self::KEY_WEEK_OF_YEAR, $year);
        $result = Yii::app()->cache->get($key);

        if ($result === false) {

            $result = array();

            for ($w = 0; ; $w++) {
                $pair = ProcessTemplate::getStartAndEndDate($w, $year);
                if (Yii::app()->dateFormatter->format('w', $pair[0]) == $w + 1) {

                    $sw = $w + 1;
                    $result[] = array(
                        'id' => $sw,
                        'text' => self::formatOrdinal($sw),
                        'date' => date('d M Y', strtotime($pair[0])) . ' - ' . date('d M Y', strtotime($pair[1]))
                    );
                } else {
                    break;
                }
            }

            Yii::app()->cache->set($key, $result, self::KEY_WEEK_OF_YEAR_EXPIRED);
        }

        return $result;
    }

    public static function formatOrdinal($value)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if (($value % 100) >= 11 && ($value % 100) <= 13) {
            return $value . 'th';
        } else {
            return $value . $ends[$value % 10];
        }
    }

    public static function getStartAndEndDateOld($week, $year)
    {

        $time = strtotime("1 January $year", time());
        $day = date('w', $time);
        $time += ((7 * $week) + 1 - $day) * 24 * 3600;
        $result[0] = date('Y-m-d', $time);
        $time += 6 * 24 * 3600;
        $result[1] = date('Y-m-d', $time);

        return $result;
    }

    public static function getStartAndEndDate($week, $year)
    {
        $dto = new DateTime();
        $ret[0] = $dto->setISODate($year, $week)->format('Y-m-d');
        $ret[1] = $dto->modify('+6 days')->format('Y-m-d');
        return $ret;
    }

    protected function afterSave()
    {

        if (!$this->isNewRecord) {

            if (is_array($this->arr_weeks)) {

                //delete all schedule
                $db = Yii::app()->db;
                $db->createCommand("DELETE FROM TemplateSchedule WHERE process_id=" . $this->id)->execute();

                //insert new schedule
                foreach ($this->arr_weeks as $w) {

                    if ($w > 0) {
                        $schedue = new TemplateSchedule;
                        $schedue->process_id = $this->id;
                        $schedue->week = intval($w);

                        if (!$schedue->save()) {
                            throw new CDbException('Save template schedule failed');
                        }
                    }
                }
            }

            //check dirty
            $d = date('Y-m-d');
            $timeNew = sprintf("%s %s", $d, $this->start_time);
            $timeOld = sprintf("%s %s", $d, $this->oldRecord->start_time);
            if ($timeNew != $timeOld ||
                $this->start_dayofweek != $this->oldRecord->start_dayofweek ||
                $this->str_weeks != $this->oldRecord->str_weeks
            ) {
                CloneTask::model()->deleteAll('template_id=:template_id', array(':template_id' => $this->id));
            }
        }
        return parent::afterSave();
    }

    public static function getOddEvenWeeks($data)
    {
        $odd = array();
        $even = array();
        foreach ($data as $item) {
            if ($item['id'] % 2 > 0) {
                $odd[] = $item['id'];
            } else {
                $even[] = $item['id'];
            }
        }

        return array(
            'odd' => $odd,
            'even' => $even
        );
    }
}
