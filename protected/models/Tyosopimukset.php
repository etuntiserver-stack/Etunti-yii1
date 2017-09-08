<?php

/**
 * This is the model class for table "sivex_tyosopimukset".
 *
 * The followings are the available columns in table 'sivex_tyosopimukset':
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
 * @property string $sopimus
 * @property string $ToistaVoimaSopimus
 * @property string $MaaraVoimaSopimusAlkaa
 * @property string $MaaraVoimaSopimusPaattyy
 * @property string $peruste
 * @property string $koeaika
 * @property string $SoveltavaSopimus
 * @property string $Tyotehtavat
 * @property string $tyonSuorittamisPaikka
 * @property string $PalkanMaaraytymisperuste
 * @property string $PalkanMaaraytymisperusteMuu
 * @property string $TyokokemusVuotta
 * @property string $TyokokemusKuu
 * @property string $palkka_kk
 * @property string $Palkkaluokka
 * @property string $palkka_h
 * @property string $Luontaiseudut
 * @property string $Raha_arvo
 * @property string $Verotusarvo
 * @property string $palkka_muu2
 * @property string $Palkanmaksukausi
 * @property string $Palkanmaksupaivat
 * @property string $Palkka_tilille
 * @property string $tyoaika_hvrk
 * @property string $tyoaika_hvko
 * @property string $tyoaika_h_jakso
 * @property string $tyoaika_vko_jaksossa
 * @property string $RuokataukonPituus
 * @property string $Muu_tyoaika
 * @property string $lomasta_sovittu
 * @property string $Salassapito
 * @property string $IrtisanomisaikaM
 * @property string $Muut_sopimusehdot
 * @property string $Muutospaiva
 * @property string $LisayksetSopimukseen
 * @property string $Paivays
 * @property string $Paikka
 * @property string $TyonantajanEdustaja
 * @property string $NimikeTehtava
 */
