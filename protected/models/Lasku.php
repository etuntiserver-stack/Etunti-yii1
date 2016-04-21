<?php

/**
 * This is the model class for table "laskut".
 *
 * The followings are the available columns in table 'laskut':
 * @property integer $id
 * @property integer $lid
 * @property integer $yid
 * @property string $time
 * @property string $tyyppi
 * @property string $yritys
 * @property string $y_tunnus
 * @property string $nimi
 * @property integer $as_nro
 * @property string $osoite
 * @property string $postinumero
 * @property string $toimipaikka
 * @property string $laskutus
 * @property string $sahkoposti
 * @property string $verkkolaskuosoite
 * @property string $v_tunnus
 * @property string $yhteyshenkilo
 * @property string $nimitarkenne
 * @property string $puhelin
 * @property string $t_yritys
 * @property string $t_y_tunnus
 * @property string $t_nimi
 * @property string $t_osoite
 * @property string $t_postinumero
 * @property string $t_toimipaikka
 * @property string $t_puhelin
 * @property string $t_sahkoposti
 * @property string $toimitusosoite
 * @property string $paivays
 * @property string $erapaiva
 * @property string $toimituspaiva
 * @property string $maksuehto
 * @property string $viitenumero
 * @property string $viivastyskorko
 * @property string $yhteensa_total_verot
 * @property string $yhteensa_total_veroton
 * @property string $yhteensa_total
 * @property string $saaja_iban
 * @property string $saaja_virtualkoodi
 * @property string $tilanne
 * @property string $maksettu_euro
 * @property string $hyvityslasku
 * @property string $laskun_nimetys
 */
