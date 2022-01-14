<?php


?>

<!-- begin: .tray-center -->
<div class="tray-center">

	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'TAG raportti'); ?> 
		<div class="pull-right montakoRiviaSivulle">
		<?php
		($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';
		($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
		($perSivu == 100) ? $defcl00 = 'btn-success' : $defcl00 = 'btn-default';
		($perSivu == 2000) ? $defcl2000 = 'btn-success' : $defcl2000 = 'btn-default';
		($perSivu == 10000) ? $defcl10000 = 'btn-success' : $defcl10000 = 'btn-default';

		echo '<button class="btn '.$defcl10.' kpl" kpl="10" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10 '.Yii::t('main', 'asiakasta sivulla').'">10</button>';
		echo '<button class="btn '.$defcl50.' kpl" kpl="50" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 50 '.Yii::t('main', 'asiakasta sivulla').'">50</button>';
		echo '<button class="btn '.$defcl00.' kpl" kpl="100" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 100 '.Yii::t('main', 'asiakasta sivulla').'">100</button>';
		echo '<button class="btn '.$defcl2000.' kpl" kpl="2000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 2000 '.Yii::t('main', 'asiakasta sivulla').'">2000</button>';
		echo '<button class="btn '.$defcl10000.' kpl" kpl="10000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10000 '.Yii::t('main', 'asiakasta sivulla').'">10000</button>';
		?>
		</div>
	</h2>
	<div id="netvisor-modal" class="modal fade" aria-hidden="true" role="dialog" tabindex="-1">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-title">
						<h2 class="modal-title">Oletko varma, että haluat lisätä Netvisoriin kaikki yhteystiedot?</h2>
					</div>
				</div>
				<div class="modal-body">
					<p>Tämä ominaisuus saattaa luoda duplikaatteja Netvisoriin niistä asiakkaista, jotka eivät ole jo siellä. </p>
				</div>
				<div class="modal-footer">
				<?php echo CHtml::link('Kyllä', array('kaikki_netvisoriin'), array('class'=>'btn btn-success' )); ?>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Sulje</button>
				</div>
			</div>
		</div>
	</div>


   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

								<!-- Autocomplete -->
								<?php
					   			$site = Yii::app()->createController('Site');
								$mod = 'Asiakkaat';
								$sarake = 'yrityksen_nimi';
								$placeholder = 'Asiakas';
								if(isset($_GET[$sarake])) 			
									$postvalue = $_GET[$sarake]; 
								else 
									$postvalue = '';				
						 	        $site[0]->autocompleteFor($mod, array('yrityksen_nimi', 'etunimi', 'sukunimi'), $placeholder, $postvalue);
								?>
								<!-- Autocomplete -->


                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
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
								$mod = 'Kohteet';
								$sarake = 'osoite';
								$placeholder = 'Osoite';
								if(isset($_GET[$sarake])) $postvalue = $_GET[$sarake]; else $postvalue='';
						 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
								?>
								<!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    			<input type="text" name="tag" class="gui-input" value="<?php if(isset($_GET['tag'])) echo $_GET['tag']; ?>" placeholder="<?php echo Yii::t('main','TAG numero'); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-at"></i>
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

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive" id="tableContent">
  <table class="table table-hovered" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Kohteet'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_tag_report',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',
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

<script type="text/javascript">
$(document).ready(function(){

 if($("#akt").val())
 $("#aktiivinen").val($("#akt").val());
 else
 $("#aktiivinen").val(1);

 if($("#ryhma").val())
 $("#ryhmaSelect").val($("#ryhma").val());

 if($("#tyoryhma").val())
 $("#tyoryhmaSelect").val($("#tyoryhma").val());

 if($("#as_tyyppi").val())
 $("#tyyppi").val($("#as_tyyppi").val());


 $(".haemob").click(function(){
	$("#mobForm").submit();
 });

 $(".kpl").click(function(){
	var asiakkaatPerSivu = $(this).attr('kpl');
        $.ajax({
           url: 'index',
           type: "POST",
           data: { "asiakkaatPerSivu" : asiakkaatPerSivu },
           success: function(data){
		var d = JSON.parse(data);
		window.location.reload();

           }
        });
 });
 // <-- Huonot symbolit hakussa
 $("input").keyup(function(){
	var specialChars = "<>!#$%^&*()_+[]{}?:;|'\"\\/~`=";
	var checkForSpecialChar = function(string){
	 for(i = 0; i < specialChars.length;i++){
	   if(string.indexOf(specialChars[i]) > -1){
	       return true
	    }
	 }
	 return false;
	}

	var str = $(this).val();
	if(checkForSpecialChar(str)){
	  alert("Tämä merkki ei sallittu");
	  $('.haemob').addClass('disabled');
	  $(this).addClass('bg-danger');
	} else {
	  $('.haemob').removeClass('disabled');
	  $(this).removeClass('bg-danger');
	}
 });
 //     Huonot symbolit hakussa -->
});
</script>
