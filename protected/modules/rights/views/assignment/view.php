<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Assignments'),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.operationbar', array('controller' => $this));
?>

<div id="assignments" class="panel-container">
    <div class="panel panel-default">

        <div class="panel-body">
            <fieldset>
                <legend><?php echo Rights::t('core', 'Assignments'); ?></legend>

                <p>
                    <?php echo Rights::t('core', 'Here you can view which permissions has been assigned to each user.'); ?>
                </p>

                <?php
                $this->widget('bootstrap.widgets.TbGridView', array(
                    'dataProvider' => $dataProvider,
                    'template' => "{items}\n{pager}",
                    'emptyText' => Rights::t('core', 'No users found.'),
                    'htmlOptions' => array('class' => 'grid-view assignment-table'),
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
                            'value' => '$data->getAssignmentNameLink()',
                        ),
                        array(
                            'name' => 'assignments',
                            'header' => Rights::t('core', 'Roles'),
                            'type' => 'raw',
                            'htmlOptions' => array('class' => 'role-column'),
                            'value' => '$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
                        ),
                        array(
                            'name' => 'assignments',
                            'header' => Rights::t('core', 'Tasks'),
                            'type' => 'raw',
                            'htmlOptions' => array('class' => 'task-column'),
                            'value' => '$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
                        ),
                        array(
                            'name' => 'assignments',
                            'header' => Rights::t('core', 'Operations'),
                            'type' => 'raw',
                            'htmlOptions' => array('class' => 'operation-column'),
                            'value' => '$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
                        ),
                    )
                ));
                ?>
            </fieldset>
        </div>

    </div>


</div>