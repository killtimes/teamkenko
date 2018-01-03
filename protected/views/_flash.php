<?php if(Yii::app()->user->hasFlash('successMessage')){ ?>
<div class='alert alert-success panel-container'>
    <span class="glyphicon glyphicon-ok"></span> <strong><?php echo Yii::app()->user->getFlash('successMessage'); ?></strong>
</div>
<?php } ?>

<?php if(Yii::app()->user->hasFlash('errorMessage')){ ?>
<div class='alert alert-danger panel-container'>
    <strong><?php echo Yii::app()->user->getFlash('errorMessage'); ?></strong>
</div>
<?php } ?>
