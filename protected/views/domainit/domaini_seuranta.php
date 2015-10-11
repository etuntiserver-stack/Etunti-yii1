<?php

//Yii::app()->db->createCommand('CREATE DATABASE tutu')->query();
//Yii::app()->db1->createCommand('SELECT * FROM tutu')->query(); //test

  if( $_SERVER['REMOTE_ADDR'] == '::1' or $_SERVER['REMOTE_ADDR'] == '127.0.0.1' )
    $pref = '';
  else
    $pref = 'estromfi_';

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

    echo '<select class="form-control input-sm form-group" name="dom">';

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

<?php if(!empty($domain)) {


    Yii::app()->db1->setActive(false);
    Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname='.$pref.$domain;
    if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
    {
      Yii::app()->db1->username = $pref.$domain;
      Yii::app()->db1->password = 'KristinA1';
    }
    Yii::app()->db1->setActive(true);


    $this->renderPartial('seuranta',array('domain'=>$domain));

} ?>





