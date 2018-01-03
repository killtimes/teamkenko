<?php

/**
 * This is the model class for table "Supplier".
 *
 * The followings are the available columns in table 'Supplier':
 * @property integer $id
 * @property string $name
 * @property string $industry
 * @property string $address
 * @property string $country
 * @property string $mobile_phone
 * @property string $office_phone
 * @property string $office_fax

 */
class Supplier extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'Supplier';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, industry', 'length', 'max' => 200),
            array('address', 'length', 'max' => 255),
            array('mobile_phone, office_phone, office_fax', 'length', 'max' => 30),
            array('country', 'length', 'max' => 10),
            array('name, industry', 'required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, industry', 'safe', 'on' => 'search'),
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
            'industry' => 'Industry',
            'address' => 'Address',
            'country' => 'Country',
            'mobile_phone' => 'Mobile phone',
            'office_phone' => 'Office phone',
            'office_fax' => 'Office fax'
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
        $criteria->compare('industry', $this->industry, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>25
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Supplier the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function afterSave() {
        $key = sprintf(self::SUPPLIER_KEY, $this->id);
        Yii::app()->cache->delete($key);
        $this->getById($this->id);
        return parent::afterSave();
    }

    const SUPPLIER_KEY = 'supplier:%s';
    const SUPPLIER_KEY_EXPIRED = 86400;

    public function getById($id) {

        $key = sprintf(self::SUPPLIER_KEY, $id);

        $attr = Yii::app()->cache->get($key);

        if (empty($attr)) {

            $model = $this->findByPk($id);

            if ($model !== null) {

                Yii::app()->cache->set($key, $model->attributes, self::SUPPLIER_KEY_EXPIRED);

                return $model;
            }
        }

        return $this->populateRecord($attr, false);
    }

}
