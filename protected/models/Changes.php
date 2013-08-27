<?php

/**
 * This is the model class for table "changes".
 *
 * The followings are the available columns in table 'changes':
 * @property string $id
 * @property integer $timestamp
 * @property string $userid
 * @property string $type
 * @property string $action
 * @property string $rid
 * @property string $change
 */
class Changes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Changes the static model class
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
		return 'changes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('timestamp, userid, type, action, rid, change', 'required'),
			array('timestamp', 'numerical', 'integerOnly'=>true),
			array('userid', 'length', 'max'=>20),
			array('type', 'length', 'max'=>14),
			array('action', 'length', 'max'=>6),
			array('rid', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, timestamp, userid, type, action, rid, change', 'safe', 'on'=>'search'),
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
                        'user' => array(self::BELONGS_TO, 'Users', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'timestamp' => 'Timestamp',
			'userid' => 'Userid',
			'type' => 'Type',
			'action' => 'Action',
			'rid' => 'Rid',
			'change' => 'Change',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('timestamp',$this->timestamp);
		$criteria->compare('userid',$this->userid,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('rid',$this->rid,true);
		$criteria->compare('change',$this->change,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}