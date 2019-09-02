<?php

/**
 * This is the model class for table "sivex_tyotodistukset".
 *
 * The followings are the available columns in table 'sivex_tyotodistukset':
 * @property integer $id
 * @property string $time
 * @property integer $key
 * @property string $tyonantaja
 * @property string $osoite
 * @property string $postinumero
 * @property string $postitoimipaikka
 * @property string $puhelin
 * @property string $y_tunnus
 * @property string $sahkoposti
 * @property string $tekijan_email
 * @property integer $tid
 * @property string $tekijan_nimi
 * @property string $tekijan_katuosoite
 * @property string $tekijan_pnumero
 * @property string $tekijan_ptoimipaikka
 * @property string $tekijan_puh
 * @property string $tekijan_henkilotunnus
 * @property string $Alku
 * @property string $Loppu
 * @property string $Tyokohde
 * @property string $Tyotehtavat
 * @property string $TyosuhteenPaattamisenSyy
 * @property string $Tyotaito
 * @property string $Kaytos
 * @property string $Arvio
 * @property string $Paivays
 * @property string $Paikka
 * @property string $TyonantajanEdustaja
 * @property string $NimikeTehtava
 * @property string $tiedosto
 */
class Tyotodistus extends DB2ActiveRecord
{

	public $template;
	public $tyontekijat;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'sivex_tyotodistukset';
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
                     'key' => 'int(1) DEFAULT 2',
                     'tyonantaja' => 'varchar(70) DEFAULT NULL',
                     'osoite' => 'varchar(255) DEFAULT NULL',
                     'postinumero' => 'varchar(7) DEFAULT NULL',
                     'postitoimipaikka' => 'varchar(100) DEFAULT NULL',
                     'puhelin' => 'varchar(50) DEFAULT NULL',
                     'y_tunnus' => 'varchar(50) DEFAULT NULL',
                     'sahkoposti' => 'varchar(100) DEFAULT NULL',
                     'tekijan_email' => 'varchar(100) DEFAULT NULL',
                     'tid' => 'int(7) DEFAULT 0',
                     'tekijan_nimi' => 'varchar(70) DEFAULT NULL',
                     'tekijan_katuosoite' => 'varchar(100) DEFAULT NULL',
                     'tekijan_pnumero' => 'varchar(7) DEFAULT NULL',
                     'tekijan_ptoimipaikka' => 'varchar(50) DEFAULT NULL',
                     'tekijan_puh' => 'varchar(50) DEFAULT NULL',
                     'tekijan_henkilotunnus' => 'varchar(50) DEFAULT NULL',
                     'Alku' => 'varchar(50) DEFAULT NULL',
                     'Loppu' => 'varchar(50) DEFAULT NULL',
                     'Tyokohde' => 'text DEFAULT NULL',
                     'Tyotehtavat' => 'text DEFAULT NULL',
                     'TyosuhteenPaattamisenSyy' => 'text DEFAULT NULL',
                     'Tyotaito' => 'text DEFAULT NULL',
                     'Kaytos' => 'text DEFAULT NULL',
                     'Arvio' => 'text DEFAULT NULL',
                     'Paivays' => 'varchar(50) DEFAULT NULL',
                     'Paikka' => 'varchar(100) DEFAULT NULL',
                     'TyonantajanEdustaja' => 'varchar(100) DEFAULT NULL',
                     'NimikeTehtava' => 'varchar(100) DEFAULT NULL',
                     'tiedosto' => 'varchar(100) DEFAULT NULL',
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
			array('template', 'required'),

			//array('time, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, Alku, Loppu, Tyokohde, Tyotehtavat, TyosuhteenPaattamisenSyy, Tyotaito, Kaytos, Arvio, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto', 'required'),

			array('Tyokohde, Tyotehtavat, TyosuhteenPaattamisenSyy, Tyotaito, Kaytos, Arvio', 'length', 'max'=>1000),

