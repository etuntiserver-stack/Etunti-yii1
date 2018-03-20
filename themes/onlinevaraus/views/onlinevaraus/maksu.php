<?php
		$return = '';
		if(isset($_SESSION['onlinevaraus']['paapalvelu']) and isset($_SESSION['onlinevaraus']['onlinevarausID']))
		{

			$ov = Onlinevaraus::model()->findbypk($_SESSION['onlinevaraus']['onlinevarausID']);
			if(isset($ov->id))
			{
			$return = $this->renderPartial('checkout', array(
				'amount'=>$_SESSION['onlinevaraus']['amount'],
				'kesto'=>$_SESSION['onlinevaraus']['sumTunti'],
				'etu_suku_nimet'=>$ov->yhteyshenkilo,
				'osoite'=>$ov->osoite,
				'postinumero'=>$ov->postinumero,
				'kaupunki'=>$ov->kaupunki,
			), true); 
			}
		}

		if($json == false)
		echo $return;
		else
		echo json_encode($return);
?>
