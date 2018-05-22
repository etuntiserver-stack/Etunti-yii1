<?php


?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Käyttäjät'); ?> 
	<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/asiakkaat/create',array('class'=>'btn btn-default fa fa-plus','data-toggle'=>'tooltip', 'data-placement'=>'top', 'title' => Yii::t('main', 'Lisää asiakas') )); ?>

	 <div class="pull-right">
	  <form action="<?=Yii::app()->request->baseUrl?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="excel_list" value="true">
	    <input type="hidden" name="ext" value="xls">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	 </div>

<script type="text/javascript">
$(document).ready(function() {

  $(".submitForm").on('click', function(e){
	$(this).prev('textarea').val(JSON.stringify($('#tableContent').html()));
	$(this).closest('form').submit();
	e.preventDefault();
  });

});
</script>

	 <div class="pull-right montakoRiviaSivulle">
	   <?php
	   ($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';
	   ($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
	   ($perSivu == 100) ? $defcl100 = 'btn-success' : $defcl100 = 'btn-default';
	   ($perSivu == 2000) ? $defcl2000 = 'btn-success' : $defcl2000 = 'btn-default';

	   echo '<button class="btn '.$defcl10.' kpl" kpl="10" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10 '.Yii::t('main', 'asiakasta sivulla').'">10</button>';
	   echo '<button class="btn '.$defcl50.' kpl" kpl="50" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 50 '.Yii::t('main', 'asiakasta sivulla').'">50</button>';
	   echo '<button class="btn '.$defcl100.' kpl" kpl="100" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 100 '.Yii::t('main', 'asiakasta sivulla').'">100</button>';
	   echo '<button class="btn '.$defcl2000.' kpl" kpl="2000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 2000 '.Yii::t('main', 'asiakasta sivulla').'">2000</button>';
	   ?>
	 </div>
	</h2>



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
		 	        $site[0]->autocompleteFor($mod, array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->


                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>

                      </div>

		      <?php if(isset($_GET['aktiivinen'])) echo '<input type="hidden" id="akt" value="'.$_GET['aktiivinen'].'">'; ?>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			   <select class="gui-input" name="aktiivinen" id="aktiivinen">
       				<option value="1"><?php echo Yii::t('main', 'Aktiiviset'); ?></option>
       				<option value="0"><?php echo Yii::t('main', 'Passiviset'); ?></option>
       				<option value="kaikki"><?php echo Yii::t('main', 'Kaikki'); ?></option>
			   </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="asiakasnumero" class="gui-input" value="<?php if(isset($_GET['asiakasnumero'])) echo $_GET['asiakasnumero']; ?>" placeholder="<?php echo Yii::t('main','Asiakasnumero'); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-at"></i>
                            </label>
                          </label>
                        </div>

                      </div>

                      <div class="col-md-2">

		      <?php if(isset($_GET['tyyppi'])) echo '<input type="hidden" id="as_tyyppi" value="'.$_GET['tyyppi'].'">'; ?>
                        <div class="section">
                          <label class="field select">
			    <?php
					$list = array(
						'yritys'=>Yii::t('main', 'Yritys'),
						 'henkilo'=>Yii::t('main', 'Henkilö') 
					);		
					if(count($list) > 0)
					{
			        	echo CHtml::dropDownList('tyyppi', 'tyyppi', $list,
					array('empty'=>'Asiakas tyyppi','class'=>'form-control form-group'));
					}
			    ?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

		      <?php if(isset($_GET['tilanne'])) echo '<input type="hidden" id="as_tilanne" value="'.$_GET['tilanne'].'">'; ?>
                        <div class="section">
                          <label class="field select">
			    <?php
					$list = array(
						'1'=>Yii::t('main', 'Tunnuksia ei vielä lähetetty'),
						'2'=>Yii::t('main', 'Käyttöehtoja ei vielä hyväksytty'),
						'3'=>Yii::t('main', 'Palvelu on käyttössä'),
						'4'=>Yii::t('main', 'Sähköposti puuttuu'),
					);		
					if(count($list) > 0)
					{
			        	echo CHtml::dropDownList('tilanne', 'tilanne', $list,
					array('empty'=>'Tilanne','class'=>'form-control form-group'));
					}
			    ?>
                            <i class="arrow double"></i>
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
				$sarake = 'puhelin';
				$placeholder = 'Puhelin';
				if(isset($_GET[$sarake])) $postvalue = $_GET[$sarake]; else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-phone"></i>
                            </label>
                          </label>
                        </div>


		      <?php if(isset($_GET['ryhma'])) echo '<input type="hidden" id="ryhma" value="'.$_GET['ryhma'].'">'; ?>
                        <div class="section">
                          <label class="field select">
			<?php
					$list = array();
			      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma_real' ",array('order' => "select_type"));
					foreach($l as $v)
					$list[$v->id] = $v->value;
			
					if(count($list) > 0)
					{
			        	echo CHtml::dropDownList('ryhma', 'ryhma', $list,
					array('empty'=>'Valitse ryhmä','class'=>'form-control form-group', 'id'=>'ryhmaSelect'));
					}
			?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="sahkoposti" class="gui-input" value="<?php if(isset($_GET['sahkoposti'])) echo $_GET['sahkoposti']; ?>" placeholder="<?php echo Yii::t('main','Sähköposti'); ?>">
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

		<button class="btn btn-primary myBgColors btn-lg btn-block laheta"><?=Yii::t('main', 'LÄHETÄ'); ?></button>


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
  <th><center>Valitse kaikki <input type="checkbox" class="valitse_kaikki"></center></th>
  <th><?=Yii::t('main', 'Yritys/Yhteyshenkilö')?></th>
  <th><?=Yii::t('main', 'Osoite')?></th>
  <th><?=Yii::t('main', 'Puhelin')?></th>
  <th><?=Yii::t('main', 'Sähköposti')?></th>
  <th><?=Yii::t('main', 'Työryhmä')?></th>
  <th><?=Yii::t('main', 'Toimialue')?></th>
  <th><?php echo Yii::t('main', 'Tyyppi'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_kayttajat',
	'viewData' => array( 'netvisor' => $netvisor ),
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

 if($("#as_tyyppi").val())
 $("#tyyppi").val($("#as_tyyppi").val());

 if($("#as_tilanne").val())
 $("#tilanne").val($("#as_tilanne").val());

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

 $(".valitse_kaikki").click(function(){
	if( this.checked )
	$('input:checkbox').prop("checked", true);
	else
	$('input:checkbox').prop("checked", false);
 });

 $(".laheta").click(function(){
	if(!confirm('Olet lähettämässä eDico-tunnuksia valituille asiakkaille.')){
		return false;
	}
	var arr = [];
	$( '.valitse_asiakas:checkbox:checked' ).each(function( ) {
	    if( $(this).attr('asiakas_id').length > 0 ){
  		arr.push($(this).attr('asiakas_id'));
	    }
	});
	if( arr.length == 0 ){
		alert('Valitse asiakas');
	}
	if( arr.length > 0 ){
	$(this).text('Odota..').removeClass('laheta');
        	$.ajax({
        	   url: 'lahetatunnukset',
        	   type: "POST",
        	   data: { "arr" : arr },
        	   success: function(data){
			console.log(data);
			if( data == 'ok' ){
			   window.location.href="kayttajat?valmis";
			}
        	   }
	        });
	}
 });


});
</script>
