<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integr $id
 * @property string $username
 * @property string $password
 * @property integr $rights
 */
class Users extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('username, password', 'required'),
            array('username, password', 'length', 'max' => 50),
            array('rights', 'numerical', 'integerOnly' => true, 'min' => 1, 'max' => 3),
            array('username, rights', 'safe', 'on' => 'search'),
            array('username', 'unique'),
        );
    }

    /**
     * @return boolean hash password before save
     */
    public function beforeSave() {
        $this->password = $this->hashPassword($this->password);
        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'rights' => 'Rights',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('rights', $this->rights);

        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

	public function getRightsName() {
		$rightsnames = array('none','read','write','admin');
		return $rightsnames[$this->rights];
	}
	
    public function validatePassword($password) {
        return $this->hashPassword($password) === $this->password;
    }

    public function hashPassword($password) {
        return sha1($password);
    }

}