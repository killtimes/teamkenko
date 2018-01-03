<?php

/**
 * This is the model class for table "TaskProcess".
 *
 * The followings are the available columns in table 'TaskProcess':
 * @property integer $id
 * @property integer $process_id
 * @property integer $task_id
 * @property integer $assign_id
 * @property integer $supplier_id
 * @property integer $shop_id
 * @property string $description
 * @property integer $priority
 * @property integer $sort_order
 * @property integer $duration
 * @property integer $stage
 * @property string $create_date
 * @property string $update_date
 * @property string $assign_date
 * @property string $due_date
 * @property integer $alert_condition
 * @property integer $alert_enable
 * @property bool $is_att_mandatory
 * @property bool $can_not_reject
 * @property integer $task_type
 * @property string $send_mail_recipient_extra
 *
 * The followings are the available model relations:
 * @property Process $process
 * @property Task $task
 * @property User $assign
 */
class TaskProcess extends CActiveRecord
{
    const STATUS_DELETE = -1;
    const STATUS_ACTIVE = 1;
    //priority
    const PRIORITY_NORMAL = 0;
    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;
    //stage
    const STAGE_NOTSET = 0;
    const STAGE_ASSIGNED = 1;
    const STAGE_REJECTED = 2;
    const STAGE_INPROGRESS = 3;
    const STAGE_WAIRFORCONFIRM = 4;
    const STAGE_COMPLETED = 5;
    //KEY CACHE 
    const CKEY_TOTAL_REQUEST = 'total_tasks_request:%s';
    const CKEY_TOTAL_DUETODAY = 'total_tasks_duetoday:%s';
    const CKEY_TOTAL_DUETOMORROW = 'total_tasks_duetomorrow:%s';
    const CKEY_TOTAL_DUEOVER2DAYS = 'total_tasks_dueover2days:%s';
    const CKEY_TOTAL_OVERDUE = 'total_tasks_overdue:%s';
    const CKEY_TOTAL_WAITFORACCEPT = 'total_tasks_waitforaccept:%s';
    const CKEY_TOTAL_COMPLETED = 'total_tasks_completed:%s';
    const CKEY_TOTAL_EXPIRED = 0;

    //old instance
    public $oldRecord;
    public $customTaskName;

    public $alert_recipients;
    public $alert_conditions;
    public $send_mail_recipients;

    public $reason;

    public $process_name;

    //cache total task by stage
    CONST CKEY_TOTAL_TASK_BYSTAGE = 'total_tasks_bystage:%s';
    CONST CKEY_TOTAL_TASK_BYSTAGE_EXPIRED = 0;

    public function afterFind()
    {
        //for detect dirty field
        $this->oldRecord = clone $this;

        $task = Task::model()->getById($this->task_id);

        $this->customTaskName = $task->name;

        $this->alert_conditions = array();

        if ((bool)($this->alert_condition & TaskProcessTemplate::ALERT_COND_NOT_ACCEPT)) {
            $this->alert_conditions[] = TaskProcessTemplate::ALERT_COND_NOT_ACCEPT;
        }

        if ((bool)($this->alert_condition & TaskProcessTemplate::ALERT_COND_OVER_DUE)) {
            $this->alert_conditions[] = TaskProcessTemplate::ALERT_COND_OVER_DUE;
        }

        if ((bool)($this->alert_condition & TaskProcessTemplate::ALERT_COND_REASSIGNED)) {
            $this->alert_conditions[] = TaskProcessTemplate::ALERT_COND_REASSIGNED;
        }

        if ((bool)($this->alert_condition & TaskProcessTemplate::ALERT_COND_REJECTED)) {
            $this->alert_conditions[] = TaskProcessTemplate::ALERT_COND_REJECTED;
        }

//        if ($this->alert_recipient > 0) {
//            $this->alert_recipients = $this->alert_recipient;
//        }

        return parent::afterFind();
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'TaskProcess';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('assign_id, priority, duration', 'required'),
            array('task_id', 'required', 'on' => 'insert,update'),
            array('process_id', 'required', 'on' => 'insert,update'),
            array('process_id, task_id, assign_id, priority, stage, shop_id, supplier_id', 'numerical', 'integerOnly' => true),
            array('duration', 'numerical', 'integerOnly' => true, 'min' => 1),
            array('description', 'length', 'max' => 7000),
            array('update_date', 'safe'),
            array('sort_order', 'unsafe'),
            array('update_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'update,updateTodo'
            ),
            array('create_date,update_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'insert,insertTodo'
            ),
            array('update_by', 'default',
                'value' => Yii::app()->user->id,
                'setOnEmpty' => false,
                'on' => 'update,updateTodo'
            ),
            array('create_by, update_by', 'default',
                'value' => Yii::app()->user->id,
                'setOnEmpty' => false,
                'on' => 'insert,insertTodo'
            ),
            array('assign_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'assign, insertTodo'
            ),
            array('accept_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'accept'
            ),
            array('reject_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'reject'
            ),
            array('complete_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'complete'
            ),
            array('process_id', 'safe', 'on' => 'insertTodo,updateTodo'),
            array('customTaskName', 'required', 'on' => 'insertTodo,updateTodo'),
            array('customTaskName', 'length', 'max' => 150, 'on' => 'insertTodo,updateTodo'),
            array('task_id', 'safe', 'on' => 'insertTodo,updateTodo'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, process_id, task_id, assign_id', 'safe', 'on' => 'search'),
            array('alert_enable', 'in', 'range' => array(0, 1)),
            array('alert_enable', 'validateAlertConfig', 'on' => 'insert,update,insertTodo,updateTodo'),
            array('alert_condition', 'numerical', 'integerOnly' => true),
            array('alert_enable, alert_recipients, alert_conditions,send_mail_recipients,send_mail_recipient_extra', 'safe'),
            array('reason', 'length', 'max' => 100),
            array('reason', 'required', 'on' => 'reject'),
            array('reason', 'filter', 'filter' => 'trim'),
            array('is_att_mandatory,can_not_reject', 'in', 'range' => array(0, 1)),
            array('task_type', 'in', 'range' => array(TaskProcessTemplate::TASK_TYPE_SINGLE, TaskProcessTemplate::TASK_TYPE_CONCURRENT))
        );
    }

