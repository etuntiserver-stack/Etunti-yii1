<?php

/**
 * This is the model class for table "sivexkuitti".
 *
 * The followings are the available columns in table 'sivexkuitti':
 * @property integer $id
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
 * @property string $hyvaksytty
 */
class Mob extends DB2ActiveRecord
{

public $tekijan_nimi;
public $domain;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Mob the static model class
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
		return 'sivexkuitti';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kohde_kannasta', 'required'),
			array('requests, kohdenID, tid, status, admin', 'numerical', 'integerOnly'=>true),
			array('asiakas_num, puh_numero, bluetooth_name, subscriber_id, tekijan_nimi', 'length', 'max'=>50),
			array('domain, imei, sim_serial_number, kohde_kannasta, hyvaksytty', 'length', 'max'=>100),
			array('my_location', 'length', 'max'=>1000),
			array('osoite', 'length', 'max'=>255),
			array('aloitan, loppui, etaisyys', 'length', 'max'=>20),
			array('viesti, tietoja', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, asiakas_num, time, requests, puh_numero, imei, bluetooth_name, sim_serial_number, subscriber_id, my_location, osoite, kohde_kannasta, kohdenID, aloitan, loppui, viesti, tekijan_nimi, tid, etaisyys, status, tietoja, admin, hyvaksytty, tekijan_nimi, domain', 'safe', 'on'=>'search'),
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
		        'tyontekijat' => array(self::BELONGS_TO, 'Tyontekijat', 'tid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('main', 'ID'),
			'asiakas_num' => Yii::t('main', 'Versio ja TAG:in ID'),
			'time' => Yii::t('main', 'Time'),
			'requests' => Yii::t('main', 'Requests'),
			'puh_numero' => Yii::t('main', 'Puh Numero'),
			'imei' => Yii::t('main', 'Imei'),
			'bluetooth_name' => Yii::t('main', 'Bluetooth Name'),
			'sim_serial_number' => Yii::t('main', 'Sim Serial Number'),
			'subscriber_id' => Yii::t('main', 'Subscriber'),
			'my_location' => Yii::t('main', 'My Location'),
			'osoite' => Yii::t('main', 'Osoite GPS'),
			'kohde_kannasta' => Yii::t('main', 'Kohde'),
			'kohdenID' => Yii::t('main', 'Kohteen ID'),
			'aloitan' => Yii::t('main', 'Aloitan'),
			'loppui' => Yii::t('main', 'Loppui'),
			'viesti' => Yii::t('main', 'Viesti'),
			'tekijan_nimi' => Yii::t('main', 'Työntekijä'),
			'tid' => Yii::t('main', 'Työntekijän ID'),
			'etaisyys' => Yii::t('main', 'Etäisyys'),
			'status' => Yii::t('main', 'Status'),
			'tietoja' => Yii::t('main', 'Tietoja'),
			'admin' => Yii::t('main', 'Admin'),
			'hyvaksytty' => Yii::t('main', 'Hyvaksytty'),
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

		$criteria->with=array('tyontekijat');

		$criteria->compare('tekijan_nimi',$this->tekijan_nimi);

		$criteria->compare('id',$this->id);
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
		$criteria->compare('hyvaksytty',$this->hyvaksytty,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}




}
