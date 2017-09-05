<?php

/**
 * This is the model class for table "sopimukset".
 *
 * The followings are the available columns in table 'sopimukset':
 * @property integer $id
 * @property string $time
 * @property integer $asiakas_id
 * @property string $teksti
 * @property string $hyvaksyn_koodi
 * @property string $asiakkaan_sahkoposti
 * @property integer $status
 * @property string $liite
 */
class CrmSopimukset extends DB2ActiveRecord
{

public $template;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{

		$tb_name = 'sopimukset';
		$table = Yii::app()->db1->schema->getTable($tb_name);
		$table_structure = array(
			'id' => 'INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST',
			'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP AFTER id',
			'asiakas_id' => 'INT(11) AFTER time',
			'teksti' => 'TEXT AFTER asiakas_id',
			'hyvaksyn_koodi' => 'varchar(255) AFTER teksti',
			'asiakkaan_sahkoposti' => 'varchar(100) AFTER hyvaksyn_koodi',
			'status' => 'INT(11) AFTER asiakkaan_sahkoposti',
			'liite' => 'varchar(255) AFTER status',
			'template' => 'varchar(255) AFTER liite',
			'yhteystiedot_id' => 'INT(11) AFTER template',
			'tarjous_id' => 'INT(11) AFTER yhteystiedot_id',
			'voimassa' => 'varchar(20) AFTER tarjous_id',

		);

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db1->createCommand()->addColumn($tb_name, $key, $value);
			}
		}		
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
			array('tarjous_id, template', 'required'),
			//array('time, asiakas_id, teksti, hyvaksyn_koodi, asiakkaan_sahkoposti, status, liite', 'required'),
			array('asiakas_id, status, yhteystiedot_id', 'numerical', 'integerOnly'=>true),
			array('hyvaksyn_koodi, liite, template', 'length', 'max'=>255),
			array('asiakkaan_sahkoposti', 'length', 'max'=>100),
			array('teksti', 'safe'),
			array('voimassa', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, asiakas_id, teksti, hyvaksyn_koodi, asiakkaan_sahkoposti, status, liite', 'safe', 'on'=>'search'),
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
		        'tarjous' => array(self::BELONGS_TO, 'CrmTarjoukset', 'tarjous_id'),
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
			'teksti' => 'Teksti',
			'hyvaksyn_koodi' => 'Hyvaksyn Koodi',
			'asiakkaan_sahkoposti' => 'Asiakkaan Sahkoposti',
			'status' => 'Status',
			'liite' => 'Liite',
			'tarjous_id' => Yii::t('main', 'Tarjous'),
			'yhteystiedot_id' => Yii::t('main', 'Yhteystiedot'),
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
		$criteria->compare('teksti',$this->teksti,true);
		$criteria->compare('hyvaksyn_koodi',$this->hyvaksyn_koodi,true);
		$criteria->compare('asiakkaan_sahkoposti',$this->asiakkaan_sahkoposti,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('liite',$this->liite,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CrmSopimukset the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
