<ul class="nav nav-tabs">
    <li class="<?php echo ((strtolower($this->route) == 'process/task/request') ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('process/task/request'); ?>">
            Tasks Request 
            <span id="spBadgeRequest" style="visibility: hidden" class="label label-as-badge badge-info">0</span>
        </a> </li>
    <li class="<?php echo ((strtolower($this->route) == 'process/task/duetoday') ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('process/task/dueToday'); ?>">
            Tasks Due Today 
            <span id="spBadgeDueToday" style="visibility: hidden" class="label label-as-badge badge-success">0</span>
        </a></li>
    <li class="<?php echo ((strtolower($this->route) == 'process/task/duetomorrow') ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('process/task/dueTomorrow'); ?>">
            Tasks Due Tomorrow 
            <span id="spBadgeDueTomorrow" style="visibility: hidden" class="label label-as-badge badge-success">0</span>
        </a></li>     
    <li class="<?php echo ((strtolower($this->route) == 'process/task/dueover2days') ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('process/task/dueOver2Days'); ?>">
            Tasks Due Over 2 Days 
            <span id="spBadgeDueOver2Days" style="visibility: hidden" class="label label-as-badge badge-success">0</span>
        </a></li>
    <li class="<?php echo ((strtolower($this->route) == 'process/task/overdue') ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('process/task/overdue'); ?>">
            Overdue Tasks 
            <span id="spBadgeOverdue" style="visibility: hidden" class="label label-as-badge badge-danger">0</span>
        </a></li> 
    <li class="<?php echo ((strtolower($this->route) == 'process/task/assigned') ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('process/task/assigned'); ?>">
            Wait For Accept 
            <span id="spBadgeWaitForAccept" style="visibility: hidden" class="label label-as-badge badge-warning">0</span>
        </a></li>  
        <li class="<?php echo ((strtolower($this->route) == 'process/task/completed') ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('process/task/completed'); ?>">
            Completed 
            <span id="spBadgeCompleted" style="visibility: hidden" class="label label-as-badge badge-default">0</span>
        </a></li>  
</ul>
<?php Yii::app()->clientscript->registerScript('summary','TaskPage.getTaskSummary("'.Yii::app()->createUrl('process/task/summary').'")',  CClientScript::POS_END); ?>