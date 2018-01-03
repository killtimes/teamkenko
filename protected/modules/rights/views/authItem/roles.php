<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Roles'),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.operationbar', array('controller' => $this));
?>

<div id="roles">
    
    <?php echo $this->renderPartial('/_flash', array('controller' => $this)); ?>

    <div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <legend><?php echo Rights::t('core', 'Roles'); ?></legend>

                <p>
                    <?php echo Rights::t('core', 'A role is group of permissions to perform a variety of tasks and operations, for example the authenticated user.'); ?><br />
                    <?php echo Rights::t('core', 'Roles exist at the top of the authorization hierarchy and can therefore inherit from other roles, tasks and/or operations.'); ?>
                </p>

                <p><?php
                    echo TbHtml::buttonGroup(array(
                        array('label' => Rights::t('core', 'Create a new role'), 'url' => array('/rights/authItem/create', 'type' => CAuthItem::TYPE_ROLE), 'color' => TbHtml::BUTTON_COLOR_PRIMARY, 'icon' => TbHtml::ICON_PLUS_SIGN),
                    ));
                    ?></p>

                <?php
                $this->widget('bootstrap.widgets.TbGridView', array(
                    'dataProvider' => $dataProvider,
                    'template' => '{items}',
                    'emptyText' => Rights::t('core', 'No roles found.'),
                    'htmlOptions' => array('class' => 'grid-view role-table'),
                    'type' => array(
                        TbHtml::GRID_TYPE_HOVER,
                        TbHtml::GRID_TYPE_CONDENSED,
                    ),
                    'columns' => array(
                        array(
                            'name' => 'name',
                            'header' => Rights::t('core', 'Name'),
                            'type' => 'raw',
                            'htmlOptions' => array('class' => 'name-column'),
                            'value' => '$data->getGridNameLink()',
                        ),
                        array(
                            'name' => 'description',
                            'header' => Rights::t('core', 'Description'),
                            'type' => 'raw',
                            'htmlOptions' => array('class' => 'description-column'),
                        ),
                        array(
                            'name' => 'bizRule',
                            'header' => Rights::t('core', 'Business rule'),
                            'type' => 'raw',
                            'htmlOptions' => array('class' => 'bizrule-column'),
                            'visible' => Rights::module()->enableBizRule === true,
                        ),
                        array(
                            'name' => 'data',
                            'header' => Rights::t('core', 'Data'),
                            'type' => 'raw',
                            'htmlOptions' => array('class' => 'data-column'),
                            'visible' => Rights::module()->enableBizRuleData === true,
                        ),
                        array(
                            'header' => '&nbsp;',
                            'type' => 'raw',
                            'htmlOptions' => array('class' => 'actions-column'),
                            'value' => '$data->getDeleteRoleLink()',
                        ),
                    )
                ));
                ?>

                <p class="text-info"><span class="label label-info">Note!</span> <?php echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?></p>
            </fieldset>
        </div>
    </div>
</div>