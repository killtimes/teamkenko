<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::getAuthItemTypeNamePlural($model->type) => Rights::getAuthItemRoute($model->type),
    $model->name,
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.operationbar', array('controller' => $this));
?>

<div id="updatedAuthItem">
    <?php echo $this->renderPartial('/_flash'); ?>
    <div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <legend><?php
                    echo Rights::t('core', 'Update :type [:name]', array(
                        ':name' => $model->name,
                        ':type' => Rights::getAuthItemTypeName($model->type),
                    ));
                    ?></legend>
                <?php $this->renderPartial('_form', array('model' => $formModel)); ?>

            </fieldset>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend><?php echo Rights::t('core', 'Relations'); ?></legend>
                <?php if ($model->name !== Rights::module()->superuserName): ?>

                    <div class="parents">

                        <h4><?php echo Rights::t('core', 'Parents'); ?></h4>

                        <?php
                        $this->widget('bootstrap.widgets.TbGridView', array(
                            'dataProvider' => $parentDataProvider,
                            'template' => '{items}',
                            'hideHeader' => false,
                            'emptyText' => Rights::t('core', 'This item has no parents.'),
                            'htmlOptions' => array('class' => 'grid-view parent-table mini'),
                            'type' => array(
                                TbHtml::GRID_TYPE_HOVER,
                                TbHtml::GRID_TYPE_CONDENSED
                            ),
                            'columns' => array(
                                array(
                                    'name' => 'name',
                                    'header' => Rights::t('core', 'Name'),
                                    'type' => 'raw',
                                    'htmlOptions' => array('class' => 'name-column'),
                                    'value' => '$data->getNameLink()',
                                ),
                                array(
                                    'name' => 'type',
                                    'header' => Rights::t('core', 'Type'),
                                    'type' => 'raw',
                                    'htmlOptions' => array('class' => 'type-column'),
                                    'value' => '$data->getTypeText()',
                                ),
                                array(
                                    'header' => '&nbsp;',
                                    'type' => 'raw',
                                    'htmlOptions' => array('class' => 'actions-column'),
                                    'value' => '',
                                ),
                            )
                        ));
                        ?>

                    </div>

                    <div class="children">

                        <h4><?php echo Rights::t('core', 'Children'); ?></h4>

                        <?php
                        $this->widget('bootstrap.widgets.TbGridView', array(
                            'dataProvider' => $childDataProvider,
                            'template' => '{items}',
                            'hideHeader' => false,
                            'emptyText' => Rights::t('core', 'This item has no children.'),
                            'htmlOptions' => array('class' => 'grid-view parent-table mini'),
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
                                    'value' => '$data->getNameLink()',
                                ),
                                array(
                                    'name' => 'type',
                                    'header' => Rights::t('core', 'Type'),
                                    'type' => 'raw',
                                    'htmlOptions' => array('class' => 'type-column'),
                                    'value' => '$data->getTypeText()',
                                ),
                                array(
                                    'header' => '&nbsp;',
                                    'type' => 'raw',
                                    'htmlOptions' => array('class' => 'actions-column'),
                                    'value' => '$data->getRemoveChildLink()',
                                ),
                            )
                        ));
                        ?>

                    </div>

                    <div class="addChild">

                        <legend><?php
                            echo Rights::t('core', 'Add Child for :type [:name]', array(
                                ':name' => $model->name,
                                ':type' => Rights::getAuthItemTypeName($model->type)
                            ));
                            ?></legend>

                        <?php if ($childFormModel !== null): ?>

                            <?php
                            $this->renderPartial('_childForm', array(
                                'model' => $childFormModel,
                                'itemnameSelectOptions' => $childSelectOptions,
                            ));
                            ?>

                        <?php else: ?>

                            <div class="alert alert-info"><?php echo Rights::t('core', 'No children available to be added to this item.'); ?></div>

                        <?php endif; ?>

                    </div>

                <?php else: ?>

                    <div class="alert alert-info">
                        <?php echo Rights::t('core', 'No relations need to be set for the superuser role.'); ?><br />
                        <?php echo Rights::t('core', 'Super users are always granted access implicitly.'); ?>
                    </div>

                <?php endif; ?>
            </fieldset>
        </div>
    </div>

</div>