<?php

/**
 * This is the model class for table "onlinevaraus_tuotteet".
 *
 * The followings are the available columns in table 'onlinevaraus_tuotteet':
 * @property integer $id
 * @property string $nimike
 * @property string $hinta
 * @property string $selitysteksti
 * @property integer $palvelu
 * @property string $kesto
 * @property integer|null $oletustuote
 * @property string $yksikko
 */
class TuotteetPalvelut extends DB2ActiveRecord
{

	public $image;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TuotteetPalvelut the static model class
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
		$tb_name = 'onlinevaraus_tuotteet';
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
			'nimike' => 'varchar(255) DEFAULT NULL',
			'kategoria' => 'varchar(255) DEFAULT NULL',
			'selitysteksti' => 'text DEFAULT NULL',
			'palvelu' => 'int(1) DEFAULT 0',
			'kesto' => 'varchar(20) DEFAULT NULL',
			'nelio' => 'varchar(20) DEFAULT NULL',
			'kotitalousvahennys' => 'varchar(20) DEFAULT NULL',
			'nayta_sivuilla' => 'int(1) DEFAULT 1',
			'nayta_vain_onlinevarauksessa' => 'int(1) DEFAULT 0',
			'paa_palvelu' => 'int(11) DEFAULT 0',
			'toinen_valikko_rakenne' => 'text DEFAULT NULL',
			'lisapalvelut' => 'text DEFAULT NULL',
			'hinta_alv_0' => 'float DEFAULT 0',
			'hinta_alv_sis' => 'float DEFAULT 0',
			'alv' => 'int(3) DEFAULT 24',
			'yksikko' => 'varchar(20) DEFAULT NULL',
			'netvisorkey' => 'int(11) DEFAULT 0',
			'aktiivinen' => 'int(1) DEFAULT 1',
			'varastoitava' => 'int(1) DEFAULT 0',
			'myyntituote' => 'int(1) DEFAULT 1',
			'myyntitili' => 'varchar(20) DEFAULT 3000',
			'alvsis' => 'varchar(20) DEFAULT "nolla"',
			'oletustuote' => 'int(1) DEFAULT 0',
			'netvisor_dimension_name' => 'varchar(100) DEFAULT NULL',
			'netvisor_dimension_item' => 'varchar(100) DEFAULT NULL',
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
			array('nimike', 'required'),
			array('image', 'file','types'=>'jpg', 'allowEmpty'=>true, 'on'=>'update'),
			array('palvelu, nayta_sivuilla, nayta_vain_onlinevarauksessa, paa_palvelu, aktiivinen, varastoitava, myyntituote, oletustuote', 'numerical', 'integerOnly'=>true),
			array('hinta_alv_0, hinta_alv_sis', 'numerical', 'integerOnly'=>false),
			array('nimike, selitysteksti, myyntitili', 'length', 'max'=>255),
			array('hinta, kesto, nelio, kotitalousvahennys, netvisorkey, yksikko', 'length', 'max'=>20),
			array('toinen_valikko_rakenne, lisapalvelut, kategoria, alvsis, alv, netvisor_dimension_name, netvisor_dimension_item', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nimike, hinta, selitysteksti, palvelu, kesto, nelio', 'safe', 'on'=>'search'),
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
			'nimike' => Yii::t('main', 'Tuotenimi'),
			'hinta' => Yii::t('main', 'Hinta'),
			'selitysteksti' => Yii::t('main', 'Selitysteksti'),
			'palvelu' => Yii::t('main', 'Palvelu'),
			'kesto' => Yii::t('main', 'Kesto (tunnilla)'),
			'nelio' => Yii::t('main', 'Neliömetri m²'),
			'kotitalousvahennys' => Yii::t('main', 'Kotitalousvähennys %'),
			'nayta_sivuilla' => Yii::t('main', 'Näytä sivulla'),
			'nayta_vain_onlinevarauksessa' => Yii::t('main', 'Näytä vain onlinevarauksessa'),
			'paa_palvelu'=> Yii::t('main', 'Pääpalvelu'),
			'image'=> Yii::t('main', 'Valokuva'),
			'netvisor_dimension_name' => Yii::t('main', 'Kustannuspaikka'),
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
		$criteria->compare('nimike',$this->nimike,true);
		$criteria->compare('hinta',$this->hinta,true);
		$criteria->compare('selitysteksti',$this->selitysteksti,true);
		$criteria->compare('palvelu',$this->palvelu);
		$criteria->compare('kesto',$this->kesto,true);
		$criteria->compare('nelio',$this->nelio,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
