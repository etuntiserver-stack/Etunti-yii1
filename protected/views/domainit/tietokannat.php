<?php

?>

<legend>
  <h1> 
	<?php echo Yii::t('main', 'TIETOKANNAT'); ?> <i class="glyphicon glyphicon-phone"></i> 
  </h1>
</legend>

<div class="row">
  <div class="col-sm-4">
    <form action="#" class="form-inline" method="POST">
	<input type="hidden" name="method" value="getStrukture">
	<input type="submit" class="btn btn-success" value="<?php echo Yii::t('main', 'GET MySQL strukture'); ?>">
    </form>
  </div>
</div>

<?php if(isset($_POST['method']) and $_POST['method'] == 'getStrukture') : ?>
<?php

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
        $result = Yii::app()->db1->createCommand('SELECT * FROM ' . $table)->query();
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
	*/
        $return.="\n\n\n";
    }
    //save file

  if (!file_exists(Yii::app()->basePath."/../protected/views/domainit/sql")) {
  	mkdir(Yii::app()->basePath."/../protected/views/domainit/sql", 0777, true);
  }

    $filepath = Yii::app()->basePath."/../protected/views/domainit/sql/".$d->domain.".sql";
    $handle = fopen($filepath, 'w+');
    fwrite($handle, $return);
    fclose($handle);

}
?>
<?php endif; ?>

<br>
<div class="">
<legend>
<h2>Structure backups</h2>
</legend>
<?php
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
?>
</div>

<?php if(isset($_POST['file'])) : ?>
<?php
	$file_content = file_get_contents($_POST['file']);
	echo $file_content;
?>
<?php endif; ?>


<br>
<div class="">
<legend>
<h2>Compare</h2>
</legend>
<?php

  $defdb = Yii::app()->baseUrl."protected/views/domainit/sql/defdb.sql";
  if (file_exists($defdb)) {
	$fn = explode("/",$defdb);
	$end = end($fn);
 	echo '<div class="form-group">
		<form class="form-inline" action="#" method="POST">
		<input type="hidden" name="compare" value="'.$defdb.'">
		<input class="btn btn-primary btn-group" type="submit" value="'.$end.'">
		</form>
	 	</div> ';
  }

?>
</div>

<?php if(isset($_POST['compare']) ) : ?>
<?php
/*
$list = Domainit::model()->findAll();
foreach($list as $d)
{

}
*/

Yii::app()->db1->setActive(false);
Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname=defdb';
Yii::app()->db1->setActive(true);

$tables = Yii::app()->db1->schema->getTableNames();
        $return="";
    foreach ($tables as $table) {
        $result = Yii::app()->db1->createCommand('SELECT * FROM ' . $table)->query();
	$row2 = Yii::app()->db1->createCommand('SHOW CREATE TABLE ' . $table)->queryRow();
	$return_defdb[] = $row2['Create Table'];
    }


Yii::app()->db1->setActive(false);
Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname=sivex';
Yii::app()->db1->setActive(true);

$tables = Yii::app()->db1->schema->getTableNames();
        $return="";
    foreach ($tables as $table) {
        $result = Yii::app()->db1->createCommand('SELECT * FROM ' . $table)->query();
	$row2 = Yii::app()->db1->createCommand('SHOW CREATE TABLE ' . $table)->queryRow();
	$return_toinen[] = $row2['Create Table'];
    }


$result = array_diff_assoc($return_defdb, $return_toinen);

print_r($defdb);




?>
<?php endif; ?>





