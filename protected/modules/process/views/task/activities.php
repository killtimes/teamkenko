
<?php
//Yii::app()->clientScript->registerScript('search', "
//lightbox.option({
//      'resizeDuration': 200,
//      'fadeDuration':200,
//      'wrapAround': true
//    });
//", CClientScript::POS_READY);
?>
<div class="activity-container">
    <div class="message-box">
        <h4>Informations</h4>
        <div class="form-group">
            <label class="col-md-2 control-label text-left">Task</label>
            <div class="col-md-7">
                <label class="control-label text-primary"><?php echo CHtml::encode(Task::model()->getById($task->task_id)->name); ?></label>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="form-group">
            <label class="col-md-2 control-label text-left">Assigned to</label>
            <div class="col-md-7">
                <label class="control-label text-default"><?php echo CHtml::encode(Profile::model()->getById($task->assign_id)->getFullName()); ?></label>
            </div>
        </div>
        <div class="clearfix"></div>

        <?php
        if ($task->process !== null) {
            $shop = Shop::model()->getById($task->process->shop_id)->name;
            $supplier = Supplier::model()->getById($task->process->supplier_id)->name;
        } else {
            if ($task->shop_id > 0) {
                $shop = Shop::model()->getById($task->shop_id)->name;
            }

            if ($task->supplier_id > 0) {
                $supplier = Supplier::model()->getById($task->supplier_id)->name;
            }
        }
        ?>

        <?php if ($task->process !== null) { ?>
            <div class="form-group">
                <label class="col-md-2 control-label text-left">Process</label>
                <div class="col-md-9">
                    <label class="control-label text-primary"><?php echo CHtml::encode($task->process->name); ?></label>
                </div>
            </div>
            <div class="clearfix"></div>
        <?php } ?>

        <?php if (!empty($shop)) { ?>
            <div class="form-group">
                <label class="col-md-2 control-label text-left">Shop</label>
                <div class="col-md-7">
                    <label class="control-label text-default"><?php echo CHtml::encode($shop); ?></label>
                </div>
            </div>
            <div class="clearfix"></div>
        <?php } ?>
        <?php if (!empty($supplier)) { ?>
            <div class="form-group">
                <label class="col-md-2 control-label text-left">Contact</label>
                <div class="col-md-7">
                    <label class="control-label text-default"><?php echo CHtml::encode($supplier); ?></label>
                </div>
            </div>
            <div class="clearfix"></div>
        <?php } ?>

        <div class="form-group">
            <label class="col-md-2 control-label text-left">Status</label>
            <div class="col-md-7">
                <label class="control-label text-default"><?php echo TaskProcess::stageAlias($task->stage); ?></label>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php if($task->is_att_mandatory){ ?>
        <div class="alert alert-danger col-md-6">*Note: Attachment is mandatory on this task.</div>
            <div class="clearfix"></div>
        <?php } ?>

    </div>

    <div class="message-box">

        <?php if (!empty($task->description)) { ?>
            <h4>Description</h4>
            <p><?php echo nl2br(CHtml::encode($task->description)); ?></p>
        <?php } ?>

        <?php if (count($attachments) > 0) { ?>
            <h4>Attachments</h4>
            <ul>
                <?php foreach ($attachments as $att) { ?>
                    <?php if ($att->status == Document::STATUS_ACTIVE) { ?>
                        <li>
                            <?php if ($att->isImage()) { ?>
                            <a data-gallery title="<?php echo CHtml::encode($att->title); ?>" href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $att->id)) ?>" > <?php echo $att->renderIcon(); ?> <?php echo CHtml::encode($att->title); ?></a> <em class="smallfont text-muted">added <?php echo TbHtml::abbr(Yii::app()->format->timeAgo($att->create_date),Yii::app()->localTime->fromUTC($att->create_date)) ; ?></em>
                            <?php } else if ($att->source_type == Document::SOURCE_TYPE_LOCAL) { ?>
                            <a target="_blank" title="<?php echo CHtml::encode($att->title); ?>" href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $att->id)) ?>"> <?php echo $att->renderIcon(); ?> <?php echo CHtml::encode($att->title); ?> </a> <em class="smallfont text-muted">added <?php echo TbHtml::abbr(Yii::app()->format->timeAgo($att->create_date),Yii::app()->localTime->fromUTC($att->create_date)) ; ?></em> 
                            <?php } else { ?>
                            <a target="_blank" title="<?php echo CHtml::encode($att->title); ?>" href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $att->id)) ?>"><?php echo $att->renderIcon(); ?> <?php echo CHtml::encode($att->title); ?></a> <em class="smallfont text-muted">added <?php echo TbHtml::abbr(Yii::app()->format->timeAgo($att->create_date),Yii::app()->localTime->fromUTC($att->create_date)) ; ?> </em>
                            <?php } ?>
                        </li>
                    <?php } else { ?>
                        <li class="text-muted deleted">
                            <?php echo $att->renderIcon(); ?> <?php echo CHtml::encode($att->title); ?> <em class="smallfont text-muted">added <?php echo TbHtml::abbr(Yii::app()->format->timeAgo($att->create_date),Yii::app()->localTime->fromUTC($att->create_date)) ; ?></em>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        <?php } ?>
        <?php $this->renderPartial('process.views.task.__message_box', array('formUpload' => $formUpload, 'formMessage' => $formMessage, 'task_id' => $task_id)); ?>
    </div>
    <div class="task-activities">
        <hr/>
        <ul class="list-group activity-list">
            <?php if (count($models) > 0) { ?>
                <?php
                foreach ($models as $model) {
                    switch ($model->action_type) {
                        case TaskActivity::ACTION_TYPE_ASSIGN:
                            $this->renderPartial('process.views.task._activity_assign', array('model' => $model));
                            break;
                        case TaskActivity::ACTION_TYPE_ACCEPT:
                            $this->renderPartial('process.views.task._activity_accept', array('model' => $model));
                            break;
                        case TaskActivity::ACTION_TYPE_REJECT:
                            $this->renderPartial('process.views.task._activity_reject', array('model' => $model));
                            break;
                        case TaskActivity::ACTION_TYPE_COMPLETE_AND_WAIT:
                            $this->renderPartial('process.views.task._activity_complete_and_wait', array('model' => $model));
                            break;
                        case TaskActivity::ACTION_TYPE_COMPLETE:
                            $this->renderPartial('process.views.task._activity_complete', array('model' => $model));
                            break;
                        case TaskActivity::ACTION_TYPE_ADDMESSAGE:
                            $this->renderPartial('process.views.task._activity_addmessage', array('model' => $model, 'task' => $task));
                            break;
                        case TaskActivity::ACTION_TYPE_ADDDOCUMENT:
                            $this->renderPartial('process.views.task._activity_adddocument', array('model' => $model, 'task' => $task));
                            break;
                    }
                }
                ?>

            <?php } else { ?>
                <li class="list-group-item"><em>No activity</em></li>
                <?php } ?>

            <?php /* ?>
              <li class="list-group-item">
              <i class="glyphicon glyphicon-cog icon-activity"></i>

              <span class="pull-right text-muted time-line">
              il y a 1 heure <span class="glyphicon glyphicon-time timestamp" data-toggle="tooltip" data-placement="bottom" title="Lundi 24 Avril 2014 à 18h25"></span>
              </span>

              Iterruption de service pour mise à jour

              </li>
              <li class="list-group-item">

              <span class="pull-right text-muted time-line">
              il y a 12 jours <span class="glyphicon glyphicon-time timestamp" data-toggle="tooltip" data-placement="bottom" title="Lundi 24 Avril 2014 à 18h25"></span>
              </span>

              <i class="glyphicon glyphicon-user icon-activity"></i> <a href="#">Bobby</a> a créé son compte
              </li>
              <li class="list-group-item">

              <img alt="64x64" class="media-object pull-left" src="http://placehold.it/25x25">

              <span class="pull-right text-muted time-line">
              il y a 2 mois <span class="glyphicon glyphicon-time timestamp" data-toggle="tooltip" data-placement="bottom" title="Lundi 24 Avril 2014 à 18h25"></span>
              </span>

              <a href="#">MarcelProust</a> s'est connecté et voici une très longue explication pour un évènement finalement sans grande importance quand on y pense vraiment

              </li>

              <li class="list-group-item">

              <img alt="64x64" class="media-object pull-left" src="http://placehold.it/25x25">

              <span class="pull-right text-muted time-line">
              il y a 3 mois <span class="glyphicon glyphicon-time timestamp" data-toggle="tooltip" data-placement="bottom" title="Lundi 24 Avril 2014 à 18h25"></span>
              </span>

              <a href="#">JeannotLapin</a> a posté un commentaire sur "<a href="#">Vive la carotte</a>"

              </li>
              <?php */ ?> 

        </ul>
    </div>
</div>