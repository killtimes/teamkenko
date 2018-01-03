<?php

class CloneProcessForm extends CFormModel {

    public $template_id;
    public $name;

    public function rules() {
        return array(
            array('template_id,name', 'required'),
            array('name', 'length', 'max' => 150),
            array('template_id', 'numerical', 'integerOnly' => true),
        );
    }

    public function attributeLabels() {
        return array(
            'template_id' => "Process Template",
            'name' => "Name",
        );
    }

}
