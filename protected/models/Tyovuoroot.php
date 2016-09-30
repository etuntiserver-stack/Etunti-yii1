<?php

/**
 * This is the model class for table "sivex_tvuoro".
 *
 * The followings are the available columns in table 'sivex_tvuoro':
 * @property integer $id
 * @property integer $tid
 * @property string $time
 * @property string $kohde
 * @property string $pvm
 * @property string $alku
 * @property string $loppu
 * @property string $pituus
 * @property string $ruokatauko
 * @property string $alku_r
 * @property string $kesto
 * @property string $tyoajanlaatu
 * @property string $tyoajanmerkinta
 * @property string $tietoja
 * @property string $osoiteOnline
 */
class Tyovuoroot extends DB2ActiveRecord
{
public $osoite;
public $tekijan_nimi;
public $toimenpiteet;
public $l_tunnit;
public $count;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tyovuoroot the static model class
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
		return 'sivex_tvuoro';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('kohde, pvm, alku, loppu, pituus, tyoajanlaatu, tyoajanmerkinta', 'required'),
			array('tid, onlinevaraus_id, status, toistuva_id, ilmoitus_avoimista_kohteesta, ilmoitus_myohastyneista_kohteesta', 'numerical', 'integerOnly'=>true),
			array('kohde', 'length', 'max'=>255),
			array('pvm', 'length', 'max'=>20),
			array('alku, loppu, pituus, alku_r, kesto', 'length', 'max'=>10),
			array('ruokatauko, tyoajanlaatu, tyoajanmerkinta', 'length', 'max'=>50),
			array('osoiteOnline', 'length', 'max'=>100),
			array('tietoja, tyopaari', 'length', 'max'=>10000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tid, time, kohde, pvm, alku, loppu, pituus, ruokatauko, alku_r, kesto, tyoajanlaatu, tyoajanmerkinta, tietoja, osoiteOnline, tekijan_nimi, toimenpiteet, osoite', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('main', 'ID'),
			'tid' => Yii::t('main', 'Työntekijä'),
			'tekijan_nimi' => Yii::t('main', 'Työntekijä'),
			'ohje' => Yii::t('main', 'Ohjeteksti kohdetiedoista'),
			'osoite' => Yii::t('main', 'Katuosoite'),
			'time' => Yii::t('main', 'Time'),
			'kohde' => Yii::t('main', 'Kohde'),
			'pvm' => Yii::t('main', 'Päivämäärä'),
			'alku' => Yii::t('main', 'Aloitus'),
			'loppu' => Yii::t('main', 'Lopetus'),
			'pituus' => Yii::t('main', 'Pituus'),
			'ruokatauko' => Yii::t('main', 'Ruokatauko'),
			'alku_r' => Yii::t('main', 'Alku R'),
			'kesto' => Yii::t('main', 'Kesto'),
			'tyoajanlaatu' => Yii::t('main', 'Tyoajanlaatu'),
			'tyoajanmerkinta' => Yii::t('main', 'Työajanmerkintä'),
			'tietoja' => Yii::t('main', 'Tietoja mobiilisovelukseen'),
			'osoiteOnline' => Yii::t('main', 'Osoite Online'),
			'status' => Yii::t('main', 'Tilanne'),
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

		$criteria->with=array('kohteet','tt');
		$criteria->compare('kohteet.osoite',$this->osoite,true);
		$criteria->compare('kohteet.ohje',$this->toimenpiteet,true);
		$criteria->compare('tt.tekijan_nimi',$this->tekijan_nimi,true);

		$criteria->compare('id',$this->id);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('kohde',$this->kohde,true);
		$criteria->compare('pvm',$this->pvm,true);
		$criteria->compare('alku',$this->alku,true);
		$criteria->compare('loppu',$this->loppu,true);
		$criteria->compare('pituus',$this->pituus,true);
		$criteria->compare('ruokatauko',$this->ruokatauko,true);
		$criteria->compare('alku_r',$this->alku_r,true);
		$criteria->compare('kesto',$this->kesto,true);
		$criteria->compare('tyoajanlaatu',$this->tyoajanlaatu,true);
		$criteria->compare('tyoajanmerkinta',$this->tyoajanmerkinta,true);
		$criteria->compare('tietoja',$this->tietoja,true);
		$criteria->compare('osoiteOnline',$this->osoiteOnline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
