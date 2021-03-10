<?php

/**
 * This is the model class for table "asiakkaat".
 *
 * The followings are the available columns in table 'asiakkaat':
 * @property integer $id
 * @property string $time
 * @property string $etunimi
 * @property string $sukunimi
 * @property string $osoite
 * @property string $kaupunki
 * @property integer $postinumero
 * @property string $puhelin
 * @property string $sahkoposti
 * @property integer $ryhma
 * @property integer $aktiivinen
 */
class Asiakkaat extends DB2ActiveRecord
{

	public $count, $filter_postitoimipaikka, $filter_tyoryhma, $filter_asiakasryhma, $filter_tyyppi;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Asiakkaat the static model class
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

		$tb_name = 'asiakkaat';

		$table = Yii::app()->db1->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {
			echo $tb_name. "\n";
			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(
			'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
			'yrityksen_nimi' => 'varchar(100) DEFAULT NULL',
			'y_tunnus' => 'varchar(50) DEFAULT NULL',
			'henkilotunnus' => 'varchar(50) DEFAULT NULL',
			'yhteyshenkilo' => 'varchar(100) DEFAULT NULL',
			'osoite' => 'varchar(255) DEFAULT NULL',
			'kaupunki' => 'varchar(100) DEFAULT NULL',
			'postinumero' => 'varchar(100) DEFAULT NULL',
			'puhelin' => 'varchar(100) DEFAULT NULL',
			'toissijainen_puhelinnumero' => 'text DEFAULT NULL',
			'sahkoposti' => 'varchar(100) DEFAULT NULL',
			'ryhma' => 'varchar(255) DEFAULT NULL',
			'aktiivinen' => 'int(1) DEFAULT 0',
			'laskutus_kanava' => 'varchar(255) DEFAULT NULL',
			'maksuehto' => 'varchar(20) DEFAULT NULL',
			'tyyppi' => 'varchar(100) DEFAULT NULL',
			'asiakasnumero' => 'varchar(100) DEFAULT NULL',
			'ovt_tunnus' => 'varchar(100) DEFAULT NULL',
			'valittajan_tunnus' => 'varchar(100) DEFAULT NULL',
			'verkkolaskuosoite' => 'varchar(255) DEFAULT NULL',
			'muistutuslasku_auto' => 'int(1) DEFAULT 0',
			'kirjeenluokka' => 'int(1) DEFAULT 0',
			'myyja' => 'varchar(100) DEFAULT NULL',
			'viivastyskorko' => 'varchar(20) DEFAULT NULL',
			'salasana' => 'varchar(255) DEFAULT NULL',
			'netvisorkey' => 'int(11) DEFAULT 0',
			'netvisor_dimension_name' => 'varchar(100) DEFAULT NULL',
			'netvisor_dimension_item' => 'varchar(100) DEFAULT NULL',
			'k_osoite' => 'varchar(255) DEFAULT NULL',
			'k_postinumero' => 'varchar(100) DEFAULT NULL',
			'k_kaupunki' => 'varchar(100) DEFAULT NULL',
			'onlinevarauksen_asiakas' => 'int(1) DEFAULT 0',
			'asiakastila' => 'int(11) DEFAULT 0',
			'sahkopostilaskuosoite' => 'varchar(255) DEFAULT NULL',
			'vinkki_id' => 'int(11) DEFAULT 0',
			'alennuskoodit' => 'text DEFAULT NULL',
			'token' => 'varchar(255) DEFAULT NULL',
			'app_kayttoehdot' => 'int(1) DEFAULT 0',
			'hinnasto_id' => 'int(11) DEFAULT 0',
			'alv' => 'int(3) DEFAULT 0',
			'hinta_tyyppi' => 'varchar(50) DEFAULT NULL',
			'hinta' => 'varchar(10) DEFAULT NULL',
			'verot' => 'varchar(100) DEFAULT NULL',
			'hinta_sis_alv' => 'float DEFAULT 0',
			'tyoryhma' => 'int(11) DEFAULT 0',
			'gcm_reg_id' => 'varchar(500) DEFAULT NULL',
			'lopetuksen_pvm' => 'varchar(50) DEFAULT NULL',
			'lopetuksen_syy' => 'TEXT DEFAULT NULL',
			'muistiinpano' => 'text DEFAULT NULL',
			'lisatietoja_laskutuksesta' => 'text DEFAULT NULL',
			'sopimustyyppi' => 'int(1) DEFAULT 1',
			'freshdesk_id' => 'BIGINT(11) DEFAULT 0',
			//'vinkki_tunnit' => 'varchar(10)',
			//'vinkki_prosentti' => 'varchar(10)',
			'etunimi' => 'varchar(255) DEFAULT NULL',
			'sukunimi' => 'varchar(255) DEFAULT NULL',
		);

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
			}
		}	

		return $tb_name;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$asetukset = Asetukset::model()->findByPk(1);
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		//	array('osoite, postinumero, kaupunki', 'required'),
		$arr = array(
			array('asiakasnumero', 'length', 'max'=>9),
			array('asiakasnumero','unique', 'message'=>'Tämä asiakasnumero on jo olemassa!'),
			//array('etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen', 'required'),
			array('kirjeenluokka, muistutuslasku_auto, aktiivinen, netvisorkey, onlinevarauksen_asiakas, asiakastila, vinkki_id, app_kayttoehdot, hinnasto_id, alv, tyoryhma, sopimustyyppi, freshdesk_id', 'numerical', 'integerOnly'=>true),
			array('etunimi, sukunimi, myyja, postinumero, k_postinumero, yhteyshenkilo, yrityksen_nimi, y_tunnus, henkilotunnus, kaupunki, k_kaupunki, sahkoposti', 'length', 'max'=>100),
			array('tyyppi, laskutus_kanava, osoite, k_osoite, verkkolaskuosoite, salasana, ryhma, sahkopostilaskuosoite, token', 'length', 'max'=>255),
			array('maksuehto, viivastyskorko, hinta, hinta_sis_alv', 'length', 'max'=>20),
			array('puhelin', 'length', 'max'=>50),
			array('asiakasnumero, ovt_tunnus, valittajan_tunnus, hinta_tyyppi, verot, lopetuksen_pvm', 'length', 'max'=>100),
			array('alennuskoodit, gcm_reg_id, lopetuksen_syy, netvisor_dimension_name, netvisor_dimension_item, toissijainen_puhelinnumero, muistiinpano, lisatietoja_laskutuksesta', 'safe'),
			array('sahkoposti','unique', 'message'=>'Tämä sähköposti on jo rekisteröity asiakkaalle.'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen, laskutus_kanava,maksuehto, tyyppi, asiakasnumero, ovt_tunnus, valittajan_tunnus, myyja, kirjeenluokka, muistutuslasku_auto', 'safe', 'on'=>'search'),
		);

		$controller = Yii::app()->getController()->getAction()->controller->id;
		$action = Yii::app()->controller->action->id;
		if( 
			$controller == 'asiakkaat' 
			and $action != 'massamuokkaus'
			and isset($asetukset->asiakas_pakkoliset) 
			and is_array(json_decode($asetukset->asiakas_pakkoliset, true)) 
		){
			$impl = implode(", ", json_decode($asetukset->asiakas_pakkoliset, true));
			array_push($arr, array($impl, 'required'));
		}
		return $arr;
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
			'asiakasnumero' => Yii::t('main', 'Asiakasnumero'),
			'time' => Yii::t('main', 'Luotu'),
			'yhteyshenkilo' => Yii::t('main', 'Yhteyshenkilö'),
			'yrityksen_nimi' => Yii::t('main', 'Yrityksen Nimi'),
			'y_tunnus' => Yii::t('main', 'Y-tunnus'),
			'henkilotunnus' => Yii::t('main', 'Henkilötunnus'),
			'osoite' => Yii::t('main', 'Osoite'),
			'k_osoite' => Yii::t('main', 'Osoite'),
			'kaupunki' => Yii::t('main', 'Postitoimipaikka'),
			'k_kaupunki' => Yii::t('main', 'Postitoimipaikka'),
			'postinumero' => Yii::t('main', 'Postinumero'),
			'k_postinumero' => Yii::t('main', 'Postinumero'),
			'puhelin' => Yii::t('main', 'Puhelin'),
			'sahkoposti' => Yii::t('main', 'Sähköposti'),
			'ryhma' => Yii::t('main', 'Asiakasryhmä'),
			'aktiivinen' => Yii::t('main', 'Aktiivinen'),
			'laskutus_kanava' => Yii::t('main', 'Laskutus kanava'),
			'maksuehto' => Yii::t('main', 'Maksuehto'),
			'viivastyskorko' => Yii::t('main', 'Viivästyskorko'),
			'tyyppi' => Yii::t('main', 'Asiakastyyppi'),
			'ovt_tunnus' => Yii::t('main', 'Yrityksen OVT-tunnus'),
			'valittajan_tunnus' => Yii::t('main', 'Operaattorin välittäjän tunnus'),
			'verkkolaskuosoite' => Yii::t('main', 'Verkkolaskuosoite'),
			'muistutuslasku_auto'=> Yii::t('main', 'Muistutuslasku automaatiseesti'),
			'kirjeenluokka'=> Yii::t('main', 'Kirjeenluokka'),
			'myyja'=> Yii::t('main', 'Myyjä'),
			'salasana'=> Yii::t('main', 'Extranet-salasana'),
			'sahkopostilaskuosoite'=> Yii::t('main', 'Sähköpostilaskuosoite'),
			'app_kayttoehdot' => Yii::t('main', 'eDico käytöehdot hyväksytty'),
			'hinnasto_id'=> Yii::t('main', 'Hinnasto'),

			'alv'=> Yii::t('main', 'ALV %'),
			'hinta_tyyppi'=> Yii::t('main', 'Hinta tyyppi'),
			'hinta'=> Yii::t('main', 'Hinta (ALV0)'),
			'hinta_sis_alv' => Yii::t('main', 'Hinta (sis. ALV)'),
			'tyoryhma'=> Yii::t('main', ' Työryhmä'),
			'filter_postitoimipaikka' => Yii::t('main', 'Postitoimipaikka'),
			'filter_tyoryhma' => Yii::t('main', 'Työryhmä'),
			'filter_asiakasryhma' => Yii::t('main', 'Asiakasryhmä'),
			'filter_tyyppi' => Yii::t('main', 'Tyyppi'),
			'lopetuksen_pvm' => Yii::t('main', 'Sopimuksen päättymispäivä jolloin asiakas menee passiviksi'),
			'lopetuksen_syy' => Yii::t('main', 'Syy'),
			'netvisor_dimension_name' => Yii::t('main', 'Kustannuspaikka'),
			'lisatietoja_laskutuksesta' => Yii::t('main', 'Lisätietoja laskutuksesta'),
		);
	}
	// Fullname
    public function getFullname(){
		$return = '';
		if( !empty($this->yrityksen_nimi) and $this->tyyppi == 'yritys' ){
			$return = $this->yrityksen_nimi;
		}
		if( !empty($this->etunimi) and $this->tyyppi == 'henkilo' ){
			$return = $this->etunimi.' '.$this->sukunimi;
		}
		if( empty($return)){
			$return = 'Asiakas id: '.$this->id;
		}
		return trim($return);
    }

	// Etusukunimi
    public function getEtusukunimi(){
		$return = '';
		if( !empty($this->etunimi) )
			$return = $this->etunimi.' '.$this->sukunimi;

		return trim($return);
    }

    public function getValikkotyoryhma(){
		$return = $this->tyoryhma;
		if( !empty($this->tyoryhma)){
			$vlk = Valikkoot::model()->findByPk($this->tyoryhma);
			if( isset($vlk->id)){ $return = $vlk->value; }
		}
		return $return;
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
		$criteria->compare('asiakasnumero',$this->asiakasnumero);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('yhteyshenkilo',$this->yhteyshenkilo,true);
		$criteria->compare('yrityksen_nimi',$this->yrityksen_nimi,true);
		$criteria->compare('y_tunnus',$this->y_tunnus,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('kaupunki',$this->kaupunki,true);
		$criteria->compare('postinumero',$this->postinumero);
		$criteria->compare('puhelin',$this->puhelin,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('ryhma',$this->ryhma);
		$criteria->compare('aktiivinen',$this->aktiivinen);
		$criteria->compare('laskutus_kanava',$this->laskutus_kanava);
		$criteria->compare('maksuehto',$this->maksuehto);
		$criteria->compare('tyyppi',$this->tyyppi);
		$criteria->compare('ovt_tunnus',$this->ovt_tunnus,true);
		$criteria->compare('valittajan_tunnus',$this->valittajan_tunnus,true);
		$criteria->compare('verkkolaskuosoite',$this->verkkolaskuosoite,true);
		$criteria->compare('alv',$this->alv,true);
		$criteria->compare('hinta_tyyppi',$this->hinta_tyyppi,true);
		$criteria->compare('hinta',$this->hinta,true);
		$criteria->compare('muistutuslasku_auto',$this->muistutuslasku_auto,true);
		$criteria->compare('kirjeenluokka',$this->kirjeenluokka,true);
		$criteria->compare('myyja',$this->myyja,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
  }

  /**
   * Attach event handler for onAfterSave event for every created model.
   */
  public function init()
  {
    $this->attachEventHandler('onAfterSave', [$this, 'onAfterSave']);
  }

  /**
   * Update Freshdesk customer after save.
   */
  public function onAfterSave($event)
  {
    /** @var Freshdesk */
    $fd = Yii::createComponent('Freshdesk');
    if ($fd->isDisabled())
      return;
    $asiakas = $event->sender;
    if (!empty($asiakas->id))
      $fd->exportContact($asiakas->id);
  }
}
