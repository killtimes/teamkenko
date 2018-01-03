<?php

/**
 * This is the model class for table "Task".
 *
 * The followings are the available columns in table 'Task':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $duration
 * @property integer $task_group
 * @property integer $instructions
 *
 * The followings are the available model relations:
 * @property TaskProcessTemplate[] $taskProcessTemplates
 */
class Task extends CActiveRecord
{

    const STATUS_DELETED = -1;
    const STATUS_ACTIVE = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Task';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('duration, task_group', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 150),
            array('name', 'unique', 'message' => 'Task name exists already'),
            array('instructions', 'ext.EWordValidator', 'max' => 700),
            array('instructions', 'length', 'max' => 7000),
            array('instructions', 'default', 'value' => ' ','setOnEmpty'=>true),
            array('name, task_group', 'required'),
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
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'taskProcessTemplates' => array(self::HAS_MANY, 'TaskProcessTemplate', 'task_id'),
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
            'duration' => 'Duration (hour)',
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
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.task_group', $this->task_group);
        $criteria->compare('status', self::STATUS_ACTIVE);

        $criteria->with = array('taskGroup');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25
            )
        ));
    }

    public function delete()
    {

        $this->status = self::STATUS_DELETED;
        return $this->update(array('status'));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Task the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function afterSave()
    {
        $key = sprintf(self::TASK_KEY, $this->id);
        Yii::app()->cache->delete($key);
        $this->getById($this->id);
        return parent::afterSave();
    }

    const TASK_KEY = 'task:%s';
    const TASK_KEY_EXPIRED = 86400;

    public function getById($id)
    {

        $key = sprintf(self::TASK_KEY, $id);

        $attr = Yii::app()->cache->get($key);

        if (empty($attr)) {

            $model = $this->findByPk($id);

            if ($model !== null) {

                Yii::app()->cache->set($key, $model->attributes, self::TASK_KEY_EXPIRED);

                return $model;
            }
        }

        return $this->populateRecord($attr, false);
    }

    public function scopeActive()
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'status=' . self::STATUS_ACTIVE,
        ));

        return $this;
    }

}
