<?php

class InvoiceXUpload extends CFormModel {

    public $file;
    public $mime_type;
    public $size;
    public $name;
    public $filename;
    public $extension;
    public $secureFileNames = false;
    protected $tempDir;
    protected $targetDir;

    const CACHE_KEY_TMP_INVOICE = "tmp:uid:%s:%s";
    const CACHE_KEY_TMP_INVOICE_EXPIRED = 3600;
    const CACHE_KEY_TMP_LISTINVOICE = "listtmp:uid:%s";
    const CACHE_KEY_TMP_LISTINVOICE_EXPIRED = 3600;

    public function init() {
        parent::init();
        $this->targetDir = Yii::getPathOfAlias('upload_dir') . DIRECTORY_SEPARATOR;
        $this->tempDir = Yii::getPathOfAlias('upload_tmp_dir') . DIRECTORY_SEPARATOR;
    }

    public function rules() {
        return array(
            array('mime_type,size,name,filename,extension', 'required'),
            array('file',
                'file',
                'types' => 'jpg,png,bmp,xls,7z,zip,rar,doc,pdf,docx,xlsx,txt',
                'maxSize' => 1024 * 1024 * 6, //6MB
                'mimeTypes' => array(
                    'image/jpeg', //jpg
                    'image/png', //png
                    'image/bmp', //bmp
                    'application/vnd.ms-excel', //xls
                    'application/x-7z-compressed', //7z
                    'application/zip', //zip
                    'application/x-rar-compressed', //rar
                    'application/msword', //doc
                    'application/pdf', //pdf
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //docx
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', //xlsx
                    'text/plain', //txt
                ),
                'maxFiles' => 1,
                'wrongMimeType' => 'File type is now allowed',
                'wrongType' => 'File type is now allowed',
                'tooLarge' => 'The file was larger than 6MB. Please upload a smaller file.',
            ),
            array('extension', 'length', 'max' => 10),
            array('mime_type', 'length', 'max' => 150),
            array('size', 'numerical', 'integerOnly' => false),
            array('name', 'length', 'max' => 150),
        );
    }

    public function attributeLabels() {
        return array(
            'file' => 'Upload files',
        );
    }

    public function beforeValidate() {

        if ($this->secureFileNames) {
            $this->filename = sha1(Yii::app()->user->id . microtime() . $this->name);
        }

        if (strlen($this->name) > 150) {
            $this->name = substr($this->name, 0, 150);
        }

        return parent::beforeValidate();
    }

    public static function fileSize($val) {
        // adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
        $sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        $retstring = '%01.2f %s';

        $lastsizestring = end($sizes);

        foreach ($sizes as $sizestring) {
            if ($val < 1024) {
                break;
            }
            if ($sizestring != $lastsizestring) {
                $val /= 1024;
            }
        }
        if ($sizestring == $sizes[0]) {
            $retstring = '%01d %s';
        } // Bytes aren't normally fractional
        return sprintf($retstring, $val, $sizestring);
    }

    public static function setCacheTempFile($filename, $data) {
        $key = sprintf(InvoiceXUpload::CACHE_KEY_TMP_INVOICE, Yii::app()->user->id, $filename);

        $added = Yii::app()->cache->set($key, $data, InvoiceXUpload::CACHE_KEY_TMP_INVOICE_EXPIRED);

        if ($added) {
            InvoiceXUpload::setCacheListTempFile($filename);
        }
    }

    public static function getCacheTempFile($filename) {
        $key = sprintf(InvoiceXUpload::CACHE_KEY_TMP_INVOICE, Yii::app()->user->id, $filename);
        return Yii::app()->cache->get($key);
    }

    /**
     * 
     * @param type $filename
     */
    public static function deleteCacheTempFile($filename) {
        $key = sprintf(InvoiceXUpload::CACHE_KEY_TMP_INVOICE, Yii::app()->user->id, $filename);
        return Yii::app()->cache->delete($key);
    }

    public static function setCacheListTempFile($filename) {
        $key = sprintf(InvoiceXUpload::CACHE_KEY_TMP_LISTINVOICE, Yii::app()->user->id);
        $listKey = InvoiceXUpload::getCacheListTempFile();

        if (empty($listKey)) {
            $listKey = array();
        }

        $listKey[] = $filename;
        Yii::app()->cache->set($key, $listKey, InvoiceXUpload::CACHE_KEY_TMP_LISTINVOICE_EXPIRED);
    }

    public static function getCacheListTempFile() {
        $key = sprintf(InvoiceXUpload::CACHE_KEY_TMP_LISTINVOICE, Yii::app()->user->id);
        return Yii::app()->cache->get($key);
    }

    public static function deleteCacheListTempFile() {
        $key = sprintf(InvoiceXUpload::CACHE_KEY_TMP_LISTINVOICE, Yii::app()->user->id);
        return Yii::app()->cache->delete($key);
    }

    public static function deleteAllTempFile() {

        $listFile = InvoiceXUpload::getCacheListTempFile();

        if ($listFile) {
            foreach ($listFile as $filename) {
                InvoiceXUpload::deleteCacheTempFile($filename);
            }
        }

        InvoiceXUpload::deleteCacheListTempFile();
    }

    public function saveTempFile() {

        $fileTmpPath = $this->tempDir . $this->filename;

        //Move our file to our temporary dir
        if (!$this->file->saveAs($fileTmpPath)) {
            return false;
        }

        chmod($fileTmpPath, 0664);

        $data = array(
            'path' => $fileTmpPath,
            'ext' => $this->extension,
            'filename' => $this->filename,
            'size' => $this->size,
            'mime' => $this->mime_type,
            'name' => $this->name
        );

        $this->setCacheTempFile($this->filename, $data);

        return true;
    }

    public function saveInvoiceFile($paymentId) {

        $yeahMonth = date('Ym');

        //move file
        $filePathTarget = $this->makeInvoiceDir($yeahMonth) . $this->filename;
        if (!$this->file->saveAs($filePathTarget)) {
            return false;
        }

        chmod($filePathTarget, 0664);

        $model = new InvoiceAttachment();
        $model->payment_id = $paymentId;
        $model->file_path = $yeahMonth . '/' . $this->filename;
        $model->name = $this->name;
        $model->minetype = $this->mime_type;
        $model->extension = $this->extension;

        if (!$model->save()) {
            return false;
        }

        return $model->id;
    }

    public static function isValidImage($file) {

        if (function_exists('exif_imagetype')) {
            return @exif_imagetype($file);
        }

        $imageInfo = @getimagesize($file);
        return $imageInfo && $imageInfo[0] && $imageInfo[1];
    }

    private function makeInvoiceDir($yeahMonth) {

        $path = $this->targetDir . 'invoices' . DIRECTORY_SEPARATOR . $yeahMonth . DIRECTORY_SEPARATOR;

        if (!is_dir($path) && !mkdir($path, 0774, true)) {
            throw new CException('Cant make directory');
        }

        chmod($path, 0774);

        return $path;
    }

    public static function isImage($mime) {
        $arrImageType = array(
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/bmp',
        );

        return in_array($mime, $arrImageType);
    }

}
