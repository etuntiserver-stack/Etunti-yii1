<?php

	$asetukset = Asetukset::model()->findbypk(1);
	$body = '

			<h3>'.date("d.m.Y", strtotime($_POST['pvm'])).'</h3>

	';
	$body .= '<input type="hidden" value="'.date("d.m.Y", strtotime($_POST['pvm'])).'" id="valinnuPvm">';

	// kuva
	function kuva($vapaaTid){
		$filepath = dirname(Yii::app()->getBasePath())."/img/tekijat/".Yii::app()->user->domain."/".$vapaaTid.".jpg";
		if (file_exists($filepath))
		{
			$url = Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$vapaaTid.".jpg";
			/*
			$size = getimagesize($url);
			if( $size[0] > 640 )
			{
				// <-- Image resize
				//header('Content-Type: image/jpeg');
				$width = 640;
				$image = imagecreatefromjpeg($url);
				$orig_width = imagesx($image);
				$orig_height = imagesy($image);
				$height = (($orig_height * $width) / $orig_width);
				$new_image = imagecreatetruecolor($width, $height);
				imagecopyresized($new_image, $image,
					0, 0, 0, 0,
					$width, $height,
					$orig_width, $orig_height);

				imagejpeg($new_image, $url);
				//	  Image resize -->
			}
			
			$imageData = base64_encode(file_get_contents($filepath));
			$src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;
			$kuva = '<img src="'.$src.'" class="img-thumbnail">';
			*/
			
			$kuva = '<img src="'.$filepath.'" class="img-thumbnail">';
			
		} else {
			$kuva = '<img src="../../lib/img/noname.jpg" class="img-thumbnail">';
		}
		return $kuva;
	}
	// kuva

	function tr($vapaaTid, $pvm, $sta, $sto, $kuva)
	{
		$return = '
		<tr class="link ajaanClick" tid="'.$vapaaTid.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" alku="'.$sta.'" loppu="'.$sto.'">
		<td class="col-xs-2">'.$kuva.'</td>
		<td>'.$sta.' - '.$sto.'</td>
		</tr>';
		return $return;
	}

	$date		= $_POST['pvm'];
	$getTyovuorot 	= $this->getTyovuorotThisMonth($date);
	$fromTyovuorot 	= (isset($getTyovuorot['returnData'][$date]))? $getTyovuorot['returnData'][$date] : [];
	$tekijat 	= $this->pmvCalNew($date, $fromTyovuorot, $getTyovuorot['tids'])[1];
	$body .= '<div class="table-responsive">';
	$body .= '<table class="table table-hover">';
	foreach($tekijat as $key=>$value)
	{
		$body .= tr($value[0], $value[1], $value[2], $value[3], kuva($value[0]));
	}
	$body .= '</table>';
	$body .= '</div>';



	echo json_encode($body);
?>

