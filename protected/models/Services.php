<?php

/**
 * This is the model class for table "services".
 *
 * The followings are the available columns in table 'services':
 * @property integer $id
 * @property string $servicename
 * @property integer $type
 */
class Services extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Services the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'services';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('servicename, type', 'required'),
			array('servicename', 'length', 'max'=>30),
			array('type', 'numerical', 'integerOnly'=>true),
			array('servicename, type', 'safe', 'on'=>'search'),
			array('servicename', 'unique'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
                        'servicetype' => array(self::BELONGS_TO, 'Servicetypes', 'type'),
                        'groups' => array(self::MANY_MANY, 'Groups', 'groupmembers(serviceid, groupid)'),
                        'services' => array(self::MANY_MANY, 'Services', 'connections(from, to)'),
                        'depending' => array(self::MANY_MANY, 'Services', 'connections(to, from)'),
                        'depconn' => array(self::HAS_MANY, 'Connections', 'to'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'servicename' => 'Servicename',
			'type' => 'Type',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('`id`',$this->id);
		$criteria->compare('`servicename`',$this->servicename,true);
		$criteria->compare('`type`',$this->type);
		$criteria->with=array('servicetype');

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
