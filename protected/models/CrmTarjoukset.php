<?php

/**
 * This is the model class for table "sivex_tarjoukset".
 *
 * The followings are the available columns in table 'sivex_tarjoukset':
 * @property integer $id
 * @property string $time
 * @property integer $asiakas_id
 * @property string $tarjous
 * @property string $hyvaksyn_koodi
 * @property string $asiakkaan_sahkoposti
 * @property integer $status
 */
class CrmTarjoukset extends DB2ActiveRecord
{
	public $asiakastila, $template;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'sivex_tarjoukset';
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
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP ',
                     'asiakas_id' => 'int(11) DEFAULT 0',
                     'as_nro' => 'int(11) DEFAULT 0',
                     'tarjous' => 'text DEFAULT NULL',
                     'hyvaksyn_koodi' => 'varchar(255) DEFAULT NULL',
                     'asiakkaan_sahkoposti' => 'varchar(100) DEFAULT NULL',
                     'status' => 'int(1) DEFAULT 0',
                     'liite' => 'varchar(255) DEFAULT 0',
                     'yhteystiedot_id' => 'int(11) DEFAULT 0',
                     'tyonkuvaus' => 'text DEFAULT NULL',
                     'kohde_id' => 'int(11) DEFAULT 0',
                     'kohteen_osoite' => 'varchar(255) DEFAULT NULL',
                     'kohteen_postinumero' => 'varchar(50) DEFAULT NULL',
                     'kohteen_postitoimipaikka' => 'varchar(255) DEFAULT NULL',
                     'tyonkuvaus_id' => 'int(11) DEFAULT 0',
                     'alv' => 'int(3) DEFAULT 0',
                     'hinta_tyyppi' => 'varchar(50) DEFAULT NULL',
                     'hinta' => 'int(11) DEFAULT 0',
                     'tarvikkeet' => 'text DEFAULT NULL',
                     'voimassa' => 'varchar(20) DEFAULT NULL',
                     'tuote_palvelu' => 'varchar(255) DEFAULT NULL',
		     'yhteensa_total_verot' => 'float DEFAULT 0',
		     'yhteensa_total_veroton' => 'float DEFAULT 0',
		     'yhteensa_total' => 'float DEFAULT 0',
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
			array('template, asiakas_id, kohde_id, kohteen_osoite, asiakkaan_sahkoposti, voimassa', 'required'),
			array('asiakas_id, as_nro, yhteystiedot_id, status, kohde_id, tyonkuvaus_id, alv, hinta', 'numerical', 'integerOnly'=>true),
			array('yhteensa_total_verot, yhteensa_total_veroton, yhteensa_total', 'type', 'type'=>'float'),
			array('hyvaksyn_koodi, liite, kohteen_osoite, kohteen_postitoimipaikka, tuote_palvelu', 'length', 'max'=>255),
			array('asiakkaan_sahkoposti, kohteen_postinumero', 'length', 'max'=>100),
			array('hinta_tyyppi', 'length', 'max'=>50),
			array('voimassa', 'length', 'max'=>20),
			array('tarjous, tyonkuvaus, tarvikkeet', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, asiakas_id, tarjous, hyvaksyn_koodi, asiakkaan_sahkoposti, status', 'safe', 'on'=>'search'),
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
		        'sopimukset' => array(self::HAS_ONE, 'CrmSopimukset', 'tarjous_id'),
		        'asiakkaat' => array(self::BELONGS_TO, 'Asiakkaat', 'asiakas_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'time' => 'Time',
			'asiakas_id' => 'Asiakas',
			'tarjous' => 'Teksti',
			'hyvaksyn_koodi' => 'Hyvaksyn Koodi',
			'asiakkaan_sahkoposti' => 'Sähköposti',
			'status' => 'Status',
			'tyonkuvaus' => Yii::t('main', 'Työnkuvaus'),
			'yhteystiedot_id' => Yii::t('main', 'Yhteystiedot'),
			'kohde_id' => Yii::t('main', 'Kohde'),
			'kohteen_osoite' => Yii::t('main', 'Kohteen osoite'),
			'kohteen_postinumero' => Yii::t('main', 'Kohteen postinumero'),
			'kohteen_postitoimipaikka' => Yii::t('main', 'Kohteen postitoimipaikka'),
			'asiakastila' => Yii::t('main', 'Asiakastila'),
			'kohde_id' => Yii::t('main', 'Kohde'),
			'tyonkuvaus_id' => Yii::t('main', 'Työnkuvaus'),
			'template' => Yii::t('main', 'Malli'),
			'tuote_palvelu' => Yii::t('main', 'Tuote / Palvelu'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('asiakas_id',$this->asiakas_id);
		$criteria->compare('tarjous',$this->tarjous,true);
		$criteria->compare('hyvaksyn_koodi',$this->hyvaksyn_koodi,true);
		$criteria->compare('asiakkaan_sahkoposti',$this->asiakkaan_sahkoposti,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CrmTarjoukset the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
