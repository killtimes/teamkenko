<?php if ($data->stage != Process::STAGE_NOTSET) { ?>
    <small class="text-muted"> 
        <div>
            Start date: <abbr title="<?php echo Yii::app()->localTime->fromUTC($data->start_date);  ?>"><?php echo Yii::app()->format->timeAgo($data->start_date); ?></abbr>
        </div>

        <?php if ($data->complete_date != null) { ?>
            <div>Complete date: <abbr title="<?php echo Yii::app()->localTime->fromUTC($data->complete_date); ?>"><?php echo Yii::app()->format->timeAgo($data->complete_date); ?></abbr></div>
        <?php } else { ?>
            <div>Complete date: <em>Not set</em></div>
        <?php } ?>
    </small>
<?php }else{ ?>
    <em class="small">Not start</em>
<?php } ?>