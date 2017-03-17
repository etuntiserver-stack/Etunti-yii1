<?php

/**
 * This is the model class for table "tarjouslaskenta".
 *
 * The followings are the available columns in table 'tarjouslaskenta':
 * @property integer $id
 * @property string $time
 * @property integer $yhteystiedot_id
 * @property integer $asiakas_id
 * @property integer $tuote_palvelu_id
 * @property string $hinta_tyyppi
 * @property integer $neliot
 * @property integer $kayntikerrat
 * @property integer $tuntien_maara
 * @property integer $yhteensa
 * @property integer $tavoite_myyntikate
 * @property integer $palkkakustannus
 * @property integer $matkat
 * @property integer $iltalisa
 * @property integer $yolisa
 * @property string $muut_kulut
 */
class Tarjouslaskenta extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tarjouslaskenta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('yhteystiedot_id, asiakas_id, tuote_palvelu_id, neliot, kayntikerrat, tuntien_maara, yhteensa, tavoite_myyntikate, palkkakustannus, matkat, iltalisa, yolisa', 'numerical', 'integerOnly'=>true),
			array('hinta_tyyppi', 'length', 'max'=>100),
			array('muut_kulut', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, yhteystiedot_id, asiakas_id, tuote_palvelu_id, hinta_tyyppi, neliot, kayntikerrat, tuntien_maara, yhteensa, tavoite_myyntikate, palkkakustannus, matkat, iltalisa, yolisa, muut_kulut', 'safe', 'on'=>'search'),
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
			'time' => Yii::t('main', 'Päivämäärä'),
			'yhteystiedot_id' => Yii::t('main', 'Yhteystiedot'),
			'asiakas_id' => Yii::t('main', 'Asiakas'),
			'tuote_palvelu_id' => Yii::t('main', 'Tuote/Palvelu'),
			'hinta_tyyppi' => Yii::t('main', 'Hinta tyyppi'),
			'neliot' => Yii::t('main', 'Neliöt'),
			'kayntikerrat' => Yii::t('main', 'Kayntikerrat'),
			'tuntien_maara' => Yii::t('main', 'Tuntien määrä'),
			'yhteensa' => Yii::t('main', 'Yhteensä'),
			'tavoite_myyntikate' => Yii::t('main', 'Tavoite myyntikate'),
			'palkkakustannus' => Yii::t('main', 'Palkkakustannus'),
			'matkat' => Yii::t('main', 'Matkat'),
			'iltalisa' => Yii::t('main', 'Iltalisä'),
			'yolisa' => Yii::t('main', 'Yölisä'),
			'muut_kulut' => Yii::t('main', 'Muut kulut'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('yhteystiedot_id',$this->yhteystiedot_id);
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('tuote_palvelu_id',$this->tuote_palvelu_id);
		$criteria->compare('hinta_tyyppi',$this->hinta_tyyppi,true);
		$criteria->compare('neliot',$this->neliot);
		$criteria->compare('kayntikerrat',$this->kayntikerrat);
		$criteria->compare('tuntien_maara',$this->tuntien_maara);
		$criteria->compare('yhteensa',$this->yhteensa);
		$criteria->compare('tavoite_myyntikate',$this->tavoite_myyntikate);
		$criteria->compare('palkkakustannus',$this->palkkakustannus);
		$criteria->compare('matkat',$this->matkat);
		$criteria->compare('iltalisa',$this->iltalisa);
		$criteria->compare('yolisa',$this->yolisa);
		$criteria->compare('muut_kulut',$this->muut_kulut,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tarjouslaskenta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
