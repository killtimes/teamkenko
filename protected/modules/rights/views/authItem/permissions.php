<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Permissions'),
);
?>

<div id="permissions">
    <?php echo $this->renderPartial('webroot.themes.theme1.views.includes.operationbar', array('controller' => $this)); ?>

    <div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <legend><?php echo Rights::t('core', 'Permissions'); ?></legend>

                <p>
                    <?php echo Rights::t('core', 'Here you can view and manage the permissions assigned to each role.'); ?><br />
                    <?php
                    echo Rights::t('core', 'Authorization items can be managed under {roleLink}, {taskLink} and {operationLink}.', array(
                        '{roleLink}' => CHtml::link(Rights::t('core', 'Roles'), array('authItem/roles')),
                        '{taskLink}' => CHtml::link(Rights::t('core', 'Tasks'), array('authItem/tasks')),
                        '{operationLink}' => CHtml::link(Rights::t('core', 'Operations'), array('authItem/operations')),
                    ));
                    ?>
                </p>

                <?php
                $this->widget('bootstrap.widgets.TbGridView', array(
                    'dataProvider' => $dataProvider,
                    'template' => '{items}',
                    'emptyText' => Rights::t('core', 'No authorization items found.'),
                    'htmlOptions' => array('class' => 'grid-view permission-table'),
                    'type' => array(
                        TbHtml::GRID_TYPE_HOVER,
                        TbHtml::GRID_TYPE_CONDENSED
                    ),
                    'columns' => $columns,
                ));
                ?>

                <p class="text-info"><span class="label label-info">Note!</span> <?php echo Rights::t('core', 'Hover to see from where the permission is inherited.'); ?></p>
            </fieldset>
        </div>
    </div>

    <script type="text/javascript">

        /**
         * Attach the tooltip to the inherited items.
         */
        jQuery('.inherited-item').rightsTooltip({
            title: '<?php echo Rights::t('core', 'Source'); ?>: '
        });

        /**
         * Hover functionality for rights' tables.
         */
        $('#rights tbody tr').hover(function () {
            $(this).addClass('hover'); // On mouse over
        }, function () {
            $(this).removeClass('hover'); // On mouse out
        });

    </script>

</div>
