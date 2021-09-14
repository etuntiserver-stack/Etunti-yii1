<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">
        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'Laskuttettavat asiakkaat'); ?></h2>

	<form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">
                      <div class="col-md-2">
                        <div class="section">
                         <label class="field select">
							<select name="kk" id="kk" class="gui-input" required>
							<option value=""><?php echo Yii::t('main', 'Valitse kuukausi'); ?></option>
							<?php
							$months = Lasku::months();
							$i = 1;
							$month = strtotime(date("Y-m-d", strtotime("first day of this month")));
							while($i <= 24)
							{
								$month_name = date('n', $month);
								$year 	= date('Y', $month);
								(isset($_GET['kk']) and $_GET['kk'] == $year.'-'.$month_name)? $selected = 'selected' : $selected = '';
								echo '<option value="'.$year.'-'. $month_name.'" '.$selected.'>'.$months[$month_name].' '.$year.'</option>';
								$month = strtotime('-1 month', $month);
								$i++;
							}
							?>
							</select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
							<select class="form-group form-control" name="rakenne_muoto">
								<option value="mobiili" <?=($rakenne_muoto !== null and $rakenne_muoto == 'mobiili')? 'selected':''?>>Hyväksytyt tunnit</option>
								<option value="tuovuoro" <?=($rakenne_muoto !== null and $rakenne_muoto == 'tuovuoro')? 'selected':''?>>Työvuoroista</option>
							</select>
                           </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
							<?php
								$list = array();
								$l = Valikkoot::model()->findAll(" select_type='tyoryhma' ",array('order' => "select_type"));
								foreach($l as $v)
									$list[$v->id] = $v->value;

								$selected = [];
								if(isset($_GET['tyoryhma']) and is_array($_GET['tyoryhma'])){
										foreach($_GET['tyoryhma'] as $rnum){
											$selected[$rnum] = ['selected'=>true];
										}
								}
				
								if(count($list) > 0)
								{
									echo CHtml::dropDownList('tyoryhma', 'tyoryhma[]', $list,
									array(
										//'empty'=>'Valitse työryhmä',
										'class'=>'form-control form-group selectpicker', 
										'id'=>'tyoryhmaSelect', 
										'multiple' => 'yes', 
										'title' => 'Valitse työryhmä',
										'options' => $selected
										)
									);
								}
							?>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

								<!-- Autocomplete -->
								<?php
					   			$site = Yii::app()->createController('Site');
								$mod = 'Asiakkaat';
								$sarake = 'yrityksen_nimi';
								$placeholder = 'Asiakas';
								if(isset($_GET[$sarake])) 			$postvalue = $_GET[$sarake]; 
								else $postvalue='';				
						 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi', 'etunimi', 'sukunimi'), $placeholder, $postvalue);
								?>
								<!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>

	</form>

        <!-- loppu: .tray-center -->
        </div>

<?php if($kk !== null and $rakenne_muoto !== null) : ?>
<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

	<div class="row">
	 <div class="table-responsive" id="tableContent">
	  <table class="table table-striped" id="mobileTable">
	  <thead class="myBgColors">
	  <tr>
	  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
	  <th><?php echo Yii::t('main', 'Lasku'); ?></th>
	  </tr>
	  </thead>
	  <?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_la_asiakkaat',
	  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',
		'viewData' => ['kk' => $kk, 'la_AsIds' => $la_AsIds], 
		'pager' => array(
	           'firstPageLabel'=>'<<',
	           'prevPageLabel'=>'< Edellinen',
	           'nextPageLabel'=>'Seuraava >',
	           'lastPageLabel'=>'>>',
	           //'maxButtonCount'=>'10',
	           'header'=>'<h3>Siirry sivulle:</h3>',
	           'cssFile'=>false,
	       ), 
	
	  )); ?>
	  </table>
	 </div>
	</div>

   </div>
  </div>
</div>

