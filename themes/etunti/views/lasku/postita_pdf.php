<?php
$content = file_get_contents(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain.'/'.$id.'.pdf');

header('Content-type: application/pdf');
echo $content;
?>
