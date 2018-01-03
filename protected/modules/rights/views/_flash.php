<div class="panel-container">

    <?php if (Yii::app()->user->hasFlash('RightsSuccess') === true): ?>

        <div class="alert alert-success">


            <?php echo Yii::app()->user->getFlash('RightsSuccess'); ?>

        </div>

    <?php endif; ?>

    <?php if (Yii::app()->user->hasFlash('RightsError') === true): ?>

        <div class="alert alert-danger">

            <?php echo Yii::app()->user->getFlash('RightsError'); ?>

        </div>

    <?php endif; ?>

</div>