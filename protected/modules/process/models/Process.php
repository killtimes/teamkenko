<?php

/**
 * This is the model class for table "Process".
 *
 * The followings are the available columns in table 'Process':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $shop_id
 * @property integer $supplier_id
 * @property string $start_date
 * @property string $complete_date
 * @property integer $progress
 * @property integer $stage
 * @property integer $status
 * @property integer $task_group
 * @property string $create_date
 * @property string $update_date
 *
 * The followings are the available model relations:
 * @property Shop $shop
 * @property Supplier $supplier
 * @property TaskProcess[] $taskProcesses
 */
class Process extends CActiveRecord
{

    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;
    const STAGE_NOTSET = 0;
    const STAGE_STARTED = 1;
    const STAGE_INPROGRESS = 2;
    const STAGE_DONE = 3;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Process';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $rules = array(
            array('shop_id, supplier_id, name, task_group', 'required'),
//            array('supplier_id', 'unique', 'criteria' => array(
//                    'condition' => '`shop_id`=:shop_id',
//                    'params' => array(
//                        ':shop_id' => $this->shop_id
//                    ),
//                )
//            ),
            array('shop_id, supplier_id, progress, stage, status, task_group', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 150),
            array('description', 'length', 'max' => 255),
            array('start_date, update_date', 'safe'),
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
            array('start_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'startProcess'
            ),
            array('complete_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'completeProcess'
            ),
            array('progress', 'default',
                'value' => 100,
                'setOnEmpty' => false,
                'on' => 'completeProcess'
            ),
            array('id, name, shop_id, supplier_id', 'safe', 'on' => 'search'),
        );

        if (!(Yii::app() instanceof CConsoleApplication)) {
            $rules[] = array('update_by', 'default',
                'value' => Yii::app()->user->id,
                'setOnEmpty' => false,
                'on' => 'update'
            );
            $rules[] = array('create_by, update_by', 'default',
                'value' => Yii::app()->user->id,
                'setOnEmpty' => false,
                'on' => 'insert'
            );
        }

        return $rules;
    }

    public function afterSave()
    {

        if ($this->scenario == 'startProcess') {

            $this->assignFirstTasks();
        }
        parent::afterSave();
    }

    protected function assignFirstTasks()
    {

        if (!empty($this->taskProcesses)) {

            $firstSingleTask = null;
            $firstConcurrentTasks = array();
            foreach ($this->taskProcesses as $task) {

                if ($task->isSingleTask()) {

                    if (empty($firstConcurrentTasks)) {
                        $firstSingleTask = $task;
                    }

                    break;
                } else if ($task->isConcurrentTask()) {
                    $firstConcurrentTasks[] = $task;
                }
            }

            if ($firstSingleTask != null) {
                $firstSingleTask->stage = TaskProcess::STAGE_ASSIGNED;
                $firstSingleTask->scenario = 'assign';
                $firstSingleTask->save();
            } else if (count($firstConcurrentTasks) > 0) {
                foreach ($firstConcurrentTasks as $task) {
                    $task->stage = TaskProcess::STAGE_ASSIGNED;
                    $task->scenario = 'assign';
                    $task->save();
                }
            }
            //@todo notify staff
        }
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
            'taskProcesses' => array(self::HAS_MANY, 'TaskProcess', 'process_id', 'order' => 'sort_order ASC'),
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
            'start_date' => 'Start Date',
            'progress' => 'Progress',
            'stage' => 'Stage',
            'status' => 'Status',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'task_group' => 'Task Group'
        );
    }

