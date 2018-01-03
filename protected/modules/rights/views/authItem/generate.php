<?php
$this->breadcrumbs = array(
    'Rights' => Rights::getBaseUrl(),
    Rights::t('core', 'Generate items'),
);
?>

<div id="generator">
    
    <?php echo $this->renderPartial('/_flash'); ?>
    <div class="panel panel-default panel-container">
        <div class="panel-body">
            <fieldset>
                <legend><?php echo Rights::t('core', 'Generate items'); ?></legend>

                <p><?php echo Rights::t('core', 'Please select which items you wish to generate.'); ?></p>

                <div class="main sm-col-12">

                    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm'); ?>

                    <table class="table items generate-item-table" border="0" cellpadding="0" cellspacing="0">

                        <tbody>

                            <tr class="application-heading-row">
                                <th colspan="3"><?php echo Rights::t('core', 'Application'); ?></th>
                            </tr>

                            <?php
                            $this->renderPartial('_generateItems', array(
                                'model' => $model,
                                'form' => $form,
                                'items' => $items,
                                'existingItems' => $existingItems,
                                'displayModuleHeadingRow' => true,
                                'basePathLength' => strlen(Yii::app()->basePath),
                            ));
                            ?>

                        </tbody>

                    </table>


                    <div class="form-group">

                        <?php
                        echo CHtml::link(Rights::t('core', 'Select all'), '#', array(
                            'onclick' => "jQuery('.generate-item-table').find(':checkbox').attr('checked', 'checked'); return false;",
                            'class' => 'selectAllLink'));
                        ?>
                        /
                        <?php
                        echo CHtml::link(Rights::t('core', 'Select none'), '#', array(
                            'onclick' => "jQuery('.generate-item-table').find(':checkbox').removeAttr('checked'); return false;",
                            'class' => 'selectNoneLink'));
                        ?>

                    </div>

                    <div class="form-group">

                        <?php echo TbHtml::submitButton(Rights::t('core', 'Generate'), array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>

                    </div>

                    <?php $this->endWidget(); ?>

                </div>
            </fieldset>
        </div>
    </div>


</div>