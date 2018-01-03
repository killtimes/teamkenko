<?php

echo TbHtml::buttonGroup(array(
    array(
        'label' => UserModule::t('Create User'),
        'url' => array('/user/admin/create'),
        'icon' => TbHtml::ICON_PLUS,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT
    ),
    array(
        'label' => UserModule::t('List Users'),
        'url' => array('/user/admin'),
        'icon' => TbHtml::ICON_LIST,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT
    ),
    array(
        'label' => UserModule::t('Online Users'),
        'url' => array('/user/admin/online'),
        'icon' => TbHtml::ICON_EYE_OPEN,
        'color' => TBHtml::BUTTON_COLOR_DEFAULT
    )
));
