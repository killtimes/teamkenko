<?php

/**
 * Rights base controller class file.
 *
 * @author Christoffer Niska <cniska@live.com>
 * @copyright Copyright &copy; 2010 Christoffer Niska
 * @since 0.6
 */
class RController extends CController {

    /**
     * @property string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @property array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @property array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $roles = array();

    /**
     * The filter method for 'rights' access filter.
     * This filter is a wrapper of {@link CAccessControlFilter}.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     */
    public function filterRights($filterChain) {
        $filter = new RightsFilter;
        $filter->allowedActions = $this->allowedActions();
        $filter->filter($filterChain);
    }

    /**
     * @return string the actions that are always allowed separated by commas.
     */
    public function allowedActions() {
        return '';
    }

    /**
     * Denies the access of the user.
     * @param string $message the message to display to the user.
     * This method may be invoked when access check fails.
     * @throws CHttpException when called unless login is required.
     */
    public function accessDenied($message = null) {
        if ($message === null)
            $message = Rights::t('core', 'You are not authorized to perform this action.');

        $user = Yii::app()->getUser();
        if ($user->isGuest === true)
            $user->loginRequired();
        else
            throw new CHttpException(403, $message);
    }

    public function notfoundException() {
        throw new CHttpException(404, 'Page not found');
    }

    private $_assetsBase;

    public function getAssetsBase() {
        if ($this->_assetsBase === null) {
            $this->_assetsBase = Yii::app()->assetManager->publish(
                    Yii::getPathOfAlias('application.assets'), false, -1
            );
        }

        return $this->_assetsBase;
    }

    public function checkAccess($operation, $params = array(), $throwException = false) {

        $b = Yii::app()->user->checkAccess($operation, $params);

        if ($throwException) {

            if ($b == false) {
                $this->accessDenied();
            }
        }

        return $b;
    }

    public function renderPartialAjax($view, $data = null) {

        $html = parent::renderPartial($view, $data, true, false);

        $cssFiles = Yii::app()->clientscript->cssFiles;
        $scriptFiles = Yii::app()->clientscript->scriptFiles;
        $scripts = array_values(Yii::app()->clientscript->scripts);

        $cssTags = '';
        $scriptTags = '';
        $jsInline = '';

        foreach ($cssFiles as $path => $css) {
            $cssTags.= CHtml::cssFile($path);
        }

        foreach ($scriptFiles as $scriptArr) {

            foreach ($scriptArr as $sc) {

                $scriptTags.= "$.cachedScript(\"$sc\"),";
            }
        }

        $scriptTags.= "$.Deferred(function( deferred ){ $(deferred.resolve); })";

        foreach ($scripts as $k => $js) {
            $jsInline.=implode('', $js);
        }

        $multiJs = "$.when( $scriptTags ).done(function(){ $jsInline });";

        echo $cssTags . $html . CHtml::script($multiJs);

        Yii::app()->end();
    }

    public function init() {
        $roles = Rights::getAssignedRoles(Yii::app()->user->id);
        foreach ($roles as $role) {
            $this->roles[] = $role->name;
        }
        parent::init();
    }

}
