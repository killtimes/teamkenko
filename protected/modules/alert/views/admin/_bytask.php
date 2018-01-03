<div class="">


    <h4><?php echo CHtml::encode(Task::model()->getById($task->task_id)->name); ?><?php echo TaskProcess::stageAlias($task->stage); ?></h4>

    <div class="form-group">
        <label class="col-md-2 control-label text-left">Assignee</label>
        <div class="col-md-7">
            <label
                class="control-label text-default"><?php echo CHtml::encode(Profile::model()->getById($task->assign_id)->getFullName()); ?></label>
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
</div>

<!-- Nav tabs -->
<ul id="alertTabs" class="nav nav-tabs panel-container" role="tablist">
    <li role="presentation" class="active">
        <a href="#alerts" aria-controls="home" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-list"></i>
            Alerts</a></li>
    <li role="presentation">
        <a href="#new-alert" aria-controls="messages" role="tab" data-toggle="tab"><i
                class="glyphicon glyphicon-plus"></i> New Alert</a>
    </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="alerts">
        <div class="panel-container"></div>
        <?php if (Yii::app()->user->hasFlash('successMessage')) { ?>
            <div class='alert alert-success'>
                <span class="glyphicon glyphicon-ok"></span><?php echo Yii::app()->user->getFlash('successMessage'); ?>
            </div>
        <?php } ?>

        <?php if (Yii::app()->user->hasFlash('errorMessage')) { ?>
            <div class='alert alert-danger'>
                <strong><?php echo Yii::app()->user->getFlash('errorMessage'); ?></strong>
            </div>
        <?php } ?>
        <?php $this->widget('\TbGridView', array(
            'ajaxUrl' => $this->createUrl('/alert/admin/bytask', array('id' => $task->id, 'ajax' => $idGridview)),
            'id' => $idGridview,
            'dataProvider' => $dataProvider,
            'type' => array(
                TbHtml::GRID_TYPE_HOVER,
                TbHtml::GRID_TYPE_CONDENSED,
//                TbHtml::GRID_TYPE_BORDERED
            ),
            'columns' => array(
                array(
                    'header' => 'Alert Type',
                    'type' => 'raw',
                    'value' => 'Alert::itemAlias("Type",$data->alert_type)'
                ),
                array(
                    'header' => 'Stage',
                    'type' => 'raw',
                    'value' => 'Alert::stageAlias($data->stage)'
                ),
                array(
                    'header' => 'Critical status',
                    'type' => 'raw',
                    'value' => 'Alert::statusAlias($data->status)'
                ),
                array(
                    'header' => 'Create data',
                    'type' => 'raw',
                    'value' => 'TbHtml::abbr(Yii::app()->format->timeAgo($data["create_date"]), $data["create_date"])'
                ),
                array(
                    'header' => '',
                    'type' => 'raw',
                    'value' => 'TbHtml::link("Edit","javascript:;",array("class"=>"edit-alert", "data-url"=>Yii::app()->createUrl("/alert/admin/bytask",array("id"=>$data->related_task_id,"alert_id"=>$data->id))))'
                ),
                array(
                    'header' => '',
                    'type' => 'raw',
                    'value' => 'TbHtml::link("Delete","javascript:;",array("class"=>"delete-alert", "data-token"=>Yii::app()->request->csrfToken,"data-token-name"=> Yii::app()->request->csrfTokenName,  "data-url"=>Yii::app()->createUrl("/alert/admin/bytask",array("id"=>$data->related_task_id,"alert_id"=>$data->id))))'
                )
            )
        )); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="new-alert">
        <?php $this->renderPartial('__new_alert', array(
            'model' => $alert,
            'task' => $task,
        )); ?>
    </div>
</div>

