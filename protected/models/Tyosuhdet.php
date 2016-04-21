<?php

/**
 * This is the model class for table "sivex_tyosuhdet".
 *
 * The followings are the available columns in table 'sivex_tyosuhdet':
 * @property integer $id
 * @property integer $tid
 * @property string $alku
 * @property string $loppu
 * @property string $vktyoaika
 * @property string $nimike
 * @property string $palkkausmuoto
 * @property string $tuntihinta
 * @property string $matka_thinta
 * @property string $lippu_kuumaks
 * @property string $koe_loppu
 * @property string $koe_hinta
 * @property string $tuloraja_ajalle
 * @property string $perusprosentti
 * @property string $lisaprosentti
 * @property string $kuukaudessa
 * @property string $kahdessa_viikossa
 * @property string $viikossa
 * @property string $paivassa
 * @property string $atk_varten
 * @property string $yksi_tuloraja
 */
class Tyosuhdet extends DB2ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tyosuhdet the static model class
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
		return 'sivex_tyosuhdet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('tid, alku, loppu, vktyoaika, nimike, palkkausmuoto, tuntihinta, matka_thinta, lippu_kuumaks, koe_loppu, koe_hinta, tuloraja_ajalle, perusprosentti, lisaprosentti, kuukaudessa, kahdessa_viikossa, viikossa, paivassa, atk_varten, yksi_tuloraja', 'required'),
			array('tid', 'numerical', 'integerOnly'=>true),
			array('alku, loppu, koe_loppu', 'length', 'max'=>20),
			array('vktyoaika, tuntihinta, matka_thinta, lippu_kuumaks, koe_hinta, perusprosentti, lisaprosentti, kuukaudessa, kahdessa_viikossa, viikossa, paivassa, atk_varten, yksi_tuloraja', 'length', 'max'=>10),
			array('nimike', 'length', 'max'=>40),
			array('palkkausmuoto', 'length', 'max'=>30),
			array('tuloraja_ajalle', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tid, alku, loppu, vktyoaika, nimike, palkkausmuoto, tuntihinta, matka_thinta, lippu_kuumaks, koe_loppu, koe_hinta, tuloraja_ajalle, perusprosentti, lisaprosentti, kuukaudessa, kahdessa_viikossa, viikossa, paivassa, atk_varten, yksi_tuloraja', 'safe', 'on'=>'search'),
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
			'tid' => Yii::t('main', 'Tid'),
			'alku' => Yii::t('main', 'Aloitus'),
			'loppu' => Yii::t('main', 'Lopetus'),
			'vktyoaika' => Yii::t('main', 'Viikkotyöaika'),
			'nimike' => Yii::t('main', 'Nimike'),
			'palkkausmuoto' => Yii::t('main', 'Palkkausmuoto'),
			'tuntihinta' => Yii::t('main', 'Tuntihinta'),
			'matka_thinta' => Yii::t('main', 'Matka tuntihinta'),
			'lippu_kuumaks' => Yii::t('main', 'Matkalipun kuukausihinta'),
			'koe_loppu' => Yii::t('main', 'Koeajan päättyminen'),
			'koe_hinta' => Yii::t('main', 'Koeajan tuntihinta'),
			'tuloraja_ajalle' => Yii::t('main', 'Tuloraja ajalle'),
			'perusprosentti' => Yii::t('main', 'Palkkaa varten Perusprosentti'),
			'lisaprosentti' => Yii::t('main', 'Lisäprosentti'),
			'kuukaudessa' => Yii::t('main', 'A Kuukaudessa'),
			'kahdessa_viikossa' => Yii::t('main', 'Kahdessa viikossa'),
			'viikossa' => Yii::t('main', 'Viikossa'),
			'paivassa' => Yii::t('main', 'Päivässä'),
			'atk_varten' => Yii::t('main', 'Laskennallinen tuloraja ATK-järjestelmiä varten'),
			'yksi_tuloraja' => Yii::t('main', 'B Ennakonpidätys yhden tulorajan mukaan'),
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
		$criteria->order = 'id DESC';
		//$criteria->condition = " tid!='' and tid!=0 ";

		$criteria->compare('id',$this->id);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('alku',$this->alku,true);
		$criteria->compare('loppu',$this->loppu,true);
		$criteria->compare('vktyoaika',$this->vktyoaika,true);
		$criteria->compare('nimike',$this->nimike,true);
		$criteria->compare('palkkausmuoto',$this->palkkausmuoto,true);
		$criteria->compare('tuntihinta',$this->tuntihinta,true);
		$criteria->compare('matka_thinta',$this->matka_thinta,true);
		$criteria->compare('lippu_kuumaks',$this->lippu_kuumaks,true);
		$criteria->compare('koe_loppu',$this->koe_loppu,true);
		$criteria->compare('koe_hinta',$this->koe_hinta,true);
		$criteria->compare('tuloraja_ajalle',$this->tuloraja_ajalle,true);
		$criteria->compare('perusprosentti',$this->perusprosentti,true);
		$criteria->compare('lisaprosentti',$this->lisaprosentti,true);
		$criteria->compare('kuukaudessa',$this->kuukaudessa,true);
		$criteria->compare('kahdessa_viikossa',$this->kahdessa_viikossa,true);
		$criteria->compare('viikossa',$this->viikossa,true);
		$criteria->compare('paivassa',$this->paivassa,true);
		$criteria->compare('atk_varten',$this->atk_varten,true);
		$criteria->compare('yksi_tuloraja',$this->yksi_tuloraja,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
