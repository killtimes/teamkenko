<?php

/**
 * This is the model class for table "Alert".
 *
 * The followings are the available columns in table 'Alert':
 * @property integer $id
 * @property integer $alert_type
 * @property string $note
 * @property string $create_date
 * @property string $update_date
 * @property integer $create_by
 * @property integer $update_by
 * @property integer $to_user_id
 * @property integer $stage
 * @property integer $status
 * @property integer $related_task_id
 */
class Alert extends CActiveRecord
{

    const TYPE_TASK_OVERDUE = 1;
    const TYPE_TASK_REJECTED = 2;
    const TYPE_TASK_NOT_ACCEPT = 3;
    const TYPE_TASK_REASSIGN = 5;

    CONST STATUS_NORMAL = 1;
    CONST STATUS_CRITICAL = 2;
    CONST STATUS_HIGH_CRITICAL = 3;

    const STAGE_ACTIVE = 1;
    const STAGE_RESOLVED = 2;
    const STAGE_DELETED = -1;

    public $to_users;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Alert';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('alert_type, related_task_id, to_users', 'required'),
            array('alert_type, create_by, update_by, to_user_id, stage, related_task_id', 'numerical', 'integerOnly' => true),
            array('note', 'length', 'max' => 500),
            array('create_date, update_date', 'safe'),
            array('status', 'in', 'range' => array_keys(self::itemAlias('Status'))),
            array('update_date', 'default', 'value' => Yii::app()->localTime->getUTCNow('Y-m-d H:i:s'), 'on' => 'update'),
            array('update_by', 'default', 'value' => Yii::app()->user->id, 'on' => 'update'),
            array('id, alert_type, note, create_date, update_date, create_by, update_by, to_user_id, stage, status, related_task_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'alert_type' => 'Alert Type',
            'note' => 'Note',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'create_by' => 'Create By',
            'update_by' => 'Update By',
            'to_user_id' => 'User',
            'stage' => 'Stage',
            'status' => 'Critical status',
            'related_task_id' => 'Related Task',
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

        $criteria = new CDbCriteria;
        $criteria->join = 'inner join TaskProcess tp on t.related_task_id=tp.id';
        $criteria->compare('tp.status', TaskProcess::STATUS_ACTIVE);
        $criteria->compare('to_user_id', $this->to_user_id);
        $criteria->compare('t.stage', $this->stage);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.related_task_id', $this->related_task_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25
            ),
            'sort' => array(
                'defaultOrder' => 'create_date desc'
            )
        ));
    }

    public function searchByUser()
    {
        $criteria = new CDbCriteria();
        $criteria->join = 'inner join TaskProcess tp on t.related_task_id=tp.id';

        $criteria->join .= ' left join AlertUser au on au.alert_id=t.id';
        $criteria->compare('tp.status', TaskProcess::STATUS_ACTIVE);

        $criteria->addCondition('(to_user_id is null and au.user_id=' . $this->to_user_id . ') or (au.user_id is null and to_user_id=' . $this->to_user_id . ')');
        $criteria->compare('t.stage', $this->stage);
        $criteria->compare('t.status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25
            ),
            'sort' => array(
                'defaultOrder' => 'create_date desc'
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Alert the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function findAllByTaskId($id)
    {
        $this->related_task_id = $id;
        return $this->search();
    }

    public static function itemAlias($type, $code = NULL)
    {
        $_items = array(
            'Type' => array(
                self::TYPE_TASK_NOT_ACCEPT => 'Task not accept',
                self::TYPE_TASK_OVERDUE => 'Task overdue',
                self::TYPE_TASK_REASSIGN => 'Task been re-assigned',
                self::TYPE_TASK_REJECTED => 'Task been rejected',
            ),
            'Status' => array(
                self::STATUS_NORMAL => 'Non-Critical',
                self::STATUS_CRITICAL => 'Critical',
                self::STATUS_HIGH_CRITICAL => 'Highly Critical',
            ),
            'Stage' => array(
                self::STAGE_ACTIVE => 'Active',
                self::STAGE_RESOLVED => 'Resolved',
                self::STAGE_DELETED => 'Deleted',
            )
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    public static function statusAlias($code)
    {
        switch ($code) {
            case self::STATUS_NORMAL:
                return TbHtml::labelTb(self::itemAlias("Status", $code), array("color" => TbHtml::LABEL_COLOR_DEFAULT));
            case self::STATUS_CRITICAL:
                return TbHtml::labelTb(self::itemAlias("Status", $code), array("color" => TbHtml::LABEL_COLOR_WARNING));
            case self::STATUS_HIGH_CRITICAL:
                return TbHtml::labelTb(self::itemAlias("Status", $code), array("color" => TbHtml::LABEL_COLOR_DANGER));
        }
    }

    public static function stageAlias($code)
    {
        switch ($code) {
            case self::STAGE_ACTIVE:
                return TbHtml::labelTb(self::itemAlias("Stage", $code), array("color" => TbHtml::LABEL_COLOR_INFO));
            case self::STAGE_RESOLVED:
                return TbHtml::labelTb(self::itemAlias("Stage", $code), array("color" => TbHtml::LABEL_COLOR_SUCCESS));
            case self::STAGE_DELETED:
                return TbHtml::labelTb(self::itemAlias("Stage", $code), array("color" => TbHtml::LABEL_COLOR_DEFAULT));

        }
    }

    public function countNonCriticalAlert($userId)
    {
        $criteria = new CDbCriteria();
        $criteria->join = 'inner join TaskProcess tp on t.related_task_id=tp.id';
        $criteria->join .= ' left join AlertUser au on au.alert_id=t.id';
        $criteria->compare('tp.status', TaskProcess::STATUS_ACTIVE);

        $criteria->addCondition('(to_user_id is null and au.user_id=' . $userId . ') or (au.user_id is null and to_user_id=' . $userId . ')');

        $criteria->compare('t.stage', self::STAGE_ACTIVE);
        $criteria->compare('t.status', self::STATUS_NORMAL);


        return $this->count($criteria);
    }

    public function countCriticalAlert($userId)
    {
        $criteria = new CDbCriteria();

        $criteria->join = 'inner join TaskProcess tp on t.related_task_id=tp.id';
        $criteria->join .= ' left join AlertUser au on au.alert_id=t.id';
        $criteria->addCondition('(to_user_id is null and au.user_id=' . $userId . ') or (au.user_id is null and to_user_id=' . $userId . ')');
        $criteria->compare('tp.status', TaskProcess::STATUS_ACTIVE);
        $criteria->compare('t.stage', self::STAGE_ACTIVE);
        $criteria->compare('t.status', self::STATUS_CRITICAL);

        return $this->count($criteria);
    }

    public function countHighlyCriticalAlert($userId)
    {
        $criteria = new CDbCriteria();
        $criteria->join = 'inner join TaskProcess tp on t.related_task_id=tp.id';

        $criteria->join .= ' left join AlertUser au on au.alert_id=t.id';
        $criteria->compare('tp.status', TaskProcess::STATUS_ACTIVE);

        $criteria->addCondition('(to_user_id is null and au.user_id=' . $userId . ') or (au.user_id is null and to_user_id=' . $userId . ')');
        $criteria->compare('t.stage', self::STAGE_ACTIVE);
        $criteria->compare('t.status', self::STATUS_HIGH_CRITICAL);

        return $this->count($criteria);
    }

    public function totalNonCriticalAlert()
    {
        $criteria = new CDbCriteria();

        $criteria->join = 'inner join TaskProcess tp on t.related_task_id=tp.id';
        $criteria->compare('tp.status', TaskProcess::STATUS_ACTIVE);

        $criteria->compare('t.stage', self::STAGE_ACTIVE);
        $criteria->compare('t.status', self::STATUS_NORMAL);

        return $this->count($criteria);
    }

    public function totalCriticalAlert()
    {
        $criteria = new CDbCriteria();
        $criteria->join = 'inner join TaskProcess tp on t.related_task_id=tp.id';
        $criteria->compare('tp.status', TaskProcess::STATUS_ACTIVE);
        $criteria->compare('t.stage', self::STAGE_ACTIVE);
        $criteria->compare('t.status', self::STATUS_CRITICAL);

        return $this->count($criteria);
    }

    public function totalHighlyCriticalAlert()
    {
        $criteria = new CDbCriteria();
        $criteria->join = 'inner join TaskProcess tp on t.related_task_id=tp.id';
        $criteria->compare('tp.status', TaskProcess::STATUS_ACTIVE);
        $criteria->compare('t.stage', self::STAGE_ACTIVE);
        $criteria->compare('t.status', self::STATUS_HIGH_CRITICAL);

        return $this->count($criteria);
    }

    public function getAlertRecipient()
    {
        $cmd = $this->dbConnection->createCommand('SELECT user_id FROM AlertUser WHERE alert_id=:alert_id');
        $cmd->bindValue(':alert_id', $this->id);

        $rows = $cmd->queryAll();

        $result = array();
        foreach ($rows as $r) {
            $result[] = $r['user_id'];
        }

        return $result;
    }

    public function afterFind()
    {
        if ($this->to_user_id > 0) {
            $this->to_users = $this->to_user_id;
        }
        parent::afterFind();
    }


    public function afterSave()
    {
        if ($this->scenario == 'insert' || $this->scenario == 'update') {
            $this->updateAlertRecipients();
        }

        parent::afterSave(); // TODO: Change the autogenerated stub
    }

    protected function updateAlertRecipients()
    {
        if (is_array($this->to_users) && count($this->to_users) > 1) {

            $rows = array();

            foreach ($this->to_users as $userId) {

                $rows[] = array(
                    'alert_id' => $this->id,
                    'user_id' => $userId
                );
            }


            if (count($rows) > 0) {

                $cmdDelete = $this->dbConnection->createCommand('DELETE FROM AlertUser WHERE alert_id=:alert_id');
                $cmdDelete->bindValue(':alert_id', $this->id);
                $cmdDelete->execute();

                $cmd = $this->dbConnection->schema->getCommandBuilder()->createMultipleInsertCommand($this->dbConnection->schema->getTable('AlertUser'), $rows);
                $cmd->execute();
            }
        } else if (count($this->to_users) == 1) {
            $cmdDelete = $this->dbConnection->createCommand('DELETE FROM AlertUser WHERE alert_id=:alert_id');
            $cmdDelete->bindValue(':alert_id', $this->id);
            $cmdDelete->execute();
        }
    }

    protected function afterValidate()
    {
        if (!$this->hasErrors()) {

            if (!empty($this->to_users)) {
                $this->to_users = explode(',', $this->to_users);
            }

            if (!is_array($this->to_users) || count($this->to_users) == 0) {
                $this->addError('alert_recipients', 'Please specify recipient for task alert');
                return false;
            }

            if (count($this->to_users) == 1) {
                $this->to_user_id = $this->to_users[0];
            } else {
                $this->to_user_id = null;
            }
        }
        parent::afterValidate();
    }

    public static function createAlert($type, $task, $status = Alert::STATUS_NORMAL)
    {

        $alert = new Alert();
        $alert->alert_type = $type;
        $alert->create_by = Yii::app()->user->id;
        $alert->to_user_id = $task['alert_recipient'];
        $alert->stage = Alert::STAGE_ACTIVE;
        $alert->status = $status;
        $alert->related_task_id = $task['id'];
        $alert->note = '';
        if (!empty($task['reason'])) {
            $alert->note = $task['reason'];
        }

        if (isset($task['alert_recipients']) && is_array($task['alert_recipients']) && count($task['alert_recipients']) > 0) {

            if ($type == Alert::TYPE_TASK_REASSIGN) {
                //add new assignee to list recipients
                $task['alert_recipients'][] = $task['assign_id'];
            }

            $alert->to_users = implode(',', array_unique($task['alert_recipients']));
            $alert->to_user_id = null;
        } else {

            $alert->to_users = $task['alert_recipient'];

            if ($type == Alert::TYPE_TASK_REASSIGN && $task['alert_recipient'] != $task['assign_id']) {
                //add new assignee to list recipients
                $alert->to_users .= ',' . $task['assign_id'];
            }
        }

        if (!$alert->save()) {
            Yii::log('createAlert:' . CVarDumper::dumpAsString($alert->errors), 'error');
            return;
        }

        //spool send mail
        $task = TaskProcess::model()->findByPk($alert->related_task_id);
        if($task != null){
            $recipients = $task->getEmailsAlertRecipients();
            Yii::app()->emailManager->notify($recipients, $alert, $task);
        }
    }

    public static function checkExistingAlert($task_id, $type)
    {
        //check existing alert
        $existAlert = Alert::model()->exists('related_task_id=:task_id and alert_type=:alert_type and stage=:stage', array(
            ':task_id' => $task_id,
            ':alert_type' => $type,
            ':stage' => Alert::STAGE_ACTIVE
        ));

        return $existAlert;
    }
}
