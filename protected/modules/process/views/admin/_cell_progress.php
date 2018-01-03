<?php
$statt = $data->getCachedStatistic();
$progressByNumTask = 0;
$totalAssignedOrRejected = 0;
$progressWaitforAccept = 0;
$totalInprogress = 0;
$progressInprogress = 0;
if ($statt['total'] > 0) {
    $progressByNumTask = round($statt['complete'] / $statt['total'] * 100, 2);
    $totalAssignedOrRejected = $statt['waitfor_accept'] + $statt['reject'];
    $progressWaitforAccept = round($totalAssignedOrRejected / $statt['total'] * 100, 2);
    $totalInprogress = $statt['in_progress'] + $statt['waitfor_confirm'];
    $progressInprogress = round($totalInprogress / $statt['total'] * 100, 2);
}
?>
<?php /* <div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $data->progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $data->progress; ?>%;">
  <span><?php echo sprintf("%s/%s", $statt['complete'], $statt['total']); ?></span>
  </div>
  </div>

 */ ?>
<div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $progressByNumTask; ?>%">
        <small class="completed" title="<?php echo $data->progress; ?>% Completed" ><?php echo sprintf("%s/%s", $statt['complete'], $statt['total']); ?></small>
    </div>
    <?php if ($totalInprogress > 0) { ?>
        <div class="progress-bar progress-bar-info" role="progressbar" style="width:<?php echo $progressInprogress; ?>%">
            <small class="in-progress" title="<?php echo Process::parseStaffName(array_merge($statt['in_progress_staff_id'], $statt['waitfor_confirm_staff_id'])); ?>" ><?php echo sprintf("%s/%s", $totalInprogress, $statt['total']); ?></small>
        </div>
    <?php } ?>
    <?php if ($totalAssignedOrRejected > 0) { ?>
        <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $progressWaitforAccept; ?>%">
            <small class="wait-accept" title="<?php echo Process::parseStaffName(array_merge($statt['waitfor_staff_id'], $statt['reject_by_staff_id'])); ?>"><?php echo sprintf("%s/%s", $totalAssignedOrRejected, $statt['total']); ?></small>
        </div>
    <?php } ?>
    <div class="progress-bar progress-bar-default" role="progressbar" style="width:<?php echo 100 - $progressInprogress - $progressByNumTask - $progressWaitforAccept; ?>%">

    </div>
</div>