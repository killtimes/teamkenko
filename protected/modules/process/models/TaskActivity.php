<?php

/**
 * This is the model class for table "TaskActivity".
 *
 * The followings are the available columns in table 'TaskActivity':
 * @property integer $id
 * @property integer $task_id
 * @property integer $action_type
 * @property integer $action_source
 * @property integer $action_target
 * @property integer $action_object
 * @property string $action_message
 * @property string $action_date
 * @property integer $status
 */
class TaskActivity extends CActiveRecord {

    const ACTION_TYPE_ASSIGN = 1;
    const ACTION_TYPE_ACCEPT = 2;
    const ACTION_TYPE_REJECT = 3;
    const ACTION_TYPE_ADDMESSAGE = 4;
    const ACTION_TYPE_COMPLETE = 5;
    const ACTION_TYPE_ADDDOCUMENT = 6;
    const ACTION_TYPE_COMPLETE_AND_WAIT = 7;
    //status
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'TaskActivity';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('task_id, action_type, action_source', 'required'),
            array('task_id, action_type, action_source, action_target', 'numerical', 'integerOnly' => true),
            array('action_message', 'length', 'max' => 500),
            array('action_object', 'length', 'max' => 255),
            array('action_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'insert'
            ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, task_id, action_type, action_source, action_target, action_object, action_message, action_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'task_id' => 'Task',
            'action_type' => 'Action Type',
            'action_source' => 'Action Source',
            'action_target' => 'Action Target',
            'action_object' => 'Action Object',
            'action_message' => 'Action Message',
            'action_date' => 'Action Date',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('task_id', $this->task_id);
        $criteria->compare('action_type', $this->action_type);
        $criteria->compare('action_source', $this->action_source);
        $criteria->compare('action_target', $this->action_target);
        $criteria->compare('action_object', $this->action_object);
        $criteria->compare('action_date', $this->action_date, true);
        $criteria->compare('status', self::STATUS_ACTIVE);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TaskActivity the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function delete() {
        $this->status = self::STATUS_DELETED;
        return $this->update(array('status'));
    }

}
