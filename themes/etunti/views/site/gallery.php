<?php
// path to image folder
$page = $_GET["page"] ?? 1;
$perPage = 20;

$offset = $page * $perPage;

// you can't reference images directly or you'll get 403 forbidden
// and encoding them like this seems to be way too much data at once, and loading="lazy" probably doesn't
// work with encoded images.
$img_path = Yii::app()->basePath."/../tiedostot/asiakkaat/" . Yii::app()->user->domain . "/";
$i = 0;
foreach(glob($img_path.'*.{jpg,JPG,jpeg,JPEG,png,PNG}',GLOB_BRACE) as $file){
    if($i++ < $offset) continue;
    if($i > $offset + $perPage) break;

    $imageFile = Yii::app()->basePath."/../tiedostot/asiakkaat/" . Yii::app()->user->domain . "/" . basename($file);
    $imageData = base64_encode(file_get_contents($imageFile));
    $img = "data: " . mime_content_type($imageFile) . ";base64,". $imageData;
    $imgHtml = '<img loading="lazy" src="'.$img.'" style="max-width: 150px; max-height: 150px" />';
    echo $imgHtml;
}


?>
