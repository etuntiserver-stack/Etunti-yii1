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

	public $filter_postitoimipaikka, $filter_tyoryhma, $filter_asiakasryhma, $filter_tyyppi;

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
		$check_this_table = true;
		if(!isset(Yii::app()->session[$tb_name]))
		{
			Yii::app()->session[$tb_name] = true;
			$check_this_table = true;
		}


		if($check_this_table)
		{
		$table = Yii::app()->db1->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(

                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'yrityksen_nimi' => 'varchar(100)',
                     'y_tunnus' => 'varchar(50)',
                     'yhteyshenkilo' => 'varchar(100)',
                     'osoite' => 'varchar(255)',
                     'kaupunki' => 'varchar(100)',
                     'postinumero' => 'varchar(100)',
                     'puhelin' => 'varchar(100)',
                     'sahkoposti' => 'varchar(100)',
                     'ryhma' => 'varchar(255)',
                     'aktiivinen' => 'int(1)',
                     'laskutus_kanava' => 'varchar(255)',
                     'maksuehto' => 'varchar(20)',
                     'tyyppi' => 'varchar(100)',
                     'asiakasnumero' => 'varchar(100)',
                     'ovt_tunnus' => 'varchar(100)',
                     'valittajan_tunnus' => 'varchar(100)',
                     'verkkolaskuosoite' => 'varchar(255)',
                     'muistutuslasku_auto' => 'int(1)',
                     'kirjeenluokka' => 'int(1)',
                     'myyja' => 'varchar(100)',
                     'viivastyskorko' => 'varchar(20)',
                     'salasana' => 'varchar(255)',
                     'netvisorkey' => 'int(11)',
                     'netvisor_dimension_name' => 'varchar(100) DEFAULT NULL',
                     'netvisor_dimension_item' => 'varchar(100) DEFAULT NULL',
                     'k_osoite' => 'varchar(255)',
                     'k_postinumero' => 'varchar(100)',
                     'k_kaupunki' => 'varchar(100)',
                     'onlinevarauksen_asiakas' => 'int(1)',
                     'asiakastila' => 'int(11)',
                     'sahkopostilaskuosoite' => 'varchar(255)',
                     'vinkki_id' => 'int(11)',
                     'alennuskoodit' => 'text',
                     'token' => 'varchar(255)',
                     'app_kayttoehdot' => 'int(1)',
		     'hinnasto_id' => 'int(11) DEFAULT 0',
                     'alv' => 'int(3)',
                     'hinta_tyyppi' => 'varchar(50)',
                     'hinta' => 'varchar(10)',
		     'verot' => 'varchar(100)',
                     'hinta_sis_alv' => 'float',
		     'tyoryhma' => 'int(11)',
                     'gcm_reg_id' => 'varchar(500) ',
                     'lopetuksen_pvm' => 'varchar(50) DEFAULT NULL',
                     'lopetuksen_syy' => 'TEXT DEFAULT NULL'

                     //'vinkki_tunnit' => 'varchar(10)',
                     //'vinkki_prosentti' => 'varchar(10)',


		);

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
			}
		}	
		} // if($check_this_table)
		return $tb_name;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('osoite, postinumero, kaupunki', 'required'),
                        array('asiakasnumero','unique', 'message'=>'Tämä asiakasnumero on jo olemassa!'),
			//array('etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen', 'required'),
			array('kirjeenluokka, muistutuslasku_auto, aktiivinen, netvisorkey, onlinevarauksen_asiakas, asiakastila, vinkki_id, app_kayttoehdot, hinnasto_id, alv, tyoryhma', 'numerical', 'integerOnly'=>true),
			array('myyja, postinumero, k_postinumero, yhteyshenkilo, yrityksen_nimi, y_tunnus, kaupunki, k_kaupunki, puhelin, sahkoposti', 'length', 'max'=>100),
			array('tyyppi, laskutus_kanava, osoite, k_osoite, verkkolaskuosoite, salasana, ryhma, sahkopostilaskuosoite, token', 'length', 'max'=>255),
			array('maksuehto, viivastyskorko, hinta, hinta_sis_alv', 'length', 'max'=>20),
			array('asiakasnumero, ovt_tunnus, valittajan_tunnus, hinta_tyyppi, verot, lopetuksen_pvm', 'length', 'max'=>100),
			array('alennuskoodit, gcm_reg_id, lopetuksen_syy, netvisor_dimension_name, netvisor_dimension_item', 'safe'),
			array('sahkoposti','unique', 'message'=>'Tämä sähköposti on jo rekisteröity asiakkaalle.'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, etunimi, sukunimi, osoite, kaupunki, postinumero, puhelin, sahkoposti, ryhma, aktiivinen, laskutus_kanava,maksuehto, tyyppi, asiakasnumero, ovt_tunnus, valittajan_tunnus, myyja, kirjeenluokka, muistutuslasku_auto', 'safe', 'on'=>'search'),
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
			'asiakasnumero' => Yii::t('main', 'Asiakasnumero'),
			'time' => Yii::t('main', 'Luotu'),
			'yhteyshenkilo' => Yii::t('main', 'Yhteyshenkilö'),
			'yrityksen_nimi' => Yii::t('main', 'Yrityksen Nimi'),
			'y_tunnus' => Yii::t('main', 'Y-tunnus'),
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
			'lopetuksen_pvm' => Yii::t('main', 'Päivämäärä jolloin asiakas menee passiviksi'),
			'lopetuksen_syy' => Yii::t('main', 'Syy'),
			'netvisor_dimension_name' => Yii::t('main', 'Kustannuspaikka'),
		);
	}

        public function getFullname(){
		$return = '';
		if( !empty($this->yrityksen_nimi) and !empty($this->tyyppi) and $this->tyyppi == 'yritys' ){
			$return = $this->yrityksen_nimi;
		}
		if( !empty($this->yhteyshenkilo) and !empty($this->tyyppi) and $this->tyyppi == 'henkilo' ){
			$return = $this->yhteyshenkilo;
		}
                return $return;
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
}
