<?php

/**
 * This is the model class for table "sivex_kirjallinen_varoitus".
 *
 * The followings are the available columns in table 'sivex_kirjallinen_varoitus':
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
 * @property string $kirjallisen_varoituksen
 * @property string $Paivays
 * @property string $Paikka
 * @property string $TyonantajanEdustaja
 * @property string $NimikeTehtava
 * @property string $tiedosto
 */
class KirjallinenVaroitus extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sivex_kirjallinen_varoitus';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('time, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, kirjallisen_varoituksen, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto', 'required'),
			array('key, tid', 'numerical', 'integerOnly'=>true),
			array('tyonantaja, tekijan_nimi', 'length', 'max'=>70),
			array('osoite, tiedosto', 'length', 'max'=>255),
			array('postinumero, tekijan_pnumero', 'length', 'max'=>7),
			array('postitoimipaikka, sahkoposti, tekijan_email, tekijan_katuosoite, Paikka, TyonantajanEdustaja, NimikeTehtava', 'length', 'max'=>100),
			array('kirjallisen_varoituksen', 'length', 'max'=>3000),
			array('puhelin, y_tunnus, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, Paivays', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, key, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, kirjallisen_varoituksen, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto', 'safe', 'on'=>'search'),
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
			'tyonantaja' => 'Työnantaja',
			'osoite' => 'Työnantaja osoite',
			'postinumero' => 'Työnantaja postinumero',
			'postitoimipaikka' => 'Työnantaja postitoimipaikka',
			'puhelin' => 'Työnantaja puhelin',
			'y_tunnus' => 'Työnantaja Y-tunnus',
			'sahkoposti' => 'Työnantaja saäköposti',
			'tekijan_email' => 'Työntekijä sähköposti',
			'tid' => 'Tid',
			'tekijan_nimi' => 'Työntekijä nimi',
			'tekijan_katuosoite' => 'Työntekijä osoite',
			'tekijan_pnumero' => 'Työntekijä postinumero',
			'tekijan_ptoimipaikka' => 'Työntekijä postitoimipaikka',
			'tekijan_puh' => 'Työntekijä puhelin',
			'tekijan_henkilotunnus' => 'Työntekijä henkilötunnus',
			'kirjallisen_varoituksen' => 'Kirjallinen varoitus teksti',
			'Paivays' => 'Päiväys',
			'Paikka' => 'Paikka',
			'TyonantajanEdustaja' => 'Työnantajan edustaja',
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
		$criteria->order = " id DESC ";

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
		$criteria->compare('kirjallisen_varoituksen',$this->kirjallisen_varoituksen,true);
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
	 * @return KirjallinenVaroitus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