class Lasku extends DB2ActiveRecord
{


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lasku the static model class
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
		return 'laskut';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        //array('laskunumero','unique', 'message'=>'Tämä laskunumero on jo olemassa!'),
			array('yid, tyyppi, as_nro, osoite, postinumero, toimipaikka, laskutus, paivays, erapaiva, maksuehto, toimitusosoite', 'required'),
			array('kirjeenluokka, muistutuslasku_auto, lid, yid, as_nro, laskunumero', 'numerical', 'integerOnly'=>true),
			array('tyyppi, yritys, nimi, sahkoposti, v_tunnus, yhteyshenkilo, nimitarkenne, t_yritys, t_nimi, t_osoite, t_toimipaikka, t_sahkoposti, toimitusosoite, viitenumero, saaja_iban, maksettu_euro, laskun_nimetys, postita_jobid, trust_jobid', 'length', 'max'=>100),
			array('vatperiod, y_tunnus, toimipaikka, laskutus, puhelin, t_y_tunnus, t_puhelin, viivastyskorko, tilanne, tapahtumapvm', 'length', 'max'=>50),
			array('deliverymethod, deliveryterm, freetext, viitenne, viitemme, osoite, verkkolaskuosoite, saaja_virtualkoodi', 'length', 'max'=>255),
			array('postinumero, t_postinumero', 'length', 'max'=>10),
			array('paivays, erapaiva, toimituspaiva, maksuehto, yhteensa_total_verot, yhteensa_total_veroton, yhteensa_total, hyvityslasku', 'length', 'max'=>20),
			array('response, response_finvoice', 'length', 'max'=>5000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lid, yid, time, tyyppi, yritys, y_tunnus, nimi, as_nro, osoite, postinumero, toimipaikka, laskutus, sahkoposti, verkkolaskuosoite, v_tunnus, yhteyshenkilo, nimitarkenne, puhelin, t_yritys, t_y_tunnus, t_nimi, t_osoite, t_postinumero, t_toimipaikka, t_puhelin, t_sahkoposti, toimitusosoite, paivays, erapaiva, toimituspaiva, maksuehto, viitenumero, viivastyskorko, yhteensa_total_verot, yhteensa_total_veroton, yhteensa_total, saaja_iban, saaja_virtualkoodi, tilanne, maksettu_euro, hyvityslasku, laskun_nimetys, response, response_finvoice, tapahtumapvm, kirjeenluokka, muistutuslasku_auto, freetext, viitenne, viitemme, vatperiod', 'safe', 'on'=>'search'),
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
			'laskunumero' => Yii::t('main', 'Laskunumero'),
			'lid' => Yii::t('main', 'Lid'),
			'yid' => Yii::t('main', 'Saaja'),
			'time' => Yii::t('main', 'Luotu'),
			'tyyppi' => Yii::t('main', 'Tyyppi'),
			'yritys' => Yii::t('main', 'Yritys'),
			'y_tunnus' => Yii::t('main', 'Y-tunnus'),
			'nimi' => Yii::t('main', 'Nimi'),
			'as_nro' => Yii::t('main', 'Asiakas'),
			'osoite' => Yii::t('main', 'Osoite'),
			'postinumero' => Yii::t('main', 'Postinumero'),
			'toimipaikka' => Yii::t('main', 'Toimipaikka'),
			'laskutus' => Yii::t('main', 'Laskutus'),
			'sahkoposti' => Yii::t('main', 'Sahkoposti'),
			'verkkolaskuosoite' => Yii::t('main', 'Verkkolaskuosoite'),
			'v_tunnus' => Yii::t('main', 'Välittäjän tunnus'),
			'yhteyshenkilo' => Yii::t('main', 'Yhteyshenkilö'),
			'nimitarkenne' => Yii::t('main', 'Nimitarkenne'),
			'puhelin' => Yii::t('main', 'Puhelin'),
			't_yritys' => Yii::t('main', 'Toimitus Yritys'),
			't_y_tunnus' => Yii::t('main', 'Toimitus Y-tunnus'),
			't_nimi' => Yii::t('main', 'Toimitus Nimi'),
			't_osoite' => Yii::t('main', 'Toimitus Osoite'),
			't_postinumero' => Yii::t('main', 'Toimitus Postinumero'),
			't_toimipaikka' => Yii::t('main', 'Toimitus Toimipaikka'),
			't_puhelin' => Yii::t('main', 'Toimitus Puhelin'),
			't_sahkoposti' => Yii::t('main', 'Toimitus Sähköposti'),
			'toimitusosoite' => Yii::t('main', 'Toimitusosoite on eri kuin laskutusosoite'),
			'paivays' => Yii::t('main', 'Päiväys'),
			'erapaiva' => Yii::t('main', 'Eräpäivä'),
			'toimituspaiva' => Yii::t('main', 'Toimituspäivä'),
			'maksuehto' => Yii::t('main', 'Maksuehto'),
			'viitenumero' => Yii::t('main', 'Viitenumero'),
			'viivastyskorko' => Yii::t('main', 'Viivästyskorko'),
			'yhteensa_total_verot' => Yii::t('main', 'Yhteensa Total Verot'),
			'yhteensa_total_veroton' => Yii::t('main', 'Yhteensa Total Veroton'),
			'yhteensa_total' => Yii::t('main', 'Yhteensä'),
			'saaja_iban' => Yii::t('main', 'Saaja IBAN'),
			'saaja_virtualkoodi' => Yii::t('main', 'Saaja Virtualkoodi'),
			'tilanne' => Yii::t('main', 'Tilanne'),
			'maksettu_euro' => Yii::t('main', 'Maksettu Euro'),
			'hyvityslasku' => Yii::t('main', 'Hyvityslasku'),
			'laskun_nimetys' => Yii::t('main', 'Laskun tyyppi'),
			'response' => Yii::t('main', 'Response'),
			'response_finvoice' => Yii::t('main', 'Response Finvoice'),
			'tapahtumapvm' => Yii::t('main', 'Tapahtuma pvm'),
			'muistutuslasku_auto'=> Yii::t('main', 'Automaattinen muistutuslasku'),
			'kirjeenluokka'=> Yii::t('main', 'Kirjeluokka'),
			'viitenne'=> Yii::t('main', 'Viitteenne'),
			'viitemme'=> Yii::t('main', 'Viitteemme'),
			'freetext'=> Yii::t('main', 'Viesti'),
			'deliverymethod'=> Yii::t('main', 'Toimitustapa'),
			'deliveryterm'=> Yii::t('main', 'Toimitusehto'),
			'vatperiod'=> Yii::t('main', 'Päivämäärä johon ALV kohdistuu'),
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
		$criteria->order = "id DESC";

		$criteria->compare('id',$this->id);
		$criteria->compare('laskunumero',$this->laskunumero);
		$criteria->compare('lid',$this->lid);
		$criteria->compare('yid',$this->yid);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('tyyppi',$this->tyyppi,true);
		$criteria->compare('yritys',$this->yritys,true);
		$criteria->compare('y_tunnus',$this->y_tunnus,true);
		$criteria->compare('nimi',$this->nimi,true);
		$criteria->compare('as_nro',$this->as_nro);
		$criteria->compare('osoite',$this->osoite,true);
		$criteria->compare('postinumero',$this->postinumero,true);
		$criteria->compare('toimipaikka',$this->toimipaikka,true);
		$criteria->compare('laskutus',$this->laskutus,true);
		$criteria->compare('sahkoposti',$this->sahkoposti,true);
		$criteria->compare('verkkolaskuosoite',$this->verkkolaskuosoite,true);
		$criteria->compare('v_tunnus',$this->v_tunnus,true);
		$criteria->compare('yhteyshenkilo',$this->yhteyshenkilo,true);
		$criteria->compare('nimitarkenne',$this->nimitarkenne,true);
		$criteria->compare('puhelin',$this->puhelin,true);
		$criteria->compare('t_yritys',$this->t_yritys,true);
		$criteria->compare('t_y_tunnus',$this->t_y_tunnus,true);
		$criteria->compare('t_nimi',$this->t_nimi,true);
		$criteria->compare('t_osoite',$this->t_osoite,true);
		$criteria->compare('t_postinumero',$this->t_postinumero,true);
		$criteria->compare('t_toimipaikka',$this->t_toimipaikka,true);
		$criteria->compare('t_puhelin',$this->t_puhelin,true);
		$criteria->compare('t_sahkoposti',$this->t_sahkoposti,true);
		$criteria->compare('toimitusosoite',$this->toimitusosoite,true);
		$criteria->compare('paivays',$this->paivays,true);
		$criteria->compare('erapaiva',$this->erapaiva,true);
		$criteria->compare('toimituspaiva',$this->toimituspaiva,true);
		$criteria->compare('maksuehto',$this->maksuehto,true);
		$criteria->compare('viitenumero',$this->viitenumero,true);
		$criteria->compare('viivastyskorko',$this->viivastyskorko,true);
		$criteria->compare('yhteensa_total_verot',$this->yhteensa_total_verot,true);
		$criteria->compare('yhteensa_total_veroton',$this->yhteensa_total_veroton,true);
		$criteria->compare('yhteensa_total',$this->yhteensa_total,true);
		$criteria->compare('saaja_iban',$this->saaja_iban,true);
		$criteria->compare('saaja_virtualkoodi',$this->saaja_virtualkoodi,true);
		$criteria->compare('tilanne',$this->tilanne,true);
		$criteria->compare('maksettu_euro',$this->maksettu_euro,true);
		$criteria->compare('hyvityslasku',$this->hyvityslasku,true);
		$criteria->compare('laskun_nimetys',$this->laskun_nimetys,true);
		$criteria->compare('response',$this->response,true);
		$criteria->compare('response_finvoice',$this->response_finvoice,true);
		$criteria->compare('tapahtumapvm',$this->tapahtumapvm,true);
		$criteria->compare('muistutuslasku_auto',$this->muistutuslasku_auto,true);
		$criteria->compare('kirjeenluokka',$this->kirjeenluokka,true);
		$criteria->compare('viitenne',$this->viitenne,true);
		$criteria->compare('viitemme',$this->viitemme,true);
		$criteria->compare('freetext',$this->freetext,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}





}
