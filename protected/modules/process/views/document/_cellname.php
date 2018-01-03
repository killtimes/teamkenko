<?php if ($data->isImage()) { ?>
    <a data-gallery="photos<?php echo $data->id; ?>" title="<?php echo CHtml::encode($data->title); ?>" href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $data->id)) ?>" > <?php echo $data->renderIcon(); ?> <?php echo CHtml::encode($data->title); ?></a>
<?php } else if ($data->source_type == Document::SOURCE_TYPE_LOCAL) { ?>
    <a target="_blank"  href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $data->id)) ?>"> <?php echo $data->renderIcon(); ?> <?php echo CHtml::encode($data->title); ?> </a>  
<?php } else { ?>
    <a target="_blank" href="<?php echo Yii::app()->createUrl('process/attachment/view', array('id' => $data->id)) ?>"><?php echo $data->renderIcon(); ?> <?php echo CHtml::encode($data->title); ?></a>
<?php } ?>