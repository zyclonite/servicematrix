<?php

/**
 * Description of AuthLdap
 *
 * @author lprettenthaler
 */
class AuthLdap extends CApplicationComponent {

    private $options = array('account_suffix' => '@domain.local',
                         'base_dn' => 'DC=domain,DC=local',
                         'domain_controllers' => array('127.0.0.1'),
                         'admin_username' => '',
                         'admin_password' => '',
                         'real_primarygroup' => false,
                         'use_ssl' => false,
                         'use_tls' => false,
                         'recursive_groups' => false,
                         'ad_port' => 389,
                         'sso' => false);
    private $adldap = null;

    public function init() {
        parent::init();
        try {
            $this->adldap = new adLDAP($this->options);
        } catch (adLDAPException $e) {
            Yii::log($e, 'warning', 'extensions.ldap.AuthLdap');
        }
    }

	public function authenticate($username, $password) {
		if($this->adldap === null)
			return false;
		return $this->adldap->authenticate($username, $password);
	}
	
    public function setAccountsuffix ($value) {
        $this->options['account_suffix'] = $value;
    }
    
    public function getAccountsuffix () {
        return $this->options['account_suffix'];
    }

    public function setBasedn ($value) {
        $this->options['base_dn'] = $value;
    }
    
    public function getBasedn () {
        return $this->options['base_dn'];
    }

    public function setDomaincontrollers ($value) {
        $this->options['domain_controllers'] = $value;
    }
    
    public function getDomaincontrollers () {
        return $this->options['domain_controllers'];
    }

    public function setAdminusername ($value) {
        $this->options['admin_username'] = $value;
    }
    
    public function getAdminusername () {
        return $this->options['admin_username'];
    }

    public function setAdminpassword ($value) {
        $this->options['admin_password'] = $value;
    }
    
    public function getAdminpassword () {
        return $this->options['admin_password'];
    }

    public function setRealprimarygroup ($value) {
        $this->options['real_primarygroup'] = $value;
    }
    
    public function getRealprimarygroup () {
        return $this->options['real_primarygroup'];
    }

    public function setUsessl ($value) {
        $this->options['use_ssl'] = $value;
    }
    
    public function getUsessl () {
        return $this->options['use_ssl'];
    }

    public function setUsetls ($value) {
        $this->options['use_tls'] = $value;
    }
    
    public function getUsetls () {
        return $this->options['use_tls'];
    }

    public function setRecursivegroups ($value) {
        $this->options['recursive_groups'] = $value;
    }
    
    public function getRecursivegroups () {
        return $this->options['recursive_groups'];
    }

    public function setPort ($value) {
        $this->options['adport'] = $value;
    }
    
    public function getPort () {
        return $this->options['adport'];
    }

    public function setSso ($value) {
        $this->options['sso'] = $value;
    }
    
    public function getSso () {
        return $this->options['sso'];
    }
}
