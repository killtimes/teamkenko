<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--        <link rel="shortcut icon" href="/yii-bootstrap/favicon.ico">-->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php Yii::app()->bootstrap->register(); ?>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $baseUrl = Yii::app()->theme->baseUrl; ?>
    <?php Yii::app()->getClientScript()->registerCssFile($baseUrl . '/bootstrap-sweetalert/sweetalert.css'); ?>
    <?php Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/custom-scrollbar/jquery.mCustomScrollbar.css', '', 99); ?>
    <?php Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/font-awesome/css/font-awesome.min.css'); ?>
    <?php Yii::app()->getClientScript()->registerCssFile($this->getAssetsBase() . '/css/main.css', '', 999); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile($baseUrl . '/bootstrap-sweetalert/sweetalert.min.js', CClientScript::POS_END); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js', CClientScript::POS_END); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/js/idle-timer.min.js', CClientScript::POS_END); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/js/tinycon.min.js', CClientScript::POS_END); ?>


    <?php Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/momentjs/moment.js', CClientScript::POS_END); ?>
    <!--        --><?php //Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/momentjs/moment-timezone.js', CClientScript::POS_END); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/momentjs/moment-timezone-with-data.js', CClientScript::POS_END); ?>


    <?php Yii::app()->getClientScript()->registerScriptFile($this->getAssetsBase() . '/js/common.js', CClientScript::POS_END); ?>

    <script type="text/javascript">
        window.mainAssetUrl = "<?php echo $this->getAssetsBase(); ?>";
    </script>
</head>
<body class="padding">

<?php
$this->widget('bootstrap.widgets.TbNavbar', array(
//            'color' => 'inverse',
    'display' => TbHtml::NAVBAR_DISPLAY_FIXEDTOP,
    'brandUrl' => Yii::app()->createUrl('/dashboard'),
    'fluid' => true,
    'collapse' => true, // requires bootstrap-responsive.css
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbNav',
            'items' => array(
                array(
                    'label' => 'Dashboard',
                    'url' => array('/dashboard'),
                    'visible' => !Yii::app()->user->isGuest,
                ),
                array(
                    'label' => 'Tasks',
                    'url' => array('#'),
                    'visible' => (in_array('Staff', $this->roles) && !Yii::app()->user->isGuest),
                    'items' => array(
                        array(
                            'label' => 'My tasks',
                            'url' => array('/process/task/request'),
                            'visible' => $this->checkAccess('Task_List', array()),
                        ),
                        array(
                            'label' => 'Wait for accept',
                            'url' => array('/process/task/assigned'),
                            'visible' => $this->checkAccess('Task_List', array()),
                        ),
                        array(
                            'label' => 'Due today',
                            'url' => array('/process/task/dueToday'),
                            'visible' => $this->checkAccess('Task_List', array()),
                        ),
                        array(
                            'label' => 'Due tomorrow',
                            'url' => array('/process/task/dueTomorrow'),
                            'visible' => $this->checkAccess('Task_List', array()),
                        ),
                        array(
                            'label' => 'Due over 2 days',
                            'url' => array('/process/task/dueOver2Days'),
                            'visible' => $this->checkAccess('Task_List', array()),
                        ),
                        array(
                            'label' => 'Overdue tasks',
                            'url' => array('/process/task/overdue'),
                            'visible' => $this->checkAccess('Task_List', array()),
                        ),
                    )
                ),
                array(
                    'label' => 'Contacts',
                    'url' => array('/supplier/supplier'),
                    'visible' => $this->checkAccess('Supplier_List', array())
                ),
                array(
                    'label' => 'Shops',
                    'url' => array('/shop/shop'),
                    'visible' => $this->checkAccess('Shop_List', array()),
                ),
                array(
                    'label' => 'Jobs',
                    'url' => array('/process/admin'),
                    'visible' => $this->checkAccess('Process_List', array()),
                ),
                array(
                    'label' => 'Tasks',
                    'url' => array('/process/todo/list'),
                    'visible' => $this->checkAccess('ToDo_List', array()),
                ),
                array(
                    'label' => 'Documents',
                    'url' => array('/process/document/list'),
                    'visible' => $this->checkAccess('Document_List', array()),
                ),
                array(
                    'label' => 'Alerts',
                    'url' => array('/alert/admin/list'),
                    'visible' => $this->checkAccess('Alert_Access', array()),
                ),
                array(
                    'label' => 'Reports',
                    'url' => array('/report'),
                    'visible' => !Yii::app()->user->isGuest && $this->checkAccess('Report_TaskSummary', array()),
                    'items' => array(
                        array(
                            'label'=>'BY GROUP'
                        ),
                        array(
                            'label' => 'All tasks',
                            'url' => array('/report/groupSummary'),
                        ),
                        array(
                            'label' => 'Daily tasks',
                            'url' => array('/report/groupSummary/daily'),
                        ),
                        '---',
                        array(
                            'label'=>'BY SHOP'
                        ),
                        array(
                            'label' => 'All tasks',
                            'url' => array('/report/shopSummary'),
                        ),
                        array(
                            'label' => 'Daily tasks',
                            'url' => array('/report/shopSummary/daily'),
                        ),
                        '---',
                        array(
                            'label'=>'BY STAFF'
                        ),
                        array(
                            'label' => 'All tasks',
                            'url' => array('/report/staffSummary'),
                        ),
                        array(
                            'label' => 'Daily tasks',
                            'url' => array('/report/staffSummary/daily'),
                        ),
                        '---',
                        array(
                            'label'=>'BY DEPARTMENT'
                        ),
                        array(
                            'label' => 'All tasks',
                            'url' => array('/report/departmentSummary'),
                        ),
                        array(
                            'label' => 'Daily tasks',
                            'url' => array('/report/departmentSummary/daily'),
                        ),
                    )
                ),
                array(
                    'label' => 'Settings',
                    'url' => array('#'),
                    'visible' => $this->checkAccess('ProcessTemplate_List', array()) || $this->checkAccess('TaskTemplate_List', array()),
                    'items' => array(
                        array(
                            'label' => 'Job Templates',
                            'url' => array('/template/process'),
                            'visible' => $this->checkAccess('ProcessTemplate_List', array())),
                        array(
                            'label' => 'Task Templates',
                            'url' => array('/template/task'),
                            'visible' => $this->checkAccess('TaskTemplate_List', array())),
                        array(
                            'label' => 'Task Group',
                            'url' => array('/template/taskgroup'),
                            'visible' => $this->checkAccess('TaskGroup_List', array())
                        ),
                        //'---'
                    )
                ),
                array(
                    'label' => 'Admin',
                    'url' => array('#'),
                    'visible' => ($this->checkAccess('Right_Manage', array()) || $this->checkAccess('User_Manage', array())),
                    'items' => array(
                        array(
                            'label' => 'User',
                            'url' => array('/user/admin'),
                            'visible'=> $this->checkAccess('User_Manage', array())
                        ),
                        array(
                            'label' => 'Rights',
                            'url' => Rights::getBaseUrl(),
                            'visible'=>$this->checkAccess('Right_Manage', array())
                        ),
                    )),
                array(
                    'label' => 'Notification',
                    'url' => array('#'),
                    'visible' => ($this->checkAccess('Right_Manage', array()) || $this->checkAccess('User_Manage', array())),
                    'items' => array(
                        array(
                            'label' => '',
                            'input' =>'form',
                            'url' => array('/dropdown/user'),
                            'visible'=> $this->checkAccess('User_Manage', array())
                        )
                    ))
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.TbNav',
            'htmlOptions' => array('class' => 'navbar-right'),
            'items' => array(
                array('label' => 'Welcome ' . Profile::model()->getById(Yii::app()->user->id)->getFullName(),
                    'url' => array('#'),
                    'visible' => !Yii::app()->user->isGuest,
                    'items' => array(
                        array('label' => 'Profile', 'url' => array('/user/profile')),
                        '---',
                        array('label' => 'Logout', 'url' => array('/user/logout')),
                    )
                ),
                //((Yii::app()->user->isGuest) ? '<li><p class="navbar-btn"><a href="' . Yii::app()->createUrl('/user/login') . '" class="btn btn-primary"><span class="glyphicon glyphicon-lock"></span> Login</a></p></li>' : '')
            ),
        ),
    ),
));

