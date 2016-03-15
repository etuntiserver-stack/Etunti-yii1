<?php

  if(isset($data[0]))
  {
	$body ='<select class="form-control input-lg" id="nelio">
		<option value="">Huoneisten koko m²</option>';
	foreach($data as $data)
	{
	  $body .=  
	  '
		<option value="'.$data->nelio.'">'.$data->nelio.'</option>
	  ';
	}
	$body .= '</select>';
	echo json_encode($body);

  } 
?>
