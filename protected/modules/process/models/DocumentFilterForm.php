<?php

class DocumentFilterForm extends CFormModel {

    //FILTER FORM
    const FILTERTYPE_UPLOADDATE = 1;
    const FILTERTYPE_BIZDATE = 2;
    //key cache
    const CKEY_DOCUMENT_FILLTER = 'document:filter:userid:%s';

    public $filter_by;
    public $start_date;
    public $end_date;
    public $date_range;
    public $supplier_id;
    public $shop_id;
    public $doc_code;
    public $doc_type;
    public $title;

    public function rules() {
        return array(
            array('date_range,filter_by,start_date,end_date, supplier_id, shop_id, doc_type,doc_code, title', 'safe'),
            array('filter_by', 'in', 'range' => array(self::FILTERTYPE_UPLOADDATE, self::FILTERTYPE_BIZDATE)),
            array('doc_type', 'in', 'range' => array(Document::DOC_TYPE_INVOICE, Document::DOC_TYPE_CREDIT_NOTE, Document::DOC_TYPE_DELIVERY_NOTE, Document::DOC_TYPE_STOCK_TRANSFER, Document::DOC_TYPE_PURCHASE_ORDER),'message'=>'Invalid document type'), 
        );
    }

    public function setCacheQuery($params) {
        Yii::app()->cache->set(sprintf(self::CKEY_DOCUMENT_FILLTER, Yii::app()->user->id), $params);
    }

    public function getCacheQuery() {
        return Yii::app()->cache->get(sprintf(self::CKEY_DOCUMENT_FILLTER, Yii::app()->user->id));
    }

    public function parseDateRange() {

        $fTime = strtotime("first day of this month");
        $eTime = strtotime("last day of this month");
        $this->start_date = date("Y-m-d H:i:s", $fTime);
        $this->end_date = date("Y-m-d H:i:s", $eTime);

        if (!empty($this->date_range) && strpos($this->date_range, '-') > 0) {
            list($start, $end) = explode('-', $this->date_range);

            $start = str_replace('/', '-', $start);
            $startTime = strtotime($start);
            $end = str_replace('/', '-', $end);
            $endTime = strtotime($end);

            if (checkdate(date('m', $startTime), date('d', $startTime), date('Y', $startTime))) {
                $this->start_date = date('Y-m-d H:i:s', $startTime);
                $fTime = $startTime;
            }

            if (checkdate(date('m', $endTime), date('d', $endTime), date('Y', $endTime))) {
                $this->end_date = date('Y-m-d H:i:s', $endTime);
                $eTime = $endTime;
            }

            $this->date_range = date('d/m/Y', $fTime) . ' - ' . date('d/m/Y', $eTime);
        }

        if ($this->filter_by > 0) {
            $this->date_range = date('d/m/Y', $fTime) . ' - ' . date('d/m/Y', $eTime);
        } else {
            $this->date_range = '';
        }
    }

    public function search() {

        $criteria = new CDbCriteria;

        if ($this->filter_by == self::FILTERTYPE_BIZDATE) {
            $this->start_date = date('Y-m-d', strtotime($this->start_date));
            $this->end_date = date('Y-m-d', strtotime($this->end_date));

            $criteria->addCondition("doc_date>='{$this->start_date}'");
            $criteria->addCondition("doc_date<='{$this->end_date}'");
        } else if ($this->filter_by == self::FILTERTYPE_UPLOADDATE) {
            $criteria->addCondition("create_date>='{$this->start_date}'");
            $criteria->addCondition("create_date<='{$this->end_date}'");
        }

        $criteria->compare('title', $this->title, true);
        $criteria->compare('shop_id', $this->shop_id);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('doc_type', $this->doc_type);
        $criteria->compare('doc_code', $this->doc_code, true);
        $criteria->compare('status', Document::STATUS_ACTIVE);

        return $criteria;
    }

}
