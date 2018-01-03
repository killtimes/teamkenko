<?php

class MigrateUserShopCommand extends CConsoleCommand
{

    public function actionIndex($dryrun = 'on')
    {
        Yii::import('application.modules.shop.models.*');
        Yii::import('application.modules.user.models.*');
        $models = User::model()->findAll();

        $rows = array();
        foreach ($models as $m) {

            if ($m->profile->shop_id) {
                echo 'user:' . $m->id . ' shop:' . $m->profile->shop_id . PHP_EOL;
                $rows[] = array(
                    'user_id'=>$m->id,
                    'shop_id'=>$m->profile->shop_id
                );
            }
        }

        if(count($rows) > 0){

            $connection = Yii::app()->db->getSchema()->getCommandBuilder();
            $cmd = $connection->createMultipleInsertCommand('UserShop',$rows);

            if($dryrun === 'off'){
                $cmd->execute();
                echo 'EXECUTED'.PHP_EOL;
            }
        }
    }
}