<div id="tp_valinta" class="form-inline" style="display:none">
<?php
	echo CHtml::dropdownList('laskurivi_tyyppi','laskurivi_tyyppi', ['tunti' => 'Kirjaus muoto - h', 'kk' => 'Kuukausi muoto - kk', 'kpl' => 'Kertakäynti muoto - kpl'], 
		['class'=>'form-control form-group laskurivi_tyyppi']
	);
	echo CHtml::dropdownList('','hinnasto', CHtml::listData(Hinnastot::model()->findAll(), 'id', 'hinnaston_otsikko'), 
		['empty'=>'Valitse hinnasto','class'=>'form-control form-group la_hinnasto']
	);
	echo CHtml::dropdownList('tuote_h','tuote_h', CHtml::listData(TuotteetPalvelut::model()->findAll("yksikko='h'"), 'id', 'nimike'), 
		['empty'=>'Valitse tuntituote','class'=>'form-control form-group la_tuote_h']
	);
	echo '<div id="kk_valinta" style="display:none">';
		echo CHtml::dropdownList('tuote_kk','tuote_kk', CHtml::listData(TuotteetPalvelut::model()->findAll("yksikko='kk'"), 'id', 'nimike'), 
			['empty'=>'Valitse kk tuote','class'=>'form-control form-group la_tuote_kk']
		);
	echo '</div>';
	echo '<div id="kpl_valinta" style="display:none">';
		echo CHtml::dropdownList('tuote_kpl','tuote_kpl', CHtml::listData(TuotteetPalvelut::model()->findAll("yksikko='kpl'"), 'id', 'nimike'), 
			['empty'=>'Valitse kpl tuote','class'=>'form-control form-group la_tuote_kpl']
		);
	echo '</div>';
	echo '<span class="btn btn-primary btn-block" id="new_hinnasto">Tallenna</span><br>';