			array('key, tid', 'numerical', 'integerOnly'=>true),
			array('tyonantaja, tekijan_nimi', 'length', 'max'=>70),
			array('osoite', 'length', 'max'=>255),
			array('postinumero, tekijan_pnumero', 'length', 'max'=>7),
			array('postitoimipaikka, sahkoposti, tekijan_email, tekijan_katuosoite, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto', 'length', 'max'=>100),
			array('puhelin, y_tunnus, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, Alku, Loppu, Paivays', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, key, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, Alku, Loppu, Tyokohde, Tyotehtavat, TyosuhteenPaattamisenSyy, Tyotaito, Kaytos, Arvio, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto', 'safe', 'on'=>'search'),
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
			'key' => 'Key',
			'tyonantaja' => Yii::t('main', 'Työnantaja'),
			'osoite' => Yii::t('main', 'Osoite'),
			'postinumero' => Yii::t('main', 'Postinumero'),
			'postitoimipaikka' => Yii::t('main', 'Postitoimipaikka'),
			'puhelin' => Yii::t('main', 'Puhelin'),
			'y_tunnus' => Yii::t('main', 'Y-tunnus'),
			'sahkoposti' => Yii::t('main', 'Sähköposti'),
			'tekijan_email' => Yii::t('main', 'Työntekijän sähköposti'),
			'tid' => 'Tid',
			'tekijan_nimi' => Yii::t('main', 'Työntekijän nimi'),
			'tekijan_katuosoite' => Yii::t('main', 'Työntekijän osoite'),
			'tekijan_pnumero' => Yii::t('main', 'Työntekijän postinumero'),
			'tekijan_ptoimipaikka' => Yii::t('main', 'Työntekijän postitoimipaikka'),
			'tekijan_puh' => Yii::t('main', 'Työntekijän puhelin'),
			'tekijan_henkilotunnus' => Yii::t('main', 'Työntekijän henkilötunnus'),
			'Alku' => Yii::t('main', 'Alku'),
			'Loppu' => Yii::t('main', 'Loppu'),
			'Tyokohde' => Yii::t('main', 'Työkohde'),
			'Tyotehtavat' => Yii::t('main', 'Työtehtävät'),
			'TyosuhteenPaattamisenSyy' => Yii::t('main', 'Työsuhteen päättämisen syy'),
			'Tyotaito' => Yii::t('main', 'Työtaito'),
			'Kaytos' => Yii::t('main', 'Käytös'),
			'Arvio' => Yii::t('main', 'Arvio'),
			'Paivays' => Yii::t('main', 'Päiväys'),
			'Paikka' => Yii::t('main', 'Paikka'),
			'TyonantajanEdustaja' => Yii::t('main', 'Työnantajan edustaja'),
			'NimikeTehtava' => Yii::t('main', 'Nimike/Tehtävä'),
			'tiedosto' => Yii::t('main', 'Tiedosto'),
			'template' => Yii::t('main', 'Malli'),
			'tyontekijat' => Yii::t('main', 'Työntekijät'),

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
		$criteria->compare('key',$this->key);
		$criteria->compare('tyonantaja',$this->tyonantaja,true);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('postinumero',$this->postinumero,true);
		$criteria->compare('postitoimipaikka',$this->postitoimipaikka,true);
		$criteria->compare('puhelin',$this->puhelin,true);
		$criteria->compare('y_tunnus',$this->y_tunnus,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('tekijan_email',$this->tekijan_email,true);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('tekijan_nimi',$this->tekijan_nimi,true);
		$criteria->compare('tekijan_katuosoite',$this->tekijan_katuosoite,true);
		$criteria->compare('tekijan_pnumero',$this->tekijan_pnumero,true);
		$criteria->compare('tekijan_ptoimipaikka',$this->tekijan_ptoimipaikka,true);
		$criteria->compare('tekijan_puh',$this->tekijan_puh,true);
		$criteria->compare('tekijan_henkilotunnus',$this->tekijan_henkilotunnus,true);
		$criteria->compare('Alku',$this->Alku,true);
		$criteria->compare('Loppu',$this->Loppu,true);
		$criteria->compare('Tyokohde',$this->Tyokohde,true);
		$criteria->compare('Tyotehtavat',$this->Tyotehtavat,true);
		$criteria->compare('TyosuhteenPaattamisenSyy',$this->TyosuhteenPaattamisenSyy,true);
		$criteria->compare('Tyotaito',$this->Tyotaito,true);
		$criteria->compare('Kaytos',$this->Kaytos,true);
		$criteria->compare('Arvio',$this->Arvio,true);
		$criteria->compare('Paivays',$this->Paivays,true);
		$criteria->compare('Paikka',$this->Paikka,true);
		$criteria->compare('TyonantajanEdustaja',$this->TyonantajanEdustaja,true);
		$criteria->compare('NimikeTehtava',$this->NimikeTehtava,true);
		$criteria->compare('tiedosto',$this->tiedosto,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tyotodistus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
