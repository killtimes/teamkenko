    <?php

/**
 * This is the model class for table "Profile".
 *
 * The followings are the available columns in table 'Profile':
 * @property integer $user_id
 * @property string $lastname
 * @property string $firstname
 * @property integer $department
 * @property string $mobile_phone
 * @property string $address
 * @property integer $shop_id
 *
 * The followings are the available model relations:
 * @property Shop $shop
 */
class Profile extends CActiveRecord
{

    const DEPARTMENT_BOD = 1; // 1;
    const DEPARTMENT_HR = 2; // 2;
    const DEPARTMENT_IT = 4; // 3;
    const DEPARTMENT_CUSTOMER_SERVICE = 8; // 4;
    const DEPARTMENT_PROCUREMENT_PURCHASING = 16; // 5;
    const DEPARTMENT_FINANCE = 32; // 6;
    const DEPARTMENT_OPERATION = 64; // 7;
    const DEPARTMENT_MARKETING = 128; //8;
    const DEPARTMENT_CREDIT_CONTROL = 256; // 9;
    const DEPARTMENT_EXPORT = 512; // 10;
    const DEPARTMENT_SHOP = 1024; // 11;
    const DEPARTMENT_FROZEN_WAREHOUSE = 2048; // 12;
    const DEPARTMENT_LOGISTICS = 4096; // 13;
    const DEPARTMENT_DRIED_WAREHOUSE = 8192; // 14;
    const DEPARTMENT_AOBABA_KITCHEN = 16384; // 15;

