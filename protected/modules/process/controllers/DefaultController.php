<?php

class DefaultController extends RController {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionList() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $limit = 10;
        $keyword = Yii::app()->getRequest()->getParam('q', '');
        $page = Yii::app()->getRequest()->getParam('page', 1);
        $offset = ($page - 1) * $limit;

        $shop_id = Profile::model()->getShopId(Yii::app()->user->id);
        $shop_filter = "";
        if ($shop_id > 0) {
            $shop_filter .= " AND shop_id=" . $shop_id;
        }

        //search contact
        $connection = Yii::app()->db;
        $sql = "SELECT SQL_CALC_FOUND_ROWS u.id, u.name "
                . " FROM `ProcessTemplate` u"
                . " WHERE u.status=:status " . $shop_filter
                . " AND (u.name LIKE :keyword) "
                . " LIMIT :limit "
                . " OFFSET :offset ";

        $command = $connection->createCommand($sql);
        $command->bindValue(':status', ProcessTemplate::STATUS_ACTIVE, PDO::PARAM_INT);
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
                'text' => $item['name']
            );
        }

        echo CJSON::encode($data);
        Yii::app()->end();
    }

    public function actionLoad() {
        if (!YII_DEBUG && !Yii::app()->request->isAjaxRequest) {
            throw new CHttpException('403', 'Forbidden access.');
        }

        $id = filter_input(INPUT_GET, 'qid');

        if (empty($id)) {
            throw new CHttpException('404', 'Missing "term" GET parameter.');
        }

        $sql = "SELECT u.id, u.name"
                . " FROM `ProcessTemplate` u"
                . " WHERE u.id=:qid "
                . " LIMIT 1 ";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(':qid', $id, PDO::PARAM_INT);
        $item = $cmd->queryRow();

        $data = array(
            'id' => $item['id'],
            'text' => $item['name'],
        );

        echo CJSON::encode($data);
        Yii::app()->end();
    }

}
