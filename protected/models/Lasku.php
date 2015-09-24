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
			array('tyyppi, nimi, as_nro, osoite, postinumero, toimipaikka, laskutus, paivays, erapaiva, toimituspaiva, maksuehto, viitenumero, viivastyskorko, toimitusosoite', 'required'),
			array('lid, yid, as_nro', 'numerical', 'integerOnly'=>true),
			array('tyyppi, yritys, nimi, sahkoposti, v_tunnus, yhteyshenkilo, nimitarkenne, t_yritys, t_nimi, t_osoite, t_toimipaikka, t_sahkoposti, toimitusosoite, viitenumero, saaja_iban, maksettu_euro, laskun_nimetys', 'length', 'max'=>100),
			array('y_tunnus, toimipaikka, laskutus, puhelin, t_y_tunnus, t_puhelin, viivastyskorko, tilanne', 'length', 'max'=>50),
			array('osoite, verkkolaskuosoite, saaja_virtualkoodi', 'length', 'max'=>255),
			array('postinumero, t_postinumero', 'length', 'max'=>10),
			array('paivays, erapaiva, toimituspaiva, maksuehto, yhteensa_total_verot, yhteensa_total_veroton, yhteensa_total, hyvityslasku', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lid, yid, time, tyyppi, yritys, y_tunnus, nimi, as_nro, osoite, postinumero, toimipaikka, laskutus, sahkoposti, verkkolaskuosoite, v_tunnus, yhteyshenkilo, nimitarkenne, puhelin, t_yritys, t_y_tunnus, t_nimi, t_osoite, t_postinumero, t_toimipaikka, t_puhelin, t_sahkoposti, toimitusosoite, paivays, erapaiva, toimituspaiva, maksuehto, viitenumero, viivastyskorko, yhteensa_total_verot, yhteensa_total_veroton, yhteensa_total, saaja_iban, saaja_virtualkoodi, tilanne, maksettu_euro, hyvityslasku, laskun_nimetys', 'safe', 'on'=>'search'),
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
			'lid' => 'Lid',
			'yid' => 'Saaja',
			'time' => 'Time',
			'tyyppi' => 'Tyyppi',
			'yritys' => 'Yritys',
			'y_tunnus' => 'Y Tunnus',
			'nimi' => 'Nimi',
			'as_nro' => 'Asiakas',
			'osoite' => 'Osoite',
			'postinumero' => 'Postinumero',
			'toimipaikka' => 'Toimipaikka',
			'laskutus' => 'Laskutus',
			'sahkoposti' => 'Sahkoposti',
			'verkkolaskuosoite' => 'Verkkolaskuosoite',
			'v_tunnus' => 'Välittäjän tunnus',
			'yhteyshenkilo' => 'Yhteyshenkilo',
			'nimitarkenne' => 'Nimitarkenne',
			'puhelin' => 'Puhelin',
			't_yritys' => 'Toimitus Yritys',
			't_y_tunnus' => 'Toimitus Y Tunnus',
			't_nimi' => 'Toimitus Nimi',
			't_osoite' => 'Toimitus Osoite',
			't_postinumero' => 'Toimitus Postinumero',
			't_toimipaikka' => 'Toimitus Toimipaikka',
			't_puhelin' => 'Toimitus Puhelin',
			't_sahkoposti' => 'Toimitus Sahkoposti',
			'toimitusosoite' => 'Toimitusosoite on eri kuin laskutusosoite',
			'paivays' => 'Paivays',
			'erapaiva' => 'Erapaiva',
			'toimituspaiva' => 'Toimituspaiva',
			'maksuehto' => 'Maksuehto',
			'viitenumero' => 'Viitenumero',
			'viivastyskorko' => 'Viivastyskorko',
			'yhteensa_total_verot' => 'Yhteensa Total Verot',
			'yhteensa_total_veroton' => 'Yhteensa Total Veroton',
			'yhteensa_total' => 'Yhteensa Total',
			'saaja_iban' => 'Saaja Iban',
			'saaja_virtualkoodi' => 'Saaja Virtualkoodi',
			'tilanne' => 'Tilanne',
			'maksettu_euro' => 'Maksettu Euro',
			'hyvityslasku' => 'Hyvityslasku',
			'laskun_nimetys' => 'Laskun Nimetys',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
