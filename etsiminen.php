<?php
header("Content-Type: text/html; charset=utf-8");

$searchDir = './protected/models';
$searchExtList = array('.php');
$searchString = 'Yii::t';

$allFiles = everythingFrom($searchDir,$searchExtList,$searchString);

$allFiles = array_unique($allFiles);


foreach($allFiles as $b)
{
	echo $b.' => '.$b.',<br>';
}


/*
echo '<pre>';
print_r($allFiles);
echo '</pre>';
*/

function everythingFrom($baseDir,$extList,$searchStr) {
    $ob = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir), RecursiveIteratorIterator::SELF_FIRST);

$g = array();

    foreach($ob as $name => $object){
        if (is_file($name)) {
            foreach($extList as $k => $ext) {
                if (substr($name,(strlen($ext) * -1)) == $ext) {
                    $tmp = file_get_contents($name);
                    if (strpos($tmp,$searchStr) !== false) {
                        $files[] = $name;


   $string = $tmp;
   
   preg_match_all("/Yii::t[(.*)](.*)[)]/", $string, $results);
/*
echo '<pre>';
   print_r($results[1]);
echo '</pre>';
*/



foreach($results[1] as $teksti)
{
$res = str_replace("'main'", "", $teksti);
$res = str_replace(",", "", $res);
//$res = str_replace("'", "", $res);
$g[] =  $res;
}




                    }
                }
            }
        }
    }

    return $g;
}
?>
