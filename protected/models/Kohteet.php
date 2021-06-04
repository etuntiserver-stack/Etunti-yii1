<?php

/**
 * This is the model class for table "sivex_kohdet".
 *
 * The followings are the available columns in table 'sivex_kohdet':
 * @property integer $id
 * @property string $time
 * @property string $tag_id
 * @property string $gps_sijainti
 * @property string $lyhenne
 * @property string $osoite
 * @property string $katuosoite
 * @property string $kaupunki
 * @property string $toimipaikka
 * @property string $pnumero
 * @property string $email
 * @property string $aikataulu
 * @property string $hinnoittelu
 * @property string $muut
 * @property string $toimenpiteet
 * @property string $tietoja
 * @property string $tyoryhma
 * @property string $ryhma
 * @property integer $aktiivinen
 * @property string $avain
 * @property string $kenella_on_avain
 * @property string $puh_nro
 * @property string $siivous
 * @property string $etu_suku_nimet
 * @property integer $maksuehto_paiva
 * @property string $viivastyskorko
 * @property string $lasku_tiedot
 */
class Kohteet extends DB2ActiveRecord
{

	public $verot, $count;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Kohteet the static model class
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
		$tb_name = 'sivex_kohdet';
		$check_this_table = true;
		//unset(Yii::app()->session[$tb_name]); // this use if want many times play
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
			'asiakas_id' => 'int(11) DEFAULT 0',
			'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
			'tag_id' => 'varchar(20) DEFAULT NULL',
			'gps_sijainti' => 'varchar(50) DEFAULT NULL',
			'lyhenne' => 'varchar(46) DEFAULT NULL',
			'osoite' => 'varchar(50) DEFAULT NULL',
			'katuosoite' => 'varchar(50) DEFAULT NULL',
			'kaupunki' => 'varchar(20) DEFAULT NULL',
			'toimipaikka' => 'varchar(20) DEFAULT NULL',
			'pnumero' => 'varchar(7) DEFAULT NULL',
			'email' => 'varchar(72) DEFAULT NULL',
			'aikataulu' => 'text DEFAULT NULL',
			'hinnoittelu' => 'text DEFAULT NULL',
			'muut' => 'text DEFAULT NULL',
			'toimenpiteet' => 'longtext DEFAULT NULL',
			'tietoja' => 'text DEFAULT NULL',
			'tyoryhma' => 'int(11) DEFAULT 0',
			'ryhma' => 'varchar(10) DEFAULT NULL',
			'aktiivinen' => 'int(1) DEFAULT 0',
			'avain' => 'varchar(255) DEFAULT NULL',
			'kenella_on_avain' => 'varchar(50) DEFAULT NULL',
			'puh_nro' => 'varchar(50) DEFAULT NULL',
			'siivous' => 'varchar(100) DEFAULT NULL',
			'etu_suku_nimet' => 'varchar(100) DEFAULT NULL',
			'maksuehto_paiva' => 'int(2) DEFAULT 0',
			'viivastyskorko' => 'varchar(10) DEFAULT NULL',
			'lasku_tiedot' => 'varchar(255) DEFAULT NULL',
			'avaimen_sijainti' => 'int(1) DEFAULT 0',
			'tarvittavien_tyontekijoiden_maara' => 'int(3) DEFAULT 0',
			'arvioitu_kesto' => 'varchar(100) DEFAULT NULL',
			'arvioitu_kello_alku' => 'varchar(100) DEFAULT NULL',
			'arvioitu_kello_loppu' => 'varchar(100) DEFAULT NULL',
			'uusi_tilaus' => 'int(1) DEFAULT 0',
			'hinnasto_id' => 'int(11) DEFAULT 0',
			'alv' => 'int(2) DEFAULT 0',
			'hinta_sis_alv' => 'float DEFAULT 0',
			'hinta_tyyppi' => 'varchar(50) DEFAULT NULL',
			'hinta' => 'varchar(10) DEFAULT NULL',
			'verot' => 'varchar(100) DEFAULT NULL',
			'tyo_erittelyt' => 'text DEFAULT NULL',
			'url_linkkit' => 'text DEFAULT NULL',
			'kohteen_neliot' => 'float(11) DEFAULT 0',
			'tyonkuvaus_tiedostot_mobiilissa' => 'int(1) DEFAULT 0',
			'kustannuspaikka_nro' => 'int(11) DEFAULT 0',
			'laskurivi_tyyppi' => 'varchar(50) DEFAULT \'tunti\'',
			'tuote_h' => 'int(11) DEFAULT 0',
			'tuote_kk' => 'int(11) DEFAULT 0',
			'tuote_kpl' => 'int(11) DEFAULT 0',
		);

		// <-- Drop column
		if(isset($table->columns['tuote']))
		{
			Yii::app()->db1->createCommand()->dropColumn($tb_name, 'tuote');
		}
		
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
			array('asiakas_id, etu_suku_nimet, osoite, tuote_h', 'required'),
			array('asiakas_id, aktiivinen, maksuehto_paiva, avaimen_sijainti, tarvittavien_tyontekijoiden_maara, uusi_tilaus, hinnasto_id, alv, tyoryhma, tyonkuvaus_tiedostot_mobiilissa, kustannuspaikka_nro, tuote_h, tuote_kk, tuote_kpl', 'numerical', 'integerOnly'=>true),
			array('tag_id, kaupunki, toimipaikka, kohteen_neliot', 'length', 'max'=>20),
			array('gps_sijainti, osoite, katuosoite, kenella_on_avain, puh_nro, hinta_sis_alv, hinta_tyyppi, laskurivi_tyyppi', 'length', 'max'=>50),
			array('lyhenne', 'length', 'max'=>46),
			array('pnumero', 'length', 'max'=>7),
			array('email', 'length', 'max'=>72),
			array('ryhma, viivastyskorko, hinta', 'length', 'max'=>10),
			array('avain, lasku_tiedot', 'length', 'max'=>255),
			array('siivous, etu_suku_nimet, arvioitu_kesto, arvioitu_kello_alku, arvioitu_kello_loppu, verot', 'length', 'max'=>100),
			array('aikataulu, hinnoittelu, muut, toimenpiteet, tietoja, tyo_erittelyt, url_linkkit', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, tag_id, gps_sijainti, lyhenne, osoite, katuosoite, kaupunki, toimipaikka, pnumero, email, aikataulu, hinnoittelu, muut, toimenpiteet, tietoja, tyoryhma, ryhma, aktiivinen, avain, kenella_on_avain, puh_nro, siivous, etu_suku_nimet, maksuehto_paiva, viivastyskorko, lasku_tiedot, asiakas_id', 'safe', 'on'=>'search'),
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
		        'avaimet' => array(self::HAS_MANY, 'Avaimet', array('kohde'=>'id')),
		        'asiakkaat' => array(self::BELONGS_TO, 'Asiakkaat', 'asiakas_id'),
		        'tyovuoroot' => array(self::HAS_MANY, 'Tyovuoroot', array('kohde'=>'id')),
		        'tuotteet_h' => array(self::BELONGS_TO, 'TuotteetPalvelut', array('tuote_h'=>'id')),
		        'tuotteet_kk' => array(self::BELONGS_TO, 'TuotteetPalvelut', array('tuote_kk'=>'id')),
		        //'mobile' => array(self::HAS_MANY, 'Mobile', array('kohdenID'=>'id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('main', 'ID'),
			'asiakas_id' => Yii::t('main', 'Asiakas'),
			'time' => Yii::t('main', 'Luotu'),
			'tag_id' => Yii::t('main', 'Tag'),
			'gps_sijainti' => Yii::t('main', 'GPS-sijainti'),
			'lyhenne' => Yii::t('main', 'Lyhenne'),
			'osoite' => Yii::t('main', 'Osoite'),
			'katuosoite' => Yii::t('main', 'Katuosoite'),
			'kaupunki' => Yii::t('main', 'Postitoimipaikka'),
			'toimipaikka' => Yii::t('main', 'Toimipaikka'),
			'pnumero' => Yii::t('main', 'Postinumero'),
			'email' => Yii::t('main', 'Sähköposti'),
			'aikataulu' => Yii::t('main', 'Aikataulu'),
			'hinnoittelu' => Yii::t('main', 'Hinnoittelu'),
			'muut' => Yii::t('main', 'Muut'),
			'toimenpiteet' => Yii::t('main', 'Työ-ohjeet'),
			'tietoja' => Yii::t('main', 'Tietoja mobiilisovelukseen'),
			'tyoryhma' => Yii::t('main', 'Työryhmä'),
			'ryhma' => Yii::t('main', 'Toimialue'),
			'aktiivinen' => Yii::t('main', 'Aktiivinen'),
			'avain' => Yii::t('main', 'Avain'),
			'kenella_on_avain' => Yii::t('main', 'Avain työntekijällä'),
			'puh_nro' => Yii::t('main', 'Puhelin'),
			'siivous' => Yii::t('main', 'Työnimike'),
			'etu_suku_nimet' => Yii::t('main', 'Kohteen yhteyshenkilö'),
			'maksuehto_paiva' => Yii::t('main', 'Maksuehto Paiva'),
			'viivastyskorko' => Yii::t('main', 'Viivästyskorko'),
			'lasku_tiedot' => Yii::t('main', 'Lasku Tiedot'),
			'avaimen_sijainti'=>Yii::t('main', 'Avaimen sijainti'),
			'tarvittavien_tyontekijoiden_maara'=>Yii::t('main', 'Tarvittavien työntekijöiden määrä'),
			'arvioitu_kesto'=>Yii::t('main', 'Arvioitu kesto'),
			'hinnasto_id'=> Yii::t('main', 'Hinnasto'),
			'tyo_erittelyt' => Yii::t('main', 'Työerittelyt'),
			'url_linkkit' => Yii::t('main', 'URL linkit'),
			'kohteen_neliot' => Yii::t('main', 'Kohteen neliöt'),
			'arvioitu_kello_alku' => Yii::t('main', 'Arvioitu aloitusaika'),
			'arvioitu_kello_loppu' => Yii::t('main', 'Arvioitu lopetusaika'),
			'tyonkuvaus_tiedostot_mobiilissa' => Yii::t('main', 'Työnkuvaukset mobiilissa'),
			'laskurivi_tyyppi' => Yii::t('main', 'Laskurivien tyyppi'),
			'tuote_h' => Yii::t('main', 'Tuntituote'),
			'tuote_kk' => Yii::t('main', 'Tuote kk'),
			'tuote_kpl' => Yii::t('main', 'Tuote kpl'),
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
		$criteria->order = "id DESC";

		$criteria->compare('id',$this->id);
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('tag_id',$this->tag_id,true);
		$criteria->compare('gps_sijainti',$this->gps_sijainti,true);
		$criteria->compare('lyhenne',$this->lyhenne,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('katuosoite',$this->katuosoite,true);
		$criteria->compare('kaupunki',$this->kaupunki,true);
		$criteria->compare('toimipaikka',$this->toimipaikka,true);
		$criteria->compare('pnumero',$this->pnumero,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('aikataulu',$this->aikataulu,true);
		$criteria->compare('hinnoittelu',$this->hinnoittelu,true);
		$criteria->compare('muut',$this->muut,true);
		$criteria->compare('toimenpiteet',$this->toimenpiteet,true);
		$criteria->compare('tietoja',$this->tietoja,true);
		$criteria->compare('tyoryhma',$this->tyoryhma,true);
		$criteria->compare('ryhma',$this->ryhma,true);
		$criteria->compare('aktiivinen',$this->aktiivinen);
		$criteria->compare('avain',$this->avain,true);
		$criteria->compare('kenella_on_avain',$this->kenella_on_avain,true);
		$criteria->compare('puh_nro',$this->puh_nro,true);
		$criteria->compare('siivous',$this->siivous,true);
		$criteria->compare('etu_suku_nimet',$this->etu_suku_nimet,true);
		$criteria->compare('maksuehto_paiva',$this->maksuehto_paiva);
		$criteria->compare('viivastyskorko',$this->viivastyskorko,true);
		$criteria->compare('lasku_tiedot',$this->lasku_tiedot,true);
		$criteria->compare('hinta_tyyppi',$this->hinta_tyyppi,true);
		$criteria->compare('hinta',$this->hinta,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
