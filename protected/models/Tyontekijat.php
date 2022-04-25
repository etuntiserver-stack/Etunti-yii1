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
 * @property string $visited_properties
 * @property string $orientation_start
 * @property string $orientation_end
 * ICE = in case of emergency
 * @property string $ice_name
 * @property string $ice_relationship
 * @property string $ice_phonenumber
 * @property integer $using_framework_agreement "Puitesopimus"
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
		$tb_name = 'sivex_ttekijat';
		$check_this_table = true;
		unset(Yii::app()->session[$tb_name]); // this use if want many times play
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
                     'imei' => 'varchar(100) DEFAULT NULL',
                     'app_lang' => 'varchar(10) DEFAULT \'fi\'',
                     'laiten_puh' => 'varchar(100) DEFAULT NULL',
                     'tekijan_nimi' => 'varchar(100) DEFAULT NULL',
                     'tekijan_henkilotunnus' => 'varchar(20) DEFAULT NULL',
                     'tekijan_puh' => 'varchar(20) DEFAULT NULL',
                     'tekijan_email' => 'varchar(50) DEFAULT NULL',
                     'tekijan_lanka_puh' => 'varchar(20) DEFAULT NULL',
                     'tekijan_katuosoite' => 'varchar(100) DEFAULT NULL',
                     'tekijan_pnumero' => 'varchar(7) DEFAULT NULL',
                     'tekijan_ptoimipaikka' => 'varchar(50) DEFAULT NULL',
                     'tyoryhma' => 'varchar(500) DEFAULT NULL',
                     'tyoehtosopimus' => 'varchar(50) DEFAULT NULL',
                     'tekijan_kulunvalvonta' => 'varchar(50) DEFAULT NULL',
                     'tekijan_pankkitili' => 'varchar(100) DEFAULT NULL',
                     'tekijan_konttori' => 'varchar(50) DEFAULT NULL',
                     'aktiivinen' => 'varchar(50) DEFAULT NULL',
                     'tekijan_tietoja' => 'text DEFAULT NULL',
                     'tekijan_muisti' => 'text DEFAULT NULL',
                     'salasana' => 'varchar(100) DEFAULT NULL',
                     'online_varauksen_valmina' => 'int(1) DEFAULT 0',
                     'kortit' => 'text DEFAULT NULL',
                     'ayjasenyys' => 'varchar(10) DEFAULT NULL',
                     'gcm_reg_id' => 'varchar(500) DEFAULT NULL',
                     'position' => 'varchar(255) DEFAULT NULL',
                     'tyo_toimialue' => 'varchar(100) DEFAULT NULL',
                     'tietoja_onlinevarauksen' => 'text DEFAULT NULL',
                     'sukunimi' => 'varchar(255) DEFAULT NULL',
                     'ilmoitus_merkkipaivasta_vuosi' => 'int(4) DEFAULT 0',
                     'kortit_voimassaolo' => 'varchar(500) DEFAULT NULL',
                     'tyontekijan_numero' => 'int(10) DEFAULT 0',
                     'ammattinimike' => 'varchar(255) DEFAULT NULL',
                     'onlinevaraus_tuotteet' => 'text DEFAULT NULL',
                     'app_naytta_osoitekenta' => 'int(1) DEFAULT 0',
		     'muistiinpano' => 'text DEFAULT NULL',
		     'naytta_tyovuorossa' => 'int(1) DEFAULT 1',
		     'omasiistijavaroitukset' => 'int(1) DEFAULT 1', // 1: näytetään omasiistijävaroitukset työvuorotaulussa
		     'app_platform' => 'varchar(100) DEFAULT NULL',
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


	public function netvisorCheck($attribute,$params)
	{

		$asetukset = Asetukset::model()->findByPk(1);
		if($asetukset->netvisor_kaytto == 1 and $asetukset->netvisor_lahetetaanko_tyontekija == 1)
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
			array('tekijan_nimi, aktiivinen, sukunimi', 'required'),
			array('tekijan_pankkitili, tekijan_konttori, ammattinimike, tekijan_henkilotunnus', 'netvisorCheck', 'on'=>'insert, update'),
			array('online_varauksen_valmina, ilmoitus_merkkipaivasta_vuosi, tyontekijan_numero, mobiili, app_naytta_osoitekenta, naytta_tyovuorossa, using_framework_agreement', 'numerical', 'integerOnly'=>true),
			array('imei', 'length', 'max'=>100),
			array('laiten_puh, tekijan_nimi, tekijan_katuosoite, tekijan_pankkitili, salasana, app_platform', 'length', 'max'=>100),
			array('tekijan_henkilotunnus, tekijan_puh, tekijan_lanka_puh', 'length', 'max'=>20),
			array('tekijan_email, tekijan_ptoimipaikka, tyoehtosopimus, tekijan_kulunvalvonta, tekijan_konttori, aktiivinen', 'length', 'max'=>50),
			array('tekijan_pnumero', 'length', 'max'=>7),
			array('ammattinimike, token, ice_name, ice_relationship, ice_phonenumber', 'length', 'max'=>255),
			array('ayjasenyys, app_lang', 'length', 'max'=>10),
			array('kortit, tekijan_muisti, tekijan_tietoja, tietoja_onlinevarauksen, muistiinpano, tyo_toimialue, visited_properties', 'safe'),
			array('gcm_reg_id, position, kortit_voimassaolo, tyoryhma', 'length', 'max'=>500),
			array('onlinevaraus_tuotteet', 'safe'),
			//array("orientation_start, orientation_end", "safe"),
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
		        'tyosuhteet' => array(self::HAS_ONE, 'Tyosuhdet', 'tid'),
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
			'kortit' => Yii::t('main', 'Kortit ja taidot'),
			'ayjasenyys' => Yii::t('main', 'Ay-jäsenyys'),
			'gcm_reg_id'=> Yii::t('main', 'Google Cloud Messaging ID'),
			'position' => Yii::t('main', 'Viimeinen sijainti'),
			'tyo_toimialue' => Yii::t('main', 'Toimialue'),
			'sukunimi' => Yii::t('main', 'Sukunimi'),
			'ammattinimike' => Yii::t('main', 'Ammattinimike'),
			'app_naytta_osoitekenta'=>Yii::t('main', 'Näytä osoite sovelluksessa'),
			'naytta_tyovuorossa' => Yii::t('main', 'Näytä työvuorosuunnittelussa'),
			"orientation_start" => Yii::t("main", "Perehdytyksen aloitus"),
			"orientation_end" => Yii::t("main", "Perehdytyksen lopetus"),
			"ice_name" => Yii::t("main", "Yhteyshenkilön nimi"),
			"ice_relationship" => Yii::t("main", "Yhteyshenkilön suhde työntekijään"),
			"ice_phonenumber" => Yii::t("main", "Yheyshenkilön puhelinnumero"),
			"using_framework_agreement" => Yii::t("main", "Puitesopimus"),
		);
	}

	public function getFullName(){
		$return = '';
		$asetukset = Asetukset::model()->findByPk(1);
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
			$return .= $this->tekijan_nimi;
			if(!empty($this->sukunimi))
				$return .= ' '.$this->sukunimi;
		} else {
			if(!empty($this->sukunimi))
				$return .= $this->sukunimi.' ';
				$return .= $this->tekijan_nimi;
		}
		return $return;
	}

	/**
	 * Returns true if this employee is currently in orientation. Otherwise returns false.
	 * Sees if the current date is between orientation_start and orientation_end
	 */
	public function isInOrientation() {
		if($this->orientation_start && $this->orientation_end) {
			// the dates are in d.m.Y because of afterFind()
			$start = date("Y-m-d", strtotime($this->orientation_start));
			$end = date("Y-m-d", strtotime($this->orientation_end));
			$now = date("Y-m-d");
			// if date now is between start and end return true
			return $now >= $start && $now <= $end;
		}
		return false;
	}

	/**
	 * Returns a comma separated string that includes
 	 * workers "tyo_toimialue" and "tyoryhma" columns. 
	 * 
	 * Returns an empty string if this employee has neither
	 * defined or json_decode fails to parse the contents.
	 * 
	 * @return string comma separated string that includes "tyo_toimialue" and "tyoryhma"
	 */
	public function getTyoryhmaToimialueString() {
		$str = "";
		// parse tyo_toimialue
		$territories = json_decode($this->tyo_toimialue, true);
		if($territories) {
		  foreach($territories as $territory) {
			$str .= "$territory, ";
		  }
		}
		// parse tyoryhma
		$workgroups = json_decode($this->tyoryhma, true);
		if($workgroups) {
		  foreach($workgroups as $workgroup) {
			$str .= "$workgroup, ";
		  }
		}
		$str = trim($str);
		if(strlen($str) > 0) {
		  // remove last character, which should be a ','
		  $str = substr($str, 0, -1);
		}
		return $str;
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

	/**
	 * Inserts a new property into visited_properties, if it is not
	 * already in the list. Duplicates will not be added.
	 * 
	 * @return bool boolean indicating if update was successful
	 */
	public function updateVisitedProperties($property_id)
	{
		// convert to int if $property_id is string
		if(is_string($property_id)) {
			$property_id = intval($property_id);
		}

		// decode json, default to empty arr if null
		$properties = json_decode($this->visited_properties, true) ?? [];
		if(!array_key_exists($property_id, $properties)) {
			$properties[$property_id] = $property_id;
			$this->visited_properties = json_encode($properties);
			return $this->save();
		}
		return true;
	}
}
