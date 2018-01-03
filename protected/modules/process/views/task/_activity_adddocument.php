<?php if ($model->status == TaskActivity::STATUS_ACTIVE) {
    
    $arrIds = explode(',', $model->action_object);
    
    $doc = Document::model()->getById($arrIds[0]);
    
    ?>
    <li class="list-group-item">
        <span class="pull-right text-muted time-line">
            <?php echo TbHtml::abbr(Yii::app()->format->timeago($model->action_date), Yii::app()->localTime->fromUTC($model->action_date)); ?>  <span class="glyphicon glyphicon-time timestamp"></span>
            <?php if($this->checkDeleteDocumentPermission($doc,$task)){ ?>
            <a class="delete-activity" data-token="<?php echo Yii::app()->request->csrfToken; ?>" href="javascript:;" data-url="<?php echo Yii::app()->createUrl('/process/task/deleteActivity', array('id' => $model->id)) ?>" data-loading-text="Deleting..."><span class="glyphicon glyphicon-remove"></span></a>
            <?php }else{ ?>
                <span class="glyphicon glyphicon-remove invisible"></span>
            <?php } ?>
        </span> 
        <i class="glyphicon glyphicon-paperclip icon-activity text-muted"></i> <a><strong><?php echo Profile::model()->getById($model->action_source)->getFullName(); ?></strong></a> 

        <?php if($doc !== null){ ?>
        added file 
        <?php foreach($arrIds as $docId){
            $doc = Document::model()->getById($docId);
            ?>

                <?php if($doc->status == Document::STATUS_ACTIVE){ ?>
                    <?php if($doc->isImage()){ ?>
                        <a data-gallery="doc-<?php echo $model->id; ?>" title="<?php echo CHtml::encode($doc->title); ?>" href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $doc->id)) ?>"> <?php echo $doc->renderIcon(); ?> <?php echo CHtml::encode($doc->title); ?></a>
                    <?php }else{ ?>
                        <a href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $doc->id)) ?>" target="_blank"><?php echo $doc->renderIcon(); ?> <?php echo CHtml::encode($doc->title); ?></a>
                    <?php } ?>
                <?php }else{ ?>
                    <span class="text-muted deleted"><?php echo $doc->renderIcon(); ?> <?php echo CHtml::encode($doc->title); ?></span>
                <?php } ?>

        <?php } ?>
                
        <?php } ?>

                
        <br/> <?php echo nl2br($model->action_message); ?>

    </li>
<?php } else { ?>
    <li class="list-group-item text-muted deleted">
        <span class="pull-right text-muted time-line">
            <?php echo Yii::app()->format->timeago($model->action_date); ?> <span class="glyphicon glyphicon-time timestamp"></span>
            <span class="glyphicon glyphicon-remove invisible"></span>
        </span> 
        <i class="glyphicon glyphicon-paperclip icon-activity text-muted"></i> <strong><?php echo Profile::model()->getById($model->action_source)->getFullName(); ?></strong> 
        <?php
            $arrIds = explode(',', $model->action_object);
    
        ?>
        
        <?php if(!empty($arrIds)){  ?>
        added file 
        <?php foreach($arrIds as $docId){ $doc = Document::model()->getById($docId); ?>
        <?php echo $doc->renderIcon(); ?> <?php echo CHtml::encode($doc->title); ?>
        <?php } ?>
        <?php } ?>
        
        <br/> <?php echo nl2br($model->action_message); ?>

    </li>
<?php } ?>