    public function validateAlertConfig()
    {

        if (!empty($this->alert_recipients) && is_string($this->alert_recipients)) {
            $this->alert_recipients = explode(',', $this->alert_recipients);
        }

        if (!empty($this->send_mail_recipients)) {
            $this->send_mail_recipients = explode(',', $this->send_mail_recipients);
        }

        if (!empty($this->send_mail_recipient_extra)) {
            $arrayEmail = explode(',', $this->send_mail_recipient_extra);
            $validator = new CEmailValidator();
            $validEmails = array();
            foreach ($arrayEmail as $email) {
                if ($validator->validateValue($email)) {
                    $validEmails[] = $email;
                }
            }

            $this->send_mail_recipient_extra = implode(',', $validEmails);
        }

        if (!empty($this->alert_enable) && $this->alert_enable == 1) {

            $error = false;

            //validate condition, at least 1 condition
            if (!is_array($this->alert_conditions) || count($this->alert_conditions) == 0) {
                $this->addError('alert_conditions', 'Please specify a condition for task alert');
                $error = true;
            }

            if (!is_array($this->alert_recipients) || count($this->alert_recipients) == 0) {
                $this->addError('alert_recipients', 'Please specify recipient for task alert');
                $error = true;
            }

            if (!$error) {

                $bitCondition = 0;
                foreach ($this->alert_conditions as $item) {
                    $bitCondition |= $item;
                }

                $this->alert_condition = $bitCondition;

//                if (count($this->alert_recipients) == 1) {
//                    $this->alert_recipient = $this->alert_recipients[0];
//                } else {
//                    $this->alert_recipient = null;
//                }
            }

            return $error;
        }

        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'process' => array(self::BELONGS_TO, 'Process', 'process_id'),
            'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
            'assign' => array(self::BELONGS_TO, 'User', 'assign_id'),
            'createBy' => array(self::BELONGS_TO, 'User', 'create_by'),
            'updateBy' => array(self::BELONGS_TO, 'User', 'update_by')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'process_id' => 'Process',
            'task_id' => 'Task',
            'assign_id' => 'Assign',
            'description' => 'Description',
            'priority' => 'Priority',
            'sort_order' => 'Sort Order',
            'duration' => 'Duration',
            'stage' => 'Stage',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'shop_id' => 'Shop',
            'supplier_id' => 'Supplier',
            'customTaskName' => 'Task name'
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
        $criteria->compare('process_id', $this->process_id);
        $criteria->compare('task_id', $this->task_id);
        $criteria->compare('assign_id', $this->assign_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'sort_order asc'
            ),
            'pagination' => false
        ));
    }

    public function beforeSave()
    {

        if ($this->stage == self::STAGE_REJECTED && $this->scenario == 'updateTodo' && $this->oldRecord->assign_id != $this->assign_id) {
            $this->stage = self::STAGE_ASSIGNED;
            $this->assign_date = new CDbExpression('NOW()');
            $this->due_date = new CDbExpression('NOW() + INTERVAL ' . $this->duration . ' HOUR');
        }

        if ($this->isNewRecord && $this->scenario != 'insertTodo') {
            $maxOrder = Yii::app()->db->createCommand('SELECT MAX(`sort_order`) AS `max_order` FROM `TaskProcess` WHERE `process_id`=' . $this->process_id)->queryScalar();
            $this->sort_order = $maxOrder + 1;
        }

        if ($this->scenario == 'assign' || $this->scenario == 'insertTodo') {
            $this->due_date = new CDbExpression('NOW() + INTERVAL ' . $this->duration . ' HOUR');
        }

        if (($this->scenario == 'update' || $this->scenario == 'updateTodo') &&
            ($this->oldRecord->assign_id != $this->assign_id || $this->oldRecord->duration != $this->duration) && $this->stage == self::STAGE_ASSIGNED
        ) {

            $this->assign_date = new CDbExpression('NOW()');
            $this->due_date = new CDbExpression('NOW() + INTERVAL ' . $this->duration . ' HOUR');
        }

        if ($this->scenario == 'insertTodo') {
            $this->stage = TaskProcess::STAGE_ASSIGNED;
        }

        return parent::beforeSave();
    }

    protected function updateAlertRecipients()
    {
        if (is_array($this->alert_recipients) && count($this->alert_recipients) > 0) {

            $rows = array();

            foreach ($this->alert_recipients as $userId) {

                if (is_array($this->send_mail_recipients) and in_array($userId, $this->send_mail_recipients)) {
                    $rows[] = array(
                        'task_id' => $this->id,
                        'user_id' => $userId,
                        'send_mail' => 1
                    );
                } else {
                    $rows[] = array(
                        'task_id' => $this->id,
                        'user_id' => $userId,
                        'send_mail' => 0

                    );
                }
            }


            if (count($rows) > 0) {

                $cmdDelete = $this->dbConnection->createCommand('DELETE FROM AlertRecipientConfig WHERE task_id=:task_id');
                $cmdDelete->bindValue(':task_id', $this->id);
                $cmdDelete->execute();

                $cmd = $this->dbConnection->schema->getCommandBuilder()->createMultipleInsertCommand($this->dbConnection->schema->getTable('AlertRecipientConfig'), $rows);
                $cmd->execute();
            }
        }
    }

    public function afterSave()
    {
        if ($this->stage == self::STAGE_ASSIGNED &&
            ($this->scenario == 'assign' || $this->scenario == 'insertTodo' || (($this->scenario == 'update' || $this->scenario == 'updateTodo') && $this->oldRecord->assign_id != $this->assign_id))
        ) {
            $this->clearCacheTotal();
            $activity = new TaskActivity();
            $activity->task_id = $this->id;
            $activity->action_type = TaskActivity::ACTION_TYPE_ASSIGN;
            $activity->action_source = Yii::app()->user->id;
            $activity->action_target = $this->assign_id;
            $activity->save();
            if ($this->process_id > 0) {
                $this->process->getCachedStatistic(true);
            }
        } else if ($this->scenario == 'accept') {
            $this->clearCacheTotal();
            $activity = new TaskActivity();
            $activity->task_id = $this->id;
            $activity->action_type = TaskActivity::ACTION_TYPE_ACCEPT;
            $activity->action_source = Yii::app()->user->id;
            $activity->save();
            if ($this->process_id > 0) {
                $this->process->getCachedStatistic(true);
            }
        } else if ($this->scenario == 'reject') {
            $this->clearCacheTotal();
            $activity = new TaskActivity();
            $activity->task_id = $this->id;
            $activity->action_type = TaskActivity::ACTION_TYPE_REJECT;
            $activity->action_source = Yii::app()->user->id;
            $activity->save();
            if ($this->process_id > 0) {
                $this->process->getCachedStatistic(true);
            }

            $activity = new TaskActivity();
            $activity->task_id = $this->id;
            $activity->action_type = TaskActivity::ACTION_TYPE_ADDMESSAGE;
            $activity->action_message = $this->reason;
            $activity->action_source = Yii::app()->user->id;
            $activity->save();
        } else if ($this->scenario == 'complete') {
            $this->clearCacheTotal();
            $activity = new TaskActivity();
            $activity->task_id = $this->id;
            $activity->action_type = TaskActivity::ACTION_TYPE_COMPLETE;
            $activity->action_source = Yii::app()->user->id;
            $activity->save();
            if ($this->process_id > 0) {
                $this->process->getCachedStatistic(true);
            }
        } else if ($this->scenario == 'waitConfirm') {
            $this->clearCacheTotal();
            $activity = new TaskActivity();
            $activity->task_id = $this->id;
            $activity->action_type = TaskActivity::ACTION_TYPE_COMPLETE_AND_WAIT;
            $activity->action_source = Yii::app()->user->id;
            $activity->save();
            if ($this->process_id > 0) {
                $this->process->getCachedStatistic(true);
            }
        }

        if ($this->scenario == 'insert' || $this->scenario == 'update' || $this->scenario == 'updateTodo' || $this->scenario == 'insertTodo') {
            $this->updateAlertRecipients();
        } else if ($this->scenario == 'accept') {
            // auto resolve alert
            $this->resolveActiveAlerts(Alert::TYPE_TASK_NOT_ACCEPT);
            $this->resolveActiveAlerts(Alert::TYPE_TASK_REASSIGN);

        } else if ($this->scenario == 'complete') {
            // auto resolve alert
            $this->resolveActiveAlerts(Alert::TYPE_TASK_OVERDUE);
            $this->resolveActiveAlerts(Alert::TYPE_TASK_REASSIGN);
        }

        //reassign
        if (($this->scenario == 'update' || $this->scenario == 'updateTodo') &&
            $this->oldRecord->assign_id != $this->assign_id &&
            (bool)($this->alert_condition & TaskProcessTemplate::ALERT_COND_REASSIGNED)
        ) {
            $this->resolveActiveAlerts(Alert::TYPE_TASK_REASSIGN);
            $this->resolveActiveAlerts(Alert::TYPE_TASK_REJECTED);
//            if (empty($this->alert_recipient)) {
            $this->alert_recipients = $this->getAlertRecipient();
//            } else {
//                $this->alert_recipients = array($this->alert_recipient);
//            }
            Alert::createAlert(Alert::TYPE_TASK_REASSIGN, $this->getAllAttributes());

        }

        //reject
        if ($this->scenario == 'reject' &&
            (bool)($this->alert_condition & TaskProcessTemplate::ALERT_COND_REJECTED)
        ) {
//            if (empty($this->alert_recipient)) {
            $this->alert_recipients = $this->getAlertRecipient();
//            } else {
//                $this->alert_recipients = array($this->alert_recipient);
//            }

            $this->resolveActiveAlerts(Alert::TYPE_TASK_REASSIGN);
            Alert::createAlert(Alert::TYPE_TASK_REJECTED, $this->getAllAttributes());
        }

        if ($this->oldRecord != null && $this->oldRecord->assign_id != $this->assign_id) {
            $this->process->getCachedStatistic(true);
        }

        parent::afterSave();
    }


    public function resolveActiveAlerts($type)
    {
        $this->dbConnection->createCommand()
            ->update('Alert', array(
                'stage' => Alert::STAGE_RESOLVED
            ), 'alert_type=:alert_type and related_task_id=:task_id  and stage=:o_stage', array(
                ':alert_type' => $type,
                ':task_id' => $this->id,
                ':o_stage' => Alert::STAGE_ACTIVE
            ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TaskProcess the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function priorityAlias($code)
    {
        switch ($code) {
            case TaskProcess::PRIORITY_NORMAL:
                return TbHtml::labelTb(TaskProcess::itemAlias("Priority", $code), array("color" => TbHtml::LABEL_COLOR_DEFAULT));
            case TaskProcess::PRIORITY_LOW:
                return TbHtml::labelTb(TaskProcess::itemAlias("Priority", $code), array("color" => TbHtml::LABEL_COLOR_INFO));
            case TaskProcess::PRIORITY_MEDIUM:
                return TbHtml::labelTb(TaskProcess::itemAlias("Priority", $code), array("color" => TbHtml::LABEL_COLOR_WARNING));
            case TaskProcess::PRIORITY_HIGH:
                return TbHtml::labelTb(TaskProcess::itemAlias("Priority", $code), array("color" => TbHtml::LABEL_COLOR_DANGER));
        }
    }

    public static function stageAlias($code)
    {
        switch ($code) {
            case TaskProcess::STAGE_NOTSET:
                return '<i>Upcomming</i>';
            case TaskProcess::STAGE_ASSIGNED:
                return TbHtml::labelTb(TaskProcess::itemAlias("Stage", $code), array("color" => TbHtml::LABEL_COLOR_DEFAULT));
            case TaskProcess::STAGE_COMPLETED:
                return TbHtml::labelTb(TaskProcess::itemAlias("Stage", $code), array("color" => TbHtml::LABEL_COLOR_SUCCESS));
            case TaskProcess::STAGE_REJECTED:
                return TbHtml::labelTb(TaskProcess::itemAlias("Stage", $code), array("color" => TbHtml::LABEL_COLOR_DANGER));
            case TaskProcess::STAGE_WAIRFORCONFIRM:
                return TbHtml::labelTb(TaskProcess::itemAlias("Stage", $code), array("color" => TbHtml::LABEL_COLOR_WARNING));
            case TaskProcess::STAGE_INPROGRESS:
                return TbHtml::labelTb(TaskProcess::itemAlias("Stage", $code), array("color" => TbHtml::LABEL_COLOR_PRIMARY));
        }
    }

    public static function itemAlias($type, $code = NULL)
    {
        $_items = array(
            'Priority' => array(
                self::PRIORITY_NORMAL => 'Normal',
                self::PRIORITY_LOW => 'Low',
                self::PRIORITY_MEDIUM => 'Medium',
                self::PRIORITY_HIGH => 'High',
            ),
            'Stage' => array(
                self::STAGE_NOTSET => '',
                self::STAGE_ASSIGNED => 'Assigned',
                self::STAGE_COMPLETED => 'Completed',
                self::STAGE_WAIRFORCONFIRM => 'Waiting confirm',
                self::STAGE_INPROGRESS => 'In progress',
                self::STAGE_REJECTED => 'Not accept',
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    private $_sqlCondDueToday = " FROM `TaskProcess` as t1    
                LEFT JOIN `TaskProcess` as t2 ON t1.sort_order-1=t2.sort_order AND t1.process_id=t2.process_id
                LEFT JOIN `Process` as t3 ON t1.process_id=t3.id 
                WHERE t1.status=1 AND ((t3.status>0 AND t3.stage>0) OR (t3.id IS NULL))
                AND t1.assign_id=:assign_id
                AND (t1.stage=:stage2 OR t1.stage=:stage3)
                AND t1.due_date >= CURRENT_DATE 
                AND t1.due_date < CURRENT_DATE + INTERVAL 1 DAY";

    public function dueToday()
    {
        $sqlCount = "SELECT count(t1.id) as count_alias";
        $sqlData = "SELECT t1.*,`t3`.`name` as `process_name`, `t3`.`update_by` as `start_by`, `t3`.`shop_id`,`t3`.`supplier_id`, `t2`.`assign_id` as `request_by`, `t1`.`shop_id` as `todo_shop_id`, `t1`.`supplier_id` as `todo_supplier_id`  ";


        $command = Yii::app()->db->createCommand($sqlData . $this->_sqlCondDueToday);

        $commandTotal = Yii::app()->db->createCommand($sqlCount . $this->_sqlCondDueToday);

        $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
        $commandTotal->bindValue(':stage2', TaskProcess::STAGE_INPROGRESS);
        $commandTotal->bindValue(':stage3', TaskProcess::STAGE_WAIRFORCONFIRM);

        $count = $commandTotal->queryScalar();

        //set cache
        Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_DUETODAY, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);

        return new CSqlDataProvider($command, array(
            'keyField' => 'id',
            'totalItemCount' => $count,
            'params' => array(
                ':assign_id' => Yii::app()->user->id,
                ':stage2' => TaskProcess::STAGE_INPROGRESS,
                ':stage3' => TaskProcess::STAGE_WAIRFORCONFIRM,
            ),
            'sort' => array(
                'attributes' => array(
                    'due_date'
                ),
                'defaultOrder' => 't1.stage asc, t1.due_date asc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    public function getTotalTaskDueToday()
    {

        $count = Yii::app()->cache->get(sprintf(self::CKEY_TOTAL_DUETODAY, Yii::app()->user->id));
        if (!$count) {
            $commandTotal = Yii::app()->db->createCommand('SELECT count(t1.id) as count_alias ' . $this->_sqlCondDueToday);

            $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
            $commandTotal->bindValue(':stage2', TaskProcess::STAGE_INPROGRESS);
            $commandTotal->bindValue(':stage3', TaskProcess::STAGE_WAIRFORCONFIRM);

            $count = $commandTotal->queryScalar();
            Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_DUETODAY, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);
        }

        return $count;
    }

    private $_sqlCondDueTomorrow = " FROM `TaskProcess` as t1 
                LEFT JOIN `TaskProcess` as t2 ON t1.sort_order-1=t2.sort_order AND t1.process_id=t2.process_id
                LEFT JOIN `Process` as t3 ON t1.process_id=t3.id 
                WHERE t1.status=1 AND ((t3.status>0 AND t3.stage>0) OR (t3.id IS NULL))
                AND t1.assign_id=:assign_id
                AND (t1.stage=:stage2 OR t1.stage=:stage3)
                AND t1.due_date >= CURRENT_DATE + INTERVAL 1 DAY 
                AND t1.due_date < CURRENT_DATE + INTERVAL 2 DAY";

    public function dueTomorrow()
    {
        $sqlCount = "SELECT count(t1.id) as total ";
        $sqlData = "SELECT t1.*,`t3`.`shop_id`, `t3`.`supplier_id`, `t3`.`name` as `process_name`, `t2`.`assign_id` as `request_by`,`t3`.`update_by` as `start_by`, `t1`.`shop_id` as `todo_shop_id`, `t1`.`supplier_id` as `todo_supplier_id`  ";

        $command = Yii::app()->db->createCommand($sqlData . $this->_sqlCondDueTomorrow);

        $commandTotal = Yii::app()->db->createCommand($sqlCount . $this->_sqlCondDueTomorrow);
        $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
        $commandTotal->bindValue(':stage2', TaskProcess::STAGE_INPROGRESS);
        $commandTotal->bindValue(':stage3', TaskProcess::STAGE_WAIRFORCONFIRM);
        $count = $commandTotal->queryScalar();

        //set cache
        Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_DUETOMORROW, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);

        return new CSqlDataProvider($command, array(
            'keyField' => 'id',
            'totalItemCount' => $count,
            'params' => array(
                ':assign_id' => Yii::app()->user->id,
                ':stage2' => TaskProcess::STAGE_INPROGRESS,
                ':stage3' => TaskProcess::STAGE_WAIRFORCONFIRM
            ),
            'sort' => array(
                'attributes' => array(
                    'due_date'
                ),
                'defaultOrder' => 't1.stage asc, t1.due_date asc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    public function getTotalTaskDueTomorrow()
    {

        $count = Yii::app()->cache->get(sprintf(self::CKEY_TOTAL_DUETOMORROW, Yii::app()->user->id));
        if (!$count) {
            $commandTotal = Yii::app()->db->createCommand('SELECT count(t1.id) as total ' . $this->_sqlCondDueTomorrow);
            $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
            $commandTotal->bindValue(':stage2', TaskProcess::STAGE_INPROGRESS);
            $commandTotal->bindValue(':stage3', TaskProcess::STAGE_WAIRFORCONFIRM);
            $count = $commandTotal->queryScalar();
            Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_DUETOMORROW, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);
        }

        return $count;
    }

    private $_sqlCondOver2Days = " FROM `TaskProcess` as t1    
                LEFT JOIN `TaskProcess` as t2 ON t1.sort_order-1=t2.sort_order AND t1.process_id=t2.process_id
                LEFT JOIN `Process` as t3 ON t1.process_id=t3.id 
                WHERE t1.status=1 AND ((t3.status>0 AND t3.stage>0) OR (t3.id IS NULL))
                AND t1.assign_id=:assign_id
                AND (t1.stage=:stage2 OR t1.stage=:stage3)
                AND t1.due_date >= CURRENT_DATE + INTERVAL 2 DAY";

    public function dueOver2Days()
    {
        $sqlCount = "SELECT count(t1.id) as total ";
        $sqlData = "SELECT t1.*,`t3`.`shop_id`,`t3`.`update_by` as `start_by`, `t3`.`supplier_id`, `t3`.`name` as `process_name`, `t2`.`assign_id` as `request_by`, `t1`.`shop_id` as `todo_shop_id`, `t1`.`supplier_id` as `todo_supplier_id`  ";

        $command = Yii::app()->db->createCommand($sqlData . $this->_sqlCondOver2Days);

        $commandTotal = Yii::app()->db->createCommand($sqlCount . $this->_sqlCondOver2Days);
        $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
        $commandTotal->bindValue(':stage2', TaskProcess::STAGE_INPROGRESS);
        $commandTotal->bindValue(':stage3', TaskProcess::STAGE_WAIRFORCONFIRM);
        $count = $commandTotal->queryScalar();

        //set cache
        Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_DUEOVER2DAYS, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);

        return new CSqlDataProvider($command, array(
            'keyField' => 'id',
            'totalItemCount' => $count,
            'params' => array(
                ':assign_id' => Yii::app()->user->id,
                ':stage2' => TaskProcess::STAGE_INPROGRESS,
                ':stage3' => TaskProcess::STAGE_WAIRFORCONFIRM
            ),
            'sort' => array(
                'attributes' => array(
                    'due_date'
                ),
                'defaultOrder' => 't1.stage asc, t1.due_date asc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    public function getTotalTaskDueOver2Days()
    {

        $count = Yii::app()->cache->get(sprintf(self::CKEY_TOTAL_DUEOVER2DAYS, Yii::app()->user->id));
        if (!$count) {
            $commandTotal = Yii::app()->db->createCommand('SELECT count(t1.id) as total ' . $this->_sqlCondOver2Days);
            $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
            $commandTotal->bindValue(':stage2', TaskProcess::STAGE_INPROGRESS);
            $commandTotal->bindValue(':stage3', TaskProcess::STAGE_WAIRFORCONFIRM);
            $count = $commandTotal->queryScalar();
            Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_DUEOVER2DAYS, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);
        }

        return $count;
    }

    private $_sqlCondOverdue = " FROM `TaskProcess` as t1    
                LEFT JOIN `TaskProcess` as t2 ON t1.sort_order-1=t2.sort_order AND t1.process_id=t2.process_id
                LEFT JOIN `Process` as t3 ON t1.process_id=t3.id 
                WHERE t1.status=1 AND ((t3.status>0 AND t3.stage>0) OR (t3.id IS NULL))
                AND (t1.stage=:stage1 OR t1.stage=:stage2)
                AND t1.assign_id=:assign_id
                AND t1.due_date < NOW() ";

    public function overdue()
    {
        $sqlCount = "SELECT count(t1.id) as total ";
        $sqlData = "SELECT t1.*,`t3`.`shop_id`,`t3`.`update_by` as `start_by`, `t3`.`supplier_id`, `t3`.`name` as `process_name`, `t2`.`assign_id` as `request_by`, `t1`.`shop_id` as `todo_shop_id`, `t1`.`supplier_id` as `todo_supplier_id`  ";

        $command = Yii::app()->db->createCommand($sqlData . $this->_sqlCondOverdue);

        $commandTotal = Yii::app()->db->createCommand($sqlCount . $this->_sqlCondOverdue);
        $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
        $commandTotal->bindValue(':stage1', TaskProcess::STAGE_WAIRFORCONFIRM);
        $commandTotal->bindValue(':stage2', TaskProcess::STAGE_INPROGRESS);

        $count = $commandTotal->queryScalar();

        //set cache
        Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_OVERDUE, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);

        return new CSqlDataProvider($command, array(
            'keyField' => 'id',
            'totalItemCount' => $count,
            'params' => array(
                ':assign_id' => Yii::app()->user->id,
                ':stage1' => TaskProcess::STAGE_WAIRFORCONFIRM,
                ':stage2' => TaskProcess::STAGE_INPROGRESS
            ),
            'sort' => array(
                'attributes' => array(
                    'due_date'
                ),
                'defaultOrder' => 't1.stage asc, t1.due_date asc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    private $_sqlCondCompleted = " FROM `TaskProcess` as t1    
                LEFT JOIN `Process` as t3 ON t1.process_id=t3.id 
                WHERE t1.status=1 AND ((t3.status>0 AND t3.stage>0) OR (t3.id IS NULL))
                AND t1.stage=:stage
                AND t1.assign_id=:assign_id";

    public function getCompleted()
    {
        $sqlCount = "SELECT count(t1.id) as total ";
        $sqlData = "SELECT t1.*,`t3`.`shop_id`,`t3`.`update_by` as `start_by`, `t3`.`supplier_id`, `t3`.`name` as `process_name`, `t1`.`shop_id` as `todo_shop_id`, `t1`.`supplier_id` as `todo_supplier_id`  ";

        $command = Yii::app()->db->createCommand($sqlData . $this->_sqlCondCompleted);

        $commandTotal = Yii::app()->db->createCommand($sqlCount . $this->_sqlCondCompleted);
        $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
        $commandTotal->bindValue(':stage', TaskProcess::STAGE_WAIRFORCONFIRM);

        $count = $commandTotal->queryScalar();

        //set cache
        Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_COMPLETED, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);

        return new CSqlDataProvider($command, array(
            'keyField' => 'id',
            'totalItemCount' => $count,
            'params' => array(
                ':assign_id' => Yii::app()->user->id,
                ':stage' => TaskProcess::STAGE_COMPLETED,
            ),
            'sort' => array(
                'attributes' => array(
                    'complete_date'
                ),
                'defaultOrder' => 't1.complete_date desc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    public function getTotalTaskCompleted()
    {
        $count = Yii::app()->cache->get(sprintf(self::CKEY_TOTAL_COMPLETED, Yii::app()->user->id));
        if (!$count) {
            $commandTotal = Yii::app()->db->createCommand('SELECT count(t1.id) as total ' . $this->_sqlCondCompleted);
            $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
            $commandTotal->bindValue(':stage', TaskProcess::STAGE_COMPLETED);

            $count = $commandTotal->queryScalar();
            Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_COMPLETED, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);
        }

        return $count;
    }

    public function getTotalTaskOverdue()
    {

        $count = Yii::app()->cache->get(sprintf(self::CKEY_TOTAL_OVERDUE, Yii::app()->user->id));
        if (!$count) {
            $commandTotal = Yii::app()->db->createCommand('SELECT count(t1.id) as total ' . $this->_sqlCondOverdue);
            $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
            $commandTotal->bindValue(':stage1', TaskProcess::STAGE_WAIRFORCONFIRM);
            $commandTotal->bindValue(':stage2', TaskProcess::STAGE_INPROGRESS);

            $count = $commandTotal->queryScalar();
            Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_OVERDUE, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);
        }

        return $count;
    }

    private $_sqlCondRequests = " FROM `TaskProcess` as t1    
                LEFT JOIN `TaskProcess` as t2 ON t1.sort_order-1=t2.sort_order AND t1.process_id=t2.process_id
                LEFT JOIN `Process` as t3 ON t1.process_id=t3.id 
                WHERE t1.status=1 AND ((t3.status>0 AND t3.stage>0) OR (t3.id IS NULL))
                AND t1.stage=:stage
                AND t1.assign_id=:assign_id";

    public function taskRequests()
    {
        $sqlCount = "SELECT count(t1.id) ";
        $sqlData = "SELECT t1.*, `t3`.`name` as `process_name`, `t3`.`update_by` as `start_by`, `t2`.`assign_id` as `request_by`, `t3`.`supplier_id`, `t3`.`shop_id`, `t1`.`shop_id` as `todo_shop_id`, `t1`.`supplier_id` as `todo_supplier_id` ";

        $command = Yii::app()->db->createCommand($sqlData . $this->_sqlCondRequests);

        $commandTotal = Yii::app()->db->createCommand($sqlCount . $this->_sqlCondRequests);
        $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
        $commandTotal->bindValue(':stage', TaskProcess::STAGE_ASSIGNED);
        $count = $commandTotal->queryScalar();

        //set cache
        Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_REQUEST, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);

        return new CSqlDataProvider($command, array(
            'keyField' => 'id',
            'totalItemCount' => $count,
            'params' => array(
                ':assign_id' => Yii::app()->user->id,
                ':stage' => TaskProcess::STAGE_ASSIGNED,
            ),
            'sort' => array(
                'attributes' => array(
                    'due_date'
                ),
                'defaultOrder' => 't1.stage asc, t1.assign_date asc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    public function getTotalTaskRequests()
    {

        $count = Yii::app()->cache->get(sprintf(self::CKEY_TOTAL_REQUEST, Yii::app()->user->id));
        if (!$count) {
            $commandTotal = Yii::app()->db->createCommand('SELECT count(t1.id) ' . $this->_sqlCondRequests);
            $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
            $commandTotal->bindValue(':stage', TaskProcess::STAGE_ASSIGNED);
            $count = $commandTotal->queryScalar();
            Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_REQUEST, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);
        }

        return $count;
    }

    private $_sqlCondAssign = " FROM `TaskProcess` as t1    
                LEFT JOIN `TaskProcess` AS t2 ON t1.sort_order+1=t2.sort_order AND t1.process_id=t2.process_id
                LEFT JOIN `Process` AS t3 ON t1.process_id=t3.id 
                WHERE t1.status=1 AND ((t3.status>0 AND t3.stage>0) OR (t3.id IS NULL))
                AND t1.stage=:stage AND t1.assign_id=:assign_id and t2.stage=1";

    public function taskAssign()
    {

        $sqlCount = "SELECT count(t2.id) ";
        $sqlData = "SELECT t2.*,`t3`.`shop_id`, `t3`.`supplier_id`, `t3`.`name` as `process_name`, `t2`.`assign_id` as `request_by`, `t3`.`update_by` as `start_by`, `t1`.`shop_id` as `todo_shop_id`, `t1`.`supplier_id` as `todo_supplier_id`  ";

        $command = Yii::app()->db->createCommand($sqlData . $this->_sqlCondAssign);

        $commandTotal = Yii::app()->db->createCommand($sqlCount . $this->_sqlCondAssign);
        $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
        $commandTotal->bindValue(':stage', TaskProcess::STAGE_WAIRFORCONFIRM);
        $count = $commandTotal->queryScalar();

        //set cache
        Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_WAITFORACCEPT, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);

        return new CSqlDataProvider($command, array(
            'keyField' => 'id',
            'totalItemCount' => $count,
            'params' => array(
                ':assign_id' => Yii::app()->user->id,
                ':stage' => TaskProcess::STAGE_WAIRFORCONFIRM,
            ),
            'sort' => array(
                'attributes' => array(
                    'due_date'
                ),
                'defaultOrder' => 't2.assign_date asc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    public function getTotalTaskAssigned()
    {

        $count = Yii::app()->cache->get(sprintf(self::CKEY_TOTAL_WAITFORACCEPT, Yii::app()->user->id));
        if (!$count) {
            $commandTotal = Yii::app()->db->createCommand('SELECT count(t2.id) ' . $this->_sqlCondAssign);
            $commandTotal->bindValue(':assign_id', Yii::app()->user->id);
            $commandTotal->bindValue(':stage', TaskProcess::STAGE_WAIRFORCONFIRM);
            $count = $commandTotal->queryScalar();
            Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_WAITFORACCEPT, Yii::app()->user->id), $count, self::CKEY_TOTAL_EXPIRED);
        }

        return $count;
    }

    public function clearCacheTotal()
    {

        $request = sprintf(self::CKEY_TOTAL_REQUEST, $this->assign_id);
        $dueToday = sprintf(self::CKEY_TOTAL_DUETODAY, $this->assign_id);
        $dueTomorrow = sprintf(self::CKEY_TOTAL_DUETOMORROW, $this->assign_id);
        $dueOver2Days = sprintf(self::CKEY_TOTAL_DUEOVER2DAYS, $this->assign_id);
        $overdue = sprintf(self::CKEY_TOTAL_OVERDUE, $this->assign_id);
        $assigned = sprintf(self::CKEY_TOTAL_WAITFORACCEPT, $this->assign_id);

        Yii::app()->cache->delete($request);
        Yii::app()->cache->delete($dueToday);
        Yii::app()->cache->delete($dueTomorrow);
        Yii::app()->cache->delete($dueOver2Days);
        Yii::app()->cache->delete($overdue);
        Yii::app()->cache->delete($assigned);

        $this->clearCacheGlobalTotal();
    }

    public function clearCacheGlobalTotal()
    {

        $totalAssigned = sprintf(self::CKEY_TOTAL_TASK_BYSTAGE, self::STAGE_ASSIGNED);
        $totalInProgress = sprintf(self::CKEY_TOTAL_TASK_BYSTAGE, self::STAGE_INPROGRESS);
        $totalReject = sprintf(self::CKEY_TOTAL_TASK_BYSTAGE, self::STAGE_REJECTED);
        $totalWaitForConfirm = sprintf(self::CKEY_TOTAL_TASK_BYSTAGE, self::STAGE_WAIRFORCONFIRM);
        $totalNotset = sprintf(self::CKEY_TOTAL_TASK_BYSTAGE, self::STAGE_NOTSET);
        $totalCompleted = sprintf(self::CKEY_TOTAL_TASK_BYSTAGE, self::STAGE_COMPLETED);

        Yii::app()->cache->delete($totalAssigned);
        Yii::app()->cache->delete($totalInProgress);
        Yii::app()->cache->delete($totalReject);
        Yii::app()->cache->delete($totalWaitForConfirm);
        Yii::app()->cache->delete($totalNotset);
        Yii::app()->cache->delete($totalCompleted);
    }

    private $_sqlCondTaskByStage = ' FROM `TaskProcess` as `tp` left outer join `Process` as `p` ON `tp`.`process_id`=`p`.`id` WHERE `tp`.`status`=1 AND ((`p`.`id` IS NULL) OR (`p`.`status`=:process_status AND `p`.`stage`>:process_stage)) AND `tp`.`stage` LIKE :task_stage ';
    private $_sqlCondTaskByAllStage = ' FROM `TaskProcess` as `tp` left outer join `Process` as `p` ON `tp`.`process_id`=`p`.`id` WHERE `tp`.`status`=1 AND ((`p`.`id` IS NULL) OR (`p`.`status`=:process_status AND `p`.`stage`>:process_stage)) ';
    private $_sqlCountTaskByStage = ' SELECT count(`tp`.`id`) ';

    public function queryTasksByStageWithFilter($all, $allStage = false)
    {

        if ($all) {

            if ($allStage) {
                return $this->_sqlCondTaskByAllStage;
            }

            return $this->_sqlCondTaskByStage;
        }

        if ($allStage) {
            $query = $this->_sqlCondTaskByAllStage;
        } else {
            $query = $this->_sqlCondTaskByStage;
        }

        if ($this->process_id > 0) {
            $query .= ' AND tp.process_id=' . $this->process_id;
        }

        if ($this->assign_id > 0) {
            $query .= ' AND tp.assign_id=' . $this->assign_id;
        }

        return $query;
    }

    /**
     * Data provider for list all tasks by stage
     * */
    public function getTasksByStage()
    {

        $sqlData = " SELECT `tp`.`id`,`tp`.`process_id`, `tp`.task_id, `tp`.`assign_id`, `tp`.`duration`, `tp`.`stage`, tp.`create_date`, `tp`.`assign_date`, `tp`.`reject_date`, `tp`.`accept_date`, `tp`.`complete_date`, `tp`.`due_date`,`p`.`name` as `process_name` ";

        $allStage = ($this->stage == null);

        $command = Yii::app()->db->createCommand($sqlData . $this->queryTasksByStageWithFilter(false, $allStage));

        $total = $this->countTotalTaskByStage($this->stage, false, $allStage);

        $order = '';

        switch ($this->stage) {
            case TaskProcess::STAGE_NOTSET:
                $order = ' `tp`.`create_date` desc ';
                break;
            case TaskProcess::STAGE_ASSIGNED:
                $order = ' `tp`.`assign_date` desc ';
                break;
            case TaskProcess::STAGE_WAIRFORCONFIRM:
                $order = ' `tp`.`assign_date` desc ';
                break;
            case TaskProcess::STAGE_COMPLETED:
                $order = ' `tp`.`complete_date` desc ';
                break;
            case TaskProcess::STAGE_REJECTED:
                $order = ' `tp`.`reject_date` desc ';
                break;
            default:
                $order = " `tp`.`create_date` desc ";
                break;
        }

        if ($allStage) {
            $params = array(
                ':process_status' => Process::STATUS_ACTIVE,
                ':process_stage' => Process::STAGE_NOTSET,
            );
        } else {
            $params = array(
                ':process_status' => Process::STATUS_ACTIVE,
                ':process_stage' => Process::STAGE_NOTSET,
                ':task_stage' => $this->stage
            );
        }


        return new CSqlDataProvider($command, array(
            'keyField' => 'id',
            'totalItemCount' => $total,
            'params' => $params,
            'sort' => array(
                // 'attributes' => array(
                //     'due_date'
                // ),
                'defaultOrder' => $order
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    private function countTotalTaskByStage($stage, $all = false, $allStage = false)
    {

        $commandTotal = Yii::app()->db->createCommand($this->_sqlCountTaskByStage . $this->queryTasksByStageWithFilter($all, $allStage));
        $commandTotal->bindValue(':process_status', Process::STATUS_ACTIVE);
        $commandTotal->bindValue(':process_stage', Process::STAGE_NOTSET);

        if (!$allStage) {
            $commandTotal->bindValue(':task_stage', $stage);
        }

        $total = $commandTotal->queryScalar();

        if ($all) {
            Yii::app()->cache->set(sprintf(self::CKEY_TOTAL_TASK_BYSTAGE, $stage), $total, self::CKEY_TOTAL_TASK_BYSTAGE_EXPIRED);
        }

        return $total;
    }

    public function getTotalTaskByStage($stage, $allStage = false)
    {

        if ($allStage) {
            $total = $this->countTotalTaskByStage($stage, true, $allStage);
            return $total;
        }

        //get from cache if exist
//        $total = Yii::app()->cache->get(sprintf(self::CKEY_TOTAL_TASK_BYSTAGE, $stage));

//        if ($total === false) {
        //hit db
        $total = $this->countTotalTaskByStage($stage, true);
//        }

        return $total;
    }

    private $_sqlCondByDate = " FROM `TaskProcess` as t1    
                LEFT JOIN `Process` as t3 ON t1.process_id=t3.id 
                WHERE t1.status=1
                %s
                AND t1.assign_date >= :assign_date 
                AND t1.assign_date < (DATE(:assign_date) + INTERVAL 24 HOUR)";

    public function getUserTaskByDate($date, $user_id)
    {

        $filterUser = ' AND t1.assign_id=:assign_id ';
        if ($user_id == 0) {
            $filterUser = ' ';
        }

        $cond = sprintf($this->_sqlCondByDate, $filterUser);

        $sqlCount = "SELECT count(t1.id) as count_alias";
        $sqlData = "SELECT t1.*,`t3`.`name` as `process_name`, `t3`.`update_by` as `start_by`, `t3`.`shop_id`,`t3`.`supplier_id`, `t1`.`shop_id` as `todo_shop_id`, `t1`.`supplier_id` as `todo_supplier_id`  ";
        $command = Yii::app()->db->createCommand($sqlData . $cond);
        $commandTotal = Yii::app()->db->createCommand($sqlCount . $cond);
        if ($user_id >= 0) {
            $commandTotal->bindValue(':assign_id', $user_id);
        }
//        $commandTotal->bindValue(':status', Process::STATUS_ACTIVE);
        $commandTotal->bindValue(':assign_date', $date . ' 00:00:00');

        $count = $commandTotal->queryScalar();

        $params = array(
//            ':status' => Process::STATUS_ACTIVE,
            ':assign_date' => $date . ' 00:00:00'
        );

        if ($user_id > 0) {
            $params[':assign_id'] = $user_id;
        }

        return new CSqlDataProvider($command, array(
            'keyField' => 'id',
            'totalItemCount' => $count,
            'params' => $params,
            'sort' => array(
                'defaultOrder' => 't1.assign_date asc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    protected function afterValidate()
    {

        if (!$this->hasErrors() && ($this->scenario == 'insertTodo' || ($this->scenario == 'updateTodo' && $this->customTaskName != $this->oldRecord->customTaskName))) {
            $taskName = trim($this->customTaskName);
            $task = Task::model()->find('name=:name', array(
                ':name' => $taskName
            ));

            if ($task === null) {
                $task = new Task;
                $task->name = $this->customTaskName;
                $task->duration = $this->duration;
                $task->task_group = -1;

                if (!$task->save()) {
                    $this->addError('general', 'An error occurred. Please try again.');
                }
            }

            $this->task_id = $task->id;
        }

        return parent::afterValidate();
    }

    public function delete()
    {

        if (empty($this->accept_date) &&
            empty($this->reject_date) &&
            empty($this->complete_date)
        ) {
            return parent::delete();
        } else {
            $this->status = self::STATUS_DELETE;
            return parent::update(array('status'));
        }
    }

    public function defaultScope()
    {
        return array(
            'condition' => $this->getTableAlias(false, false) . '.status=' . self::STATUS_ACTIVE
        );
    }

    public function getEmailsAlertRecipients()
    {
        $userEmail = array();
        $recipients = $this->getEmailRecipient();

        foreach ($recipients as $id) {
            $user = User::model()->findByPk($id);
            if ($user != null) {
                $userEmail[] = $user->email;
            }
        }

        if (!empty($this->send_mail_recipient_extra)) {
            $emails = explode(',', $this->send_mail_recipient_extra);
            $userEmail = CMap::mergeArray($userEmail, $emails);
        }

        return $userEmail;
    }

    public function getEmailRecipient()
    {

        $data = $this->getAlertRecipient2();
        if (count($data) > 0) {
            $r = array();
            foreach ($data as $k => $v) {
                if ((bool)$v) {
                    $r[] = $k;
                }
            }

            return $r;
        }

        return array();
    }

    public function getAlertRecipient2()
    {
//        if ($this->alert_recipient != null && $this->alert_recipient > 0) {
//            return array($this->alert_recipient);
//        }

        $cmd = $this->dbConnection->createCommand('SELECT user_id,send_mail FROM AlertRecipientConfig WHERE task_id=:task_id');
        $cmd->bindValue(':task_id', $this->id);

        $rows = $cmd->queryAll();

        $result = array();
        foreach ($rows as $r) {
            $result[$r['user_id']] = $r['send_mail'];
        }

        return $result;
    }


    public function getAlertRecipient()
    {
//        if ($this->alert_recipient != null && $this->alert_recipient > 0) {
//            return array($this->alert_recipient);
//        }

        $cmd = $this->dbConnection->createCommand('SELECT user_id FROM AlertRecipientConfig WHERE task_id=:task_id');
        $cmd->bindValue(':task_id', $this->id);

        $rows = $cmd->queryAll();

        $result = array();
        foreach ($rows as $r) {
            $result[] = $r['user_id'];
        }

        return $result;
    }

    public function getAllAttributes()
    {
        $attr = $this->getAttributes();
        $safeAttr = $this->getSafeAttributeNames();

        foreach ($safeAttr as $name) {
            if (property_exists($this, $name)) {
                $attr[$name] = $this->{$name};
            }
        }

        return $attr;
    }

    public function isConcurrentTask()
    {
        return $this->task_type == TaskProcessTemplate::TASK_TYPE_CONCURRENT;
    }

    public function isSingleTask()
    {
        return $this->task_type == TaskProcessTemplate::TASK_TYPE_SINGLE;
    }

    /**
     * @param TaskProcess $cTask
     * @param TaskProcess[] $allTasks
     * @return TaskProcess[]
     */
    public function findPreviousTasks($cTask, $allTasks = null)
    {

        if ($allTasks == null) {
            $allTasks = TaskProcess::model()->findAll(array(
                    'condition' => 'process_id=:process_id and status=1',
                    'params' => array(':process_id' => $cTask->process_id),
                    'order' => 'sort_order asc')
            );
        }

        $preTasks = array();

        foreach ($allTasks as $task) {

            if ($task->id == $cTask->id) {
                break;
            }

            if ($task->isSingleTask()) {
                $preTasks = $task;
            } else if ($task->isConcurrentTask()) {

                if (!is_array($preTasks)) {
                    $preTasks = array();
                }

                $preTasks[] = $task;
            }
        }

        if (!is_array($preTasks)) {
            $preTasks = array($preTasks);
        }

        return $preTasks;
    }

    /**
     * @param TaskProcess $cTask
     * @param TaskProcess[] $allTasks
     * @return TaskProcess[]
     */
    public function findNextTasks($cTask, $allTasks = null)
    {
        if ($allTasks == null) {
            $allTasks = TaskProcess::model()->findAll(array(
                    'condition' => 'process_id=:process_id and status=1',
                    'params' => array(':process_id' => $cTask->process_id),
                    'order' => 'sort_order asc')
            );
        }

        $nextTasks = array();

        $found = false;
        $nextTask = null;

        //find next task
        foreach ($allTasks as $task) {

            if ($task->id == $cTask->id) {

                $found = true;
                continue;
            }

            if ($found) {

                if ($task->isSingleTask()) {

                    if (empty($nextTasks)) {
                        $nextTasks = array($task);
                    }

                    break;
                } else if ($task->isConcurrentTask()) {
                    $nextTasks[] = $task;
                }

            }


        }

        return $nextTasks;
    }

    /**
     * @param TaskProcess[] $preTasks
     * @param TaskProcess[] $allTasks
     * @return float
     */
    public function findCompletedProgress($preTasks, $allTasks = null)
    {

        $totalHours = 0;
        $hoursComplete = 0;

        foreach ($allTasks as $task) {

            $totalHours += $task->duration;

            if ($task->stage == TaskProcess::STAGE_COMPLETED) {
                $hoursComplete += $task->duration;
            }

        }

        $prevTaskDuration = 0;

        foreach ($preTasks as $t) {
            $prevTaskDuration += $t->duration;
        }

        $progress = floor(($hoursComplete + $prevTaskDuration) / $totalHours * 100);

        return $progress;
    }

    /**
     * @param TaskProcess $task
     * @param TaskProcess[] $allTasks
     * @throws CDbException
     * @return  bool
     */
    public function onAfterTaskAccepted($task, $allTasks = null)
    {

        if ($allTasks == null) {
            $allTasks = TaskProcess::model()->findAll(array(
                    'condition' => 'process_id=:process_id and status=1',
                    'params' => array(':process_id' => $task->process_id),
                    'order' => 'sort_order asc')
            );

            if (empty($allTasks)) {
                return false;
            }
        }

        if ($task->isSingleTask()) {

            //find previous tasks
            $preTasks = $this->findPreviousTasks($task, $allTasks);

            if (!empty($preTasks)) {

                foreach ($preTasks as $t) {
                    $t->complete();
                }
            }

        } else if ($task->isConcurrentTask()) {

            //any other concurrent task need accept
            $groupTasks = $this->findGroupConcurrentTasks($task, $allTasks);

            if (!$this->isAnyTaskInConcurrentGroupNotAccept($groupTasks)) {

                //find previous tasks
                $preTasks = $this->findPreviousTasksOfConcurrentGroup($groupTasks, $allTasks);

                if (!empty($preTasks)) {

                    foreach ($preTasks as $t) {
                        $t->complete();
                    }
                }
            }
        }

        $job = $task->process;
        $job->updateProgress($allTasks);
    }

    /**
     * @param TaskProcess[] $list
     * @return bool
     */
    public function isAnyTaskInConcurrentGroupNotAccept($list)
    {
        foreach ($list as $t) {
            if ($this->id != $t->id && ($t->isAssigned() || $t->isRejected())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TaskProcess[] $list
     * @return bool
     */
    public function isAnyTaskInConcurrentGroupNotPreCompleted($list)
    {
        foreach ($list as $t) {
            if ($this->id != $t->id && ($t->isInprogress() || $t->isAssigned() || $t->isRejected())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TaskProcess[] $groupTasks
     * @param TaskProcess[] $allTasks
     * @return TaskProcess[]
     */
    public function findPreviousTasksOfConcurrentGroup($groupTasks, $allTasks)
    {

        $firstTask = $this->findFirstTaskInConcurrentGroup($groupTasks);
        return $this->findPreviousTasks($firstTask, $allTasks);
    }

    /**
     * @param TaskProcess[] $groupTasks
     * @param TaskProcess[] $allTasks
     * @return TaskProcess[]
     */
    public function findNextTasksOfConcurrentGroup($groupTasks, $allTasks)
    {

        $lastTask = $this->findLastTaskInConcurrentGroup($groupTasks);
        return $this->findNextTasks($lastTask, $allTasks);
    }

    /**
     * @param $groupTasks
     * @return TaskProcess
     */
    public function findLastTaskInConcurrentGroup($groupTasks)
    {
        $maxOrder = -1;
        $lastTask = null;

        //get last task in this group
        foreach ($groupTasks as $t) {

            if ($maxOrder == -1) {
                $maxOrder = $t->sort_order;
                $lastTask = $t;
            }

            if ($t->sort_order > $maxOrder) {
                $maxOrder = $t->sort_order;
                $lastTask = $t;
            }
        }

        return $lastTask;
    }

    /**
     * @param $groupTasks
     * @return TaskProcess
     */
    public function findFirstTaskInConcurrentGroup($groupTasks)
    {
        $minOrder = -1;
        $firstTask = null;
        //get first task in this group
        foreach ($groupTasks as $t) {

            if ($minOrder == -1) {
                $minOrder = $t->sort_order;
                $firstTask = $t;
            }

            if ($t->sort_order < $minOrder) {
                $minOrder = $t->sort_order;
                $firstTask = $t;
            }
        }

        return $firstTask;
    }

    /**
     * @param TaskProcess $task
     * @param TaskProcess[] $allTasks
     * @return array
     */
    public function findGroupConcurrentTasks($task, $allTasks)
    {

        if ($task->isSingleTask()) {
            return array();
        }

        $groupTasks = array();
        $found = false;

        foreach ($allTasks as $t) {

            if ($t->id == $task->id) {
                $found = true;
            }

            if ($t->isConcurrentTask()) {
                $groupTasks[] = $t;
            } else if ($t->isSingleTask()) {
                if (!$found) {
                    $groupTasks = array();
                } else {
                    break;
                }
            }
        }

        return $groupTasks;
    }

    public function accept()
    {
        $this->stage = TaskProcess::STAGE_INPROGRESS;
        $this->scenario = 'accept';
        if (!$this->save()) {
            throw new CDbException('Accept task failed.');
        }

        return true;
    }

    public function preComplete()
    {
        //update task to wait confirm
        $this->stage = TaskProcess::STAGE_WAIRFORCONFIRM;
        $this->scenario = 'waitConfirm';
        if (!$this->save()) {
            throw new CDbException('preComplete task failed.');
        }

        return true;
    }

    /**
     * @param TaskProcess $task
     * @param TaskProcess[] $allTasks
     * @return bool
     */
    public function onAfterTaskPreCompleted($task, $allTasks = null)
    {

        if ($allTasks == null) {
            $allTasks = TaskProcess::model()->findAll(array(
                    'condition' => 'process_id=:process_id and status=1',
                    'params' => array(':process_id' => $task->process_id),
                    'order' => 'sort_order asc')
            );

            if (empty($allTasks)) {
                return false;
            }
        }

        if ($task->isSingleTask()) {

            //get next task
            $nextTasks = $this->findNextTasks($task, $allTasks);

            if (!empty($nextTasks)) {

                $task->preComplete();

                foreach ($nextTasks as $t) {
                    $t->assign();
                }
            } else {

                $task->complete();

                $job = $task->process;
                $job->complete();
            }

        } else if ($task->isConcurrentTask()) {

            //any other concurrent task need accept
            $groupTasks = $this->findGroupConcurrentTasks($task, $allTasks);

            if (!$this->isAnyTaskInConcurrentGroupNotPreCompleted($groupTasks)) {

                //find next tasks
                $nextTasks = $this->findNextTasksOfConcurrentGroup($groupTasks, $allTasks);

                if (!empty($nextTasks)) {

                    $task->preComplete();

                    foreach ($nextTasks as $t) {
                        $t->assign();
                    }

                } else {
                    $task->complete();

                    if ($task->isOtherTaskInConcurrentGroupCompleted($groupTasks)) {
                        $job = $task->process;
                        $job->complete();
                    } else {
                        $job = $task->process;
                        $job->updateProgress($allTasks, $task->id);
                    }
                }
            } else {

                //find next tasks
                $nextTasks = $this->findNextTasksOfConcurrentGroup($groupTasks, $allTasks);

                if (!empty($nextTasks)) {
                    $task->preComplete();
                } else {
                    $task->complete();

                    if ($task->isOtherTaskInConcurrentGroupCompleted($groupTasks)) {
                        $job = $task->process;
                        $job->complete();
                    } else {
                        $job = $task->process;
                        $job->updateProgress($allTasks, $task->id);
                    }

                }
            }
        }
    }

    /**
     * @param TaskProcess[] $groupTasks
     * @return bool
     */
    public function isOtherTaskInConcurrentGroupCompleted($groupTasks)
    {
        foreach ($groupTasks as $t) {
            if (!$t->isCompleted() && $t->id != $this->id) {
                return false;
            }
        }

        return true;
    }

    public function assign()
    {
        $this->scenario = 'assign';
        $this->stage = TaskProcess::STAGE_ASSIGNED;
        if (!$this->save()) {
            throw new CDbException('Assign task failed');
        }

        return true;
    }


    public function isInprogress()
    {
        return $this->stage == TaskProcess::STAGE_INPROGRESS;
    }

    public function isCompleted()
    {
        return $this->stage == TaskProcess::STAGE_COMPLETED;
    }

    public function isAssigned()
    {
        return $this->stage == TaskProcess::STAGE_ASSIGNED;
    }

    public function isRejected()
    {
        return $this->stage == TaskProcess::STAGE_REJECTED;
    }

    public function isPreCompleted()
    {
        return $this->stage == TaskProcess::STAGE_WAIRFORCONFIRM;
    }

    public function complete()
    {

        if (!$this->isInprogress() && !$this->isPreCompleted()) {
            throw new Exception('Invalid task stage');
        }

        $this->scenario = 'complete';
        $this->stage = TaskProcess::STAGE_COMPLETED;

        if (!$this->save()) {
            throw new CDbException('Complete previous task failed.');
        }

        return true;
    }
}
