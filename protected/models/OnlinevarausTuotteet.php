<?php

/**
 * This is the model class for table "onlinevaraus_tuotteet".
 *
 * The followings are the available columns in table 'onlinevaraus_tuotteet':
 * @property integer $id
 * @property string $nimike
 * @property string $hinta
 * @property string $selitysteksti
 * @property integer $palvelu
 * @property string $kesto
 */
class OnlinevarausTuotteet extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OnlinevarausTuotteet the static model class
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
		return 'onlinevaraus_tuotteet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nimike, palvelu, kesto, hinta', 'required'),
			array('palvelu, nayta_sivuilla', 'numerical', 'integerOnly'=>true),
			array('nimike, selitysteksti', 'length', 'max'=>255),
			array('hinta, kesto, nelio, kotitalousvahennys', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nimike, hinta, selitysteksti, palvelu, kesto, nelio', 'safe', 'on'=>'search'),
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
			'nimike' => Yii::t('main', 'Nimike'),
			'hinta' => Yii::t('main', 'Hinta'),
			'selitysteksti' => Yii::t('main', 'Selitysteksti'),
			'palvelu' => Yii::t('main', 'Palvelu'),
			'kesto' => Yii::t('main', 'Kesto (tunnilla)'),
			'nelio' => Yii::t('main', 'Neliömetri m²'),
			'kotitalousvahennys' => Yii::t('main', 'Kotitalousvähennys %'),
			'nayta_sivuilla' => Yii::t('main', 'Näytä sivuilla'),
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
		$criteria->compare('nimike',$this->nimike,true);
		$criteria->compare('hinta',$this->hinta,true);
		$criteria->compare('selitysteksti',$this->selitysteksti,true);
		$criteria->compare('palvelu',$this->palvelu);
		$criteria->compare('kesto',$this->kesto,true);
		$criteria->compare('nelio',$this->nelio,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
