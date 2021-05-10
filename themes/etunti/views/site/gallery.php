<?php
// path to image folder
$img_path = Yii::app()->basePath."/../tiedostot/asiakkaat/" . Yii::app()->user->domain . "/";
foreach(glob($img_path.'*.{jpg,JPG,jpeg,JPEG,png,PNG}',GLOB_BRACE) as $file){
    // path to a single imaGe
    $path = "/tiedostot/asiakkaat/". Yii::app()->user->domain. "/". basename($file);
    echo '<a href="'.$path.'">
    <img loading="lazy" src="'.$path.'" />
    </a>
    ';
}
?>
