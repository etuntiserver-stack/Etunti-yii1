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
public $t_tunnit;


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
		return 'sivexkuitti_repaired';
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
			array('kid, requests, kohdenID, tid, status, admin', 'numerical', 'integerOnly'=>true),
			array('asiakas_num, puh_numero, bluetooth_name, subscriber_id, tekijan_nimi', 'length', 'max'=>50),
			array('imei, sim_serial_number, kohde_kannasta, tyoajanlaatu, tyoajanmerkinta', 'length', 'max'=>100),
			array('my_location', 'length', 'max'=>1000),
			array('osoite', 'length', 'max'=>255),
			array('aloitan, loppui, etaisyys', 'length', 'max'=>20),
			array('viesti', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, kid, asiakas_num, time, requests, puh_numero, imei, bluetooth_name, sim_serial_number, subscriber_id, my_location, osoite, kohde_kannasta, kohdenID, aloitan, loppui, viesti, tekijan_nimi, tid, etaisyys, status, tietoja, admin, tyoajanlaatu, tyoajanmerkinta', 'safe', 'on'=>'search'),
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
			'kid' => 'Kid',
			'asiakas_num' => 'Asiakas Num',
			'time' => 'Time',
			'requests' => 'Requests',
			'puh_numero' => 'Puh Numero',
			'imei' => 'Imei',
			'bluetooth_name' => 'Bluetooth Name',
			'sim_serial_number' => 'Sim Serial Number',
			'subscriber_id' => 'Subscriber',
			'my_location' => 'My Location',
			'osoite' => 'Osoite',
			'kohde_kannasta' => 'Osoite',
			'kohdenID' => 'Kohden',
			'aloitan' => 'Aloitus',
			'loppui' => 'Lopetus',
			'viesti' => 'Viesti',
			'tekijan_nimi' => 'Tekijan Nimi',
			'tid' => 'Tid',
			'etaisyys' => 'Etaisyys',
			'status' => 'Tilanne',
			'tietoja' => 'Tietoja',
			'admin' => 'Admin',
			'tyoajanlaatu' => 'Tyoajanlaatu',
			'tyoajanmerkinta' => 'Tyoajanmerkinta',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
