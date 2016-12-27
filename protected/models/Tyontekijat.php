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

public $count;
public $tunnus;


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


	public function netvisorCheck($attribute,$params)
	{

		$asetukset = Asetukset::model()->findByPk(1);
		if($asetukset->netvisor_kaytto == 1)
		{
			if(
				empty($this->tekijan_pankkitili)
				or empty($this->tekijan_konttori)
				or empty($this->ammattinimike)
				or empty($this->tekijan_henkilotunnus)
			)
			$this->addError($attribute, $this->attributeLabels()[$attribute].' '.Yii::t('main', ' on pakkolinen'));
		}
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tekijan_nimi, aktiivinen', 'required'),
			array('tekijan_pankkitili, tekijan_konttori, ammattinimike, tekijan_henkilotunnus', 'netvisorCheck', 'on'=>'insert, update'),
			array('online_varauksen_valmina, ilmoitus_merkkipaivasta_vuosi, tyontekijan_numero', 'numerical', 'integerOnly'=>true),
			array('imei', 'length', 'max'=>100),
			array('tyo_toimialue, laiten_puh, tekijan_nimi, tekijan_katuosoite, tekijan_pankkitili, salasana', 'length', 'max'=>100),
			array('tekijan_henkilotunnus, tekijan_puh, tekijan_lanka_puh', 'length', 'max'=>20),
			array('tekijan_email, tekijan_ptoimipaikka, tyoehtosopimus, tekijan_kulunvalvonta, tekijan_konttori, aktiivinen', 'length', 'max'=>50),
			array('tekijan_pnumero', 'length', 'max'=>7),
			array('sukunimi, ammattinimike', 'length', 'max'=>255),
			array('ayjasenyys', 'length', 'max'=>10),
			array('kortit, tekijan_muisti, tekijan_tietoja, tietoja_onlinevarauksen', 'length', 'max'=>2000),
			array('gcm_reg_id, position, kortit_voimassaolo, tyoryhma', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, imei, laiten_puh, tekijan_nimi, tekijan_henkilotunnus, tekijan_puh, tekijan_email, tekijan_lanka_puh, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tyoryhma, tyoehtosopimus, tekijan_kulunvalvonta, tekijan_pankkitili, tekijan_konttori, aktiivinen, tekijan_tietoja, tekijan_muisti, salasana, online_varauksen_valmina, kortit, ayjasenyys, gcm_reg_id, position, tyo_toimialue, sukunimi', 'safe', 'on'=>'search'),
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
			'imei' => Yii::t('main', 'Imei'),
			'laiten_puh' => Yii::t('main', 'Työpuhelin'),
			'tekijan_nimi' => Yii::t('main', 'Etunimi'),
			'tekijan_henkilotunnus' => Yii::t('main', 'Henkilötunnus'),
			'tekijan_puh' => Yii::t('main', 'Oma puhelin'),
			'tekijan_email' => Yii::t('main', 'Sähköposti'),
			'tekijan_lanka_puh' => Yii::t('main', 'Lanka Puh'),
			'tekijan_katuosoite' => Yii::t('main', 'Katuosoite'),
			'tekijan_pnumero' => Yii::t('main', 'Postinumero'),
			'tekijan_ptoimipaikka' => Yii::t('main', 'Postitoimipaikka'),
			'tyoryhma' => Yii::t('main', 'Työryhmä'),
			'tyoehtosopimus' => Yii::t('main', 'Työehtosopimus'),
			'tekijan_kulunvalvonta' => Yii::t('main', 'Kulunvalvonta'),
			'tekijan_pankkitili' => Yii::t('main', 'Pankkitili'),
			'tekijan_konttori' => Yii::t('main', 'BIC'),
			'aktiivinen' => Yii::t('main', 'Työntekijän tila'),
			'tekijan_tietoja' => Yii::t('main', 'Tietoja'),
			'tekijan_muisti' => Yii::t('main', 'Muistiinpanoja'),
			'salasana' => Yii::t('main', 'Mobiilisovelluksen salasana'),
			'online_varauksen_valmina' => Yii::t('main', 'Valmis onlinevaraukseen'),
			'kortit' => Yii::t('main', 'Kortit'),
			'ayjasenyys' => Yii::t('main', 'Ay-jäsenyys'),
			'gcm_reg_id'=> Yii::t('main', 'Google Cloud Messaging ID'),
			'position' => Yii::t('main', 'Viimeinen sijainti'),
			'tyo_toimialue' => Yii::t('main', 'Toimialue'),
			'sukunimi' => Yii::t('main', 'Sukunimi'),
			'ammattinimike' => Yii::t('main', 'Ammattinimike'),
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
		$criteria->order = "tekijan_nimi";


		if(Yii::app()->request->getPost('aktiivinen') == 'yes')
		Yii::app()->session['aktiivinen'] = true;
		if(Yii::app()->request->getPost('aktiivinen') == 'no')
		unset(Yii::app()->session['aktiivinen']);
/*
		if(Yii::app()->session['aktiivinen'])
		$criteria->condition = " aktiivinen=0 or aktiivinen=1 or aktiivinen=2 ";
		else
		$criteria->condition = " aktiivinen=1 ";
*/
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
		$criteria->compare('gcm_reg_id',$this->gcm_reg_id);
		$criteria->compare('position',$this->position);
		$criteria->compare('tyo_toimialue',$this->tyo_toimialue);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
