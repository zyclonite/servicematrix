<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
        private $_id;
    
        public function authenticate()
		{
                $username=strtolower($this->username);
                if(Yii::app()->params['ldapauth']) {
					$ldap = Yii::app()->ldap;
					if(!$ldap->authenticate($this->username, $this->password)) {
                        $this->errorCode=self::ERROR_PASSWORD_INVALID;
					} else {
	                	$user=Users::model()->find('LOWER(username)=?',array($username));
	                	if($user===null) {
	                		$user=new Users;
	                		$user->username=$this->username;
	                		$user->password='ldapuser';
	                		$user->rights=1;
	                		$user->save();
	                	}
                        $this->_id=$user->id;
                        $this->username=$user->username;
                        $this->setState('rights', $user->rights);
                        $this->errorCode=self::ERROR_NONE;
					}
                }else{
                	$user=Users::model()->find('LOWER(username)=?',array($username));
                	if($user===null) {
                        $this->errorCode=self::ERROR_USERNAME_INVALID;
                	} else if(!$user->validatePassword($this->password)) {
                        $this->errorCode=self::ERROR_PASSWORD_INVALID;
                    } else {
                        $this->_id=$user->id;
                        $this->username=$user->username;
                        $this->setState('rights', $user->rights);
                        $this->errorCode=self::ERROR_NONE;
                	}
                }
                return $this->errorCode==self::ERROR_NONE;
		}
        
        public function getId()
        {
                return $this->_id;
        }
}
