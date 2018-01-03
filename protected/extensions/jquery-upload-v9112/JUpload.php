<?php

Yii::import('zii.widgets.jui.CJuiInputWidget');

class JUpload extends CJuiInputWidget {

    public $url;
    public $multiple = false;
    public $autoUpload = false;
    public $showForm = true;
    public $template = 'tpl';

    public function init() {
        parent::init();
        $this->publishAssets();
    }

    public static function publishGalleryAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);

        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCssFile($baseUrl . '/css/blueimp-gallery.min.css');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/vendor/jquery.blueimp-gallery.min.js', CClientScript::POS_END);
//            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/vendor/text.js', CClientScript::POS_END);

        }
    }

    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);

        if (is_dir($assets)) {

            Yii::app()->clientScript->registerCssFile($baseUrl . '/css/blueimp-gallery.min.css');
            Yii::app()->clientScript->registerCssFile($baseUrl . '/css/jquery.fileupload.css');
            Yii::app()->clientScript->registerCssFile($baseUrl . '/css/jquery.fileupload-ui.css');
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/vendor/tmpl.min.js', CClientScript::POS_END);
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/vendor/jquery.blueimp-gallery.min.js', CClientScript::POS_END);
//            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/vendor/text.js', CClientScript::POS_END);

            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.iframe-transport.js', CClientScript::POS_END);
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.fileupload.js', CClientScript::POS_END);
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.fileupload-process.js', CClientScript::POS_END);
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.fileupload-validate.js', CClientScript::POS_END);
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.fileupload-ui.js', CClientScript::POS_END);
        } else {
            throw new CHttpException(500, __CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

    public function run() {

        if (!isset($this->htmlOptions['enctype'])) {
            $this->htmlOptions['enctype'] = 'multipart/form-data';
        }

        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = "jupload-form";
        }

        $this->options['url'] = $this->url;
        $this->options['autoUpload'] = $this->autoUpload;

        if (!$this->multiple) {
            $this->options['maxNumberOfFiles'] = 1;
        }

        $options = CJavaScript::encode($this->options);

        Yii::app()->clientScript->registerScript(__CLASS__ . '#' . $this->htmlOptions['id'], "jQuery('#{$this->htmlOptions['id']}').fileupload({$options});", CClientScript::POS_READY);

        $htmlOptions = array('multiple' => $this->multiple);

        $this->render('form', compact('htmlOptions'));
        $this->render($this->template);
    }

    public function showResult() {
        $this->render('result');
    }

}
