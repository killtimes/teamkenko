<?php

class MessageActivityForm extends CFormModel {

    public $message;
//    public $file_source;
//    public $file_name;
//    public $file_label;
//    public $doc_type;
//    public $doc_code;
//    public $doc_date;
    public $listAttachments;

    const SOURCE_TYPE_LOCAL = 1;
    const SOURCE_TYPE_DROPBOX = 2;

    public function rules() {
        return array(
            array('listAttachments', 'type', 'type' => 'array', 'allowEmpty' => true),
            array('message', 'checkMessage'),
            array('message', 'length', 'max' => 500),
//            array('doc_code', 'length', 'max' => 30),
//            array('file_label', 'length', 'max' => 100),
//            array(
//                'doc_date',
//                'date',
//                'format' => array(
//                    'dd-MM-yyyy'
//                ),
//            ),
//            array('doc_type', 'in', 'range' => array_keys(Document::itemAlias('Type'))),
//            array('file_source', 'in', 'range' => array(self::SOURCE_TYPE_LOCAL, self::SOURCE_TYPE_DROPBOX), 'message' => 'Invalid request')
        );
    }

    public function validateFiles() {

        if (!empty($this->listAttachments) && is_array($this->listAttachments)) {

            foreach ($this->listAttachments as $hash => $data) {

                if (!in_array($data['file_source'], array(Document::SOURCE_TYPE_LOCAL, Document::SOURCE_TYPE_DROPBOX))) {
                    $this->addError('file_source', 'Invalid file source');
                    return false;
                }

                if (empty($data['file_label'])) {
                    $this->addError('file_label', 'File name cannot be blank');
                    return false;
                } else if ((int) $data['doc_type'] <= 0) {
                    $this->addError('doc_type', 'Document type cannot be blank');
                    return false;
                } else if (!array_key_exists($data['doc_type'], Document::itemAlias('Type'))) {
                    $this->addError('doc_type', 'Document type is invalid');
                    return false;
                } else if ($data['doc_type'] == Document::DOC_TYPE_INVOICE && empty($data['doc_code'])) {
                    $this->addError('doc_code', 'Invoice code can not be blank (e.g Invoice number, Transaction number,...)');
                    return false;
                } else if ($data['doc_type'] == Document::DOC_TYPE_INVOICE && empty($data['doc_date'])) {
                    $this->addError('doc_date', 'Invoice date can not be blank');
                    return false;
                }else if ($data['doc_type'] == Document::DOC_TYPE_PURCHASE_ORDER && empty($data['doc_date'])) {
                    $this->addError('doc_date', 'Document date (Delivery date) cannot be blank');
                    return false;
                } else if (!empty($data['doc_date'])) {
                    list($d, $m, $y) = explode('-', $data['doc_date']);
                    if (!CTimestamp::isValidDate($y, $m, $d)) {
                        $this->addError('doc_date', 'Invoice date is invalid');
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function checkMessage($attribute_name, $param) {

        if (empty($this->message)) {

            if (empty($this->listAttachments)) {
                $this->addError('message', 'Please enter your message or upload your file.');
                return false;
            } else {
                return $this->validateFiles();
            }
        } else {
            return $this->validateFiles();
        }

        return true;
    }

    public function attributeLabels() {
        return array(
            'message' => 'Message',
            'file_source' => 'File Source',
            'file_name' => 'File Name',
            'file_label' => 'File Name',
            'doc_type' => 'Document Type',
            'doc_code' => 'Document Code',
        );
    }

}
