<?php

//Yii::app()->db->createCommand('CREATE DATABASE tutu')->query();
//Yii::app()->db1->createCommand('SELECT * FROM tutu')->query(); //test

  if( $_SERVER['REMOTE_ADDR'] == '::1' or $_SERVER['REMOTE_ADDR'] == '127.0.0.1' )
    $pref = '';
  else
    $pref = 'estromfi_';

?>

<legend>
  <h1> 
	<?php echo Yii::t('main', 'TIETOKANNAT'); ?> <i class="glyphicon glyphicon-phone"></i> 
  </h1>
</legend>

<div class="form-inline">
  <div class="form-group">
    <form action="#" class="form-inline" method="POST">
	<input type="hidden" name="method" value="getStrukture">
	<input type="hidden" name="domain" value="defdb">
	<input type="submit" class="btn btn-success" value="<?php echo Yii::t('main', 'GET defdb strukture'); ?>">
    </form>
  </div>
  <div class="form-group">
<?php
   $filepath = Yii::app()->baseUrl."protected/views/domainit/sql/defdb.sql";
   echo '<div class="form-group">';

 	echo '	<form class="form-inline" action="#" method="POST">
		<input type="hidden" name="domain" value="defdb">
		<input type="hidden" name="file" value="'.$filepath.'">
		<input class="btn btn-primary btn-group" type="submit" value="defdb.sql">
		</form>';
   echo '</div>';
?>
  </div>
</div>

<?php if(isset($_POST['method']) and $_POST['method'] == 'getStrukture') : ?>
<?php


    Yii::app()->db1->setActive(false);
    Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname='.$pref.'defdb';
    if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
    {
      Yii::app()->db1->username = $pref.'defdb';
      Yii::app()->db1->password = 'KristinA1';
    }
    Yii::app()->db1->setActive(true);

    $tables = '*';
    if ($tables == '*') {
        $tables = array();
        $tables = Yii::app()->db1->schema->getTableNames();
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }
    $return = '';

    foreach ($tables as $table) {
        $row2 = Yii::app()->db1->createCommand('SHOW CREATE TABLE ' . $table)->queryRow();
        $return.= "\n\n" . $row2['Create Table'] . ";\n\n";

        $return.="\n\n";
    }
    //save file

  if (!file_exists(Yii::app()->basePath."/../protected/views/domainit/sql")) {
  	mkdir(Yii::app()->basePath."/../protected/views/domainit/sql", 0777, true);
  }

    $filepath = Yii::app()->basePath."/../protected/views/domainit/sql/defdb.sql";
    $handle = fopen($filepath, 'w+');
    fwrite($handle, $return);
    fclose($handle);

?>
<?php endif; ?>



<?php if(isset($_POST['file'])) : ?>
<?php
	$file_content = file_get_contents($_POST['file']);
	echo '<pre>'.$file_content.'</pre>';
?>
<?php endif; ?>




<br>
<div class="">
<legend>
<h2>SARAKKEEN VERTAILLU DEFDB KANNASTA</h2>
</legend>
<?php


 	echo '<div class="form-inline">

		<form class="form-group" action="#" method="POST">
		<input type="hidden" name="compare" >
		<input class="btn btn-success btn-group" type="submit" value="TARKISTA">
		</form>
		';


?>
</div>

<br>

<?php if(isset($_POST['compare']) ) : ?>
<?php

function arrayRecursiveDiff($aArray1, $aArray2) {
  $aReturn = array();

  foreach ($aArray1 as $mKey => $mValue) {
    if (array_key_exists($mKey, $aArray2)) {
      if (is_array($mValue)) {
        $aRecursiveDiff = arrayRecursiveDiff($mValue, $aArray2[$mKey]);
        if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
      } else {
        if ($mValue != $aArray2[$mKey]) {
          $aReturn[$mKey] = $mValue;
        }
      }
    } else {
      $aReturn[$mKey] = $mValue;
    }
  }
  return $aReturn;
} 