class Tyosopimukset extends DB2ActiveRecord
{

public $template;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		$tb_name = 'sivex_tyosopimukset';
		$check_this_table = false;
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

                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP ',
                     'key' => 'int(1) DEFAULT 1 ',
                     'tyonantaja' => 'varchar(70) ',
                     'osoite' => 'varchar(255) ',
                     'postinumero' => 'varchar(7) ',
                     'postitoimipaikka' => 'varchar(100) ',
                     'puhelin' => 'varchar(50) ',
                     'y_tunnus' => 'varchar(50) ',
                     'sahkoposti' => 'varchar(100) ',
                     'tekijan_email' => 'varchar(100) ',
                     'tid' => 'int(7) ',
                     'tekijan_nimi' => 'varchar(70) ',
                     'tekijan_katuosoite' => 'varchar(100) ',
                     'tekijan_pnumero' => 'varchar(7) ',
                     'tekijan_ptoimipaikka' => 'varchar(50) ',
                     'tekijan_puh' => 'varchar(50) ',
                     'tekijan_henkilotunnus' => 'varchar(50) ',
                     'sopimus' => 'varchar(50) ',
                     'ToistaVoimaSopimus' => 'varchar(100) ',
                     'MaaraVoimaSopimusAlkaa' => 'varchar(100) ',
                     'MaaraVoimaSopimusPaattyy' => 'varchar(100) ',
                     'peruste' => 'text ',
                     'koeaika' => 'varchar(100) ',
                     'SoveltavaSopimus' => 'varchar(100) ',
                     'Tyotehtavat' => 'text ',
                     'tyonSuorittamisPaikka' => 'text ',
                     'PalkanMaaraytymisperuste' => 'varchar(50) ',
                     'PalkanMaaraytymisperusteMuu' => 'varchar(100) ',
                     'TyokokemusVuotta' => 'varchar(20) ',
                     'TyokokemusKuu' => 'varchar(20) ',
                     'palkka_kk' => 'varchar(20) ',
                     'Palkkaluokka' => 'varchar(50) ',
                     'palkka_h' => 'varchar(20) ',
                     'Luontaiseudut' => 'text ',
                     'Raha_arvo' => 'varchar(70) ',
                     'Verotusarvo' => 'varchar(70) ',
                     'palkka_muu2' => 'varchar(70) ',
                     'Palkanmaksukausi' => 'varchar(50) ',
                     'Palkanmaksupaivat' => 'varchar(50) ',
                     'Palkka_tilille' => 'varchar(100) ',
                     'tyoaika_hvrk' => 'varchar(50) ',
                     'tyoaika_hvko' => 'varchar(50) ',
                     'tyoaika_h_jakso' => 'varchar(50) ',
                     'tyoaika_vko_jaksossa' => 'varchar(50) ',
                     'RuokataukonPituus' => 'varchar(50) ',
                     'Muu_tyoaika' => 'text ',
                     'lomasta_sovittu' => 'text ',
                     'Salassapito' => 'text ',
                     'IrtisanomisaikaM' => 'varchar(50) ',
                     'Muut_sopimusehdot' => 'text ',
                     'Muutospaiva' => 'varchar(50) ',
                     'LisayksetSopimukseen' => 'text ',
                     'Paivays' => 'varchar(50) ',
                     'Paikka' => 'varchar(100) ',
                     'TyonantajanEdustaja' => 'varchar(100) ',
                     'NimikeTehtava' => 'varchar(100) ',
                     'tiedosto' => 'varchar(255) ',
                     'teksti' => 'text ',




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
/*
			array('tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, sopimus, ToistaVoimaSopimus, MaaraVoimaSopimusAlkaa, MaaraVoimaSopimusPaattyy, peruste, koeaika, SoveltavaSopimus, Tyotehtavat, tyonSuorittamisPaikka, PalkanMaaraytymisperuste, PalkanMaaraytymisperusteMuu, TyokokemusVuotta, TyokokemusKuu, palkka_kk, Palkkaluokka, palkka_h, Luontaiseudut, Raha_arvo, Verotusarvo, palkka_muu2, Palkanmaksukausi, Palkanmaksupaivat, Palkka_tilille, tyoaika_hvrk, tyoaika_hvko, tyoaika_h_jakso, tyoaika_vko_jaksossa, RuokataukonPituus, Muu_tyoaika, lomasta_sovittu, Salassapito, IrtisanomisaikaM, Muut_sopimusehdot, Muutospaiva, LisayksetSopimukseen, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava', 'required'),
*/
			array('key, tid', 'numerical', 'integerOnly'=>true),
			array('tyonantaja, tekijan_nimi, Raha_arvo, Verotusarvo, palkka_muu2', 'length', 'max'=>70),
			array('osoite, tiedosto', 'length', 'max'=>255),
			array('teksti, Muu_tyoaika, lomasta_sovittu, Muut_sopimusehdot, LisayksetSopimukseen, Salassapito, Luontaiseudut, Tyotehtavat, tyonSuorittamisPaikka, peruste', 'length', 'max'=>3000),
			array('postinumero, tekijan_pnumero', 'length', 'max'=>7),
			array('postitoimipaikka, sahkoposti, tekijan_email, tekijan_katuosoite, ToistaVoimaSopimus, MaaraVoimaSopimusAlkaa, MaaraVoimaSopimusPaattyy, koeaika, SoveltavaSopimus, PalkanMaaraytymisperusteMuu, Palkka_tilille, Paikka, TyonantajanEdustaja, NimikeTehtava', 'length', 'max'=>100),
			array('puhelin, y_tunnus, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, sopimus, PalkanMaaraytymisperuste, Palkkaluokka, Palkanmaksukausi, Palkanmaksupaivat, tyoaika_hvrk, tyoaika_hvko, tyoaika_h_jakso, tyoaika_vko_jaksossa, RuokataukonPituus, IrtisanomisaikaM, Muutospaiva, Paivays', 'length', 'max'=>50),
			array('TyokokemusVuotta, TyokokemusKuu, palkka_kk, palkka_h', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time, key, tyonantaja, osoite, postinumero, postitoimipaikka, puhelin, y_tunnus, sahkoposti, tekijan_email, tid, tekijan_nimi, tekijan_katuosoite, tekijan_pnumero, tekijan_ptoimipaikka, tekijan_puh, tekijan_henkilotunnus, sopimus, ToistaVoimaSopimus, MaaraVoimaSopimusAlkaa, MaaraVoimaSopimusPaattyy, peruste, koeaika, SoveltavaSopimus, Tyotehtavat, tyonSuorittamisPaikka, PalkanMaaraytymisperuste, PalkanMaaraytymisperusteMuu, TyokokemusVuotta, TyokokemusKuu, palkka_kk, Palkkaluokka, palkka_h, Luontaiseudut, Raha_arvo, Verotusarvo, palkka_muu2, Palkanmaksukausi, Palkanmaksupaivat, Palkka_tilille, tyoaika_hvrk, tyoaika_hvko, tyoaika_h_jakso, tyoaika_vko_jaksossa, RuokataukonPituus, Muu_tyoaika, lomasta_sovittu, Salassapito, IrtisanomisaikaM, Muut_sopimusehdot, Muutospaiva, LisayksetSopimukseen, Paivays, Paikka, TyonantajanEdustaja, NimikeTehtava', 'safe', 'on'=>'search'),
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
			'sopimus' => 'Sopimus',
			'ToistaVoimaSopimus' => 'Aloitus',
			'MaaraVoimaSopimusAlkaa' => 'Aloitus',
			'MaaraVoimaSopimusPaattyy' => 'Lopetus',
			'peruste' => 'Peruste',
			'koeaika' => 'Koeaika kk',
			'SoveltavaSopimus' => 'Sovellettava työehtosopimus',
			'Tyotehtavat' => 'Työtehtävät',
			'tyonSuorittamisPaikka' => 'Työn suorittamispaikka / työkohde',
			'PalkanMaaraytymisperuste' => 'Palkan Maaraytymisperuste',
			'PalkanMaaraytymisperusteMuu' => 'Palkan Maaraytymisperuste Muu',
			'TyokokemusVuotta' => 'Tyokokemus Vuotta',
			'TyokokemusKuu' => 'Tyokokemus Kuu',
			'palkka_kk' => 'Palkka Kk',
			'Palkkaluokka' => 'Palkkaluokka',
			'palkka_h' => 'Palkka H',
			'Luontaiseudut' => 'Luontaiseudut',
			'Raha_arvo' => 'Raha Arvo',
			'Verotusarvo' => 'Verotusarvo',
			'palkka_muu2' => 'Palkka Muu2',
			'Palkanmaksukausi' => 'Palkanmaksukausi',
			'Palkanmaksupaivat' => 'Palkanmaksupäivät',
			'Palkka_tilille' => 'Palkka maksetaan tilille',
			'tyoaika_hvrk' => 'h/vrk',
			'tyoaika_hvko' => 'h/vko',
			'tyoaika_h_jakso' => 'h/jakso',
			'tyoaika_vko_jaksossa' => 'vko:n jaksoissa',
			'RuokataukonPituus' => 'Ruokataukon pituus (min)',
			'Muu_tyoaika' => 'Muu työaika',
			'lomasta_sovittu' => 'Vuosilomasta lisäksi sovittu',
			'Salassapito' => 'Salassapito',
			'IrtisanomisaikaM' => 'Irtisanomisaika M',

			'Muut_sopimusehdot' => 'Muut Sopimusehdot',
			'Muutospaiva' => 'Muutospäivä',
			'LisayksetSopimukseen' => 'Lisäykset työsopimukseen',
			'Paivays' => 'Päiväys',
			'Paikka' => 'Paikka',
			'TyonantajanEdustaja' => 'Työnantajan edustaja',
			'NimikeTehtava' => 'Nimike/Tehtävä',
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
		$criteria->compare('sopimus',$this->sopimus,true);
		$criteria->compare('ToistaVoimaSopimus',$this->ToistaVoimaSopimus,true);
		$criteria->compare('MaaraVoimaSopimusAlkaa',$this->MaaraVoimaSopimusAlkaa,true);
		$criteria->compare('MaaraVoimaSopimusPaattyy',$this->MaaraVoimaSopimusPaattyy,true);
		$criteria->compare('peruste',$this->peruste,true);
		$criteria->compare('koeaika',$this->koeaika,true);
		$criteria->compare('SoveltavaSopimus',$this->SoveltavaSopimus,true);
		$criteria->compare('Tyotehtavat',$this->Tyotehtavat,true);
		$criteria->compare('tyonSuorittamisPaikka',$this->tyonSuorittamisPaikka,true);
		$criteria->compare('PalkanMaaraytymisperuste',$this->PalkanMaaraytymisperuste,true);
		$criteria->compare('PalkanMaaraytymisperusteMuu',$this->PalkanMaaraytymisperusteMuu,true);
		$criteria->compare('TyokokemusVuotta',$this->TyokokemusVuotta,true);
		$criteria->compare('TyokokemusKuu',$this->TyokokemusKuu,true);
		$criteria->compare('palkka_kk',$this->palkka_kk,true);
		$criteria->compare('Palkkaluokka',$this->Palkkaluokka,true);
		$criteria->compare('palkka_h',$this->palkka_h,true);
		$criteria->compare('Luontaiseudut',$this->Luontaiseudut,true);
		$criteria->compare('Raha_arvo',$this->Raha_arvo,true);
		$criteria->compare('Verotusarvo',$this->Verotusarvo,true);
		$criteria->compare('palkka_muu2',$this->palkka_muu2,true);
		$criteria->compare('Palkanmaksukausi',$this->Palkanmaksukausi,true);
		$criteria->compare('Palkanmaksupaivat',$this->Palkanmaksupaivat,true);
		$criteria->compare('Palkka_tilille',$this->Palkka_tilille,true);
		$criteria->compare('tyoaika_hvrk',$this->tyoaika_hvrk,true);
		$criteria->compare('tyoaika_hvko',$this->tyoaika_hvko,true);
		$criteria->compare('tyoaika_h_jakso',$this->tyoaika_h_jakso,true);
		$criteria->compare('tyoaika_vko_jaksossa',$this->tyoaika_vko_jaksossa,true);
		$criteria->compare('RuokataukonPituus',$this->RuokataukonPituus,true);
		$criteria->compare('Muu_tyoaika',$this->Muu_tyoaika,true);
		$criteria->compare('lomasta_sovittu',$this->lomasta_sovittu,true);
		$criteria->compare('Salassapito',$this->Salassapito,true);
		$criteria->compare('IrtisanomisaikaM',$this->IrtisanomisaikaM,true);
		$criteria->compare('Muut_sopimusehdot',$this->Muut_sopimusehdot,true);
		$criteria->compare('Muutospaiva',$this->Muutospaiva,true);
		$criteria->compare('LisayksetSopimukseen',$this->LisayksetSopimukseen,true);
		$criteria->compare('Paivays',$this->Paivays,true);
		$criteria->compare('Paikka',$this->Paikka,true);
		$criteria->compare('TyonantajanEdustaja',$this->TyonantajanEdustaja,true);
		$criteria->compare('NimikeTehtava',$this->NimikeTehtava,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tyosopimukset the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
