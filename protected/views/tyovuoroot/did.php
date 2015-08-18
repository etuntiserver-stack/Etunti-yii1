<?php

		if(isset($_GET['id']))
		  $tv=Tyovuoroot::model()->findbypk($_GET['id']);
		else
		  $tv = Tyovuoroot::model()->find("id !='' order by id desc");

		$did = date("Ymd",strtotime($tv->pvm));
		echo '<div class="small laatikko latikkoAsetukset" pvm="'.$tv->pvm.'" tid="'.$tv->tid.'" id="'.$did.'_'.$tv->tid.'">';

		$tv = Tyovuoroot::model()->findAll("tid = '".$tv->tid."' and pvm = '".$tv->pvm."' ",array('select'=>'kohde')); 
		foreach($tv as $tvVal)
		{
		$k = Kohteet::model()->findbypk($tvVal->kohde);

	  	    $strlen = strlen($k['osoite']);

	     	  if($strlen > 18)
	  	    $k['osoite'] = substr($k['osoite'],0,18).'..';
	   	  else
		    $k['osoite'] = $k['osoite'];

		  if($tvVal->alku > 0 and $tvVal->loppu > 0)
		    $al = $tvVal->alku.'-'.$tvVal->loppu;
		  else
		    $al = '';

		  echo '<a href="#" class="link tv_edit" id="tv_'.$tvVal->id.'">'.$al.' '.$k['osoite'].'</a><br>';

		}

	  	echo '</div>';
		?>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>
		<?php

?>
