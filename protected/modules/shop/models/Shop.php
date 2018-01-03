<?php

/**
 * This is the model class for table "Shop".
 *
 * The followings are the available columns in table 'Shop':
 * @property integer $id
 * @property string $name
 * @property string $employees
 * @property string $address
 * @property string $phone
 * @property string $fax
 */
class Shop extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'Shop';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, address', 'length', 'max' => 200),
            array('employees, phone, fax', 'length', 'max' => 50),
            array('name', 'required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
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
            'name' => 'Name',
            'employees' => 'Employees',
            'address' => 'Address',
            'phone' => 'Phone',
            'fax' => 'Fax',
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
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Shop the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function afterSave() {
        $key = sprintf(self::SHOP_KEY, $this->id);
        Yii::app()->cache->delete($key);
        $this->getById($this->id);
        return parent::afterSave();
    }

    const SHOP_KEY = 'shop:%s';
    const SHOP_KEY_EXPIRED = 86400;

    public function getById($id) {

        $key = sprintf(self::SHOP_KEY, $id);

        $attr = Yii::app()->cache->get($key);

        if (empty($attr)) {

            $model = $this->findByPk($id);

            if ($model !== null) {

                Yii::app()->cache->set($key, $model->attributes, self::SHOP_KEY_EXPIRED);

                return $model;
            }
        }

        return $this->populateRecord($attr, false);
    }

}
