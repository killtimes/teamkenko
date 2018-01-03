<?php

/**
 * This is the model class for table "Document".
 *
 * The followings are the available columns in table 'Document':
 * @property integer $id
 * @property integer $task_id
 * @property integer $shop_id
 * @property integer $supplier_id
 * @property integer $doc_type
 * @property integer $doc_code
 * @property integer $doc_date
 * @property string $title
 * @property string $file_name
 * @property string $file_type
 * @property string $source_type
 * @property string $create_date
 * @property string $update_date
 * @property string $upload_by
 * @property string $status
 * The followings are the available model relations:
 * @property TaskProcess $task
 */
class Document extends CActiveRecord
{

    const SOURCE_TYPE_LOCAL = 1;
    const SOURCE_TYPE_DROPBOX = 2;
    //document type
    const DOC_TYPE_INVOICE = 1;
    const DOC_TYPE_CREDIT_NOTE = 2;
    const DOC_TYPE_DELIVERY_NOTE = 3;
    const DOC_TYPE_STOCK_TRANSFER = 4;
    const DOC_TYPE_PURCHASE_ORDER = 5;
    const DOC_TYPE_OTHERS = 6;
    //FILTER FORM
    const FILTERTYPE_UPLOADDATE = 1;
    const FILTERTYPE_BIZDATE = 2;
    //status
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;

    public $doc_date_filtertype = 1;
    public $date_range_filter;


