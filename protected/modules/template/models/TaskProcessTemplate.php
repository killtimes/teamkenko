<?php

/**
 * This is the model class for table "TaskProcessTemplate".
 *
 * The followings are the available columns in table 'TaskProcessTemplate':
 * @property integer $id
 * @property integer $process_id
 * @property integer $task_id
 * @property integer $assign_id
 * @property string $description
 * @property integer $duration
 * @property integer $stage
 * @property integer $priority
 * @property integer $sort_order
 * @property string $create_date
 * @property string $update_date
 * @property integer $alert_condition
 * @property integer $alert_enable
 * @property string $send_mail_recipient_extra
 *
 * @property bool $is_att_mandatory
 * @property bool $can_not_reject
 * @property integer $task_type
 *
 *
 *
 * The followings are the available model relations:
 * @property ProcessTemplate $process
 * @property Task $task
 * @property User $assign
 */
class TaskProcessTemplate extends CActiveRecord
{

    //priority
    const PRIORITY_NORMAL = 0;
    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;

    const ALERT_COND_NOT_ACCEPT = 1;
    const ALERT_COND_OVER_DUE = 2;
    const ALERT_COND_REASSIGNED = 4;
    const ALERT_COND_REJECTED = 8;

    public $alert_recipients;
    public $alert_conditions;
    public $send_mail_recipients;