?>
<div class="container-fluid">
    <div>
        <?php if (!Yii::app()->user->isGuest && isset($this->breadcrumbs)): ?>
            <?php
            $this->widget('bootstrap.widgets.TbBreadcrumb', array(
                'links' => $this->breadcrumbs
            ));
            ?>
            <!-- breadcrumbs -->
        <?php endif ?>
        <div id="view-container">

            <?php echo $content; ?>


            <p class="text-muted pull-right">
                <?php //echo date('l jS \of F Y h:i:s A (e)'); ?>
                <span id="clock-uk" class="text-right"></span> UK <br>
                <span id="clock-vn" class="text-right"></span> VN
            </p>
            <p class="text-muted">Load time: <?php echo sprintf('%0.5f', Yii::getLogger()->getExecutionTime()); ?></p>
        </div>


    </div>

</div>

<script type="text/javascript">
    var _urq = _urq || [];
    _urq.push(['initSite', '0a61d1d0-ee94-485b-988e-6d443afdb38e']);
    (function () {
        var ur = document.createElement('script');
        ur.type = 'text/javascript';
        ur.async = true;
        ur.src = ('https:' == document.location.protocol ? 'https://cdn.userreport.com/userreport.js' : 'http://cdn.userreport.com/userreport.js');
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ur, s);
    })();
</script>
</body>
</html>
<?php
$time = time();
Yii::app()->clientScript->registerScript('clock', "
    $('#clock-uk').clock({unixTimestamp:$time,timezone:'Europe/London'});
    $('#clock-vn').clock({unixTimestamp:$time,timezone:'Asia/Ho_Chi_Minh'});
    ", ClientScript::POS_READY);
?>