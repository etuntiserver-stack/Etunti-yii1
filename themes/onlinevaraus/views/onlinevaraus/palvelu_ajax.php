<?php

	$d2 = array();
	$toinen_valikko_rakenne = json_decode($data->toinen_valikko_rakenne, true);

	$body = '';

	if( isset($toinen_valikko_rakenne['values']) and is_array($toinen_valikko_rakenne) and !empty($toinen_valikko_rakenne['otsikko']) )
	{

		if(isset($data->selitysteksti))
		$body .= $data->selitysteksti.'<br>';


	 	$body .='<select class="form-control input-lg" id="toinen_valiko_values">';
		if(isset($toinen_valikko_rakenne['otsikko']))
		$body .= '<option value="">Valitse '.$toinen_valikko_rakenne['otsikko'].'</option>';

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


	}







	$lisapalvelut = json_decode($data->lisapalvelut, true);
	$lisat = '';
	if( isset($lisapalvelut['values']['otsikko'][0]) and is_array($lisapalvelut['values']) and !empty($lisapalvelut['values']['otsikko'][0]) )
	{
	$lisat .= '<h3>'.Yii::t('main', 'Lisäpalvelut').'</h3>
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
				  <div class="col-sm-12">
				   <table class="tblisat">
				    <tr>
				     <td width=20>
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
