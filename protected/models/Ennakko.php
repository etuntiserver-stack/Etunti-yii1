<?php

/**
 * This is the model class for table "sivex_ennakko".
 *
 * The followings are the available columns in table 'sivex_ennakko':
 * @property integer $id
 * @property integer $tid
 * @property string $time
 * @property string $pvm
 * @property string $syy
 * @property string $ennakko
 */
class Ennakko extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ennakko the static model class
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
		return 'sivex_ennakko';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tid, pvm, syy, ennakko', 'required'),
			array('tid', 'numerical', 'integerOnly'=>true),
			array('pvm, ennakko', 'length', 'max'=>20),
			array('syy', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tid, time, pvm, syy, ennakko', 'safe', 'on'=>'search'),
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
			'tid' => Yii::t('main', 'Työntekijä'),
			'time' => Yii::t('main', 'Luotu'),
			'pvm' => Yii::t('main', 'Päivämäärä'),
			'syy' => Yii::t('main', 'Syy'),
			'ennakko' => Yii::t('main', 'Ennakko'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('pvm',$this->pvm,true);
		$criteria->compare('syy',$this->syy,true);
		$criteria->compare('ennakko',$this->ennakko,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
