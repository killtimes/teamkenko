<ul class="nav nav-tabs">
    <li class="<?php echo (($this->stage === 'all') ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('/process/todo/list'); ?>">
            All
            <span id="spBadgeall"  style='visibility: hidden' class="label label-as-badge badge-default ">0</span>
        </a>
    </li>
    <li class="<?php echo (($this->stage == TaskProcess::STAGE_ASSIGNED) ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('/process/todo/list', array('stage'=> TaskProcess::STAGE_ASSIGNED )); ?>">
            Assigned 
            <span id="spBadge<?php echo TaskProcess::STAGE_ASSIGNED ?>"  style='visibility: hidden' class="label label-as-badge badge-default ">0</span>
        </a> 
    </li>
    <li class="<?php echo (($this->stage == TaskProcess::STAGE_INPROGRESS) ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('/process/todo/list', array('stage'=> TaskProcess::STAGE_INPROGRESS )); ?>">
            In Progress 
            <span id="spBadge<?php echo TaskProcess::STAGE_INPROGRESS ?>"  style='visibility: hidden' class="label label-as-badge badge-default ">0</span>
        </a>
    </li>
    
    <li class="<?php echo (($this->stage == TaskProcess::STAGE_REJECTED) ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('/process/todo/list', array('stage'=> TaskProcess::STAGE_REJECTED )); ?>">
            Rejected
            <span id="spBadge<?php echo TaskProcess::STAGE_REJECTED ?>"  style='visibility: hidden' class="label label-as-badge badge-default ">0</span>
        </a>
    </li>
    <li class="<?php echo (($this->stage == TaskProcess::STAGE_WAIRFORCONFIRM ) ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('/process/todo/list', array('stage'=> TaskProcess::STAGE_WAIRFORCONFIRM )); ?>">
            Wait For Accept
            <span id="spBadge<?php echo TaskProcess::STAGE_WAIRFORCONFIRM ?>"  style='visibility: hidden' class="label label-as-badge badge-default ">0</span>
        </a>
    </li>   
    <li class="<?php echo (($this->stage === TaskProcess::STAGE_NOTSET.'' ) ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('/process/todo/list', array('stage'=> TaskProcess::STAGE_NOTSET )); ?>">
            Upcoming
            <span id="spBadge<?php echo TaskProcess::STAGE_NOTSET ?>"  style='visibility: hidden' class="label label-as-badge badge-default ">0</span>
        </a>
    </li>  
     <li class="<?php echo (($this->stage == TaskProcess::STAGE_COMPLETED) ? 'active' : '') ?>"><a href="<?php echo Yii::app()->createUrl('/process/todo/list', array('stage'=> TaskProcess::STAGE_COMPLETED )); ?>">
            Completed
            <span id="spBadge<?php echo TaskProcess::STAGE_COMPLETED ?>"  style='visibility: hidden' class="label label-as-badge badge-default ">0</span>
        </a>
    </li>

</ul>
