<?php

//Yii::app()->db->createCommand('CREATE DATABASE tutu')->query();
//Yii::app()->db1->createCommand('SELECT * FROM tutu')->query(); //test

  if( $_SERVER['REMOTE_ADDR'] == '::1' or $_SERVER['REMOTE_ADDR'] == '127.0.0.1' )
    $pref = '';
  else
    $pref = '';

$domain = '';

if(isset($_POST['domain']))
$domain = $_POST['domain'];

if(isset($_GET['domain']))
$domain = $_GET['domain'];
?>

<legend>
  <h1> 
	<?php echo Yii::t('main', 'SEURANTA'); ?>
  </h1>
</legend>


<div class="row">
  <div class="col-sm-3">
    <form action="#" method="POST" class="form-inline">
<?php

       	$criteria = new CDbCriteria();
	//$criteria->select = " ";
	$criteria->order = " domain ";
	$criteria->condition = " domain!='defdb' ";
    	$list = Domainit::model()->findAll($criteria);

    echo '<select class="form-control input-sm form-group" name="domain">';

	if(!empty($domain))
       	echo '<option value="'.$domain.'">'.$domain.'</option>';
	else
       	echo '<option value="">'.Yii::t('main', 'Valitse domaini').'</option>';

    foreach($list as $val){
    echo '<option value="'.$val->domain.'">'.$val->domain.'</option>';
    }
    echo '</select>';
?>
    <input type="submit" class="btn btn-sm btn-primary">
    </form>

  </div>
</div>


<br>

<?php
$db_host = 'localhost';
$site = Yii::app()->createController('Site');
$conn = $site[0]->dbConnectArr();
if( isset($conn['host']) )
	$db_host = $conn['host'];
try {
	$mysqli = new mysqli($conn['host'], $conn['username'], $conn['password']);
} catch (\Exception $e) {
	echo $e->getMessage(), PHP_EOL;
	exit;
}
if(!empty($domain)) {

    Yii::app()->db1->setActive(false);
    Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $domain;
    Yii::app()->db1->setActive(true);


    $this->renderPartial('seuranta',array('domain'=>$domain));

}
?>





