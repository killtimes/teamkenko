<?php

class ConsoleUser extends CApplicationComponent implements IWebUser {

    public $primaryKey;
    private $systemUser;

    public function init() {
        parent::init();

        if (!isset($this->primaryKey)) {
            throw new Exception('You must set the "primary key" of the user
                to execute the console application.');
        }
        $user = Profile::model()->getById($this->primaryKey);

        if ($user) {
            $this->systemUser = $user;
        } else {
            throw new Exception('Could not find Admin User to execute console application.');
        }
    }

    /**
     * Returns a value that uniquely represents the identity.
     * @return mixed a value that uniquely represents the identity (e.g. primary key value).
     */
    public function getId() {
        return $this->systemUser->user_id;
    }

    public function loginRequired() {
        
    }

    /**
     * Returns the display name for the identity (e.g. username).
     * @return string the display name for the identity.
     */
    public function getName() {
        return $this->systemUser->getFullName();
    }

    /**
     * Returns a value indicating whether the user is a guest (not authenticated).
     * @return boolean whether the user is a guest (not authenticated)
     */
    public function getIsGuest() {
        return false;
    }

    /**
     * Performs access check for this user.
     * @param string $operation the name of the operation that need access check.
     * @param array $params name-value pairs that would be passed to business rules associated
     * with the tasks and roles assigned to the user.
     * @return boolean whether the operations can be performed by this user.
     */
    public function checkAccess($operation, $params = array()) {
        return true;
    }

    public function getIsAdmin() {
        return true;
    }

    public function setFlash($key, $value, $defaultValue = null) {
        
    }

}
