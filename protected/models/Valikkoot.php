<?php

/**
 * This is the model class for table "sivex_selects".
 *
 * The followings are the available columns in table 'sivex_selects':
 * @property integer $id
 * @property string $value
 * @property string $select_type
 */
class Valikkoot extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Valikkoot the static model class
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
		return 'sivex_selects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('value, select_type', 'required'),
			array('value', 'length', 'max'=>255),
			array('select_type', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, value, select_type', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('main', 'ID'),
			'value' => Yii::t('main', 'Value'),
			'select_type' => Yii::t('main', 'Select Type'),
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
		$criteria->order = "id DESC";

		$criteria->compare('id',$this->id);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('select_type',$this->select_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
