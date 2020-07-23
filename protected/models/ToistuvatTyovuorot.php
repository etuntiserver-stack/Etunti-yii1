<?php

/**
 * This is the model class for table "toistuvat_tyovuorot".
 *
 * The followings are the available columns in table 'toistuvat_tyovuorot':
 * @property integer $id
 * @property string $time
 * @property string $pfrom
 * @property string $pto
 * @property integer $viikkoja
 * @property string $viikko_paivat
 * @property integer $tid
 * @property integer $kohde
 * @property string $pvm
 * @property string $alku
 * @property string $loppu
 * @property string $kesto
 * @property string $tyoajanmerkinta
 * @property integer $status
 * @property string $tietoja
 * @property string $tyopaari
 * @property int $omasiistijavaroitus
 *   0: Disabled, 1: Enabled
 * @property int $omasiistijailmoitus
 *   0: Not notified, 1: Notified
 */
class ToistuvatTyovuorot extends DB2ActiveRecord
{
	public $uusi_tilaus, $osoiteOnline, $count, $l_tunnit;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		
		$tb_name = 'toistuvat_tyovuorot';
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
                     'pfrom' => 'varchar(50) DEFAULT NULL',
                     'pto' => 'varchar(50) DEFAULT NULL',
                     'viikkoja' => 'int(1) DEFAULT 0',
                     'viikko_paivat' => 'text DEFAULT NULL',
                     'tid' => 'int(11) DEFAULT 0',
                     'kohde' => 'int(11) DEFAULT 0',
                     'osoite' => 'varchar(255) DEFAULT NULL',
                     'postinumero' => 'varchar(255) DEFAULT NULL',
                     'postitoimipaikka' => 'varchar(255) DEFAULT NULL',
                     'pvm' => 'varchar(50) DEFAULT NULL',
                     'alku' => 'varchar(10) DEFAULT NULL',
                     'loppu' => 'varchar(10) DEFAULT NULL',
                     'pituus' => 'varchar(10) DEFAULT NULL',
                     'kesto' => 'varchar(10) DEFAULT NULL',
                     'tyoajanmerkinta' => 'varchar(100) DEFAULT NULL',
                     'status' => 'int(3) DEFAULT 0',
                     'tietoja' => 'text DEFAULT NULL',
                     'tyopaari' => 'text DEFAULT NULL',
		     'piilota_mobiilista' => 'int(1) DEFAULT 0',
                     'tuoteID' => 'int(1) DEFAULT 0',
                     'lisa_tuotteet' => 'text DEFAULT NULL',
                     'ilmoitus_paattymisesta' => 'int(1) DEFAULT 0',
                     'tvuoro_ids' => 'text DEFAULT NULL',
                     'poistettu_pvm' => 'text DEFAULT NULL',
		     'tyo_erittelyt' => 'text DEFAULT NULL',
		     'muistiinpano' => 'text DEFAULT NULL',
                     'tyoajanlaatu' => 'varchar(50) DEFAULT NULL',
                     'korjattu_poista_tama' => 'int(1) DEFAULT 0',
		     'new_poistettu_pvm' => 'text DEFAULT NULL',
                     'peruutettu' => 'int(1) DEFAULT 0',
		     'laskutettu' => 'int(1) DEFAULT 0',
         'omasiistijavaroitus' => 'int(1) DEFAULT 1',
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
			//array('time, pfrom, pto, viikkoja, viikko_paivat, tid, kohde, pvm, alku, loppu, kesto, tyoajanmerkinta, status, tietoja, tyopaari', 'required'),
			array('viikkoja, tid, kohde, status, ilmoitus_paattymisesta, peruutettu, piilota_mobiilista, laskutettu, tuoteID, omasiistijavaroitus', 'numerical', 'integerOnly'=>true),
			array('osoite, postinumero, postitoimipaikka, tyoajanlaatu, korjattu_poista_tama', 'length', 'max'=>255),
			array('pfrom, pto, pvm', 'length', 'max'=>50),
			array('alku, loppu, pituus, kesto', 'length', 'max'=>10),
			array('tyoajanmerkinta', 'length', 'max'=>100),
			array('tietoja', 'length', 'max'=>10000),
			array('tyopaari, tvuoro_ids, poistettu_pvm, lisa_tuotteet, tyo_erittelyt, muistiinpano, viikko_paivat, new_poistettu_pvm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, pfrom, pto, viikkoja, viikko_paivat, tid, kohde, pvm, alku, loppu, kesto, tyoajanmerkinta, status, tietoja, tyopaari', 'safe', 'on'=>'search'),
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
		        'kohteet' => array(self::BELONGS_TO, 'Kohteet', 'kohde'),
		        'tt' => array(self::BELONGS_TO, 'Tyontekijat', 'tid'),
		        'avaimet' => array(self::HAS_MANY, 'Avaimet', array('kohde'=>'kohde')),
		);
	}

        public function getTyontekijanNimi(){
		$site = Yii::app()->createController('Site');
		$return = $site[0]->etuSukunimi($this->tid);
                return $return;
        }

        public function getOsoiteFunc(){
		$return = '';
		if( empty($this->osoite) and isset($this->kohteet->osoite) )
			$return = $this->kohteet->osoite;
		elseif( !empty($this->osoite) )
			$return = $this->osoite;
                return $return;
        }

        public function getosoiteById(){
		$return = '';
		if(empty($this->osoite) and $this->status != '2' and $this->status != '10'){
			$k = Kohteet::model()->findByPk($this->kohde);
			if( isset($k->id) ){
				$return = $k->osoite;
			}
		} elseif(!empty($this->osoite) and $this->status != '2' and $this->status != '10'){
			$return = $this->osoite;
		} elseif($this->status == '2'){
			$return = 'MATKA';
		} elseif($this->status == '10'){
			$return = 'Lounastauko';
		}
                return $return;
        }

        public function getTyopaariFunc(){
		$site = Yii::app()->createController('Site');
		$return = '';
		if( is_array(json_decode($this->tyopaari, true)) )
			foreach(json_decode($this->tyopaari, true) as $tid)
				$return .= $site[0]->etuSukunimi($tid)."<br>";
                return $return;
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'time' => Yii::t('main', 'Luotu'),
			'pfrom' => Yii::t('main', 'Aloitus'),
			'pto' => Yii::t('main', 'Lopetus'),
			'viikkoja' => 'Viikkoja',
			'viikko_paivat' => Yii::t('main', 'Viikko päivät'),
			'tid' => Yii::t('main', 'Työntekijän id nro.'),
			'kohde' => 'Kohde',
			'pvm' => 'Pvm',
			'alku' => 'Alku',
			'loppu' => 'Loppu',
			'kesto' => 'Kesto',
			'tyoajanmerkinta' => 'Tyoajanmerkinta',
			'status' => 'Status',
			'tietoja' => 'Tietoja',
			'tyopaari' => 'Tyopaari',
			'piilota_mobiilista'=>Yii::t('main', 'Näytä mobiilissa'),
      'omasiistijavaroitus' => Yii::t('main', 'Omasiistijävaroitukset'),
		);
	}

        public function getToistuva_id(){
		$return = $this->id;
                return $return;
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
		$criteria->compare('pfrom',$this->pfrom,true);
		$criteria->compare('pto',$this->pto,true);
		$criteria->compare('viikkoja',$this->viikkoja);
		$criteria->compare('viikko_paivat',$this->viikko_paivat,true);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('kohde',$this->kohde);
		$criteria->compare('pvm',$this->pvm,true);
		$criteria->compare('alku',$this->alku,true);
		$criteria->compare('loppu',$this->loppu,true);
		$criteria->compare('kesto',$this->kesto,true);
		$criteria->compare('tyoajanmerkinta',$this->tyoajanmerkinta,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('tietoja',$this->tietoja,true);
		$criteria->compare('tyopaari',$this->tyopaari,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ToistuvatTyovuorot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
