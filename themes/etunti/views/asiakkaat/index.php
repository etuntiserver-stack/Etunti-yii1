<?php


?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Asiakkaat'); ?> 
	<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/asiakkaat/create',array('class'=>'btn btn-default fa fa-plus','data-toggle'=>'tooltip', 'data-placement'=>'top', 'title' => Yii::t('main', 'Lisää asiakas') )); ?>
	<?php echo CHtml::link('<i class="fa fa-edit"></i>',Yii::app()->request->baseUrl.'/index.php/asiakkaat/massamuokkaus',array('class'=>'btn btn-default','data-toggle'=>'tooltip', 'data-placement'=>'top', 'title' => Yii::t('main', 'Muokkaa kaikkia kerrallaan') )); ?>
	<?php if($netvisor): ?>
	<?php
		echo '
		<span data-toggle="tooltip" data-placement="top" title="'.Yii::t('main', 'Siirrä kaikki netvisoriin').'">
			<button id="netvisor-modal-toggle" class="btn btn-default">
				<i class="fa fa-share-square"></i>
			</button>
		</span>';
	?>
	<?php endif; ?>
	<?php if(!empty(Yii::app()->user->kotipuhtaaksi)):?>
	<?php echo CHtml::link('Tarkista asiakkaat', Yii::app()->request->baseUrl.'/index.php/asiakkaat/checkworkgroups',
		["class" => "btn btn-default", "data-toggle" => "tooltip",
		 	"data-placement" => "top", "title" => "Tarkista asiakkaiden työryhmät ja kustannuspaikat"]
		); ?>
	<?php endif; ?>
	<?php $checkOikeus = "asiakkaat_5_".Yii::app()->user->adminStatus; ?>
	<?php if($site[0]->checkOikeusFields($checkOikeus) == 1): ?>
	 <div class="pull-right">
	  <form action="<?=Yii::app()->request->baseUrl?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="excel_list" value="true">
	    <input type="hidden" name="ext" value="xls">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	 </div>
	<?php endif; ?>

<script type="text/javascript">
$(document).ready(function() {

  $(".submitForm").on('click', function(e){
	$(this).prev('textarea').val($('#tableContent').html());
	$(this).closest('form').submit();
	e.preventDefault();
  });

  $("#netvisor-modal-toggle").on('click', function(e) {
	$("#netvisor-modal").modal();
  });

});
</script>

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
		 	        $site[0]->autocompleteFor($mod, array('yrityksen_nimi', 'etunimi', 'sukunimi'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->


                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field select">
			   <select class="gui-input" name="myyja" id="myyja">
       				<option value=><?php echo Yii::t('main', 'Myyjä'); ?></option>
				<?php foreach(Administrators::model()->findAll() as $item): ?>
       				<option value="<?=$item->id?>" <?=(isset($_GET['myyja']) and $_GET['myyja'] == $item->id)?'selected':''?>><?=$item->adm_nimi?></option>
				<?php endforeach; ?>
			   </select>
                            <i class="arrow double"></i>
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

		        <?php if(isset($_GET['tyoryhma'])) echo '<input type="hidden" id="tyoryhma" value="'.$_GET['tyoryhma'].'">'; ?>
                        <div class="section">
                          <label class="field select">
						  <?php
								$list = array();
								$criteria 				= new CDbCriteria;
								$criteria->order 		= 'select_type ASC';
								$criteria->condition 	= "select_type='tyoryhma'";
							  	$l = Valikkoot::model()->findAll($criteria);
								foreach($l as $v)
								$list[$v->id] = $v->value;
						
								if(count($list) > 0)
								{
									echo CHtml::dropDownList('tyoryhma', 'tyoryhma', $list,
								array('empty'=>'Valitse työryhmä','class'=>'form-control form-group', 'id'=>'tyoryhmaSelect'));
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

							$criteria 				= new CDbCriteria;
							$criteria->order 		= 'select_type ASC';
							$criteria->condition 	= "select_type='asiakas_ryhma_real'";
							$l = Valikkoot::model()->findAll($criteria);

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

                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'kaupunki';
				$placeholder = 'Postitoimipaikka';
				if(isset($_GET[$sarake])) 			
					$postvalue = $_GET[$sarake]; 
				else 
					$postvalue = '';				
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
				$sarake = 'postinumero';
				$placeholder = 'Postinumero';
				if(isset($_GET[$sarake])) 			
					$postvalue = $_GET[$sarake]; 
				else 
					$postvalue = '';				
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
  <th></th>
  <th></th>
  <?php	if($this->tas(2)){ echo '<th></th>'; }  ?>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Yritys').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=yrityksen_nimi&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=yrityksen_nimi&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Asiakasnumero').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=asiakasnumero&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=asiakasnumero&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Osoite').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=osoite&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=osoite&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Postinumero').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=postinumero&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=postinumero&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Postitoimipaikka').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=kaupunki&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=kaupunki&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Yhteyshenkilo').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=yhteyshenkilo&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=yhteyshenkilo&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Puhelin').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=puhelin&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=puhelin&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Sähköposti').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=sahkoposti&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=sahkoposti&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Työryhmä').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=tyoryhma&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=tyoryhma&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo '
	<table>
	 <tr>
	  <td>
		<span class="p10">'.Yii::t('main', 'Toimialue').'</span>
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-down"></i>','index?sort=ryhma&s=asc').'
	  </td><td>
		'.CHtml::link('<i class="fa fa-arrow-up"></i>','index?sort=ryhma&s=desc').'
	  </td>
	 </tr>
	</table>';
      ?>
  </th>
  <th><?php echo Yii::t('main', 'Tyyppi'); ?></th>
  <th>
	<?php echo Yii::t('main', "Suunnitellut tunnit");?>
  </th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
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