//    public function beforeValidate() {
//
//        if (parent::beforeValidate()) {
//
//            $validator = CValidator::createValidator('unique', $this, 'supplier_id', array(
//                        'criteria' => array(
//                            'condition' => '`shop_id`=:shop_id',
//                            'params' => array(
//                                ':shop_id' => $this->shop_id
//                            )
//                        )
//            ));
//
//            $validator->message = "Process for this shop and supplier is already defined";
//            $this->getValidatorList()->insertAt(0, $validator);
//
//            return true;
//        }
//
//        return false;
//    }

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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('shop_id', $this->shop_id);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('status', self::STATUS_ACTIVE);

        if (is_null($this->stage) || $this->stage == '') {
            $criteria->compare('stage', '<>' . Process::STAGE_DONE);
        } else {
            $criteria->compare('stage', $this->stage);
        }

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

    public function searchCompletedProcess()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('shop_id', $this->shop_id);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('stage', Process::STAGE_DONE);
        $criteria->compare('status', Process::STATUS_ACTIVE);


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

    public function searchDeletedProcess()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('shop_id', $this->shop_id);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('status', Process::STATUS_DELETED);

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
     * @return Process the static model class
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
            'State' => array(
                self::STAGE_NOTSET => 'Not set',
                self::STAGE_STARTED => 'Started',
                self::STAGE_INPROGRESS => 'In progress',
                self::STAGE_DONE => 'Completed',
            ),
            'StageNotCompleted' => array(
                self::STAGE_NOTSET => 'Not set',
                self::STAGE_STARTED => 'Started',
                self::STAGE_INPROGRESS => 'In progress',
            )
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    public static function stageAlias($code)
    {
        switch ($code) {
            case Process::STAGE_NOTSET:
                return TbHtml::labelTb(Process::itemAlias("State", $code), array("color" => TbHtml::LABEL_COLOR_DEFAULT));
            case Process::STAGE_INPROGRESS:
                return TbHtml::labelTb(Process::itemAlias("State", $code), array("color" => TbHtml::LABEL_COLOR_INFO));
            case Process::STAGE_STARTED:
                return TbHtml::labelTb(Process::itemAlias("State", $code), array("color" => TbHtml::LABEL_COLOR_WARNING));
            case Process::STAGE_DONE:
                return TbHtml::labelTb(Process::itemAlias("State", $code), array("color" => TbHtml::LABEL_COLOR_SUCCESS));
        }
    }


    public function delete()
    {

        if ($this->stage == self::STAGE_DONE) {
            $this->addError('general', 'This job cannot be deleted');
            return false;
        }

        $this->status = self::STATUS_DELETED;
        $this->update(array('status'));

        //delete task
        $this->getDbConnection()->createCommand()
            ->update('TaskProcess', array(
                'status' => TaskProcess::STATUS_DELETE
            ), 'process_id=:process_id', array(
                ':process_id' => $this->id
            ));

        return true;
    }

    public function recover()
    {

        if ($this->status == self::STATUS_DELETED) {

            $this->status = self::STATUS_ACTIVE;
            $this->update(array('status'));

            //delete task
            $this->getDbConnection()->createCommand()
                ->update('TaskProcess', array(
                    'status' => TaskProcess::STATUS_ACTIVE
                ), 'process_id=:process_id', array(
                    ':process_id' => $this->id
                ));

            return true;
        }

        return false;
    }


    const KEY_JOB_STATT = 'job_statistic:id:%s';
    const KEY_JOB_STATT_EXPIRED = 0;

    public function getCachedStatistic($ignoreCache = false)
    {


        $key = sprintf(self::KEY_JOB_STATT, $this->id);

        $statt = Yii::app()->cache->get($key);

        if ($statt === false || $ignoreCache) {

            //hit db
            $statt = $this->getStatistic();

            //set cache
            Yii::app()->cache->set($key, $statt, self::KEY_JOB_STATT_EXPIRED);
        }

        return $statt;
    }

    //get total task completed/inprogress/wait for accept/totaltask
    public function getStatistic()
    {

        $tasks = $this->taskProcesses;

        $stt = array(
            'total' => count($tasks),
            'complete' => 0,
            'waitfor_accept' => 0,
            'waitfor_staff_id' => array(),
            'in_progress' => 0,
            'in_progress_staff_id' => array(),
            'reject' => 0,
            'reject_by_staff_id' => array(),
            'waitfor_confirm' => 0,
            'waitfor_confirm_staff_id' => array()
        );

        if ($stt['total'] <= 0) {
            return $stt;
        }

        foreach ($tasks as $t) {
            if ($t->stage == TaskProcess::STAGE_COMPLETED) {
                $stt['complete']++;
            } else if ($t->stage == TaskProcess::STAGE_INPROGRESS) {
                $stt['in_progress']++;
                $stt['in_progress_staff_id'][] = $t->assign_id;
            } else if ($t->stage == TaskProcess::STAGE_ASSIGNED) {
                $stt['waitfor_accept']++;
                $stt['waitfor_staff_id'][] = $t->assign_id;
            } else if ($t->stage == TaskProcess::STAGE_REJECTED) {
                $stt['reject']++;
                $stt['reject_by_staff_id'][] = $t->assign_id;
            } else if ($t->stage == TaskProcess::STAGE_WAIRFORCONFIRM) {
                $stt['waitfor_confirm']++;
                $stt['waitfor_confirm_staff_id'][] = $t->assign_id;
            }
        }

        return $stt;
    }

    public static function parseStaffName($arrStaffId)
    {

        if (!is_array($arrStaffId) || empty($arrStaffId)) {
            return '';
        }

        $arrName = array();
        foreach ($arrStaffId as $id) {
            $arrName[] = Profile::model()->getById($id)->getFullName();
        }

        return implode(', ', $arrName);
    }

    public function updateProgress($allTasks = null, $tid = null)
    {
        if ($allTasks == null) {
            $allTasks = $this->taskProcesses;
        }

        $totalHours = 0;
        $hoursComplete = 0;

        foreach ($allTasks as $task) {

            $totalHours += $task->duration;

            if ($task->stage == TaskProcess::STAGE_COMPLETED || $tid == $task->id) {
                $hoursComplete += $task->duration;
            }

        }

        $progress = floor(($hoursComplete) / $totalHours * 100);

        $this->stage = self::STAGE_INPROGRESS;
        $this->progress = $progress;

        if (!$this->save()) {
            throw  new CDbException('Update progress failed');
        }
    }

    public function complete()
    {
        $this->scenario = 'completeProcess';
        $this->stage = Process::STAGE_DONE;
        if (!$this->save()) {
            throw new CDbException('Complete process failed.');
        }

        return true;
    }

}
