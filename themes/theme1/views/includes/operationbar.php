<?php
$isSystemUser = Yii::app()->getModule('rights')->isSystemUser(Yii::app()->user->name);
echo TbHtml::buttonGroup(array(
    array(
        'label' => Rights::t('core', 'Assignments'),
        'url' => array('/rights/assignment/view'),
        'icon' => TbHtml::ICON_CHECK,
        'color' => ( ($controller->id == 'assignment') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => Rights::t('core', 'Permissions'),
        'url' => array('/rights/authItem/permissions'),
        'icon' => TbHtml::ICON_LOCK,
        'color' => ( ($controller->id == 'authItem' && $controller->action->id == 'permissions') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'label' => Rights::t('core', 'Roles'),
        'url' => array('/rights/authItem/roles'),
        'icon' => TbHtml::ICON_BRIEFCASE,
        'color' => ( ($controller->id == 'authItem' && $controller->action->id == 'roles') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'visible' => $isSystemUser,
        'label' => Rights::t('core', 'Tasks'),
        'url' => array('/rights/authItem/tasks'),
        'icon' => TbHtml::ICON_TASKS,
        'color' => ( ($controller->id == 'authItem' && ($controller->action->id == 'tasks' || $controller->action->id == 'update')) ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
    array(
        'visible' => $isSystemUser,
        'label' => Rights::t('core', 'Operations'),
        'url' => array('/rights/authItem/operations'),
        'icon' => TbHtml::ICON_COG,
        'color' => ( ($controller->id == 'authItem' && $controller->action->id == 'operations') ? TbHtml::BUTTON_COLOR_PRIMARY : TBHtml::BUTTON_COLOR_DEFAULT)
    ),
));