    public $tempPath;
    public $fileName;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Document';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('task_id,title, file_name', 'required'),
            array('task_id, source_type, shop_id, supplier_id', 'numerical', 'integerOnly' => true),
            array('source_type', 'in', 'range' => array(self::SOURCE_TYPE_LOCAL, self::SOURCE_TYPE_DROPBOX), 'message' => 'Invalid source type'),
            array('doc_type', 'in', 'range' => array_keys(Document::itemAlias('Type')), 'message' => 'Document type is not valid'),
            array('title', 'length', 'max' => 200),
            array('doc_code', 'length', 'max' => 30),
            array(
                'doc_date',
                'date',
                'format' => array(
                    'yyyy-MM-dd'
                ),
            ),
            array('file_name', 'length', 'max' => 255),
            array('file_type', 'length', 'max' => 100),
            array('update_date', 'safe'),
            array('create_date, update_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'insert'
            ),
            array('update_date', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false,
                'on' => 'update'
            ),
            array('upload_by', 'default',
                'value' => Yii::app()->user->id,
                'setOnEmpty' => true,
                'on' => 'insert'
            ),
            array('tempPath, fileName', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, task_id, title, file_name, file_type, create_date, update_date, doc_date_filtertype, date_range_filter', 'safe', 'on' => 'search'),
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
            'task' => array(self::BELONGS_TO, 'TaskProcess', 'task_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'task_id' => 'Task',
            'shop_id' => 'Shop',
            'supplier_id' => 'Contact',
            'title' => 'Title',
            'file_name' => 'File Name',
            'file_type' => 'File Type',
            'create_date' => 'Upload Date',
            'update_date' => 'Update Date',
            'doc_type' => 'Document type',
            'doc_code' => 'Document code',
            'doc_date' => 'Business Date'
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
        $criteria->compare('task_id', $this->task_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('file_name', $this->file_name, true);
        $criteria->compare('file_type', $this->file_type, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('update_date', $this->update_date, true);

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
     * @return Document the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getFilePath()
    {
        return Yii::getPathOfAlias('upload_dir') . $this->file_name;
    }

    public function makePath()
    {
        if (empty($this->shop_id)) {
            $this->shop_id = 0;
        }
        if (empty($this->supplier_id)) {
            $this->supplier_id = 0;
        }
        return sprintf("%s%s%s%s%s", DIRECTORY_SEPARATOR, $this->shop_id, DIRECTORY_SEPARATOR, $this->supplier_id, DIRECTORY_SEPARATOR);
    }

    public function beforeValidate()
    {

        if ($this->source_type == self::SOURCE_TYPE_LOCAL && !empty($this->tempPath) && !empty($this->fileName)) {

            $relativePath = $this->makePath();
            $path = Yii::getPathOfAlias('upload_dir') . $relativePath;

            if (!is_dir($path) && !mkdir($path, 0774, true)) {
                throw new CException('Cant make directory');
            }

            @chmod($path, 0774);

            if (!is_file($this->tempPath)) {
                throw new CException('Temp file not exist');
            }

            $newPath = $path . $this->fileName;

            if (!copy($this->tempPath, $newPath)) {
                throw new CException('Move file failed');
            }

            @chmod($newPath, 0774);

            $this->file_name = $relativePath . $this->fileName;
        }

        return parent::beforeValidate();
    }

    public function afterSave()
    {
        $key = sprintf(self::ATTACHMENT_KEY, $this->id);
        Yii::app()->cache->delete($key);
        $this->getById($this->id);
        return parent::afterSave();
    }

    const ATTACHMENT_KEY = 'attachment:%s';
    const ATTACHMENT_KEY_EXPIRED = 86400;

    public function getById($id)
    {

        $key = sprintf(self::ATTACHMENT_KEY, $id);

        $attr = Yii::app()->cache->get($key);

        if (empty($attr)) {

            $model = $this->findByPk($id);

            if ($model !== null) {

                Yii::app()->cache->set($key, $model->attributes, self::ATTACHMENT_KEY_EXPIRED);

                return $model;
            }
        }

        return $this->populateRecord($attr, false);
    }

    public function renderIcon()
    {
        switch ($this->file_type) {
            case 'image/gif':
            case 'image/jpeg':
            case 'image/png':
            case 'image/bmp':
                return '<i class="fa fa-file-image-o"></i>';
            case 'application/pdf':
                return '<i class="fa fa-file-pdf-o"></i>';
            case 'application/excel':
            case 'application/excel':
            case 'application/x-excel':
            case 'application/x-msexcel':
            case 'application/excel':
            case 'application/vnd.ms-excel':
            case 'application/x-excel':
            case 'application/excel':
            case 'application/vnd.ms-excel':
            case 'application/x-excel':
            case 'application/excel':
            case 'application/x-excel':
            case 'application/excel':
            case 'application/x-excel':
            case 'application/excel':
            case 'application/vnd.ms-excel':
            case 'application/x-excel':
            case 'application/excel':
            case 'application/vnd.ms-excel':
            case 'application/x-excel':
            case 'application/excel':
            case 'application/vnd.ms-excel':
            case 'application/x-excel':
            case 'application/x-msexcel':
            case 'application/excel':
            case 'application/x-excel':
            case 'application/excel':
            case 'application/x-excel':
            case 'application/excel':
            case 'application/vnd.ms-excel':
            case 'application/x-excel':
            case 'application/x-msexcel':
                return '<i class="fa fa-file-excel-o"></i>';
            case 'application/x-compress':
            case 'application/x-compressed':
            case 'application/x-compressed':
            case 'application/x-zip-compressed':
            case 'application/zip':
            case 'multipart/x-zip':
                return '<i class="fa fa-file-archive-o"></i>';
            case 'application/msword':
                return '<i class="fa fa-file-word-o"></i>';
            default:
                if ($this->source_type == self::SOURCE_TYPE_DROPBOX) {
                    return '<i class="fa fa-dropbox"></i>';
                }
                return '<i class="fa fa-file-text-o"></i>';
        }
    }

    public function isImage()
    {

        $arrImageType = array(
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/bmp',
        );
        if ($this->source_type == self::SOURCE_TYPE_LOCAL) {
            return in_array($this->file_type, $arrImageType);
        }

        return false;
    }

    public static function itemAlias($type, $code = NULL)
    {
        $_items = array(
            'Type' => array(
                self::DOC_TYPE_INVOICE => 'Invoice',
                self::DOC_TYPE_CREDIT_NOTE => 'Credit Note',
                self::DOC_TYPE_DELIVERY_NOTE => 'Delivery Note',
                self::DOC_TYPE_STOCK_TRANSFER => 'Stock Transfer',
                self::DOC_TYPE_PURCHASE_ORDER => 'Purchase Order',
                self::DOC_TYPE_OTHERS => 'Others',
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    public function delete()
    {
        $this->status = self::STATUS_DELETED;
        return $this->update(array('status'));
    }

    public function getDocumentsByJobId($jobId)
    {
        $criteria = new CDbCriteria();
        $criteria->join = " inner join `TaskProcess` tp on tp.id=t.task_id inner join `Process` p on p.id=tp.process_id ";
        $criteria->condition = " t.status=" . self::STATUS_ACTIVE . " AND p.id= " . $jobId;
        $criteria->order = "create_date desc";

        return $this->findAll($criteria);
    }

}
