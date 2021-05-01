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
							<select name="kk" id="kk" class="gui-input">
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
										'empty'=>'Valitse työryhmä',
										'class'=>'form-control form-group selectpicker', 
										'id'=>'tyoryhmaSelect', 
										'multiple' => 'yes', 
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

<?php if($kk !== null) : ?>
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
	  <th></th>
	  </tr>
	  </thead>
	  <?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_la_asiakkaat',
	  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',
		//'viewData' => ['mob_lista' => $mob_lista], 
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

<div id="tp_valinta" style="display:none">
<?php
	$criteria = new CDbCriteria();
	$criteria->order = " nimike ";
	$criteria->condition = " 
		hinta_alv_0!=0 AND yksikko='h'
	";
	echo CHtml::dropdownList('','palvelu', CHtml::listData(TuotteetPalvelut::model()->findAll($criteria), 'id', 'nimike'), 
		['empty'=>'','class'=>'form-control bg-warning valitseTuote']
	);
?>
</div>

<script type="text/javascript">
$(document).ready(function(){

	$(document).delegate(".laskutukseen","click",function(e){
		e.preventDefault()
		var lasku_rivit = [];
		$('.lasku_rivi').each(function(){
			lasku_rivit.push([{
				'tuote_id' : $(this).closest('tr').find('.tuote').attr('tuote_id'),
				'tv_id' : $(this).closest('tr').find('.tuote').attr('tv_id'),
				'tuote' : $(this).closest('tr').find('.tuote').text(),
				'maara' : $(this).closest('tr').find('.maara').text(),
				'alv' : $(this).closest('tr').find('.alv').text(),
				'hinta' : $(this).closest('tr').find('.hinta').text(),
				'free_text' : $(this).closest('tr').find('.free_text').text()
			}]);
		});
		//console.log(lasku_rivit);
		$('#la_asiakkaat_tr_rivit').val( JSON.stringify(lasku_rivit) );
		$(this).closest('form').submit();
	});
	
	var kohde_id = 0;
	$(document).delegate(".tuote_puutu","click",function(){
		$(this).replaceWith( $('#tp_valinta').html() );
		kohde_id = $(this).attr('kohde_id');
	});

	$(document).delegate(".valitseTuote","change",function(){
		var cl = $(this).closest('.closest_td');

		$.ajax({
			url: 'tuotepalvelukohdelle?id=' + kohde_id + '&tuote=' + $(this, 'option:selected').val(),
			success: function(data){
				var data = JSON.parse(data);
				console.log(data);
				
				ajaxForLasku(cl);
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
	
	function ajaxForLasku(thisFor)
	{
		var asiakas_id 		= thisFor.closest('td').find('.nayta_collapse').attr('asiakas_id');
		var rakenne_muoto 	= thisFor.closest('td').find('.rakenne_muoto').val();
		var rivi_muoto 		= thisFor.closest('td').find('.rivi_muoto').val();
		var kk_valinta 		= thisFor.closest('td').find('.kk_valinta').val();
		
		if( !thisFor.closest('td').find('.nayta_collapse').hasClass('collapsed') )
		{
			thisFor.closest('td').find('.paivita').show(370);
			var link = 'kklaskuperasiakas?asiakas_id=' + asiakas_id + '&from=<?=$from?>&to=<?=$to?>&rakenne_muoto=' + rakenne_muoto + '&rivi_muoto=' + rivi_muoto + '&kk_valinta=' + kk_valinta;
			//console.log('Link: ' + link);
			$.ajax({
				url: link,
				success: function(data){
					var data = JSON.parse(data);
					//console.log(data);
					
					$('#collapse_id_' + asiakas_id).html(data);
					var sum = 0;
					$('.forsumm').each(function(){
						sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
					});
					$('#summ_result').html(sum);
					$('[data-toggle="tooltip"]').tooltip();
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

