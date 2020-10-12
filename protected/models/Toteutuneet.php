<?php

/**
 * This is the model class for table "mobile_repaired".
 *
 * The followings are the available columns in table 'mobile_repaired':
 * @property integer $id
 * @property integer $kid
 * @property string $asiakas_num
 * @property string $time
 * @property integer $requests
 * @property string $puh_numero
 * @property string $imei
 * @property string $bluetooth_name
 * @property string $sim_serial_number
 * @property string $subscriber_id
 * @property string $my_location
 * @property string $osoite
 * @property string $kohde_kannasta
 * @property integer $kohdenID
 * @property string $aloitan
 * @property string $loppui
 * @property string $viesti
 * @property string $tekijan_nimi
 * @property integer $tid
 * @property string $etaisyys
 * @property integer $status
 * @property string $tietoja
 * @property integer $admin
 * @property string $tyoajanlaatu
 * @property string $tyoajanmerkinta
 */
class Toteutuneet extends DB2ActiveRecord
{

	public $l_tunnit;
	public $s_tunnit;
	public $t_tunnit;
	public $count;


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Toteutuneet the static model class
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
		$tb_name = 'sivexkuitti_repaired';
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
                     'kid' => 'int(7) DEFAULT 0',
                     'asiakas_num' => 'varchar(50) DEFAULT NULL',
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'requests' => 'int(7) DEFAULT 1',
                     'puh_numero' => 'varchar(50) DEFAULT NULL',
                     'imei' => 'varchar(100) DEFAULT NULL',
                     'bluetooth_name' => 'varchar(50) DEFAULT NULL',
                     'sim_serial_number' => 'varchar(100) DEFAULT NULL',
                     'subscriber_id' => 'varchar(50) DEFAULT NULL',
                     'my_location' => 'varchar(1000) DEFAULT NULL',
                     'osoite' => 'varchar(255) DEFAULT NULL',
                     'kohde_kannasta' => 'varchar(100) DEFAULT NULL',
                     'kohdenID' => 'int(7) DEFAULT 0',
                     'aloitan' => 'varchar(20) DEFAULT NULL',
                     'loppui' => 'varchar(20) DEFAULT NULL',
                     'viesti' => 'varchar(250) DEFAULT NULL',
                     'tekijan_nimi' => 'varchar(50) DEFAULT NULL',
                     'tid' => 'int(7) DEFAULT 0',
                     'etaisyys' => 'varchar(20) DEFAULT NULL',
                     'status' => 'int(1) DEFAULT 0',
                     'tietoja' => 'text DEFAULT NULL',
                     'admin' => 'int(1) DEFAULT 0',
                     'tyoajanlaatu' => 'varchar(100) DEFAULT NULL',
                     'tyoajanmerkinta' => 'varchar(100) DEFAULT NULL',
                     'hyvaksytty' => 'varchar(100) DEFAULT NULL',
                     'asiakas_hyvaksy' => 'varchar(100) DEFAULT NULL',
                     'sairaus' => 'int(1) DEFAULT 0',
                     'laskutettu' => 'int(1) DEFAULT 0',
                     'laskutetaan' => 'int(1) DEFAULT 1',
                     'tuoteID' => 'int(11) DEFAULT 0',
                     'tv_id' => 'int(11) DEFAULT 0',
		     'deleted' => 'int(11) DEFAULT 0',
		     'tyo_erittelyt' => 'text DEFAULT NULL',
		     'palkanlaskentaan' => 'int(1) DEFAULT 1',
		     'laskurivi_id' => 'int(11) DEFAULT 0',
		     'app_aloitus_destination_checker' => 'varchar(10) DEFAULT NULL',
		     'app_lopetus_destination_checker' => 'varchar(10) DEFAULT NULL',
		     'gps_enabled' => 'varchar(100) DEFAULT \'disabled\'',
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

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('kid, asiakas_num, time, puh_numero, imei, bluetooth_name, sim_serial_number, subscriber_id, my_location, osoite, kohde_kannasta, kohdenID, aloitan, loppui, viesti, tekijan_nimi, tid, etaisyys, status, tietoja, tyoajanlaatu, tyoajanmerkinta', 'required'),
			array('kid, requests, kohdenID, tid, status, admin, sairaus, laskutetaan, tuoteID, tv_id, deleted, palkanlaskentaan, laskurivi_id', 'numerical', 'integerOnly'=>true),
			array('asiakas_num, puh_numero, bluetooth_name, subscriber_id, tekijan_nimi', 'length', 'max'=>50),
			array('imei, asiakas_hyvaksy, sim_serial_number, kohde_kannasta, hyvaksytty, tyoajanlaatu, tyoajanmerkinta, gps_enabled, app_platform', 'length', 'max'=>100),
			array('my_location, tietoja, tyo_erittelyt, app_aloitus_destination_checker, app_lopetus_destination_checker', 'safe'),
			array('osoite', 'length', 'max'=>255),
			array('aloitan, loppui, etaisyys', 'length', 'max'=>20),
			array('viesti', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, kid, asiakas_num, time, requests, puh_numero, imei, bluetooth_name, sim_serial_number, subscriber_id, my_location, osoite, kohde_kannasta, kohdenID, aloitan, loppui, viesti, tekijan_nimi, tid, etaisyys, status, tietoja, admin, tyoajanlaatu, tyoajanmerkinta, hyvaksytty, sairaus', 'safe', 'on'=>'search'),
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
		        'kohteet' => array(self::BELONGS_TO, 'Kohteet', 'kohdenID'),
		        'tyovuoroot' => array(self::BELONGS_TO, 'Tyovuoroot', 'tv_id'),
		        'laskurivi' => array(self::BELONGS_TO, 'LaskunRivit', 'laskurivi_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('main', 'ID'),
			'kid' => Yii::t('main', 'Kid'),
			'asiakas_num' => Yii::t('main', 'Asiakas Num'),
			'time' => Yii::t('main', 'Time'),
			'requests' => Yii::t('main', 'Requests'),
			'puh_numero' => Yii::t('main', 'Puh Numero'),
			'imei' => Yii::t('main', 'Imei'),
			'bluetooth_name' => Yii::t('main', 'Bluetooth Name'),
			'sim_serial_number' => Yii::t('main', 'Sim Serial Number'),
			'subscriber_id' => Yii::t('main', 'Subscriber'),
			'my_location' => Yii::t('main', 'My Location'),
			'osoite' => Yii::t('main', 'Kerta osoite'),
			'kohde_kannasta' => Yii::t('main', 'Osoite'),
			'kohdenID' => Yii::t('main', 'Kohden'),
			'aloitan' => Yii::t('main', 'Aloitus'),
			'loppui' => Yii::t('main', 'Lopetus'),
			'viesti' => Yii::t('main', 'Viesti'),
			'tekijan_nimi' => Yii::t('main', 'Tekijan Nimi'),
			'tid' => Yii::t('main', 'Tid'),
			'etaisyys' => Yii::t('main', 'Etaisyys'),
			'status' => Yii::t('main', 'Tilanne'),
			'tietoja' => Yii::t('main', 'Tietoja'),
			'admin' => Yii::t('main', 'Admin'),
			'tyoajanlaatu' => Yii::t('main', 'Tyoajanlaatu'),
			'tyoajanmerkinta' => Yii::t('main', 'Tyoajanmerkinta'),
			'sairaus'=> Yii::t('main', 'Sairaus'),
			'tuoteID' => Yii::t('main', 'Tuote/Palvelu'),
			'tyo_erittelyt' => Yii::t('main', 'Työerittelyt'),
			'palkanlaskentaan' => Yii::t('main', 'Palkanlaskentaan'),
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
		$criteria->order = 't.id DESC';

		$criteria->compare('id',$this->id);
		$criteria->compare('kid',$this->kid);
		$criteria->compare('asiakas_num',$this->asiakas_num,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('requests',$this->requests);
		$criteria->compare('puh_numero',$this->puh_numero,true);
		$criteria->compare('imei',$this->imei,true);
		$criteria->compare('bluetooth_name',$this->bluetooth_name,true);
		$criteria->compare('sim_serial_number',$this->sim_serial_number,true);
		$criteria->compare('subscriber_id',$this->subscriber_id,true);
		$criteria->compare('my_location',$this->my_location,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('kohde_kannasta',$this->kohde_kannasta,true);
		$criteria->compare('kohdenID',$this->kohdenID);
		$criteria->compare('aloitan',$this->aloitan,true);
		$criteria->compare('loppui',$this->loppui,true);
		$criteria->compare('viesti',$this->viesti,true);
		$criteria->compare('tekijan_nimi',$this->tekijan_nimi,true);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('etaisyys',$this->etaisyys,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('tietoja',$this->tietoja,true);
		$criteria->compare('admin',$this->admin);
		$criteria->compare('tyoajanlaatu',$this->tyoajanlaatu,true);
		$criteria->compare('tyoajanmerkinta',$this->tyoajanmerkinta,true);
		$criteria->compare('hyvaksytty',$this->hyvaksytty,true);
		$criteria->compare('sairaus',$this->sairaus,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