$insert = false;
$domain = '';
$list = Domainit::model()->findAll(" domain!='defdb' ");
foreach($list as $d)
{


	Yii::app()->db1->setActive(false);
	Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname='.$pref.'defdb';
        if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
        {
      	    Yii::app()->db1->username = 'estromfi_defdb';
            Yii::app()->db1->password = 'KristinA1';
    	}
	Yii::app()->db1->setActive(true);
	
	$tables = Yii::app()->db1->schema->getTableNames();
	$return="";
	    foreach ($tables as $table) {
		$r = Yii::app()->db1->createCommand('SHOW COLUMNS FROM  '. $table)->queryAll();
		$return1[$table] = $r;
	    }
	
	
	Yii::app()->db1->setActive(false);
	Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname='.$pref.$d->domain;
        if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
        {
      	    Yii::app()->db1->username = $pref.$d->domain;
            Yii::app()->db1->password = 'KristinA1';
    	}
	Yii::app()->db1->setActive(true);
	
	$tables = Yii::app()->db1->schema->getTableNames();
	$return="";
	    foreach ($tables as $table) {
		$b = Yii::app()->db1->createCommand('SHOW COLUMNS FROM  '. $table)->queryAll();
		$return2[$table] = $b;
	    }
	
	echo '<label>'.$d->domain.'</label>';

	if($result = arrayRecursiveDiff($return1, $return2))
	{
	  echo '<textarea class="form-control" rows="20">';
	  $tb = '';
	    foreach($result as $k=>$v)
	    {
		echo $k."\n";
		print_r($v);

		  foreach($v as $field)
		  {

		    if(isset($field['Field']) and !empty($field['Field']))
  		    {

			$insert = true;
		    	$tb = "alter table $k add ";
		    	$tb .= $field['Field'].' '.$field['Type'];

			if(isset($field['Null']) and $field['Null'] == 'NO')
		    	$tb .= ' not null ';

			if(isset($field['Default']) and !empty($field['Default']))
		    	$tb .= ' default '.$field['Default'];

			/*
			Yii::app()->db1->setActive(false);
			Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname=defdb';
			Yii::app()->db1->setActive(true);

		        $row2 = Yii::app()->db1->createCommand('SHOW CREATE TABLE ' . $k)->queryRow();
       			$return = "\n\n" . $row2['Create Table'] . ";\n\n";
			$return = str_replace("CREATE TABLE","CREATE TABLE IF NOT EXISTS",$return);

			Yii::app()->db1->setActive(false);
			Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname='.$domain;
			Yii::app()->db1->setActive(true);

			if(isset($_POST['insert']) )
		 		Yii::app()->db1->createCommand($return)->query();
			*/

		    	
			echo $tb."\n";

			if(isset($_POST['insert']) 
				and $_POST['domainForInsert'] != 'kaikki' 
				and $_POST['domainForInsert'] == $d->domain)
			{
		    		Yii::app()->db1->createCommand($tb)->query();
				$this->redirect('tietokannat');
			} 

			if(isset($_POST['insert']) and $_POST['domainForInsert'] == 'kaikki')
		    		Yii::app()->db1->createCommand($tb)->query();


		    } 
	
		  }
	    }

	  echo '</textarea>';
	} else {
	  echo ' OK<hr>';
	}

  
}

if($insert){
	echo '<BR>
		<form class="form-inline" action="#" method="POST">';

		echo '<select name="domainForInsert" class="form-control">';
		echo '<option value="kaikki">Kaikki</option>';
		foreach($list as $d)
		echo '<option value="'.$d->domain.'">'.$d->domain.'</option>';
		echo '</select>';

	echo '
		<input type="hidden" name="compare" >
		<input type="hidden" name="insert" value="true">
		<input class="btn btn-danger btn-group" type="submit" value=" >> INSERT">
		</form>
	 	</div> ';
}

?>
<?php endif; ?>






















<?php
/*

$list = Domainit::model()->findAll();
foreach($list as $d)
{

Yii::app()->db1->setActive(false);
Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname='.$d->domain;
Yii::app()->db1->setActive(true);



    $tables = '*';
    if ($tables == '*') {
        $tables = array();
        $tables = Yii::app()->db1->schema->getTableNames();
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }
    $return = '';

    foreach ($tables as $table) {
        //$result = Yii::app()->db1->createCommand('SELECT * FROM ' . $table)->query();
        //$return.= 'DROP TABLE IF EXISTS ' . $table . ';';
        $row2 = Yii::app()->db1->createCommand('SHOW CREATE TABLE ' . $table)->queryRow();
        $return.= "\n\n" . $row2['Create Table'] . ";\n\n";
	/*
        foreach ($result as $row) {
            $return.= 'INSERT INTO ' . $table . ' VALUES(';
            foreach ($row as $data) {
                $data = addslashes($data);

                // Updated to preg_replace to suit PHP5.3 +
                $data = preg_replace("/\n/", "\\n", $data);
                if (isset($data)) {
                    $return.= '"' . $data . '"';
                } else {
                    $return.= '""';
                }
                $return.= ',';
            }
            $return = substr($return, 0, strlen($return) - 1);
            $return.= ");\n";
        }
	
        $return.="\n\n";
    }
    //save file

  if (!file_exists(Yii::app()->basePath."/../protected/views/domainit/sql")) {
  	mkdir(Yii::app()->basePath."/../protected/views/domainit/sql", 0777, true);
  }

  if (file_exists(Yii::app()->basePath."/../protected/views/domainit/sql/*.sql"))
    unlink(Yii::app()->basePath."/../protected/views/domainit/sql/*.sql");

    $filepath = Yii::app()->basePath."/../protected/views/domainit/sql/".$d->domain.".sql";
    $handle = fopen($filepath, 'w+');
    fwrite($handle, $return);
    fclose($handle);

}



















   $filepath = Yii::app()->baseUrl."protected/views/domainit/sql/";
   echo '<div class="form-inline">';
	$i = 0;
	foreach(array_reverse(glob($filepath.'*.sql')) as $file) {
	$i++;
	$fn = explode("/",$file);
	$end = end($fn);

 	echo '<div class="form-group">
		<form class="form-inline" action="#" method="POST">
		<input type="hidden" name="file" value="'.$file.'">
		<input class="btn btn-primary btn-group" type="submit" value="'.$end.'">
		</form>
	 	</div> ';
	}
   echo '</div>';
*/


