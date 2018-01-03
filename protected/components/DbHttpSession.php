<?php

/**
 * DBHttpSession
 *
 * Stores session data in database and transfer data when session is destroy.
 * Uses for get users online, user's last activity and last ip (and more information if needed)
 *
 *
 * Add this to component section in config/main.php
 *
 * 'session' => array (
 * 'class' => 'application.components.DbHttpSession',
 * 'connectionID' => 'db',
 * 'sessionTableName' => 'session',
 * 'userTableName' => 'user'
 * ),
 *
 * Session table will be created automatically
 *
 *
 * Add columns to your user table:
 * ALTER TABLE user ADD user_id INT(11) NOT NULL, ADD last_ip VARCHAR(100) NOT NULL, ADD last_activity DATETIME NOT NULL
 *
 */
class DbHttpSession extends CDbHttpSession {

    public $userTableName = "user";

    const SESSION_USER_ONLINE_KEY = 'session:useronline:%s';

    /**
     * Transfer data to user table when session is destroy or delete expired records
     *
     * @param        int user_id
     * @param string $last_activity
     */
    protected function transferData($user_id, $last_activity) {

        if (empty($user_id)) { // skip guests users, otherwise sql will return error
            return true;
        }

        $db = $this->getDbConnection();
        $command = $db->createCommand("UPDATE $this->userTableName SET last_activity=:last_activity WHERE id=:user_id");

        $command->bindValue(':last_activity', $last_activity);
        $command->bindValue(':user_id', $user_id);
        $command->execute();

        //remove status online
        $this->removeCacheStatus($user_id);

        return true;
    }

    protected function removeCacheStatus($userId) {
        $key = sprintf(self::SESSION_USER_ONLINE_KEY, $userId);
        Yii::app()->cache->delete($key);
    }

    protected function setCacheStatus($userId) {
        $key = sprintf(self::SESSION_USER_ONLINE_KEY, $userId);
        Yii::app()->cache->set($key, 1, $this->getTimeout());
    }

    public function isUserOnline($userId) {
        $key = sprintf(self::SESSION_USER_ONLINE_KEY, $userId);
        $result = Yii::app()->cache->get($key);
        return ($result == 1);
    }

    /**
     * Clear expired records from session table and transfer data to user table from the records
     */
    public function clearOldSessions() {

        $db = $this->getDbConnection();
        $time = time();
        try {
            $command = $db->createCommand("SELECT * FROM $this->sessionTableName WHERE expire<:current_time");
            $command->bindValue(':current_time', $time);
            $result = $command->queryAll();

            foreach ($result as $item) {

                $id = $item["id"];
                $user_id = $item["user_id"];
                $last_activity = $item["last_activity"];
                $last_ip = $item["last_ip"];

                if (!empty($user_id)) { // skip guests users, otherwise sql will return error
                    $cmdUpd = $db->createCommand("UPDATE $this->userTableName SET last_activity=:last_activity, last_ip=:last_ip WHERE id=:user_id");
                    $cmdUpd->bindValue(':last_activity', $last_activity);
                    $cmdUpd->bindValue(':last_ip', $last_ip);
                    $cmdUpd->bindValue(':user_id', $user_id);
                    $cmdUpd->execute();

                    //remove status
                    $this->removeCacheStatus($user_id);
                }

                $cmdDel = $db->createCommand("DELETE FROM $this->sessionTableName WHERE id=:session_id");
                $cmdDel->bindValue(':session_id', $id);
                $cmdDel->execute();
            }
        } catch (Exception $e) {
            //TODO write log
//            $this->createSessionTable($db, $this->sessionTableName);
        }
    }

    protected function createSessionTable($db, $tableName) {
        parent::createSessionTable($db, $tableName);
        $db->createCommand()->addColumn($tableName, 'user_id', 'integer not null');
        $db->createCommand()->addColumn($tableName, 'last_activity', 'timestamp not null');
        $db->createCommand()->addColumn($tableName, 'last_ip', 'string not null');
    }

    public function openSession($savePath, $sessionName) {
        $db = $this->getDbConnection();
        $db->setActive(true);
        $this->clearOldSessions();
        return true;
    }

    public function writeSession($id, $data) {

        $db = $this->getDbConnection();

        try {
            $expire = time() + $this->getTimeout();
            if ($db->getDriverName() == 'sqlsrv' || $db->getDriverName() == 'mssql' || $db->getDriverName() == 'dblib') {
                $data = new CDbExpression('CONVERT(VARBINARY(MAX), ' . $db->quoteValue($data) . ')');
            }
            if ($db->createCommand()->select('id')->from($this->sessionTableName)->where('id=:id', array(':id' => $id))
                            ->queryScalar() === false
            ) {
                //Add needed fields to the queries
                $db->createCommand()->insert(
                        $this->sessionTableName, array(
                    'id' => $id,
                    'data' => $data,
                    'expire' => $expire,
                    'user_id' => Yii::app()->getUser()->getId(),
                    'last_activity' => new CDbExpression('NOW()'),
                    'last_ip' => CHttpRequest::getUserHostAddress(),
                        )
                );
            } else {
                $db->createCommand()->update(
                        $this->sessionTableName, array(
                    'data' => $data,
                    'expire' => $expire,
                    'user_id' => Yii::app()->getUser()->getId(),
                    'last_activity' => new CDbExpression('NOW()'),
                    'last_ip' => CHttpRequest::getUserHostAddress(),
                        ), 'id=:id', array(':id' => $id)
                );
            }

            $this->setCacheStatus(Yii::app()->getUser()->getId());
            
        } catch (Exception $e) {
//            $this->createSessionTable($db, $this->sessionTableName);
            if (YII_DEBUG) {
                echo $e->getMessage();
            }
            return false;
        }
        return true;
    }

    public function destroySession($id) {
        $db = $this->getDbConnection();
        $command = $db->createCommand("SELECT user_id, last_activity FROM $this->sessionTableName WHERE id=:session_id");
        $command->bindValue(':session_id', $id);
        $result = $command->queryRow();
        if (!empty($result)) {
            $this->transferData($result['user_id'], $result['last_activity']);
            $db->createCommand()->delete($this->sessionTableName, 'id=:id', array(':id' => $id));
        }
        return true;
    }

    public function gcSession($maxLifetime) {
        $this->clearOldSessions();
        return true;
    }

}
