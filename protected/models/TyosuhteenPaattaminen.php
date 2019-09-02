<?php

/**
 * This is the model class for table "tyosuhteen_paattaminen".
 *
 * The followings are the available columns in table 'tyosuhteen_paattaminen':
 * @property integer $id
 * @property string $time
 * @property integer $key
 * @property string $titteli
 * @property string $kuuleminen
 * @property string $tyosuhteen_paattaminen
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
 * @property string $teksti
 * @property string $Paivays
 * @property string $Paikka
 * @property string $TyonantajanEdustaja
 * @property string $NimikeTehtava
 * @property string $tiedosto
 * @property string $alku_pvm
 * @property string $loppu_pvm
 */
class TyosuhteenPaattaminen extends DB2ActiveRecord
{

	public $template;
	public $tyontekijat;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'tyosuhteen_paattaminen';
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
                     'titteli' => 'varchar(255) DEFAULT NULL',
                     'kuuleminen' => 'text DEFAULT NULL',
                     'tyosuhteen_paattaminen' => 'text DEFAULT NULL',
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
                     'teksti' => 'text DEFAULT NULL',
                     'Paivays' => 'varchar(50) DEFAULT NULL',
                     'Paikka' => 'varchar(100) DEFAULT NULL',
                     'TyonantajanEdustaja' => 'varchar(100) DEFAULT NULL',
                     'NimikeTehtava' => 'varchar(100) DEFAULT NULL',
                     'tiedosto' => 'varchar(255) DEFAULT NULL',
                     'alku_pvm' => 'varchar(50) DEFAULT NULL',
                     'loppu_pvm' => 'varchar(50) DEFAULT NULL',
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
			array('template, titteli, kuuleminen, tyosuhteen_paattaminen, tyonantaja, osoite, postinumero, postitoimipaikka, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_henkilotunnus, Paivays, Paikka, TyonantajanEdustaja, alku_pvm, loppu_pvm', 'required'),
			//array('time, titteli, kuuleminen, tyosuhteen_paattaminen, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, teksti, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto, alku_pvm, loppu_pvm', 'required'),
			array('key, tid', 'numerical', 'integerOnly'=>true),
			array('titteli, osoite, tiedosto', 'length', 'max'=>255),
			array('tyonantaja, tekijan_nimi', 'length', 'max'=>70),
			array('postinumero, tekijan_pnumero', 'length', 'max'=>7),
			array('postitoimipaikka, sahkoposti, tekijan_email, tekijan_katuosoite, Paikka, TyonantajanEdustaja, NimikeTehtava', 'length', 'max'=>100),
			array('puhelin, y_tunnus, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, Paivays, alku_pvm, loppu_pvm', 'length', 'max'=>50),
			array('teksti, kuuleminen, tyosuhteen_paattaminen', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, key, titteli, kuuleminen, tyosuhteen_paattaminen, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, teksti, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava, tiedosto, alku_pvm, loppu_pvm', 'safe', 'on'=>'search'),
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
			'titteli' => 'Titteli',
			'kuuleminen' => 'Kuuleminen',
			'tyosuhteen_paattaminen' => Yii::t('main', 'Työsuhteen päättäminen'),
			'tyonantaja' => 'Työnantaja',
			'osoite' => 'Työnantaja osoite',
			'postinumero' => 'Työnantaja postinumero',
			'postitoimipaikka' => 'Työnantaja postitoimipaikka',
			'puhelin' => 'Työnantaja puhelin',
			'y_tunnus' => 'Työnantaja Y-tunnus',
			'sahkoposti' => 'Työnantaja sähköposti',
			'tekijan_email' => 'Työntekijä sähköposti',
			'tid' => 'Tid',
			'tekijan_nimi' => 'Työntekijä nimi',
			'tekijan_katuosoite' => 'Työntekijä osoite',
			'tekijan_pnumero' => 'Työntekijä postinumero',
			'tekijan_ptoimipaikka' => 'Työntekijä postitoimipaikka',
			'tekijan_puh' => 'Työntekijä puhelin',
			'tekijan_henkilotunnus' => 'Työntekijä henkilötunnus',
			'teksti' => 'Teksti',
			'Paivays' => 'Päiväys',
			'Paikka' => 'Paikka',
			'TyonantajanEdustaja' => 'Työnantajan edustaja',
			'NimikeTehtava' => 'Nimike Tehtava',
			'tiedosto' => 'Tiedosto',
			'alku_pvm' => Yii::t('main', 'Työsuhteen alkamispäivä'),
			'loppu_pvm' => Yii::t('main', 'Työsuhteen päättymispäivä'),
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
		$criteria->compare('titteli',$this->titteli,true);
		$criteria->compare('kuuleminen',$this->kuuleminen,true);
		$criteria->compare('tyosuhteen_paattaminen',$this->tyosuhteen_paattaminen,true);
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
		$criteria->compare('teksti',$this->teksti,true);
		$criteria->compare('Paivays',$this->Paivays,true);
		$criteria->compare('Paikka',$this->Paikka,true);
		$criteria->compare('TyonantajanEdustaja',$this->TyonantajanEdustaja,true);
		$criteria->compare('NimikeTehtava',$this->NimikeTehtava,true);
		$criteria->compare('tiedosto',$this->tiedosto,true);
		$criteria->compare('alku_pvm',$this->alku_pvm,true);
		$criteria->compare('loppu_pvm',$this->loppu_pvm,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TyosuhteenPaattaminen the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
