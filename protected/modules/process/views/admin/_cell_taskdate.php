<small>
    <?php if ($data->stage != TaskProcess::STAGE_NOTSET) { ?>
    <div>Assigned: <?php echo $data->assign_date; ?></div>
    <div>Due: <?php echo $data->due_date; ?></div>
        
    <?php } ?>
</small>