    const TASK_TYPE_SINGLE = 1;
    const TASK_TYPE_CONCURRENT = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'TaskProcessTemplate';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('process_id, task_id, assign_id, duration, priority', 'required'),
            array('process_id, task_id, assign_id', 'numerical', 'integerOnly' => true),
            array('duration', 'numerical', 'integerOnly' => true, 'min' => 1),
            array('description', 'length', 'max' => 255),
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
            array('id, process_id, task_id, assign_id', 'safe', 'on' => 'search'),
            array('alert_enable', 'in', 'range' => array(0, 1)),
            array('alert_enable', 'validateAlertConfig'),
            array('alert_condition', 'numerical', 'integerOnly' => true),
            array('alert_condition', 'numerical', 'integerOnly' => true),
            array('alert_recipients, alert_conditions,send_mail_recipients,send_mail_recipient_extra', 'safe'),
            array('is_att_mandatory,can_not_reject', 'in', 'range' => array(0, 1)),
            array('task_type', 'in', 'range' => array(self::TASK_TYPE_SINGLE, self::TASK_TYPE_CONCURRENT))
        );
    }

    public function validateAlertConfig()
    {
        if (!empty($this->alert_recipients)) {
            $this->alert_recipients = explode(',', $this->alert_recipients);
        }

        if (!empty($this->send_mail_recipients)) {
            $this->send_mail_recipients = explode(',', $this->send_mail_recipients);
        }

        if (!empty($this->send_mail_recipient_extra)) {
            $arrayEmail = explode(',',$this->send_mail_recipient_extra);
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
        return array(
            'process' => array(self::BELONGS_TO, 'ProcessTemplate', 'process_id'),
            'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
            'assign' => array(self::BELONGS_TO, 'User', 'assign_id'),
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
            'duration' => 'Duration (hours)',
            'progress' => 'Progress',
        );
    }


    public function afterFind()
    {
        $this->alert_conditions = array();

        if ((bool)($this->alert_condition & self::ALERT_COND_NOT_ACCEPT)) {
            $this->alert_conditions[] = self::ALERT_COND_NOT_ACCEPT;
        }

        if ((bool)($this->alert_condition & self::ALERT_COND_OVER_DUE)) {
            $this->alert_conditions[] = self::ALERT_COND_OVER_DUE;
        }

        if ((bool)($this->alert_condition & self::ALERT_COND_REASSIGNED)) {
            $this->alert_conditions[] = self::ALERT_COND_REASSIGNED;
        }

        if ((bool)($this->alert_condition & self::ALERT_COND_REJECTED)) {
            $this->alert_conditions[] = self::ALERT_COND_REJECTED;
        }

//        if ($this->alert_recipient > 0) {
//            $this->alert_recipients = $this->alert_recipient;
//        }

        parent::afterFind();
    }

    public function afterSave()
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

                $cmdDelete = $this->dbConnection->createCommand('DELETE FROM AlertRecipientConfigTemplate WHERE task_id=:task_id');
                $cmdDelete->bindValue(':task_id', $this->id);
                $cmdDelete->execute();

                $cmd = $this->dbConnection->schema->getCommandBuilder()->createMultipleInsertCommand($this->dbConnection->schema->getTable('AlertRecipientConfigTemplate'), $rows);
                $cmd->execute();
            }
        }

        parent::afterSave();
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $maxOrder = Yii::app()->db->createCommand('SELECT MAX(`sort_order`) AS `max_order` FROM `TaskProcessTemplate` WHERE `process_id`=' . $this->process_id)->queryScalar();
            $this->sort_order = $maxOrder + 1;
        }
        return parent::beforeSave();
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
            'pagination' => array(
                'pageSize' => 25
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TaskProcessTemplate the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function priorityAlias($code)
    {
        switch ($code) {
            case TaskProcessTemplate::PRIORITY_NORMAL:
                return TbHtml::labelTb(TaskProcessTemplate::itemAlias("Priority", $code), array("color" => TbHtml::LABEL_COLOR_DEFAULT));
            case TaskProcessTemplate::PRIORITY_LOW:
                return TbHtml::labelTb(TaskProcessTemplate::itemAlias("Priority", $code), array("color" => TbHtml::LABEL_COLOR_INFO));
            case TaskProcessTemplate::PRIORITY_MEDIUM:
                return TbHtml::labelTb(TaskProcessTemplate::itemAlias("Priority", $code), array("color" => TbHtml::LABEL_COLOR_WARNING));
            case TaskProcessTemplate::PRIORITY_HIGH:
                return TbHtml::labelTb(TaskProcessTemplate::itemAlias("Priority", $code), array("color" => TbHtml::LABEL_COLOR_DANGER));
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
            'AlertCondition' => array(
                self::ALERT_COND_NOT_ACCEPT => 'If not accept',
                self::ALERT_COND_OVER_DUE => 'If over due in 1 hour',
                self::ALERT_COND_REASSIGNED => 'If been reassigned',
                self::ALERT_COND_REJECTED => 'If been rejected',
            ),
            'TaskType' => array(
                self::TASK_TYPE_SINGLE => 'Single',
                self::TASK_TYPE_CONCURRENT => 'Concurrent'
            ),
        );

        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : '';
        else
            return isset($_items[$type]) ? $_items[$type] : '';
    }


    public function getAlertRecipientTemplate()
    {

//        if ($this->alert_recipient != null && $this->alert_recipient > 0) {
//            return array($this->alert_recipient);
//        }

        $cmd = $this->dbConnection->createCommand('SELECT user_id FROM AlertRecipientConfigTemplate WHERE task_id=:task_id');
        $cmd->bindValue(':task_id', $this->id);

        $rows = $cmd->queryAll();

        $result = array();
        foreach ($rows as $r) {
            $result[] = $r['user_id'];
        }

        return $result;
    }

    public function getAlertRecipientTemplate2()
    {

//        if ($this->alert_recipient != null && $this->alert_recipient > 0) {
//            return array($this->alert_recipient=>);
//        }

        $cmd = $this->dbConnection->createCommand('SELECT user_id,send_mail FROM AlertRecipientConfigTemplate WHERE task_id=:task_id');
        $cmd->bindValue(':task_id', $this->id);

        $rows = $cmd->queryAll();

        $result = array();
        foreach ($rows as $r) {
            $result[$r['user_id']] = $r['send_mail'];
        }

        return $result;
    }
}
