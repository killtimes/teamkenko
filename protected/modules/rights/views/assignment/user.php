<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Assignments') => array('assignment/view'),
    $model->getName(),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.operationbar', array('controller' => $this));
?>

<?php echo $this->renderPartial('/_flash', array('controller' => $this)); ?>

<div id="userAssignments" class="panel-container">

    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend>
                    <?php
                    echo Rights::t('core', 'Assignments for [:username]', array(
                        ':username' => $model->getName()
                    ));
                    ?>
                </legend>


                <?php
                $this->widget('bootstrap.widgets.TbGridView', array(
                    'dataProvider' => $dataProvider,
                    'template' => '{items}',
                    'hideHeader' => false,
                    'emptyText' => Rights::t('core', 'This user has not been assigned any items.'),
                    'htmlOptions' => array('class' => 'grid-view user-assignment-table mini'),
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
                            'value' => '$data->getNameText()',
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
                            'value' => '$data->getRevokeAssignmentLink()',
                        ),
                    )
                ));
                ?>
            </fieldset>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend><?php
                    echo Rights::t('core', 'Assign item to [:username]', array(
                        ':username' => $model->getName()
                    ));
                    ?></legend>

                <?php if ($formModel !== null): ?>

                    <?php
                    $this->renderPartial('_form', array(
                        'model' => $formModel,
                        'itemnameSelectOptions' => $assignSelectOptions,
                    ));
                    ?>


                <?php else: ?>

                    <p class="text-info"><?php echo Rights::t('core', 'No assignments available to be assigned to this user.'); ?>

                    <?php endif; ?>
            </fieldset>
        </div>
    </div>

</div>
