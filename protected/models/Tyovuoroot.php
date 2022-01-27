<?php

/**
 * This is the model class for table "sivex_tvuoro".
 *
 * The followings are the available columns in table 'sivex_tvuoro':
 * @property integer $id
 * @property integer $tid
 * @property string $time
 * @property string $kohde
 * @property string $pvm
 * @property string $alku
 * @property string $loppu
 * @property string $pituus
 * @property string $ruokatauko
 * @property string $alku_r
 * @property string $kesto
 * @property string $tyoajanlaatu
 * @property string $tyoajanmerkinta
 * @property string $tietoja
 * @property string $osoiteOnline
 * @property int $omasiistijavaroitus 0: Disabled, 1: Enabled
 * @property int $omasiistijailmoitus 0: Not notified, 1: Notified
 */
class Tyovuoroot extends DB2ActiveRecord
{
	public $osoite;
	public $kaupunki;
	public $tekijan_nimi;
	public $l_tunnit;
	public $count;
	public $suunnittellut;
	public $kpl;


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tyovuoroot the static model class
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
		$tb_name = 'sivex_tvuoro';
		$check_this_table = true;
		if($check_this_table)
		{
		$table = Yii::app()->db1->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db1->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(
                     'tid' => 'int(7) DEFAULT 0',
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'kohde' => 'varchar(255) DEFAULT NULL',
                     'osoite' => 'varchar(255) DEFAULT NULL',
                     'postinumero' => 'varchar(255) DEFAULT NULL',
                     'postitoimipaikka' => 'varchar(255) DEFAULT NULL',
                     'pvm' => 'varchar(20) DEFAULT NULL',
                     'alku' => 'varchar(10) DEFAULT NULL',
                     'loppu' => 'varchar(10) DEFAULT NULL',
                     'pituus' => 'varchar(10) DEFAULT NULL',
                     'ruokatauko' => 'varchar(50) DEFAULT NULL',
                     'alku_r' => 'varchar(10) DEFAULT NULL',
                     'kesto' => 'varchar(10) DEFAULT NULL',
                     'tyoajanlaatu' => 'varchar(50) DEFAULT NULL',
                     'tyoajanmerkinta' => 'varchar(50) DEFAULT NULL',
                     'tietoja' => 'text DEFAULT NULL',
                     'osoiteOnline' => 'varchar(100) DEFAULT NULL',
                     'onlinevaraus_id' => 'int(11) DEFAULT 0',
                     'status' => 'int(2) DEFAULT 0',
                     'toistuva_id' => 'int(11) DEFAULT 0',
                     'tyopaari' => 'text DEFAULT NULL',
                     'ilmoitus_avoimista_kohteesta' => 'int(1) DEFAULT 0',
                     'ilmoitus_myohastyneista_kohteesta' => 'int(1) DEFAULT 0',
                     'piilota_mobiilista' => 'int(1) DEFAULT 0',
                     'tuoteID' => 'int(1) DEFAULT 0',
                     'lisa_tuotteet' => 'text DEFAULT NULL',
                     'peruutettu' => 'int(1) DEFAULT 0',
		     'apuaika' => 'int(1) DEFAULT 0',
		     'laskutettu' => 'int(1) DEFAULT 0',
		     'lasku_id' => 'int(1) DEFAULT 0',
		     'tilausviesti' => 'TEXT DEFAULT NULL',
		     'toimenpiteet' => 'TEXT DEFAULT NULL',
		     'uusi_tilaus' => 'int(1) DEFAULT 0',
		     'tyo_erittelyt' => 'text DEFAULT NULL',
		     'url_linkkit' => 'text DEFAULT NULL',
		     'muistiinpano' => 'text DEFAULT NULL',
		     'omasiistijavaroitus' => 'int(1) DEFAULT 1', // 0: Disabled, 1: Enabled
		     'omasiistijailmoitus' => 'int(1) DEFAULT 0', // 0: Not notified, 1: Notified
		);

		foreach($table_structure as $key=>$value)
		{
			// <-- Change column type
			/*
			if($key == 'tuoteID' and $table->columns[$key]->dbType == 'int(11)'){
				Yii::app()->db1->createCommand()->alterColumn($tb_name, $key, 'TEXT' );
			}
			*/
			//     Change column type -->

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
			//array('kohde, pvm, alku, loppu, pituus, tyoajanlaatu, tyoajanmerkinta', 'required'),
			array('tid, onlinevaraus_id, status, toistuva_id, ilmoitus_avoimista_kohteesta, ilmoitus_myohastyneista_kohteesta, piilota_mobiilista, peruutettu, apuaika, laskutettu, tuoteID, lasku_id, uusi_tilaus, omasiistijavaroitus, omasiistijailmoitus', 'numerical', 'integerOnly'=>true),
			array('kohde, osoite, postinumero, postitoimipaikka', 'length', 'max'=>255),
			array('pvm', 'length', 'max'=>20),
			array('alku, loppu, pituus, alku_r, kesto', 'length', 'max'=>10),
			array('ruokatauko, tyoajanlaatu, tyoajanmerkinta', 'length', 'max'=>50),
			array('osoiteOnline', 'length', 'max'=>100),
			array('tyopaari, tietoja, lisa_tuotteet, tilausviesti, toimenpiteet, tyo_erittelyt, muistiinpano, url_linkkit', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tid, time, kohde, pvm, alku, loppu, pituus, ruokatauko, alku_r, kesto, tyoajanlaatu, tyoajanmerkinta, tietoja, osoiteOnline, tekijan_nimi, toimenpiteet, osoite', 'safe', 'on'=>'search'),
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
		        'toistuvat' => array(self::BELONGS_TO, 'ToistuvatTyovuorot', 'toistuva_id'),
		        'kohteet' => array(self::BELONGS_TO, 'Kohteet', 'kohde'),
		        'avaimet' => array(self::HAS_MANY, 'Avaimet', array('kohde'=>'kohde')),
		        'tt' => array(self::BELONGS_TO, 'Tyontekijat', 'tid'),
		        'tp' => array(self::BELONGS_TO, 'TuotteetPalvelut', 'tuoteID'),
		        'mobile' => array(self::HAS_MANY, 'Mobile', array('tv_id'=>'id', 'kohdenID'=>'kohde'), 'condition' => 'tv_id!=0 AND deleted=0 and (hyvaksytty="" OR hyvaksytty NOT LIKE "%auto%")'),
		        'toteutuneet' => array(self::HAS_MANY, 'Toteutuneet', array('tv_id'=>'id', 'kohdenID'=>'kohde'), 'condition' => 'tv_id!=0 AND deleted=0 and (hyvaksytty="" OR hyvaksytty NOT LIKE "%auto%")'),
		        'toistuvat' => array(self::BELONGS_TO, 'ToistuvatTyovuorot', 'toistuva_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('main', 'ID'),
			'tid' => Yii::t('main', 'Työntekijä'),
			'tekijan_nimi' => Yii::t('main', 'Työntekijä'),
			'ohje' => Yii::t('main', 'Ohjeteksti kohdetiedoista'),
			'osoite' => Yii::t('main', 'Katuosoite'),
			'time' => Yii::t('main', 'Time'),
			'kohde' => Yii::t('main', 'Kohde'),
			'pvm' => Yii::t('main', 'Päivämäärä'),
			'alku' => Yii::t('main', 'Aloitus'),
			'loppu' => Yii::t('main', 'Lopetus'),
			'pituus' => Yii::t('main', 'Pituus'),
			'ruokatauko' => Yii::t('main', 'Ruokatauko'),
			'alku_r' => Yii::t('main', 'Alku R'),
			'kesto' => Yii::t('main', 'Kesto'),
			'tyoajanlaatu' => Yii::t('main', 'Tyoajanlaatu'),
			'tyoajanmerkinta' => Yii::t('main', 'Työajanmerkintä'),
			'tietoja' => Yii::t('main', 'Tietoja mobiilisovellukseen'),
			'osoiteOnline' => Yii::t('main', 'Osoite Online'),
			'status' => Yii::t('main', 'Tilanne'),
			'piilota_mobiilista'=>Yii::t('main', 'Näytä mobiilissa'),
			'tuoteID' => Yii::t('main', 'Tuote/palvelu'),
			'tyoajanlaatu' => Yii::t('main', 'Lomat ja poissaolot'),
			'toimenpiteet' => Yii::t('main', 'Työ-ohjeet'),
			'omasiistijavaroitus' => Yii::t('main', 'Omasiistijävaroitukset'),
			'omasiistijailmoitus' => Yii::t('main', 'Ilmoitus asiakkaalle omasiistijöistä'),
			'tilausviesti' => Yii::t('main', 'Tilausviesti asiakkaalle'),
			'url_linkkit' => Yii::t('main', 'URL linkit'),
		);
	}

        public function getosoiteAndAika(){
			$return = '';
			if(!empty($this->kohde)){
				$k = Kohteet::model()->findByPk($this->kohde);
				if( isset($k->id) ){
					$return = $k->osoite.' '.$this->pvm.', '.$this->alku.'-'.$this->loppu;
				}
			}
			return $return;
        }

        public function getosoiteById(){
			$return = '';
			if(!empty($this->kohde) and empty($this->osoite)){
				$k = Kohteet::model()->findByPk($this->kohde);
				if( isset($k->id) ){
					$return = $k->osoite;
				}
			} elseif(!empty($this->osoite)){
				$return = $this->osoite;
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
		$criteria->order = 't.id DESC';

		$criteria->with=array('kohteet','tt');
		$criteria->compare('kohteet.osoite',$this->osoite,true);
		$criteria->compare('kohteet.ohje',$this->toimenpiteet,true);
		$criteria->compare('tt.tekijan_nimi',$this->tekijan_nimi,true);

		$criteria->compare('id',$this->id);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('kohde',$this->kohde,true);
		$criteria->compare('pvm',$this->pvm,true);
		$criteria->compare('alku',$this->alku,true);
		$criteria->compare('loppu',$this->loppu,true);
		$criteria->compare('pituus',$this->pituus,true);
		$criteria->compare('ruokatauko',$this->ruokatauko,true);
		$criteria->compare('alku_r',$this->alku_r,true);
		$criteria->compare('kesto',$this->kesto,true);
		$criteria->compare('tyoajanlaatu',$this->tyoajanlaatu,true);
		$criteria->compare('tyoajanmerkinta',$this->tyoajanmerkinta,true);
		$criteria->compare('t.tietoja',$this->tietoja,true);
		$criteria->compare('osoiteOnline',$this->osoiteOnline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Updates the shifts properties visit count fields
	 * 
	 * @param Array $additionalWorkPairIds any additional employee IDs you might want to specify
	 * to count towards a successful visit.
	 * @return boolean boolean indicating if save was successful
	 */
	public function updatePropertySuccessCounts($additionalWorkPairIds = [])
	{
		// skip if there's no property defined (or it's 0)
		// we'll return true in that case.
		if($this->kohde) {
			// get shifts work pair IDs
			// in a normal shift, tyopaari should be a object instead of an array
			// {"someShiftId": "someWorkerId", ...}
			$workPairIds = json_decode($this->tyopaari, true) ?? [];
			// the IDs can be an associative array, get just the values
			$workPairIds = array_values($workPairIds);
			// merge with additional IDs
			$workPairIds = array_merge($workPairIds, $additionalWorkPairIds);
			$employeeId = intval($this->tid);
			// the work pair column can also be empty, just in case let's add this shifts
			// "primary" employee id to the list.
			$workPairIds[] = $employeeId;
			// but still remove duplicates, which can occur because we added the shifts
			// "primary" employee ID to the list (tid)
			$workPairIds = array_unique($workPairIds);
			$criteria = new CDbCriteria();
			$criteria->addInCondition("id", $workPairIds);
			$employees = Tyontekijat::model()->findAll($criteria);
			$propertyId = $this->kohde;
			
			$success = false;
			foreach($employees as $employee) {
				$visited_properties = json_decode($employee->visited_properties, true) ?? [];
				if(array_key_exists($propertyId, $visited_properties)) {
					$success = true;
					// if propertyId is found, exit loop early. it counts as a
					// success regardless of the other employees
					break;
				}
			}
			$property = Kohteet::model()->findByPk($propertyId);
			if($property) {
				return $property->updateVisitCounts($success);
			}
			// return true even if property wasn't found
			return true;
		}
		// return true even if property isn't defined
		return true;
	}

	/**
	 * Finds the closest shift by 'alku' (starting time) compared to $starting_timestamp,
	 * which is a date string in YYYY-MM-DD HH:mm:ss format. Also supports YYYY-MM-DD HH:mm format.
	 * $propertyId and $employeeId should be defined
	 * @param string $starting_timestamp date in YYYY-MM-DD HH:mm:ss format
	 * @param int $propertyId ID of the property the closest shift should be for
	 * @param int $employeeId ID of the employee the closest shift should be for
	 * @return Tyovuoroot|null returns the closest shift or null if there are no shifts found
	 */
	public static function findClosestShift($starting_timestamp, $propertyId, $employeeId)
	{
		// make sure property & employee IDs are defined
		// and > 0
		if(!$propertyId || !$employeeId) {
			return null;
		}
		$startDate = DateTime::createFromFormat("Y-m-d H:i:s", $starting_timestamp);
		// make sure startDate was parsed successfully
		if($startDate === false) {
			// attempt to parse with another
			$startDate = DateTime::createFromFormat("Y-m-d H:i", $starting_timestamp);
			if($startDate === false) {
				return null;
			}
		}

		$dmyDate = $startDate->format("d.m.Y");
		$crit = new CDbCriteria();
		$crit->compare("pvm", $dmyDate);
		$crit->compare("kohde", $propertyId);
		$crit->compare("tid", $employeeId);
		$crit->compare("peruutettu", 0);
		$shifts = Tyovuoroot::model()->findAll($crit);
		// usually there's only one shift, and we can just return it straight away
		// if it is the only one
		if(count($shifts) === 1) {
			return $shifts[0];
		} else if (count($shifts) > 0) {
			// if there's more than 1 shift, find the one with the closest starting time (alku)
			// to $startDate
			$startingTimeMap = [];
			foreach($shifts as $shift) {
				$shiftStartTime = DateTime::createFromFormat("d.m.Y H:i", $shift->pvm . " " . $shift->alku);
				// attempt to parse with H:i:s format if the above fails
				if($shiftStartTime === false) {
					$shiftStartTime = DateTime::createFromFormat("d.m.Y H:i:s", $shift->pvm . " " . $shift->alku);
				}
				if($shiftStartTime) {
					$startingTimeMap[$shiftStartTime] = $shift;
				}
			}
			// find closest shift to $starting_timestamp
			$closest = null;
			foreach($shiftStartTime as $shiftTimestamp => $shift) {
				// get the diff between shifts start and startDate, use abs to get a positive value
				// so we can easily compare times before and after startDate equally
				$diff = abs($shiftTimestamp->getTimestamp() - $startDate->getTimestamp());
				// if closest is already defined, compare the defined diff with this one
				if($closest) {
					if($diff < $closest["diff"]) {
						$closest = ["shift" => $shift, "diff" => $diff];
					}
				} else {
					// closests wasn't defined, we can say that this (probably the first shift
					// in the list) is the closest
					$closest = ["shift" => $shift, "diff" => $diff];
				}
			}
			// if closest is defined, return the shift
			if($closest) {
				return $closest["shift"];
			}
		}
		return null;
	}
}
