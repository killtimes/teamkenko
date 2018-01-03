<?php
/**
 * WhBasicFileUpload widget class
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @copyright Copyright &copy; 2amigos.us 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package YiiWheels.widgets.fileupload
 * @uses YiiStrap.helpers.TbArray
 */
Yii::import('bootstrap.helpers.TbArray');

class WhBasicFileUpload extends CInputWidget
{
    /**
     * Editor options that will be passed to the editor
     * @see http://imperavi.com/redactor/docs/
     */
    public $pluginOptions = array();

    /**
     * @var string upload action url
     */
    public $uploadAction;

    /**
     * Widget's initialization method
     * @throws CException
     */
    public function init()
    {
        if ($this->uploadAction === null) {
            throw new CException(Yii::t('zii', '"uploadAction" attribute cannot be blank'));
        }

        $this->attachBehavior('ywplugin', array('class' => 'yiiwheels.behaviors.WhPlugin'));
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        $this->renderField();
        $this->registerClientScript();
    }

    /**
     * Renders the input file field
     */
    public function renderField()
    {
        list($name, $id) = $this->resolveNameID();

        TbArray::defaultValue('id', $id, $this->htmlOptions);
        TbArray::defaultValue('name', $name, $this->htmlOptions);
        $this->htmlOptions['data-url'] = $this->uploadAction;
        $this->pluginOptions['url']    = $this->uploadAction;
        if ($this->hasModel()) {
            echo CHtml::activeFileField($this->model, $this->attribute, $this->htmlOptions);

        } else {
            echo CHtml::fileField($name, $this->value, $this->htmlOptions);
        }
    }

    /**
     * Registers client script
     */
    public function registerClientScript()
    {
        /* publish assets dir */
        $path      = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $assetsUrl = $this->getAssetsUrl($path);

        /* @var $cs CClientScript */
        $cs = Yii::app()->getClientScript();

        $cs->registerCssFile($assetsUrl . '/css/jquery.fileupload-ui.css','',100);
        $cs->registerScriptFile($assetsUrl . '/js/vendor/jquery.ui.widget.js', CClientScript::POS_END);
        $cs->registerScriptFile($assetsUrl . '/js/jquery.iframe-transport.js', CClientScript::POS_END);
        $cs->registerScriptFile($assetsUrl . '/js/jquery.fileupload.js', CClientScript::POS_END);
        $cs->registerScriptFile($assetsUrl . '/js/jquery.fileupload-ui.js', CClientScript::POS_END);


        /* initialize plugin */
        $selector = '#' . TbArray::getValue('id', $this->htmlOptions, $this->getId());

        $this->getApi()->registerPlugin('fileupload', $selector, $this->pluginOptions);
    }

}