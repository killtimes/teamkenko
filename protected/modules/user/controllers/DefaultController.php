<?php

class DefaultController extends RController
{

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('User', array(
            'criteria' => array(
                'condition' => 'status>' . User::STATUS_BANNED,
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->controller->module->user_page_size,
            ),
        ));

        $this->render('/user/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionList()
    {

        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $limit = 10;
        $keyword = Yii::app()->getRequest()->getParam('q', '');
        $page = Yii::app()->getRequest()->getParam('page', 1);
        $offset = ($page - 1) * $limit;

        $shopId = 0;
        if (isset($_GET['sid'])) {
            $shopId = intval($_GET['sid']);
        }

        $query = "1";
        if (isset($_GET['did'])) {

            if(!empty($_GET['did'])){
                $department = explode(',', $_GET['did']);

                if (!empty($department) && is_array($department)) {
                    $query = " 0 ";
                    foreach ($department as $b) {
                        $query .= " OR department & $b";
                    }
                } else {
                    $query = " id=" . Yii::app()->user->id;
                }
            }else{
                $query = " id=" . Yii::app()->user->id;

            }

        }


        //search contact
        $connection = Yii::app()->db;
        $sql = "SELECT SQL_CALC_FOUND_ROWS u.id, u.email, p.firstname, p.lastname "
            . " FROM `User` u"
            . " LEFT OUTER JOIN `Profile` p "
            . " ON u.id=p.user_id "
            . " WHERE u.status=:user_state and ($query)";

//        if ($shopId > 0) {
//            $sql .= " AND u.shop_id=:shop_id ";
//        }

        $sql .= " AND CONCAT(p.firstname,' ',p.lastname) LIKE :keyword "
            . " LIMIT :limit "
            . " OFFSET :offset ";

        $command = $connection->createCommand($sql);
        $command->bindValue(':user_state', User::STATUS_ACTIVE, PDO::PARAM_INT);
//        if ($shopId > 0) {
//            $command->bindValue(':shop_id', $shopId, PDO::PARAM_INT);
//        }
        $command->bindValue(':keyword', "%" . $keyword . "%", PDO::PARAM_STR);
        $command->bindValue(':limit', $limit, PDO::PARAM_INT);
        $command->bindValue(':offset', $offset, PDO::PARAM_INT);

        $rows = $command->queryAll();

        //get total for paging
        $total = Yii::app()->db->createCommand('SELECT FOUND_ROWS() AS total')->queryScalar();

        $data = array();
        $data['total'] = $total;
        $data['results'] = array();

        foreach ($rows as $item) {
            $data['results'][] = array(
                'id' => $item['id'],
                'text' => sprintf('%s %s', $item['firstname'], $item['lastname']),
                'email' => $item['email']
            );
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function actionLoad()
    {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $id = filter_input(INPUT_GET, 'qid');

        if (empty($id)) {
            throw new CHttpException('404', 'Missing "term" GET parameter.');
        }

        if (strpos($id, ',') !== false) {
            $ids = explode(',', $id);
            $id = array();
            foreach ($ids as $n) {
                $id[] = (int)$n;
            }
            $id = implode(',', $id);
        }

        $sql = "SELECT u.id, u.email, p.firstname, p.lastname "
            . " FROM `User` u"
            . " LEFT OUTER JOIN `Profile` p "
            . " ON u.id=p.user_id "
            . " WHERE u.id IN (" . $id . ") "
            . " LIMIT 50 ";

        $cmd = Yii::app()->db->createCommand($sql);

        $item = $cmd->queryAll();

        $data = array();
        if (count($item) == 1) {
            $item = $item[0];
            $data = array(
                'id' => $item['id'],
                'text' => sprintf('%s %s', $item['firstname'], $item['lastname']),
                'email' => $item['email']
            );
        } else {
            foreach ($item as $i) {
                $data[] = array(
                    'id' => $i['id'],
                    'text' => sprintf('%s %s', $i['firstname'], $i['lastname']),
                    'email' => $i['email']
                );
            }
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }

}