    public $arr_department = 0;
    public $arr_shop_id = array();

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Profile';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, firstname, lastname', 'required'),
            array('shop_id, department', 'numerical', 'integerOnly' => true),
            array('lastname, firstname', 'length', 'max' => 50),
            array('mobile_phone', 'length', 'max' => 30),
            array('address', 'length', 'max' => 255),
            array('arr_department', 'checkArrDepartment'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('user_id, lastname, firstname, department, mobile_phone, address, shop_id', 'safe', 'on' => 'search'),
            array('arr_shop_id', 'type', 'type' => 'array', 'allowEmpty' => true)
        );
    }

    public function checkArrDepartment($attributeName, $params)
    {

        if (empty($this->arr_department)) {
            $this->addError('arr_department', "Department can not be blank.");
            $this->arr_department = 0;
            return false;
        }

        if (!is_array($this->arr_department)) {
            $this->addError('arr_department', "Department is not valid.");
            $this->arr_department = 0;
            return false;
        }

        return true;
    }

    protected function beforeSave()
    {
        if (!empty($this->arr_department)) {
            $bit = 0;
            foreach ($this->arr_department as $d) {
                $bit |= $d;
            }
            $this->department = $bit;
        } else {
            $this->department = 0;
        }
        return parent::beforeSave();
    }

    protected function afterFind()
    {

        $this->arr_department = array();

        if ((bool)($this->department & self::DEPARTMENT_BOD)) {
            $this->arr_department[] = self::DEPARTMENT_BOD;
        }
        if ((bool)($this->department & self::DEPARTMENT_HR)) {
            $this->arr_department[] = self::DEPARTMENT_HR;
        }
        if ((bool)($this->department & self::DEPARTMENT_IT)) {
            $this->arr_department[] = self::DEPARTMENT_IT;
        }
        if ((bool)($this->department & self::DEPARTMENT_CUSTOMER_SERVICE)) {
            $this->arr_department[] = self::DEPARTMENT_CUSTOMER_SERVICE;
        }
        if ((bool)($this->department & self::DEPARTMENT_PROCUREMENT_PURCHASING)) {
            $this->arr_department[] = self::DEPARTMENT_PROCUREMENT_PURCHASING;
        }
        if ((bool)($this->department & self::DEPARTMENT_FINANCE)) {
            $this->arr_department[] = self::DEPARTMENT_FINANCE;
        }
        if ((bool)($this->department & self::DEPARTMENT_OPERATION)) {
            $this->arr_department[] = self::DEPARTMENT_OPERATION;
        }

        if ((bool)($this->department & self::DEPARTMENT_MARKETING)) {
            $this->arr_department[] = self::DEPARTMENT_MARKETING;
        }

        if ((bool)($this->department & self::DEPARTMENT_CREDIT_CONTROL)) {
            $this->arr_department[] = self::DEPARTMENT_CREDIT_CONTROL;
        }

        if ((bool)($this->department & self::DEPARTMENT_EXPORT)) {
            $this->arr_department[] = self::DEPARTMENT_EXPORT;
        }

        if ((bool)($this->department & self::DEPARTMENT_SHOP)) {
            $this->arr_department[] = self::DEPARTMENT_SHOP;
        }

        if ((bool)($this->department & self::DEPARTMENT_FROZEN_WAREHOUSE)) {
            $this->arr_department[] = self::DEPARTMENT_FROZEN_WAREHOUSE;
        }

        if ((bool)($this->department & self::DEPARTMENT_LOGISTICS)) {
            $this->arr_department[] = self::DEPARTMENT_LOGISTICS;
        }

        if ((bool)($this->department & self::DEPARTMENT_DRIED_WAREHOUSE)) {
            $this->arr_department[] = self::DEPARTMENT_DRIED_WAREHOUSE;
        }

        if ((bool)($this->department & self::DEPARTMENT_AOBABA_KITCHEN)) {
            $this->arr_department[] = self::DEPARTMENT_AOBABA_KITCHEN;
        }

        if (count($this->arr_department) <= 0) {
            $this->arr_department = 0;
        }

        if ($this->scenario == 'update') {
            $shops = UserShop::model()->findAll('user_id=:user_id', array(
                ':user_id' => $this->user_id
            ));

            foreach($shops as $s){
                $this->arr_shop_id[] = $s->shop_id;
            }
        }

        return parent::afterFind();
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'user_id' => 'User ID',
            'lastname' => 'Last Name',
            'firstname' => 'First Name',
            'department' => 'Department',
            'mobile_phone' => 'Mobile Phone',
            'address' => 'Address',
            'shop_id' => 'Shop',
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

        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('lastname', $this->lastname, true);
        $criteria->compare('firstname', $this->firstname, true);
        $criteria->compare('department', $this->department);
        $criteria->compare('mobile_phone', $this->mobile_phone, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('shop_id', $this->shop_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Profile the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function showDepartment()
    {
        $arr = array();
        $this->afterFind();
        if (is_array($this->arr_department)) {
            foreach ($this->arr_department as $bit) {
                $arr[] = self::itemAlias('Department', $bit);
            }
        }

        return implode(', ', $arr);
    }

    public static function itemAlias($type, $code = NULL)
    {
        $_items = array(
            'Department' => array(
                '' => '',
                self::DEPARTMENT_AOBABA_KITCHEN => 'Aobaba Kitchen',
                self::DEPARTMENT_BOD => 'Board of Director',
                self::DEPARTMENT_CREDIT_CONTROL => 'Credit Control',
                self::DEPARTMENT_CUSTOMER_SERVICE => 'Customer Service',
                self::DEPARTMENT_DRIED_WAREHOUSE => 'Dried Warehouse',
                self::DEPARTMENT_EXPORT => 'Export',
                self::DEPARTMENT_FINANCE => 'Finance',
                self::DEPARTMENT_FROZEN_WAREHOUSE => 'Warehouse',
                self::DEPARTMENT_HR => 'HR',
                self::DEPARTMENT_IT => 'IT',
                self::DEPARTMENT_LOGISTICS => 'Logistics',
                self::DEPARTMENT_MARKETING => 'Marketing',
                self::DEPARTMENT_OPERATION => 'Operation',
                self::DEPARTMENT_PROCUREMENT_PURCHASING => 'Procurement Purchasing',
                self::DEPARTMENT_SHOP => 'Shop',
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : $code;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    private function saveShop()
    {

        //delete old shop
        UserShop::model()->deleteAll('user_id=:user_id', array(
            ':user_id' => $this->user_id
        ));

        if (!empty($this->arr_shop_id)) {

            $statement = array();
            //prepare statement
            foreach ($this->arr_shop_id as $id) {
                $statement[] = array(
                    'user_id' => $this->user_id,
                    'shop_id' => $id
                );
            }

            if (!empty($statement)) {
                $connection = $this->getDbConnection()->getSchema()->getCommandBuilder();
                $command = $connection->createMultipleInsertCommand(UserShop::model()->tableName(), $statement);
                $command->execute();
            }

        }
    }

    public function afterSave()
    {
        $this->saveShop();

        $key = sprintf(self::PROFILE_KEY, $this->user_id);
        Yii::app()->cache->delete($key);
        $this->getById($this->user_id);
        return parent::afterSave();
    }

    const PROFILE_KEY = 'profile:%s';
    const PROFILE_KEY_EXPIRED = 86400;

    public function getById($id)
    {

        $key = sprintf(self::PROFILE_KEY, $id);

        $attr = Yii::app()->cache->get($key);

        if (empty($attr)) {

            $model = $this->findByPk($id);

            if ($model !== null) {

                Yii::app()->cache->set($key, $model->attributes, self::PROFILE_KEY_EXPIRED);

                return $model;
            }
        }

        $model = new Profile;
        $model->attributes = $attr;

        return $model;
    }

    public function getFullName()
    {
        return $this->firstname . " " . $this->lastname;
    }

    public function getShopId($userId)
    {

        $profile = $this->getById($userId);

        return $profile->shop_id;
    }

    public function getDepartment($userId){
        $profile = $this->getById($userId);

        if($profile!=null){

            $arr_department = array();

            if ((bool)($profile->department & self::DEPARTMENT_BOD)) {
                $arr_department[] = self::DEPARTMENT_BOD;
            }
            if ((bool)($profile->department & self::DEPARTMENT_HR)) {
                $arr_department[] = self::DEPARTMENT_HR;
            }
            if ((bool)($profile->department & self::DEPARTMENT_IT)) {
                $arr_department[] = self::DEPARTMENT_IT;
            }
            if ((bool)($profile->department & self::DEPARTMENT_CUSTOMER_SERVICE)) {
                $arr_department[] = self::DEPARTMENT_CUSTOMER_SERVICE;
            }
            if ((bool)($profile->department & self::DEPARTMENT_PROCUREMENT_PURCHASING)) {
                $arr_department[] = self::DEPARTMENT_PROCUREMENT_PURCHASING;
            }
            if ((bool)($profile->department & self::DEPARTMENT_FINANCE)) {
                $arr_department[] = self::DEPARTMENT_FINANCE;
            }
            if ((bool)($profile->department & self::DEPARTMENT_OPERATION)) {
                $arr_department[] = self::DEPARTMENT_OPERATION;
            }

            if ((bool)($profile->department & self::DEPARTMENT_MARKETING)) {
                $arr_department[] = self::DEPARTMENT_MARKETING;
            }

            if ((bool)($profile->department & self::DEPARTMENT_CREDIT_CONTROL)) {
                $arr_department[] = self::DEPARTMENT_CREDIT_CONTROL;
            }

            if ((bool)($profile->department & self::DEPARTMENT_EXPORT)) {
                $arr_department[] = self::DEPARTMENT_EXPORT;
            }

            if ((bool)($profile->department & self::DEPARTMENT_SHOP)) {
                $arr_department[] = self::DEPARTMENT_SHOP;
            }

            if ((bool)($profile->department & self::DEPARTMENT_FROZEN_WAREHOUSE)) {
                $arr_department[] = self::DEPARTMENT_FROZEN_WAREHOUSE;
            }

            if ((bool)($profile->department & self::DEPARTMENT_LOGISTICS)) {
                $arr_department[] = self::DEPARTMENT_LOGISTICS;
            }

            if ((bool)($profile->department & self::DEPARTMENT_DRIED_WAREHOUSE)) {
                $arr_department[] = self::DEPARTMENT_DRIED_WAREHOUSE;
            }

            if ((bool)($this->department & self::DEPARTMENT_AOBABA_KITCHEN)) {
                $arr_department[] = self::DEPARTMENT_AOBABA_KITCHEN;
            }

            return $arr_department;
        }

        return array();
    }

}
