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

	// defining these as a string, since LoginController writes this data as
	// a string to the user state.
	const LEVEL_MOBILE = "1";
	const LEVEL_ANNUAL_LEAVES = "2";
	const LEVEL_BILLING = "3";
	const LEVEL_ONLINEVARAUS = "4";
	const LEVEL_EDICO = "5";
	const LEVEL_MANAGEMENT = "6";
	const LEVEL_DIGISTEN = "999";

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
		$tb_name = 'domainit';

		$check_this_table = true;
		if(!isset(Yii::app()->session[$tb_name]))
		{
			Yii::app()->session[$tb_name] = true;
			$check_this_table = true;
		}

		if($check_this_table)
		{

		$table = Yii::app()->db->schema->getTable($tb_name);
		if(!isset($table->columns['id'])) {

			Yii::app()->db->createCommand(" CREATE TABLE IF NOT EXISTS $tb_name 
			(`id` int(11) AUTO_INCREMENT PRIMARY KEY)
			")->execute();
		}

		$table_structure = array(
                     'time' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
                     'domain' => 'varchar(100) DEFAULT NULL',
                     'paketti' => 'varchar(100) DEFAULT NULL',
                     'yritys' => 'varchar(100) DEFAULT NULL',
                     'puhelin' => 'varchar(255) DEFAULT NULL',
                     'sahkoposti' => 'varchar(255) DEFAULT NULL',
                     'pakettin_nimetus' => 'varchar(100) DEFAULT NULL',
                     'huoltokatko' => 'int(1) DEFAULT 0',
                     'palveluhinta_persiivoja' => 'int(11) DEFAULT 0',
                     'tyovuorohinta_persiivoja' => 'int(11) DEFAULT 0',
                     'muut_tyokaluhinta' => 'int(11) DEFAULT 0',
                     'aktiivinen' => 'int(1) DEFAULT 1',
                     'maksullinen' => 'int(1) DEFAULT 0',
                     'ilmainen_versio_kayttotunnit' => 'int(11) DEFAULT 0',
                     'kirjautumistunnus' => 'varchar(100) DEFAULT NULL',
		     'y_tunnus' => 'varchar(100) DEFAULT NULL',
                );

		foreach($table_structure as $key=>$value)
		{
			if (!isset($table->columns[$key])) {
				Yii::app()->db->createCommand()->addColumn($tb_name, $key, $value);
			}
		}	

		} // if($check_this_table)

		return $tb_name;
	}

/*
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
*/

	public static function sendGCM($tid, $subject, $message, $sound) 
	{
		$t = Tyontekijat::model()->findbypk($tid);
		$a = FirmanTiedot::model()->findbypk(1);
		$ApiKey = 'AAAANdUAxyU:APA91bFuKiRWqW-EyzEHr_JuPfnEfJRf6vx8tuxjt8Wn1E3B_OOH9CYENG5jEb7yHcDfpVI3d8KjTuTuOCW6YmahViMivFzGDy8shvhKW5cMKWP1Lu28ajDQQte9Ue6ogqYQKuowskyGzptOX69WEiZnfl8N1lQB1g';

		if(!isset($t->gcm_reg_id) or empty($t->gcm_reg_id))
		{
			//echo 'Push nitification error';
			return false;
		}
	
		$json_data = '{ 
			"data": { 
			  "Viesti": "'.$message.'"
	                },
	                "notification": {
	                  "title": "'.$a->tyonantaja.': '.$subject.'",
	                  "body": "'.$message.'",
	                  "sound": "default",
	                  "click_action": "FCM_PLUGIN_ACTIVITY",
	                  "icon": "icon_name"
	                },
	                "to": "'.$t->gcm_reg_id.'",
	                "priority": "high"
	              }';

		/*
		"data": { 
  	                  "price": "0",
	                  "currency": "EUR" 
	                },
		"to": "/topics/all",
		*/
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                                            'Content-Type: application/json',                                                                                
                                            'Content-Length: '.strlen($json_data),
                                            'Authorization:key='.$ApiKey  
                                          ));           
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec($ch);
		curl_close($ch);
		//echo $output;
		//exit;
	}

	public static function sendGCMeDico($aid, $subject, $message, $sound) 
	{
		$t = Asiakkaat::model()->findbypk($aid);
		$a = FirmanTiedot::model()->findbypk(1);
		$ApiKey = 'AAAAdW_OCt8:APA91bHW3yItK76c9kcziRUWpFdgDH2VE8cnuaPK0OhD7Aa0KrxsL94mTKbe4MFQVX0MroDk0oT5Qy9XQSfDmjezyuOe0ToiHFUKM0l7dpdWy1zithlV_5NARTwLGJMRmsqc9bpjm4Ps';

		if(!isset($t->gcm_reg_id) or empty($t->gcm_reg_id))
		{
			//echo 'Push nitification error';
			return false;
		}
	
		$json_data = '{ 
			"data": { 
			  "Viesti": "'.$message.'"
	                },
	                "notification": {
	                  "title": "'.$subject.'",
	                  "body": "'.$message.'",
	                  "sound": "default",
	                  "click_action": "FCM_PLUGIN_ACTIVITY",
	                  "icon": "icon_name"
	                },
	                "to": "'.$t->gcm_reg_id.'",
	                "priority": "high"
	              }';


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                                            'Content-Type: application/json',                                                                                
                                            'Content-Length: '.strlen($json_data),
                                            'Authorization:key='.$ApiKey  
                                          ));           
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec($ch);
		curl_close($ch);
		//echo $output;
		//exit;
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
			array('huoltokatko, palveluhinta_persiivoja, tyovuorohinta_persiivoja, muut_tyokaluhinta, aktiivinen, maksullinen', 'numerical', 'integerOnly'=>true),
			array('time, domain, paketti, yritys, pakettin_nimetus, y_tunnus', 'length', 'max'=>100),
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
			'time' => Yii::t('main', 'Perustettu'),
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
