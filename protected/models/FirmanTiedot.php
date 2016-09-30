<?php

/**
 * This is the model class for table "sivex_tyonantaja".
 *
 * The followings are the available columns in table 'sivex_tyonantaja':
 * @property integer $id
 * @property string $tyonantaja
 * @property string $osoite
 * @property string $postinumero
 * @property string $postitoimipaikka
 * @property string $puhelin
 * @property string $y_tunnus
 * @property string $sahkoposti
 * @property string $tilinumero
 * @property string $iban
 * @property string $bic
 * @property string $johtaja
 */
class FirmanTiedot extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FirmanTiedot the static model class
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
		return 'sivex_tyonantaja';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti', 'required'),
			array('tyonantaja, postinumero, postitoimipaikka, y_tunnus', 'length', 'max'=>50),
			array('osoite, puhelin, sahkoposti, tilinumero, iban, johtaja', 'length', 'max'=>100),
			array('bic', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tilinumero, iban, bic, johtaja', 'safe', 'on'=>'search'),
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
			'tyonantaja' => Yii::t('main', 'Yrityksen nimi'),
			'osoite' => Yii::t('main', 'Osoite'),
			'postinumero' => Yii::t('main', 'Postinumero'),
			'postitoimipaikka' => Yii::t('main', 'Postitoimipaikka'),
			'puhelin' => Yii::t('main', 'Puhelin'),
			'y_tunnus' => Yii::t('main', 'Y-Tunnus'),
			'sahkoposti' => Yii::t('main', 'Sähköposti'),
			'tilinumero' => Yii::t('main', 'Tilinumero'),
			'iban' => Yii::t('main', 'IBAN'),
			'bic' => Yii::t('main', 'BIC'),
			'johtaja' => Yii::t('main', 'Johtaja'),
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
		$criteria->compare('tyonantaja',$this->tyonantaja,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('postinumero',$this->postinumero,true);
		$criteria->compare('postitoimipaikka',$this->postitoimipaikka,true);
		$criteria->compare('puhelin',$this->puhelin,true);
		$criteria->compare('y_tunnus',$this->y_tunnus,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('tilinumero',$this->tilinumero,true);
		$criteria->compare('iban',$this->iban,true);
		$criteria->compare('bic',$this->bic,true);
		$criteria->compare('johtaja',$this->johtaja,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
