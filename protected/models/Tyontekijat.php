<?php

/**
 * This is the model class for table "sivex_ttekijat".
 *
 * The followings are the available columns in table 'sivex_ttekijat':
 * @property integer $id
 * @property string $imei
 * @property string $laiten_puh
 * @property string $tekijan_nimi
 * @property string $tekijan_henkilotunnus
 * @property string $tekijan_puh
 * @property string $tekijan_email
 * @property string $tekijan_lanka_puh
 * @property string $tekijan_katuosoite
 * @property string $tekijan_pnumero
 * @property string $tekijan_ptoimipaikka
 * @property string $tyoryhma
 * @property string $tyoehtosopimus
 * @property string $tekijan_kulunvalvonta
 * @property string $tekijan_pankkitili
 * @property string $tekijan_konttori
 * @property string $aktiivinen
 * @property string $tekijan_tietoja
 * @property string $tekijan_muisti
 * @property string $salasana
 * @property integer $online_varauksen_valmina
 * @property string $kortit
 * @property string $ayjasenyys
 */
class Tyontekijat extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tyontekijat the static model class
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
		return 'sivex_ttekijat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('imei, laiten_puh, tekijan_nimi, tekijan_henkilotunnus, tekijan_puh, tekijan_email, tekijan_lanka_puh, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tyoryhma, tyoehtosopimus, tekijan_kulunvalvonta, tekijan_pankkitili, tekijan_konttori, aktiivinen, tekijan_tietoja, tekijan_muisti, salasana, kortit, ayjasenyys', 'required'),
			array('online_varauksen_valmina', 'numerical', 'integerOnly'=>true),
			array('imei', 'length', 'max'=>30),
			array('laiten_puh, tekijan_nimi, tekijan_katuosoite, tekijan_pankkitili, salasana', 'length', 'max'=>100),
			array('tekijan_henkilotunnus, tekijan_puh, tekijan_lanka_puh, tyoryhma', 'length', 'max'=>20),
			array('tekijan_email, tekijan_ptoimipaikka, tyoehtosopimus, tekijan_kulunvalvonta, tekijan_konttori, aktiivinen', 'length', 'max'=>50),
			array('tekijan_pnumero', 'length', 'max'=>7),
			array('ayjasenyys', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, imei, laiten_puh, tekijan_nimi, tekijan_henkilotunnus, tekijan_puh, tekijan_email, tekijan_lanka_puh, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tyoryhma, tyoehtosopimus, tekijan_kulunvalvonta, tekijan_pankkitili, tekijan_konttori, aktiivinen, tekijan_tietoja, tekijan_muisti, salasana, online_varauksen_valmina, kortit, ayjasenyys', 'safe', 'on'=>'search'),
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
			'imei' => 'Imei',
			'laiten_puh' => 'Laiten Puh',
			'tekijan_nimi' => 'Työntekijä',
			'tekijan_henkilotunnus' => 'Tekijan Henkilotunnus',
			'tekijan_puh' => 'Tekijan Puh',
			'tekijan_email' => 'Tekijan Email',
			'tekijan_lanka_puh' => 'Tekijan Lanka Puh',
			'tekijan_katuosoite' => 'Tekijan Katuosoite',
			'tekijan_pnumero' => 'Tekijan Pnumero',
			'tekijan_ptoimipaikka' => 'Tekijan Ptoimipaikka',
			'tyoryhma' => 'Tyoryhma',
			'tyoehtosopimus' => 'Tyoehtosopimus',
			'tekijan_kulunvalvonta' => 'Tekijan Kulunvalvonta',
			'tekijan_pankkitili' => 'Tekijan Pankkitili',
			'tekijan_konttori' => 'Tekijan Konttori',
			'aktiivinen' => 'Aktiivinen',
			'tekijan_tietoja' => 'Tekijan Tietoja',
			'tekijan_muisti' => 'Tekijan Muisti',
			'salasana' => 'Salasana',
			'online_varauksen_valmina' => 'Online Varauksen Valmina',
			'kortit' => 'Kortit',
			'ayjasenyys' => 'Ayjasenyys',
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
		$criteria->compare('imei',$this->imei,true);
		$criteria->compare('laiten_puh',$this->laiten_puh,true);
		$criteria->compare('tekijan_nimi',$this->tekijan_nimi,true);
		$criteria->compare('tekijan_henkilotunnus',$this->tekijan_henkilotunnus,true);
		$criteria->compare('tekijan_puh',$this->tekijan_puh,true);
		$criteria->compare('tekijan_email',$this->tekijan_email,true);
		$criteria->compare('tekijan_lanka_puh',$this->tekijan_lanka_puh,true);
		$criteria->compare('tekijan_katuosoite',$this->tekijan_katuosoite,true);
		$criteria->compare('tekijan_pnumero',$this->tekijan_pnumero,true);
		$criteria->compare('tekijan_ptoimipaikka',$this->tekijan_ptoimipaikka,true);
		$criteria->compare('tyoryhma',$this->tyoryhma,true);
		$criteria->compare('tyoehtosopimus',$this->tyoehtosopimus,true);
		$criteria->compare('tekijan_kulunvalvonta',$this->tekijan_kulunvalvonta,true);
		$criteria->compare('tekijan_pankkitili',$this->tekijan_pankkitili,true);
		$criteria->compare('tekijan_konttori',$this->tekijan_konttori,true);
		$criteria->compare('aktiivinen',$this->aktiivinen,true);
		$criteria->compare('tekijan_tietoja',$this->tekijan_tietoja,true);
		$criteria->compare('tekijan_muisti',$this->tekijan_muisti,true);
		$criteria->compare('salasana',$this->salasana,true);
		$criteria->compare('online_varauksen_valmina',$this->online_varauksen_valmina);
		$criteria->compare('kortit',$this->kortit,true);
		$criteria->compare('ayjasenyys',$this->ayjasenyys,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
