<?php

  if(isset($data[0]))
  {
	$d2 = array();

	$body ='<select class="form-control input-lg" id="nelio">
		<option value="">Huoneisten koko m²</option>';

	foreach($data as $d)
	{
	  $ex = explode("-",$d->nelio);
	  $d2[$ex[0]] =  $d->nelio.'//'.$d->nelio;
	}
	ksort($d2);
	foreach($d2 as $d)
	{
	  $ex = explode("//",$d);
	  $body .=  
	  '
		<option value="'.$ex[0].'">'.$ex[1].'</option>
	  ';
	}

	$body .= '</select>';
	echo json_encode($body);

  } 
?>
