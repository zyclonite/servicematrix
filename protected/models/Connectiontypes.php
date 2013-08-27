<?php

/**
 * This is the model class for table "connectiontypes".
 *
 * The followings are the available columns in table 'connectiontypes':
 * @property integer $id
 * @property string $name
 * @property string $color
 */
class Connectiontypes extends CActiveRecord
{
        /** @soap @var string */ public $name;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Connectiontypes the static model class
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
		return 'connectiontypes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, color', 'required'),
			array('name', 'length', 'max'=>20),
			array('color', 'length', 'max'=>6),
			array('name', 'safe', 'on'=>'search'),
			array('name', 'unique'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'color' => 'Color',
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
		$criteria->compare('`name`',$this->name,true);
		$criteria->compare('`color`',$this->color,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
