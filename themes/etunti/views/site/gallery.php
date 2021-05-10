<?php
// path to image folder
$img_path = Yii::app()->basePath."/../tiedostot/asiakkaat/" . Yii::app()->user->domain . "/";
foreach(glob($img_path.'*.{jpg,JPG,jpeg,JPEG,png,PNG}',GLOB_BRACE) as $file){
    $imageFile = Yii::app()->basePath."/../tiedostot/asiakkaat/" . Yii::app()->user->domain . "/" . basename($file);
    $imageData = base64_encode(file_get_contents($imageFile));
    $img = "data: " . mime_content_type($imageFile) . ";base64,". $imageData;
    echo '
        <img loading="lazy" src="'.$img.'" />
    ';
}
?>
