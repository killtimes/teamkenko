<?php

class XUploadForm extends CFormModel {

    public $file;
    public $mime_type;
    public $size;
    public $name;
    public $filename;
    public $hashname;
    protected $tempDir;
    protected $targetDir;

    /**
     * @var boolean dictates whether to use sha1 to hash the file names
     * along with time and the user id to make it much harder for malicious users
     * to attempt to delete another user's file
     */
    public $secureFileNames = false;

    const CACHE_KEY_TMP_FILE = "tmp:uid:%s:%s";
    const CACHE_KEY_TMP_FILE_EXPIRED = 3600;
    const CACHE_KEY_TMP_LISTFILE = "listtmp:uid:%s";
    const CACHE_KEY_TMP_LISTFILE_EXPIRED = 3600;

    public function init() {
        parent::init();
        $this->targetDir = Yii::getPathOfAlias('upload_dir') . DIRECTORY_SEPARATOR;
        $this->tempDir = Yii::getPathOfAlias('upload_tmp_dir') . DIRECTORY_SEPARATOR;
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('mime_type,size,name,filename,hashname', 'required'),
            array('file', 'file',
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
            array('mime_type', 'length', 'max' => 150),
            array('size', 'numerical', 'integerOnly' => false),
            array('name', 'length', 'max' => 150),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'file' => 'Upload files',
        );
    }

    public function getReadableFileSize($retstring = null) {
        // adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
        $sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        if ($retstring === null) {
            $retstring = '%01.2f %s';
        }

        $lastsizestring = end($sizes);

        foreach ($sizes as $sizestring) {
            if ($this->size < 1024) {
                break;
            }
            if ($sizestring != $lastsizestring) {
                $this->size /= 1024;
            }
        }
        if ($sizestring == $sizes[0]) {
            $retstring = '%01d %s';
        } // Bytes aren't normally fractional
        return sprintf($retstring, $this->size, $sizestring);
    }

    /**
     * A stub to allow overrides of thumbnails returned
     * @since 0.5
     * @author acorncom
     * @return string thumbnail name (if blank, thumbnail won't display)
     */
    public function getThumbnailUrl($publicPath) {
        return $publicPath . $this->filename;
    }

    /**
     * Change our filename to match our own naming convention
     * @return bool
     */
    public function beforeValidate() {

        //(optional) Generate a random name for our file to work on preventing
        // malicious users from determining / deleting other users' files
        if ($this->secureFileNames) {
            $this->filename = sha1(Yii::app()->user->id . microtime() . $this->name);
            $this->filename .= "." . $this->file->getExtensionName();
        }

        if (strlen($this->name) > 150) {
            $this->name = substr($this->name, 0, 150);
        }


        return parent::beforeValidate();
    }

    public static function slugify($str) {
        $str = preg_replace('/[^A-Za-z0-9]/', ' ', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $str = str_replace(' ', '-', trim($str));
        return mb_strtoupper($str);
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
            'filename' => $this->filename,
            'size' => $this->size,
            'mime' => $this->mime_type,
            'name' => $this->name,
            'hashname'=>$this->hashname
        );

        $this->setCacheTempFile($this->hashname, $data);

        return true;
    }

    public static function deleteCacheTempFile($filename) {
        $key = sprintf(self::CACHE_KEY_TMP_FILE, Yii::app()->user->id, $filename);
        return Yii::app()->cache->delete($key);
    }

    public static function setCacheTempFile($filename, $data) {

        $key = sprintf(self::CACHE_KEY_TMP_FILE, Yii::app()->user->id, $filename);

        $added = Yii::app()->cache->set($key, $data, self::CACHE_KEY_TMP_FILE_EXPIRED);

        if ($added) {
            self::setCacheListTempFile($filename);
        }
    }
    
    public static function getCacheTempFile($filename) {
        $key = sprintf(self::CACHE_KEY_TMP_FILE, Yii::app()->user->id, $filename);
        return Yii::app()->cache->get($key);
    }

    public static function setCacheListTempFile($filename) {
        $key = sprintf(self::CACHE_KEY_TMP_LISTFILE, Yii::app()->user->id);
        $listKey = self::getCacheListTempFile();

        if (empty($listKey)) {
            $listKey = array();
        }

        $listKey[] = $filename;
        Yii::app()->cache->set($key, $listKey, self::CACHE_KEY_TMP_LISTFILE_EXPIRED);
    }

    public static function getCacheListTempFile() {
        $key = sprintf(self::CACHE_KEY_TMP_LISTFILE, Yii::app()->user->id);
        return Yii::app()->cache->get($key);
    }

    public static function deleteCacheListTempFile() {
        $key = sprintf(self::CACHE_KEY_TMP_LISTFILE, Yii::app()->user->id);
        return Yii::app()->cache->delete($key);
    }

    public static function deleteAllTempFile() {

        $listFile = self::getCacheListTempFile();

        if ($listFile) {
            foreach ($listFile as $filename) {
                self::deleteCacheTempFile($filename);
            }
        }

        self::deleteCacheListTempFile();
    }

}
