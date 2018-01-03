<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Create :type', array(':type' => Rights::getAuthItemTypeName($_GET['type']))),
);

echo $this->renderPartial('webroot.themes.theme1.views.includes.operationbar', array('controller' => $this));

?>

<div class="createAuthItem">
    <div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <legend><?php
                    echo Rights::t('core', 'Create :type', array(
                        ':type' => Rights::getAuthItemTypeName($_GET['type']),
                    ));
                    ?></legend>
                    
                <?php echo $this->renderPartial('/_flash'); ?>

                <?php $this->renderPartial('_form', array('model' => $formModel)); ?>
            </fieldset>
        </div>
    </div>
</div>