<?php

/**
 * This is the model class for table "servicetypes".
 *
 * The followings are the available columns in table 'servicetypes':
 * @property integer $id
 * @property string $name
 * @property integer $level
 * @property string $color
 * @property string $image
 */
class Servicetypes extends CActiveRecord
{
        /** @soap @var string */ public $name;
        /** @soap @var int */ public $level;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Servicetypes the static model class
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
		return 'servicetypes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, color, image, level', 'required'),
			array('level', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>20),
			array('color', 'length', 'max'=>6),
			array('image', 'length', 'max'=>30),
			array('name, level, color', 'safe', 'on'=>'search'),
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
			'level' => 'Level',
			'color' => 'Color',
			'image' => 'Image',
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
		$criteria->compare('`level`',$this->level);
		$criteria->compare('`color`',$this->color,true);
		$criteria->compare('`image`',$this->image,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
