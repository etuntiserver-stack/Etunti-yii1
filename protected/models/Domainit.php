<?php

/**
 * This is the model class for table "domainit".
 *
 * The followings are the available columns in table 'domainit':
 * @property integer $id
 * @property string $domain
 * @property integer $paketti
 */
class Domainit extends CActiveRecord
{

public $viesti;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Domainit the static model class
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
		return 'domainit';
	}


	public static function PushNotify($tid,$title,$message,$sound){

		$t = Tyontekijat::model()->findbypk($tid); 
		$a = AsetuksetForAll::model()->find(" asetus='asetus1' ");
		
		//define( 'API_ACCESS_KEY', $a->api_access_key );
		$registrationIds = array( $t->gcm_reg_id );
		// prep the bundle
		$msg = array
		(
			'message' 	=> $message,
			'title'		=> $title,
			'subtitle'	=> 'This is a subtitle. subtitle',
			'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
			'vibrate'	=> 1,
			'sound'		=> $sound, // viella on "danger"
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);
		$fields = array
		(
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		 
		$headers = array
		(
			'Authorization: key=' . $a->api_access_key,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );

		$result = json_decode($result);
		//var_dump($result->success);

		return $result;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('domain', 'required'),
			array('huoltokatko, palveluhinta_persiivoja, tyovuorohinta_persiivoja, muut_tyokaluhinta', 'numerical', 'integerOnly'=>true),
			array('domain, paketti, yritys, pakettin_nimetus', 'length', 'max'=>100),
			array('puhelin, sahkoposti', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, domain, paketti, yritys, pakettin_nimetus', 'safe', 'on'=>'search'),
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
			'domain' => Yii::t('main', 'Domain'),
			'paketti' => Yii::t('main', 'Tasot'),
			'yritys' => Yii::t('main', 'Yritys'),
			'puhelin' => Yii::t('main', 'Puhelin'),
			'sahkoposti' => Yii::t('main', 'Sähköposti'),
			'pakettin_nimetus' => Yii::t('main', 'Pakettin nimetus'),
			'palveluhinta_persiivoja'=> Yii::t('main', 'Palveluhinta per siivoja'),
			'tyovuorohinta_persiivoja'=> Yii::t('main', 'Työvuorot hinta per siivoja'),
			'muut_tyokaluhinta'=> Yii::t('main', 'Muut työkalut hinta'),
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
		$criteria->compare('yritys',$this->yritys);
		$criteria->compare('pakettin_nimetus',$this->pakettin_nimetus);
		$criteria->compare('domain',$this->domain,true);
		$criteria->compare('paketti',$this->paketti);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
