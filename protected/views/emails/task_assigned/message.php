<?php /* @var $alert Alert */
if ($task != null) {
    $t = Task::model()->getById($task->task_id);
    $name = $t->name;

    $name .= ' (' . TaskProcess::itemAlias('Stage', $task->stage) . ')';

    $process = $task->process;
    $job = 'n/a';
    if ($task->process !== null) {
        $job = $task->process->name;
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
}
?>
<p>TASK BEEN REASSIGNED!</p>

<table border="0"  cellspacing="5" cellpadding="5">
    <tr>
        <td valign="top">Task:</td>
        <td valign="top"><strong><?php echo $name; ?></strong></td>
    </tr>
    <tr>
        <td valign="top">Assignee</td>
        <td valign="top"><strong><?php echo CHtml::encode(Profile::model()->getById($task->assign_id)->getFullName()); ?></strong></td>
    </tr>
    <tr>
        <td>Assign date</td>
        <td><?php echo $task->assign_date; ?></td>
    </tr>
    <tr>
        <td valign="top">Job:</td>
        <td valign="top"><?php echo $job; ?></td>
    </tr>
    <tr>
        <td valign="top">Shop:</td>
        <td valign="top"><?php echo $shop; ?></td>
    </tr>
    <tr>
        <td valign="top">Contact:</td>
        <td valign="top"><?php echo $supplier; ?></td>
    </tr>
    <tr>
        <td valign="top">Alert type:</td>
        <td valign="top"><?php echo Alert::itemAlias('Type', $alert->alert_type); ?></td>
    </tr>
    <tr>
        <td valign="top">Critical status:</td>
        <td valign="top"><strong><?php echo Alert::itemAlias('Status', $alert->status); ?></strong></td>
    </tr>
    <tr>
        <td></td>
        <td><a href="<?php echo Yii::app()->createAbsoluteUrl('/alert/list/view', array('id' => $alert->id)); ?>">View alert
                detail</a></td>
    </tr>
</table>

