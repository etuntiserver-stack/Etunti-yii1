<?php

	$d2 = array();
	$toinen_valikko_rakenne = json_decode($data->toinen_valikko_rakenne, true);

	$body = '';

	if( is_array($toinen_valikko_rakenne))
	{

		if(isset($data->selitysteksti))
		$body .= '<p class="small">'.$data->selitysteksti.'</p>';

		$body .='<div class="col-sm-4 col-sm-offset-4">';

		if(isset($toinen_valikko_rakenne['otsikko']))
		$body .='<h4>'.$toinen_valikko_rakenne['otsikko'].'</h4>';

	 	$body .='<select class="form-control input-lg" id="toinen_valiko_values">';
		$body .= '<option value="">'.Yii::t('main', 'Valitse').'</option>';

		$nimike = array();
		foreach($toinen_valikko_rakenne['values']['nimike'] as $key=>$item)
			$nimike[] = $item;

		$hinta = array();
		foreach($toinen_valikko_rakenne['values']['hinta'] as $key=>$item)
			$hinta[] = $item;

		$kesto = array();
		foreach($toinen_valikko_rakenne['values']['kesto'] as $key=>$item)
			$kesto[] = $item;


		foreach($nimike as $key=>$rivi)
			$body .= '<option value="'.$toinen_valikko_rakenne['otsikko'].'//'.$nimike[$key].'//'.$hinta[$key].'//'.$kesto[$key].'">'.$nimike[$key].'</option>';

		$body .= ' </select>';

		$body .= '</div>';

	}







	$lisapalvelut = json_decode($data->lisapalvelut, true);
	$lisat = '';
	if( is_array($lisapalvelut) )
	{
	$lisat .= '<p><h4>Valitse lisäpalvelu</h4></p>
	<div class="row">';


			   $otsikko = array();
			   foreach($lisapalvelut['values']['otsikko'] as $key=>$item)
				$otsikko[] = $item;

			   $kuvaus = array();
			   foreach($lisapalvelut['values']['kuvaus'] as $key=>$item)
				$kuvaus[] = $item;

			   $hinta = array();
			   foreach($lisapalvelut['values']['hinta'] as $key=>$item)
				$hinta[] = $item;

			   $kesto = array();
			   foreach($lisapalvelut['values']['kesto'] as $key=>$item)
				$kesto[] = $item;



			   $i = 999;
			   foreach($otsikko as $key=>$rivi)
			   {
			   $i++;
	  			$lisat .= 
				  '
				  <div class="col-sm-6">
				   <table class="tblisat">
				    <tr>
				     <td width=1>
					<input class="checkbox lisat" fordata="'.$data->id.$i.'" for="'.$otsikko[$key].'//'.$hinta[$key].'//'.$kesto[$key].'" type="checkbox">
				     </td><td>
				        <a href="#" data-toggle="modal" data-target="#myModal_'.$data->id.$i.'">'.$otsikko[$key].'</a>
			
				     </td>
				    </tr>
				   </table>
				  </div>
				  ';



				$lisat .= '
				<!-- Modal -->
				<div id="myModal_'.$data->id.$i.'" class="modal fade" role="dialog">
				  <div class="modal-dialog">
				
				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">'.Yii::t('main', 'Selitysteksti').'</h4>
				      </div>
				      <div class="modal-body">
				        <p>'.$kuvaus[$key].'</p>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">'.Yii::t('main', 'Sulje').'</button>
				      </div>
				    </div>
				
				  </div>
				
				</div>';
						
			   }
	}



	echo json_encode(array($body, $lisat));
?>
