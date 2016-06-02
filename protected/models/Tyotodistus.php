<?php

/**
 * This is the model class for table "sivex_tyotodistukset".
 *
 * The followings are the available columns in table 'sivex_tyotodistukset':
 * @property integer $id
 * @property string $time
 * @property integer $key
 * @property string $tyonantaja
 * @property string $osoite
 * @property string $postinumero
 * @property string $postitoimipaikka
 * @property string $puhelin
 * @property string $y_tunnus
 * @property string $sahkoposti
 * @property string $tekijan_email
 * @property integer $tid
 * @property string $tekijan_nimi
 * @property string $tekijan_katuosoite
 * @property string $tekijan_pnumero
 * @property string $tekijan_ptoimipaikka
 * @property string $tekijan_puh
 * @property string $tekijan_henkilotunnus
 * @property string $Alku
 * @property string $Loppu
 * @property string $Tyokohde
 * @property string $Tyotehtavat
 * @property string $TyosuhteenPaattamisenSyy
 * @property string $Tyotaito
 * @property string $Kaytos
 * @property string $Arvio
 * @property string $Paivays
 * @property string $Paikka
 * @property string $TyonantajanEdustaja
 * @property string $NimikeTehtava
 * @property string $tiedosto
 */
class Tyotodistus extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sivex_tyotodistukset';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('time, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, Alku, Loppu, Tyokohde, Tyotehtavat, TyosuhteenPaattamisenSyy, Tyotaito, Kaytos, Arvio, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto', 'required'),

			array('Tyokohde, Tyotehtavat, TyosuhteenPaattamisenSyy, Tyotaito, Kaytos, Arvio', 'length', 'max'=>1000),

			array('key, tid', 'numerical', 'integerOnly'=>true),
			array('tyonantaja, tekijan_nimi', 'length', 'max'=>70),
			array('osoite', 'length', 'max'=>255),
			array('postinumero, tekijan_pnumero', 'length', 'max'=>7),
			array('postitoimipaikka, sahkoposti, tekijan_email, tekijan_katuosoite, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto', 'length', 'max'=>100),
			array('puhelin, y_tunnus, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, Alku, Loppu, Paivays', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, key, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, Alku, Loppu, Tyokohde, Tyotehtavat, TyosuhteenPaattamisenSyy, Tyotaito, Kaytos, Arvio, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto', 'safe', 'on'=>'search'),
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
			'time' => 'Time',
			'key' => 'Key',
			'tyonantaja' => 'Tyonantaja',
			'osoite' => 'Osoite',
			'postinumero' => 'Postinumero',
			'postitoimipaikka' => 'Postitoimipaikka',
			'puhelin' => 'Puhelin',
			'y_tunnus' => 'Y Tunnus',
			'sahkoposti' => 'Sahkoposti',
			'tekijan_email' => 'Tekijan Email',
			'tid' => 'Tid',
			'tekijan_nimi' => 'Tekijan Nimi',
			'tekijan_katuosoite' => 'Tekijan Katuosoite',
			'tekijan_pnumero' => 'Tekijan Pnumero',
			'tekijan_ptoimipaikka' => 'Tekijan Ptoimipaikka',
			'tekijan_puh' => 'Tekijan Puh',
			'tekijan_henkilotunnus' => 'Tekijan Henkilotunnus',
			'Alku' => 'Alku',
			'Loppu' => 'Loppu',
			'Tyokohde' => 'Tyokohde',
			'Tyotehtavat' => 'Tyotehtavat',
			'TyosuhteenPaattamisenSyy' => 'Tyosuhteen Paattamisen Syy',
			'Tyotaito' => 'Tyotaito',
			'Kaytos' => 'Kaytos',
			'Arvio' => 'Arvio',
			'Paivays' => 'Paivays',
			'Paikka' => 'Paikka',
			'TyonantajanEdustaja' => 'Tyonantajan Edustaja',
			'NimikeTehtava' => 'Nimike Tehtava',
			'tiedosto' => 'Tiedosto',
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
		$criteria->compare('key',$this->key);
		$criteria->compare('tyonantaja',$this->tyonantaja,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('postinumero',$this->postinumero,true);
		$criteria->compare('postitoimipaikka',$this->postitoimipaikka,true);
		$criteria->compare('puhelin',$this->puhelin,true);
		$criteria->compare('y_tunnus',$this->y_tunnus,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('tekijan_email',$this->tekijan_email,true);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('tekijan_nimi',$this->tekijan_nimi,true);
		$criteria->compare('tekijan_katuosoite',$this->tekijan_katuosoite,true);
		$criteria->compare('tekijan_pnumero',$this->tekijan_pnumero,true);
		$criteria->compare('tekijan_ptoimipaikka',$this->tekijan_ptoimipaikka,true);
		$criteria->compare('tekijan_puh',$this->tekijan_puh,true);
		$criteria->compare('tekijan_henkilotunnus',$this->tekijan_henkilotunnus,true);
		$criteria->compare('Alku',$this->Alku,true);
		$criteria->compare('Loppu',$this->Loppu,true);
		$criteria->compare('Tyokohde',$this->Tyokohde,true);
		$criteria->compare('Tyotehtavat',$this->Tyotehtavat,true);
		$criteria->compare('TyosuhteenPaattamisenSyy',$this->TyosuhteenPaattamisenSyy,true);
		$criteria->compare('Tyotaito',$this->Tyotaito,true);
		$criteria->compare('Kaytos',$this->Kaytos,true);
		$criteria->compare('Arvio',$this->Arvio,true);
		$criteria->compare('Paivays',$this->Paivays,true);
		$criteria->compare('Paikka',$this->Paikka,true);
		$criteria->compare('TyonantajanEdustaja',$this->TyonantajanEdustaja,true);
		$criteria->compare('NimikeTehtava',$this->NimikeTehtava,true);
		$criteria->compare('tiedosto',$this->tiedosto,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tyotodistus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
