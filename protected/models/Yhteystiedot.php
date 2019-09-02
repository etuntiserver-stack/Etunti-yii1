<?php

/**
 * This is the model class for table "yhteystiedot".
 *
 * The followings are the available columns in table 'yhteystiedot':
 * @property integer $id
 * @property string $time
 * @property integer $yhteystieto_tyyppi
 * @property string $yrityksen_nimi
 * @property string $y_tunnus
 * @property string $yhteyshenkilo
 * @property string $osoite
 * @property string $postitoimipaikka
 * @property integer $postinumero
 * @property string $puhelin
 * @property string $sahkoposti
 * @property string $ryhma
 * @property string $myyja
 * @property integer $status
 */
class Yhteystiedot extends DB2ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'yhteystiedot';
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
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'yhteystieto_tyyppi' => 'varchar(100) DEFAULT NULL',
                     'yrityksen_nimi' => 'varchar(100) DEFAULT NULL',
                     'y_tunnus' => 'varchar(50) DEFAULT NULL',
                     'yhteyshenkilo' => 'varchar(100) DEFAULT NULL',
                     'osoite' => 'varchar(255) DEFAULT NULL',
                     'postitoimipaikka' => 'varchar(255) DEFAULT NULL',
                     'postinumero' => 'int(10) DEFAULT 0',
                     'puhelin' => 'varchar(100) DEFAULT NULL',
                     'sahkoposti' => 'varchar(100) DEFAULT NULL',
                     'ryhma' => 'varchar(100) DEFAULT NULL',
                     'myyja' => 'varchar(100) DEFAULT NULL',
                     'status' => 'int(1) DEFAULT 0',
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
			//array('yhteystieto_tyyppi, yrityksen_nimi, yhteyshenkilo, osoite, postitoimipaikka, postinumero, puhelin, sahkoposti, ryhma, myyja, status', 'required'),
			array('postinumero, status', 'numerical', 'integerOnly'=>true),
			array('yhteystieto_tyyppi, yrityksen_nimi, yhteyshenkilo, puhelin, sahkoposti, ryhma, myyja', 'length', 'max'=>100),
			array('y_tunnus', 'length', 'max'=>50),
			array('osoite, postitoimipaikka', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, yhteystieto_tyyppi, yrityksen_nimi, y_tunnus, yhteyshenkilo, osoite, postitoimipaikka, postinumero, puhelin, sahkoposti, ryhma, myyja, status', 'safe', 'on'=>'search'),
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
			'time' => 'Time',
			'yhteystieto_tyyppi' => Yii::t('main', 'Yhteystieto tyyppi'),
			'yrityksen_nimi' => Yii::t('main', 'Yrityksen nimi'),
			'y_tunnus' => Yii::t('main', 'Y-tunnus'),
			'yhteyshenkilo' => Yii::t('main', 'Yhteyshenkilö'),
			'osoite' => 'Osoite',
			'postitoimipaikka' => 'Postitoimipaikka',
			'postinumero' => 'Postinumero',
			'puhelin' => 'Puhelin',
			'sahkoposti' => Yii::t('main', 'Sähköposti'),
			'ryhma' => Yii::t('main', 'Ryhmä'),
			'myyja' => Yii::t('main', 'Myyjä'),
			'status' => Yii::t('main', 'Tila'),
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
		$criteria->compare('yhteystieto_tyyppi',$this->yhteystieto_tyyppi);
		$criteria->compare('yrityksen_nimi',$this->yrityksen_nimi,true);
		$criteria->compare('y_tunnus',$this->y_tunnus,true);
		$criteria->compare('yhteyshenkilo',$this->yhteyshenkilo,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('postitoimipaikka',$this->postitoimipaikka,true);
		$criteria->compare('postinumero',$this->postinumero);
		$criteria->compare('puhelin',$this->puhelin,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('ryhma',$this->ryhma,true);
		$criteria->compare('myyja',$this->myyja,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Yhteystiedot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
