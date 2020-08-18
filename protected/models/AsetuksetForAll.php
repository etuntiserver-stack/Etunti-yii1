<?php

/**
 * This is the model class for table "asetukset".
 *
 * The followings are the available columns in table 'asetukset':
 * @property integer $id
 * @property string $asetus
 * @property string $api_access_key
 * @property string $muut
 */
class AsetuksetForAll extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AsetuksetForAll the static model class
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
		$tb_name = 'asetukset';
		$check_this_table = true;

		if($check_this_table)
		{
		$table = Yii::app()->db->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(
                     'asetus' => 'varchar(255) DEFAULT NULL',
                     'api_access_key' => 'varchar(500) DEFAULT NULL',
                     'ohjesivu' => 'text DEFAULT NULL',
                     'googlemaps_apikey' => 'varchar(500) DEFAULT NULL',
                     'viralliset_pyhapaivat' => 'text DEFAULT NULL',
                     'erikoislauantai' => 'text DEFAULT NULL',
                     'app_info_sivu' => 'text DEFAULT NULL',
                     'app_ilmoitus_kaikkille' => 'text DEFAULT NULL',
		     'app_ilmoitus_vastaanottajat' => 'text DEFAULT NULL',
                     'app_ilmoitus_voimassa_asti' => 'DATETIME DEFAULT NULL',
                     'app_ilmoitus_versio_eisamakun' => 'varchar(255) DEFAULT "0.0.620"',
		     'email' => 'varchar(255) DEFAULT NULL',
                     'session_aikamaara' => 'int(2) DEFAULT 8',
                     'max_ilmaiset_tunnit' => 'int(11) DEFAULT 500',
		);

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db->createCommand()->addColumn($tb_name, $key, $value);
			}
		}

		} // check

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
			array('api_access_key', 'required'),
			array('max_ilmaiset_tunnit', 'numerical', 'integerOnly'=>true),
			array('asetus, email, app_ilmoitus_versio_eisamakun', 'length', 'max'=>255),
			array('api_access_key, googlemaps_apikey', 'length', 'max'=>500),
			array('ohjesivu, viralliset_pyhapaivat, erikoislauantai, app_info_sivu, session_aikamaara, app_ilmoitus_kaikkille, app_ilmoitus_vastaanottajat, app_ilmoitus_voimassa_asti', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, asetus, api_access_key, ohjesivu', 'safe', 'on'=>'search'),
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
			'asetus' => Yii::t('main', 'Asetus'),
			'email' => Yii::t('main', 'Sähköposti'),
			'api_access_key' => Yii::t('main', 'Api Access Key'),
			'ohjesivu' => Yii::t('main', 'Ohjesivu'),
			'session_aikamaara' => Yii::t('main', 'Automaatinen kirjaudu ulos (tunti määrä)'),
			'app_ilmoitus_kaikkille' => Yii::t('main', 'Ilmoituksen teksti'),
			'app_ilmoitus_voimassa_asti' => Yii::t('main', 'Ilmoitus on voimassa Asti'),
			'app_ilmoitus_versio_eisamakun' => Yii::t('main', 'Ilmoitus tulee kaikkille joiden APP versio ei sama kun:'),
			'max_ilmaiset_tunnit' => Yii::t('main', 'Ilmaiset tunnit maksimi määrä'),
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
		$criteria->compare('asetus',$this->asetus,true);
		$criteria->compare('api_access_key',$this->api_access_key,true);
		$criteria->compare('ohjesivu',$this->ohjesivu,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
