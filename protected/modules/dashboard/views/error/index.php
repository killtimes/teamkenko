<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
    'Error',
);
?>


<div class="panel panel-default panel-container">
    <div class="panel-body">
        <h2>Error <?php echo $code; ?></h2>
        <div class="error">
            <?php echo CHtml::encode($message); ?>
        </div>
        <div>
            <?php if(YII_DEBUG){ ?>
            <pre><?php print_r ($trace); ?></pre>
            <?php } ?>
        </div>
    </div>
</div>