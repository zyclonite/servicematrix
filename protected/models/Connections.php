<?php

/**
 * This is the model class for table "connections".
 *
 * The followings are the available columns in table 'connections':
 * @property integer $id
 * @property integer $from
 * @property integer $to
 * @property integer $percent
 * @property integer $type
 */
class Connections extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Connections the static model class
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
		return 'connections';
	}

	/**
	 * @return array of the defaultScope
	 */
	public function defaultScope()
	{
		return array('with' => array('connectiontype','fromservice','toservice'));
	}

    /**
	 * @return array behaviors for model attributes.
	 */
        public function behaviors() {
                return array(
                        'ECompositeUniqueKeyValidatable' => array(
                                'class' => 'ECompositeUniqueKeyValidatable',
                                'uniqueKeys' => array(
                                        'attributes' => 'from, to',
                                        'errorMessage' => 'This connection already exists',
                                        'skipOnErrorIn' => array('from', 'to'),
                                ),
                        ),
                );
        }

        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('from, to, type, percent', 'required'),
			array('percent', 'numerical', 'integerOnly'=>true, 'min'=>0, 'max'=>100),
			array('from, to, type', 'numerical', 'integerOnly'=>true),
			array('from, to, type, percent', 'safe', 'on'=>'search'),
                        array('*', 'compositeUniqueKeysValidator'),
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
                        'connectiontype' => array(self::BELONGS_TO, 'Connectiontypes', 'type'),
                        'fromservice' => array(self::BELONGS_TO, 'Services', 'from'),
                        'toservice' => array(self::BELONGS_TO, 'Services', 'to'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'from' => 'From',
			'to' => 'To',
			'percent' => 'Percent',
			'type' => 'Type',
		);
	}

        /**
         * Validates composite unique keys
         *
         * Validates composite unique keys declared in the
         * ECompositeUniqueKeyValidatable bahavior
         */
        public function compositeUniqueKeysValidator() {
                $this->validateCompositeUniqueKeys();
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
		$criteria->compare('`from`',$this->from);
		$criteria->compare('`to`',$this->to);
		$criteria->compare('`percent`',$this->percent);
		$criteria->compare('`type`',$this->type);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