?>
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	$(document).delegate(".laskutetuksi","click",function(e){
	
		e.preventDefault();

		var lasku_rivit = [];
		$(this).closest('.closest_td').find('.lasku_rivi').find('.laskutetaan:checkbox:checked').each(function(){		
			lasku_rivit.push([{
				'tuote_id' : $(this).closest('tr').find('.tuote').attr('tuote_id'),
				'rivi_tunniste' : $(this).closest('tr').find('.tuote').attr('rivi_tunniste'),
				'kohde_ids' : $(this).closest('tr').find('.tuote').attr('kohde_ids'),
				'tv_id' : $(this).closest('tr').find('.tuote').attr('tv_id'),
				'tuote' : $(this).closest('tr').find('.tuote').text(),
				'maara' : $(this).closest('tr').find('.maara').text(),
				'yksikko' : $(this).closest('tr').find('.yksikko').text(),
				'alv' : $(this).closest('tr').find('.alv').text(),
				'hinta' : $(this).closest('tr').find('.hinta').text(),
				'free_text' : $(this).closest('tr').find('.free_text').text(),
				'tiedot' : $(this).closest('tr').find('.tiedot').text(),
				'kk_hyv_lista' : (  $(this).closest('tr').find('.kk_hyv_lista').find('textarea').val() )? $(this).closest('tr').find('.kk_hyv_lista').find('textarea').val() : ''
			}]);
		});
		
		var closestForm = $(this).closest('form');
		var asiakas_id 	= $(this).attr('asiakas_id');
		var from 		= $(this).attr('from');
		var to 			= $(this).attr('to');
		var thisButton  = $(this);
		var cl 			= $(this).closest('.closest_td');
		var closest_asiakas_td	= $(this).closest('tr').find('.closest_asiakas_td');
		var laskun_paivays = cl.find('.laskun_paivays').val();
		var etunti_tunniste = $(this).attr('etunti_tunniste');
		
		thisButton.text('Odota...');

		//console.log(lasku_rivit)
		//return false;
		
		$.ajax({
			url: 'laskutetuksi?asiakas_id=' + asiakas_id + '&from=' + from + '&to=' + to + '&tilanne=new',
			type : "POST",
			data : { la_asiakkaat_tr_rivit : JSON.stringify(lasku_rivit), laskun_paivays : laskun_paivays, etunti_tunniste : etunti_tunniste },
			success: function(data){
				var data = JSON.parse(data);
				console.log(data);
				if(data['lasku_id'] && parseInt(data['lasku_id']) > 0)
				{
					ajaxForLasku(cl);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			   	console.log(XMLHttpRequest);
			}
		});
	});

	$(document).delegate("#laskurivi_tyyppi","change",function(){
		var cl = $(this).closest('td');
		laskurivityyppi(cl);
	});

	function laskurivityyppi(cl)
	{
		if( cl.find('#laskurivi_tyyppi option:selected').val() == 'kk' )
		{
			cl.find('#kk_valinta').show(375);
			cl.find('#kpl_valinta').hide(375);
			cl.find('#tuote_kpl').val(0);
		} else if( cl.find('#laskurivi_tyyppi option:selected').val() == 'kpl' )
		{
			cl.find('#kpl_valinta').show(375);
			cl.find('#kk_valinta').hide(375);
			cl.find('#tuote_kk').val(0);
		} else {
			cl.find('#kk_valinta').hide(375);
			cl.find('#tuote_kk').val(0);
			cl.find('#kpl_valinta').hide(375);
			cl.find('#tuote_kpl').val(0);
		}
	}
	 	
	var kohde_id 		= 0;
	var TuotteetBefore 	= '';
	
	$(document).delegate(".tuote_puutu","click",function(){
		$(this).replaceWith( $('#tp_valinta').html() );
		kohde_id = $(this).attr('kohde_id');
		TuotteetBefore = $('#tp_valinta').find('.la_tuote_h').html();
	});

	$(document).delegate(".la_hinnasto", "change", function(){

		var cl 		= $(this).closest('td');
		var thisVal = $(this, 'option:selected').val();
	
		if(thisVal)
		{
			$.ajax({
				url: location.protocol + '//' + location.host + '/index.php/kohteet/tuotteetbyhinnasto?id=' + thisVal,
				success: function(data){
					console.log(data);
					if(data !== '')
					{
						data = JSON.parse(data);
						cl.find('.la_tuote_h').html(data);
					}
				}
			});
			
		} else {
			cl.find('.la_tuote_h').html(TuotteetBefore);
		}
	});
		
	$(document).delegate("#new_hinnasto", "click", function(){
	
		var cl 			= $(this).closest('td');
		var laskurivi_tyyppi	= cl.find('.laskurivi_tyyppi', 'option:selected').val();
		var hinnasto 	= parseInt(cl.find('.la_hinnasto', 'option:selected').val()) || 0;
		var tuote_h		= parseInt(cl.find('.la_tuote_h', 'option:selected').val()) || 0;
		var tuote_kk	= parseInt(cl.find('.la_tuote_kk', 'option:selected').val()) || 0;
		var tuote_kpl	= parseInt(cl.find('.la_tuote_kpl', 'option:selected').val()) || 0;
		var closest_td 	= $(this).closest('.closest_td');

		if(tuote_h == 0)
		{
			alert('Tuntituote ei saa olla tyhjä');
			return false;
		}
		if(laskurivi_tyyppi == 'kk' && tuote_kk == 0)
		{
			alert('KK tuote ei saa olla tyhjä');
			return false;
		}
		if(laskurivi_tyyppi == 'kpl' && tuote_kpl == 0)
		{
			alert('Kertakäynti tuote ei saa olla tyhjä');
			return false;
		}
	
		$.ajax({
			url: 'tuotepalvelukohdelle?id=' + kohde_id + '&laskurivi_tyyppi='+ laskurivi_tyyppi +'&tuote_h=' + tuote_h + '&tuote_kk=' + tuote_kk + '&tuote_kpl=' + tuote_kpl + '&hinnasto=' + hinnasto,
			success: function(data){
				var data = JSON.parse(data);
				console.log(data);
				
				ajaxForLasku(closest_td);
				$('[data-toggle="tooltip"]').tooltip();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			   	console.log(XMLHttpRequest);
			}
		});
	});
	
	$(document).delegate(".paivita","click",function(){
		ajaxForLasku($(this));
	});
	
	$(document).delegate(".rakenne_muoto, .rivi_muoto, .kk_valinta","change",function(){
		ajaxForLasku($(this));
	});
	
	$(document).delegate(".nayta_collapse","click",function(){
		ajaxForLasku($(this));
	});

	// <-- Check laskutamattomat rivit laskutetut asiakkaasta
	$('.tehdyt').each(function(){
	
		var thisFor 		= $(this);
		var thisForText		= $(this).text();
		thisFor.html('<h4 class="text-warning">Odota...</h4>');
		
		var asiakas_id 		= $(this).attr('asiakas_id');
		var rakenne_muoto 	= '<?=$rakenne_muoto?>';
		var rivi_muoto 		= $(this).closest('tr').find('.rivi_muoto').val();

		var link = 'kklaskuperasiakas?asiakas_id=' + asiakas_id + '&from=<?=$from?>&to=<?=$to?>&rakenne_muoto=' + rakenne_muoto + '&rivi_muoto=' + rivi_muoto;
		//console.log('Link: ' + link);
		$.ajax({
			url: link,
			success: function(data){
				var data = JSON.parse(data);
				//console.log(data);
				
				$('#collapse_id_' + asiakas_id).html(data);
				
				var sum = 0;
				$('#collapse_id_' + asiakas_id).closest('td').find('.laskutetaan:checkbox:checked').each(function(){
					sum++;
				});
				
				if(sum > 0)
				{
					thisFor.html(thisForText + '<br>Laskuttamattomat rivit: (' + sum + ') kpl');
					thisFor.addClass('btn btn-block btn-warning');
				} else {
					thisFor.text('Laskutettu');
					thisFor.addClass('btn btn-block btn-success');
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			   	console.log(XMLHttpRequest);
			}
		});
	});
	// Check laskutamattomat rivit laskutetut asiakkaasta -->
		
	function ajaxForLasku(thisFor)
	{	
		var asiakas_id 		= thisFor.closest('td').find('.nayta_collapse').attr('asiakas_id');
		var rakenne_muoto 	= '<?=$rakenne_muoto?>';
		var rivi_muoto 		= thisFor.closest('td').find('.rivi_muoto').val();
		
		if( !thisFor.closest('td').find('.nayta_collapse').hasClass('collapsed') )
		{
			thisFor.closest('td').find('.paivita').show(370);
			var link = 'kklaskuperasiakas?asiakas_id=' + asiakas_id + '&from=<?=$from?>&to=<?=$to?>&rakenne_muoto=' + rakenne_muoto + '&rivi_muoto=' + rivi_muoto;
			//console.log('Link: ' + link);
			$.ajax({
				url: link,
				success: function(data){
					var data = JSON.parse(data);
					//console.log(data);
					
					$('#collapse_id_' + asiakas_id).html(data);
					var sum = 0;
					thisFor.closest('td').find('.forsumm').each(function(){
						sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
					});
					thisFor.closest('td').find('#summ_result').html(sum.toFixed(2));
					$('[data-toggle="tooltip"]').tooltip();
					
					thisFor.closest('td').find('.laskutetaan:checkbox:checked').each(function(){
						thisFor.closest('td').find('.laskutetuksi').show()
					});
					
					$(".datepickerLA").datepicker({
						format: "mm.yyyy",
						viewMode: "months", 
						minViewMode: "months",
						language: "fi",
					});
			
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
				   	console.log(XMLHttpRequest);
				}
			});
			
		} else {
			thisFor.closest('td').find('.paivita').hide(370);
		}
	}
})
</script>
<?php endif; ?>

