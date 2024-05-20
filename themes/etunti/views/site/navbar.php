<?php 
$site = Yii::app()->createController('Site');

     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

  Yii::app()->user->domain = strtolower(Yii::app()->user->domain);
  $filepath = dirname(Yii::app()->getBasePath())."/img/noname.jpg";
  if(isset(Yii::app()->user->domain) and file_exists('img/admins/'.Yii::app()->user->domain.'/'.Yii::app()->user->id.".jpg") 
	and isset(Yii::app()->user->id))
  {
	  $filepath = dirname(Yii::app()->getBasePath()).'/img/admins/'.Yii::app()->user->domain.'/'.Yii::app()->user->id.".jpg";
  }
  $imageData = base64_encode(file_get_contents($filepath));
  $user_img = 'data: '.mime_content_type($filepath).';base64,'.$imageData;



$curpage = Yii::app()->getController()->getAction()->controller->id;
$curpage .= '/'.Yii::app()->getController()->getAction()->controller->action->id;
echo '<input type="hidden" id="curpage" value="'.$curpage.'">';

$tyovuorot_sivut = '';
if( $curpage == 'tyovuoroot/tv_kohteet' )
   $tyovuorot_sivut = Yii::t('main','Työvuorot kohteen mukaan');
elseif( $curpage == 'tyovuoroot/index' )
   $tyovuorot_sivut = Yii::t('main','Työvuorot viikkoittain');
if( $curpage == 'tyovuoroot/tv2' )
   $tyovuorot_sivut = Yii::t('main','Työvuorot työntekijöiden mukaan');
if( $curpage == 'tyovuoroot/tv3' )
   $tyovuorot_sivut = Yii::t('main','Työvuorot v3');
if( $curpage == 'tyovuoroot/beta' )
   $tyovuorot_sivut = Yii::t('main','Työvuorot v4');
?>
  

       
  <!-- Start: Header -->
    <header class="navbar navbar-fixed-top navbar-shadow">

      <div class="navbar-branding">
        <a class="navbar-brand" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/index">
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/img/logo.png" height="40">
        </a>
        <span id="toggle_sidemenu_l" class="ad ad-lines"></span>
      </div>
      <ul class="nav navbar-nav navbar-left">
        <li>
          <a class="sidebar-menu-toggle hidden" href="#">
            <span class="ad ad-ruby fs18"></span>
          </a>
        </li>
        <li data-toggle="tooltip" title="<?php echo Yii::t('main', 'Ylävalikko'); ?>">
          <a class="topbar-menu-toggle" href="#">
            <span class="ad ad-wand fs16"></span>
          </a>
        </li>
        <li class="hidden-xs" id="fullscreenTila" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Kokoruuduntila'); ?>">
          <a class="request-fullscreen toggle-active" href="#">
            <span class="ad ad-screen-full fs18"></span>
          </a>
        </li>
<?php 
/*
            <li id="domainTila">
              <a href="#">
                <span class="mr10"></span> <?php echo strtoupper(Yii::app()->user->domain); ?> </a>
            </li>
*/
?>
	    <?php if( !empty($tyovuorot_sivut) ) : ?>

<!--
            <li>
              <a href="#">
                <span class="mr10"></span> <?php echo $tyovuorot_sivut; ?> </a>
            </li>
-->


	<!-- Haku -->
	<?php if($curpage == 'tyovuoroot/tv2' or $curpage == 'tyovuoroot/index' or $curpage == 'tyovuoroot/tv3' or $curpage == 'tyovuoroot/beta') : ?>
	<?php 
		$year           = Yii::app()->session['year'];
		$week           = Yii::app()->session['week'];

		// <-- Previous Next Weeks
	   	$tv = Yii::app()->createController('Tyovuoroot');
	   	$getWeeks = $tv[0]->previousNextWeeks($year,$week);

		$previousWeek 	= $getWeeks['previousWeek'];
		$previousYear	= $getWeeks['previousYear'];
		$nextWeek 	= $getWeeks['nextWeek'];
		$nextYear 	= $getWeeks['nextYear'];
		//     Previous Next Weeks -->
	?>
	<?php endif; ?>

	<?php
		$hakuPainike = "";
	if(
		isset($_SESSION['haku_asiakas'])
		or isset($_SESSION['haku_kohde'])
		or isset(Yii::app()->session['tyo_toimialue'])
		or isset(Yii::app()->session['kohteiden_tyonimike'])
		or isset(Yii::app()->session['tyoryhma'])
	)
	{
		$hakuPainike = "bg-warning";
	}
	?>

        <li class="dropdown menu-merge <?php echo $hakuPainike; ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
		 <?php echo Yii::t('main','HAKU'); ?>
            <span class="caret caret-tp hidden-xs"></span>
          </a>
          <ul class="dropdown-menu list-group dropdown-persist" role="menu" style="width:600px">
	  <form action="#" class="admin-form" method="POST">
            <li class="list-group-item">
            <div class="row">
             <div class="col-sm-6">
	     <legend><?php echo Yii::t('main','Haku'); ?></legend>

              <div class="form-group">
		<?php if($curpage == 'tyovuoroot/beta' and isset($_GET['mode']) and $_GET['mode'] == 'tt') : ?>
		    <label><?php echo Yii::t('main','Aikaväli'); ?></label>
			<div class="row">
			 <div class="col-sm-6">
	   			<input type="text" name="from" class="form-control datepickerFI" value="<?php if(isset(Yii::app()->session['from'])) echo date('d.m.Y', strtotime(Yii::app()->session['from'])); ?>" placeholder="<?php echo Yii::t('main' ,'Päivämäärä'); ?>">
			 </div><div class="col-sm-6">
	   			<input type="text" name="to" class="form-control datepickerFI" value="<?php if(isset(Yii::app()->session['to'])) echo date('d.m.Y', strtotime(Yii::app()->session['to'])); ?>" placeholder="<?php echo Yii::t('main' ,'Päivämäärä'); ?>">
			 </div>
			</div>
		<?php endif; ?>

		<?php if( $curpage == 'tyovuoroot/beta' and isset($_GET['mode']) and $_GET['mode'] == 'vko' ) : ?>
			<div class="row">
			 <div class="col-sm-5">
		    	  <label><?php echo Yii::t('main','Vuosi'); ?></label>
			  <select class="form-control" name="year">
				<?php
				$v = date('Y',strtotime('-5 year'));
				for ($i = 1; $i <= 10; $i++) {
					if($year == ($v+$i))
			    			echo '<option value="'.(int)($v+$i).'" selected>'.(int)($v+$i).'</option>';
					else
			    			echo '<option value="'.(int)($v+$i).'">'.(int)($v+$i).'</option>';
				}
				?>
		 	  </select>
			 </div><div class="col-sm-7">
		    	  <label><?php echo Yii::t('main','Viikko'); ?></label>
			  <select class="form-control" name="week">
				<?php
				define('NL', "\n");
				$firstDayOfYear = mktime(0, 0, 0, 1, 1, $year);
				$nextMonday     = strtotime('monday', $firstDayOfYear);
				$nextSunday     = strtotime('sunday', $nextMonday);
				
				    echo '<option value="'.$week.'">'.$week.', '.date('d.m', strtotime($year ."W". $week . '1')), NL.'-'.date('d.m', strtotime($year ."W". $week . '7')), NL.'</option>';

				while (date('Y', $nextMonday) == $year) {
				    echo '<option value="'.date('W', $nextMonday), NL.'">'.date('W', $nextMonday), NL.', '.date('d.m', $nextMonday), NL.'-'.date('d.m', $nextSunday), NL.'</option>';
	
				    $nextMonday = strtotime('+1 week', $nextMonday);
				    $nextSunday = strtotime('+1 week', $nextSunday);
				}
				?>
			  </select>
			 </div>
			</div>
		<?php endif; ?>
	      </div>


              <div class="form-group">
		    <label><?php echo Yii::t('main','Työntekijät'); ?></label>

				<?php
		   		$tyontekiatLista = $site[0]->tyontekiatLista( 
					'tyontekijat', // name
					'multTyontekijat', // class
					null, // id
					Yii::app()->session['tyontekijat'], //selected
					1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>

	      </div>

             </div><div class="col-sm-6">
	     <legend><?php echo Yii::t('main','Ekstrat'); ?></legend>

              <div class="form-group">
		    <label><?php echo Yii::t('main','Asiakas'); ?></label>
		      <input type="text" class="form-control" name="haku_asiakas" id="asiakasHakussa" placeholder="<?php echo Yii::t('main','Yritys, Yhteyshenkilö, Puhelin'); ?>..." value="<?php if(isset($_SESSION['haku_asiakas'])) echo $_SESSION['haku_asiakas']; ?>" AUTOCOMPLETE="off">
			<div id="asiakasAutocompleteResultHakussa"></div>
	      </div>

              <div class="form-group">
		    <label><?php echo Yii::t('main','Kohde'); ?></label>
		      <input type="text" class="form-control" name="haku_kohde" id="kohdeHakussa" placeholder="<?php echo Yii::t('main','Osoite, Puhelin'); ?>..." value="<?php if(isset($_SESSION['haku_kohde'])) echo $_SESSION['haku_kohde']; ?>" AUTOCOMPLETE="off">
			<div id="kohdeAutocompleteResultHakussa"></div>
	      </div>

              <div class="form-group">
		 <label><?php echo Yii::t('main','Työntekijän toimialue'); ?></label>
		        <?php
			// Toimialue
			$list = array();
			$criteria = new CDbCriteria();
			$criteria->order = " select_type ";
			$criteria->condition = " select_type='tyo_toimialue' ";
			$l = Valikkoot::model()->findAll($criteria);
			foreach($l as $v)
			$list[$v->value] = $v->value;
			
			echo '<select name="tyo_toimialue[]" class="multToimialue" multiple title="Toimialue">';
			foreach($list as $key=>$val){
			  if(isset(Yii::app()->session['tyo_toimialue']) and in_array($key, Yii::app()->session['tyo_toimialue']))
			    echo '<option value="'.$key.'" selected>'.$val.'</option>';
			  else
			    echo '<option value="'.$key.'">'.$val.'</option>';
			}
			echo '</select>';
		       ?>
	      </div>

              <div class="form-group">
		 <label><?php echo Yii::t('main','Työntekijän työryhmä'); ?></label>
		        <?php
			// Toimialue
			$list = array();
			$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		       	$criteria = new CDbCriteria();
			$criteria->order = " value ";
			$criteria->condition = "select_type='tyoryhma'";

			// <-- Tyoryhmat
			$arr = $site[0]->TyoryhmatHelper();
			$ids = implode(",", $arr);
			if( count($arr) > 0 ){
				$criteria->addCondition (" id IN ($ids) ");	
			}
			//     Tyoryhmat -->

			$l = Valikkoot::model()->findAll($criteria);
			foreach($l as $v)
			$list[$v->value] = $v->value;
			
			echo '<select name="tyoryhma[]" class="multTyoryhma" multiple title="Työryhmät">';
			foreach($list as $key=>$val){
			  if(isset(Yii::app()->session['tyoryhma']) and in_array($key, Yii::app()->session['tyoryhma']))
			    echo '<option value="'.$key.'" selected>'.$val.'</option>';
			  else
			    echo '<option value="'.$key.'">'.$val.'</option>';
			}
			echo '</select>';
		       ?>
	      </div>

              <div class="form-group">
		    <label><?php echo Yii::t('main','Kohteiden työnimike'); ?></label>
		    <?php
			// Kohteen ryhman mukaan
			$list = array();
			$criteria = new CDbCriteria();
			$criteria->order = " select_type ";
			$criteria->condition = " select_type='siivous' ";
			$l = Valikkoot::model()->findAll($criteria);
			foreach($l as $v)
			$list[$v->value] = $v->value;

			echo '<select name="kohteiden_tyonimike" class="form-control">';
			    echo '<option value="">'.Yii::t('main','Kaikki').'</option>';
			foreach($list as $key=>$val){
			  if(isset(Yii::app()->session['kohteiden_tyonimike']) and $key ==Yii::app()->session['kohteiden_tyonimike'])
			    echo '<option value="'.$key.'" selected>'.$val.'</option>';
			  else
			    echo '<option value="'.$key.'">'.$val.'</option>';
			}
			echo '</select>';
		    ?>
	      </div>

             </div>
            </li>


            <li class="list-group-item">
              <span class="animated animated-short fadeInUp">
		<div class="row">
		 <div class="col-sm-12">
		        <button type="submit" class="col-sm-10 btn btn-primary" name="haku" controller="<?php echo $curpage; ?>" type="button"><?php echo Yii::t('main','Hae'); ?></button>
			<a class="col-sm-2 btn btn-default" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/<?php echo $curpage; ?>?reset<?=((isset($_GET['mode']))?'&mode='.$_GET['mode']:'')?>"><i class="fa fa-remove"></i></a>
		 </div>
		</div>
	      </span>
            </li>

	  </form>
          </ul>
        </li>
	<!-- Haku -->

	<?php if( $curpage == 'tyovuoroot/beta' and isset($_GET['mode']) and $_GET['mode'] == 'vko' ) : ?>
        <li class="p10" data-toggle="tooltip">
              <div class="form-group">
               <div class="form-inline">

		     	<a href="<?php echo $_SERVER['PHP_SELF'].'?week='.$previousWeek.'&year='.$previousYear.'&mode='.((isset($_GET['mode']))?$_GET['mode']:'').((isset($_GET['vapaat']))?'&vapaat=true':''); ?>">
				<i class="fa fa-arrow-left btn btn-default btn-group" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Edellinen viikko'); ?>"></i>
			</a>

			<?php
				$firstDayOfYear = mktime(0, 0, 0, 1, 1, $year);
				$nextMonday     = strtotime('monday', $firstDayOfYear);
				$nextSunday     = strtotime('sunday', $nextMonday);
			?>

			<select class="form-control form-group" id="viikkonhyppaminen">
			<?php
			    echo '<option value="'.$_SERVER['PHP_SELF'].'?week='.$week.'&year='.$year.'">'.Yii::t('main', 'Viikko').': '.date('W', strtotime($year ."W". $week . '1')).', '.Yii::t('main', 'Vuosi').': '.date('Y', strtotime($year ."W". $week . '1')).', '.date('d.m', strtotime($year ."W". $week . '1')).' - '.date('d.m', strtotime($year ."W". $week . '7')).'</option>';

			while (date('Y', $nextMonday) == $year) {
			    echo '<option value="'.$_SERVER['PHP_SELF'].'?week='.date('W', $nextMonday).'&year='.$year.'&mode='.((isset($_GET['mode']))?$_GET['mode']:'').((isset($_GET['vapaat']))?'&vapaat=true':'').'">'.Yii::t('main', 'Viikko').': '.date('W', $nextMonday).', '.Yii::t('main', 'Vuosi').': '.date('Y', $nextMonday).', '.date('d.m', $nextMonday).' - '.date('d.m', $nextSunday).'</option>';
	
			    $nextMonday = strtotime('+1 week', $nextMonday);
			    $nextSunday = strtotime('+1 week', $nextSunday);
			}
      $nextYearIndex = 0;
      while($nextYearIndex < 3) {
        echo '<option value="'.$_SERVER['PHP_SELF'].'?week='.date('W', $nextMonday).'&year='.date('Y', $nextMonday).'&mode='.((isset($_GET['mode']))?$_GET['mode']:'').((isset($_GET['vapaat']))?'&vapaat=true':'').'">'.Yii::t('main', 'Viikko').': '.date('W', $nextMonday).', '.Yii::t('main', 'Vuosi').': '.date('Y', $nextMonday).', '.date('d.m', $nextMonday).' - '.date('d.m', $nextSunday).'</option>';

        $nextMonday = strtotime('+1 week', $nextMonday);
        $nextSunday = strtotime('+1 week', $nextSunday);
        $nextYearIndex++;
      }
			?>
			</select>

		     	<a href="<?php echo $_SERVER['PHP_SELF'].'?week='.$nextWeek.'&year='.$nextYear.'&mode='.((isset($_GET['mode']))?$_GET['mode']:'').((isset($_GET['vapaat']))?'&vapaat=true':''); ?>">
				<i class="fa fa-arrow-right btn btn-default btn-group" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Seuraava viikko'); ?>"></i>
			</a> 
			<button class="btn btn-default fa fa-calendar-check-o" id="vkolopput" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Viikonloput'); ?>"></button>
               </div>
              </div>
        </li>
	<?php endif; ?>
	<!-- Viikonloput -->


	<!-- Tilaus -->
        <li class="p10" data-toggle="tooltip">
		<button class="btn btn-default fa fa-shopping-cart uusiTilaus" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Uusi tilaus'); ?>"></button>
		<button class="btn btn-default fa fa-angle-double-down tvasetus <?=((!isset($_SESSION['skrollaus']))?'btn-success':'')?>" for="skrollaus" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Skrollaus'); ?>"></button>
        </li>
	<!-- Tilaus -->



<script type="text/javascript">
$(document).ready(function(){

// <-- Asiakas Autocomplete
  $('#asiakasHakussa').keyup(function(){
	var thisVal = $(this).val();

	if( thisVal.length >= 2 )
	{

	  	 $.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/asiakas_autocomplete',
			type:'GET',
			async : false,
			data: { "key" : thisVal },
			  success:function(data){
				data = JSON.parse(data);
			  	//console.log(data);
				if(data)
					$('#asiakasAutocompleteResultHakussa').html(data).show();

			  },
			  error:function(data){
			  	console.log(data);
			  }
	 	});

	} else {
					$('#asiakasAutocompleteResultHakussa').html('');
	}

     $('.asiakasSelecter').click(function(){
		var thisAsiakas = $(this).text();
		$('#asiakasHakussa').val(thisAsiakas);
		$('#asiakasAutocompleteResultHakussa').html('');
     });

  });
// Asiakas Autocomplete -->


// <-- Kohde Autocomplete
  $('#kohdeHakussa').keyup(function(){
	var thisVal = $(this).val();

	if( thisVal.length >= 2 )
	{

	  	 $.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/kohde_autocomplete',
			type:'GET',
			async : false,
			data: { "key" : thisVal },
			  success:function(data){
				data = JSON.parse(data);
			  	//console.log(data);
				if(data)
					$('#kohdeAutocompleteResultHakussa').html(data).show();

			  },
			  error:function(data){
			  	console.log(data);
			  }
	 	});

	} else {
					$('#kohdeAutocompleteResultHakussa').html('');
	}

     $('.kohdeSelecter').click(function(){
		var thisAsiakas = $(this).text();
		$('#kohdeHakussa').val(thisAsiakas);
		$('#kohdeAutocompleteResultHakussa').html('');
     });

  });
// Kohde Autocomplete -->

$(".tvasetus").click(function(){
   var whatfor = $(this).attr('for');
   $.ajax({
	url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/tvasetus?whatfor=' + whatfor,
	success:function(data){
		console.log(data);
		data = JSON.parse(data);
		if( data['skrollaus'] )
			window.location.reload();
   	},
	error:function(data){
		console.log(data);
    	}
    });

});

$('.multTyontekijat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki työntekijät"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});

$('.multToimialue').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki toimialueet"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});

$('.multTyoryhma').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki työryhmät"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});

});
</script>

	    <li class="muokkausLi" style="display:none">
	     <a href="#" class="bg-warning">
	        <span class="mr10">Muokkaus tila</span>
	     </a>
	    </li>
	    <li class="muokkausLi" style="display:none">
	     <a href="#" class="trash fa fa-trash-o" style="font-size: 150%" data-toggle="tooltip" data-placement="bottom" title="Poista valitut työvuorot">
	     </a>
	    </li>
	    <li class="muokkausLi" style="display:none">
	     <a href="#" class="clear fa fa-circle-o-notch" style="font-size: 150%" data-toggle="tooltip" data-placement="bottom" title="Keskeytä">
	     </a>
	    </li>
	    <?php endif; ?>

      </ul>

      <ul class="nav navbar-nav navbar-right">
<?php /*
        <li class="dropdown menu-merge" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Uusimmat viestit'); ?>">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <span class="ad ad-radio-tower fs18" id="notiFyClick"></span>
          </a>
          <ul class="dropdown-menu media-list w350 animated animated-shorter fadeIn" role="menu">
            <li class="dropdown-header">
              <span class="dropdown-title"> Notifications</span>
              <span class="label label-warning">5</span>
            </li>
	    <get id="viestiGet"></get>
	    	
          </ul>
        </li>
*/ ?>
	<!-- Ilmoitus kaikkille -->
	<?php
	$criteria = new CDbCriteria();
	$criteria->condition = " 
		NOW() BETWEEN aloitus AND lopetus
		AND vastaanottajat LIKE '%".Yii::app()->user->domain."%'
	";
	$ilmoitukset = IlmoitusKaikkille::model()->findAll($criteria);
	$ilmoitukset_content = '';
	if(count($ilmoitukset) > 0){
		$ilmoitukset_content = '';
		foreach($ilmoitukset as $item){
			$ilmoitukset_content .= '<div class="panel">
	                <div class="panel-heading bg-danger">
        	          <span class="panel-title">'.$item->otsikko.'</span>
        	        </div>
        	        <div class="panel-body">';
			$files = Asetukset::model()->getFiles(
				'digisten', 
				'digisten_ilmoitukset', 
				$item->id,
				true,
				false
			);
			$ilmoitukset_content .= '<div style="color:#333">'.str_replace("\n", "<br>", $item->viesti).'</div>';
			if( !empty($files) ){
				$ilmoitukset_content .= '<br><label>Tiedostot:</label><br>'.$files;
			}
			$ilmoitukset_content .= '</div></div>';
		}

	}
	?>
	<?php if( isset($ilmoitukset) and count($ilmoitukset) > 0 ): ?>
        <li class="p10" data-toggle="tooltip">
              <div class="form-group">
	 	<div class="btn btn-danger pulsar fa fa-envelope" data-toggle="collapse" data-target="#myModalIlmoitukset" title="<?php echo Yii::t('main', 'Uusi ilmoitus'); ?>"></div>
	      </div>
	</li>

	<div id="myModalIlmoitukset" class="collapse" style="position: absolute; left: 25%; top: 40px;">
	  <div class="modal-dialog modal-lg">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
	        <h4 class="modal-title">Ilmoitukset</h4>
	      </div>
	      <div class="modal-body">
	        <p><?=$ilmoitukset_content?></p>
	      </div>
	      <div class="modal-footer">
	        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>-->
	      </div>
	    </div>

	  </div>
	</div>
	<?php endif; ?>
	<!-- Ilmoitus kaikkille -->



        <li class="dropdown menu-merge" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Luo uusi'); ?>">
          <a href="#" class="dropdown-toggle " data-toggle="dropdown"> 
	    <i class="fa fa-plus"></i>
            <span class="caret caret-tp hidden-xs"></span>
          </a>
          <ul class="dropdown-menu list-group dropdown-persist w250" role="menu">
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/create" class="animated animated-short fadeInUp">
                <span class="fa fa-plus"></span> <?php echo Yii::t('main','Asiakas'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kohteet/create" class="animated animated-short fadeInUp">
                <span class="fa fa-plus"></span> <?php echo Yii::t('main','Kohde'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/create" class="animated animated-short fadeInUp">
                <span class="fa fa-plus"></span> <?php echo Yii::t('main','Työntekijä'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/viestinta/create" class="animated animated-short fadeInUp">
                <span class="fa fa-plus"></span> <?php echo Yii::t('main','Viesti'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/lasku/create" class="animated animated-short fadeInUp">
                <span class="fa fa-plus"></span> <?php echo Yii::t('main','Lasku'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="#" class="animated animated-short fadeInUp uusiTilaus">
                <span class="fa fa-plus"></span> <?php echo Yii::t('main','Uusi tilaus'); ?> </a>
            </li>
          </ul>
        </li>

        <li class="dropdown menu-merge" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Valitse kieli'); ?>">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
             <span class=""></span> 
		<?php
		$lang = 'fi';
		if(isset($_SESSION['lang']) and !empty($_SESSION['lang']))
  		$lang = $_SESSION['lang'];
		echo strtoupper ($lang);
		?>
          </a>
          <ul class="dropdown-menu pv5 animated animated-short flipInX" role="menu">
            <li>
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu?lang=fi">
                <span class="mr10"></span> Suomi </a>
            </li>
            <li>
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu?lang=en">
                <span class="mr10"></span> English </a>
            </li>
          </ul>
        </li>

        <li class="dropdown menu-merge" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Asetukset'); ?>">
          <a href="#" class="dropdown-toggle " data-toggle="dropdown"> 
	    <i class="fa fa-gear"></i>
            <span class="caret caret-tp hidden-xs"></span>
          </a>
          <ul class="dropdown-menu list-group dropdown-persist w250" role="menu">
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetukset/update?id=1" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Asetukset'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetukset/yrityksentiedot?id=1" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?=Yii::t('main','Yrityksen tiedot')?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetukset/oikeudet" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?=Yii::t('main','Käyttöoikeudet')?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetukset/tiedostot?id=1" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?=Yii::t('main','Tiedostot')?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/administrators/index" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Käyttäjät'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/ohjesivu" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Ohjeet'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/log/index" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Historia'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tietosuoja/index?id=1" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Tietosuoja'); ?> </a>
            </li>
<!--
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/laskuHistoria/index" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Lasku historia'); ?> </a>
            </li>
-->
          </ul>
        </li>

        <li class="dropdown menu-merge">
          <a href="#" class="dropdown-toggle " data-toggle="dropdown"> 
	    <i class="fa fa-question-circle"></i>
            <span class="caret caret-tp hidden-xs"></span>
          </a>
          <ul class="dropdown-menu list-group dropdown-persist w250" role="menu">
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/ohjeet" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main', 'Lue ohjeet'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="https://app.etunti.fi/lib/pdf/etunti_ko.pdf" target="_blank" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main', 'Lue ohjeet'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="https://app.etunti.fi/lib/pdf/etunti_mob.pdf" target="_blank" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main', 'Mobiilisovelluksen käyttöohje'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/ohjevideot" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main', 'Katso ohjevideot'); ?> </a>
            </li>
	  </ul>
	</li>

        <li class="dropdown menu-merge" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Valitse värit'); ?>">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
             <span class="fa fa-eyedropper"></span> 

	  </a>
          <ul class="dropdown-menu pv5 animated animated-short flipInX" role="menu">
            <li>

  <div id="skin-toolbox">
    <div class="panel">
      <div class="panel-heading">

      </div>
      <div class="panel-body pn">

        <div class="text-dark">
          <div class="col-sm-6">
            <form id="toolbox-header-skin">
              <h4 class="mv20"><?php echo Yii::t('main','Väri'); ?></h4>
              <div class="skin-toolbox-swatches">
                <div class="checkbox-custom checkbox-disabled fill mb5">
                  <input type="radio" name="headerSkin" id="headerSkin8" checked value="">
                  <label for="headerSkin8">Light</label>
                </div>
                <div class="checkbox-custom fill checkbox-primary mb5">
                  <input type="radio" name="headerSkin" id="headerSkin1" value="bg-primary">
                  <label for="headerSkin1">Primary</label>
                </div>
                <div class="checkbox-custom fill checkbox-info mb5">
                  <input type="radio" name="headerSkin" id="headerSkin3" value="bg-info">
                  <label for="headerSkin3">Info</label>
                </div>
                <div class="checkbox-custom fill checkbox-warning mb5">
                  <input type="radio" name="headerSkin" id="headerSkin4" value="bg-warning">
                  <label for="headerSkin4">Warning</label>
                </div>
                <div class="checkbox-custom fill checkbox-danger mb5">
                  <input type="radio" name="headerSkin" id="headerSkin5" value="bg-danger">
                  <label for="headerSkin5">Danger</label>
                </div>
                <div class="checkbox-custom fill checkbox-alert mb5">
                  <input type="radio" name="headerSkin" id="headerSkin6" value="bg-alert">
                  <label for="headerSkin6">Alert</label>
                </div>
                <div class="checkbox-custom fill checkbox-system mb5">
                  <input type="radio" name="headerSkin" id="headerSkin7" value="bg-system">
                  <label for="headerSkin7">System</label>
                </div>
                <div class="checkbox-custom fill checkbox-success mb5">
                  <input type="radio" name="headerSkin" id="headerSkin2" value="bg-success">
                  <label for="headerSkin2">Success</label>
                </div>
                <div class="checkbox-custom fill mb5">
                  <input type="radio" name="headerSkin" id="headerSkin9" value="bg-dark">
                  <label for="headerSkin9">Dark</label>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-6">
            <form id="toolbox-sidebar-skin">
              <h4 class="mv20"><?php echo Yii::t('main','Sivupalkin väri'); ?></h4>
              <div class="skin-toolbox-swatches">
                <div class="checkbox-custom fill mb5">
                  <input type="radio" name="sidebarSkin" checked id="sidebarSkin3" value="">
                  <label for="sidebarSkin3">Dark</label>
                </div>
                <div class="checkbox-custom fill checkbox-disabled mb5">
                  <input type="radio" name="sidebarSkin" id="sidebarSkin1" value="sidebar-light">
                  <label for="sidebarSkin1">Light</label>
                </div>
                <div class="checkbox-custom fill checkbox-light mb5">
                  <input type="radio" name="sidebarSkin" id="sidebarSkin2" value="sidebar-light light">
                  <label for="sidebarSkin2">Lighter</label>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

        <div class="form-group mn br-t p15">
          <a href="#" id="clearLocalStorage" class="btn btn-primary btn-block pb10 pt10"><?php echo Yii::t('main', 'Palauta oletusasetukset'); ?></a>
        </div>

	</li>
      </ul>
     </li>




        <li class="dropdown menu-merge" id="etuSukuNimi">
          <a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown"> 
	     <etuSukuNimi>
		<img src="<?php echo $user_img; ?>" alt="avatar" class="mw30 br64 mr15"> 
		<?php if(isset(Yii::app()->user->nimi)) echo Yii::app()->user->nimi; ?>
                <span class="caret caret-tp hidden-xs"></span>
	     </etuSukuNimi>

	     <etuSukuNimiSm>
		<span class="fa fa-user" aria-hidden="true"></span>
	     </etuSukuNimiSm>

          </a>




          <ul class="dropdown-menu list-group dropdown-persist w250" role="menu">
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/administrators/update?id=<?php echo Yii::app()->user->id; ?>" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Omat asetukset'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/user/logout" class="animated animated-short fadeInUp">
                <span class="fa fa-power-off"></span> <?php echo Yii::t('main','Kirjaudu ulos'); ?> </a>
            </li>
          </ul>
        </li>
        <li id="toggle_sidemenu_t">  
        		<span class="fa fa-caret-up"></span>
        </li>



      </ul>

    </header>
    <!-- End: Header -->

    <!-- Start: Sidebar -->
    <aside id="sidebar_left" class="">

      <!-- Start: Sidebar Left Content -->
      <div class="sidebar-left-content nano-content">
        <!-- Start: Sidebar Menu -->
        <ul class="nav sidebar-menu">

          <li>
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu">
              <span class="fa fa-home"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Etusivu'); ?></span>
            </a>
          </li>



          <li>
            <a class="accordion-toggle asiakkaidenHallinta" href="#">
              <span class="fa fa-user"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Asiakkaiden hallinta'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/index">
                  <span class="glyphicon glyphicon-home"></span> <?php echo Yii::t('main', 'Asiakkaat'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kohteet/index">
                  <span class="glyphicon glyphicon-home"></span> <?php echo Yii::t('main', 'Kohteet'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/tag_report">
                  <span class="glyphicon glyphicon-home"></span> <?php echo Yii::t('main', 'TAG raportti'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/avaimet/index">
                  <span class="fa fa-key"></span> <?php echo Yii::t('main', 'Avaimet'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/avaimet/avaimet_tyontekijalle">
                  <span class="fa fa-key"></span> <?php echo Yii::t('main', 'Avaimet työvuoroittain'); ?></a>
              </li>
              <li>
                <a href="<?= Yii::app()->request->baseUrl; ?>/index.php/avaimet/employeekeys">
                  <span class="fa fa-key"></span><?= Yii::t("main", "Tarvittavat avaimet"); ?>
                </a>
              </li>
              <!--li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/kartta">
                  <span class="fa fa-map"></span> <?php echo Yii::t('main', 'Kartta'); ?></a>
              </li-->
	      <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/laskutettu">
                  <span class="glyphicon glyphicon-phone"></span> <?php echo Yii::t('main', 'Laskutettavat kohteet'); ?></a>
              </li>
            </ul>
          </li>


	  <?php if( $site[0]->checkOikeusFields("tuntienhallinta_4_".Yii::app()->user->adminStatus) == 1 ): ?>
          <li>
            <a class="accordion-toggle tuntienHallinta" href="#">
              <span class="fa fa-mobile"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Tuntien hallinta'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/index">
                  <span class="glyphicon glyphicon-phone"></span> <?php echo Yii::t('main', 'Tunnit'); ?></a>
              </li>


          <li>
            <a class="accordion-toggle tuntienHyvaksynta" href="#">
              <span class="fa fa-bars"></span>
              <span><?php echo Yii::t('main', 'Tuntien hyväksyntä'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/toteutuneet/index">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntien hyväksyntä'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/hyvaksymattomat">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Hyväksymättömät tunnit'); ?></a>
              </li>    
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/lahetys_asiakkaalle">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Lähetä hyväksyttäväksi'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakasHyvaksynta/index">
                  <span class="glyphicon glyphicon-ok"></span> <?php echo Yii::t('main', 'Asiakkaiden hyväksymät tunnit'); ?></a>
              </li>
            </ul>
          </li>

              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/toteutuneet/kk">
                  <span class="fa fa-calendar-check-o"></span> <?php echo Yii::t('main', 'Tuntien toteuma kk'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/toteutuneet/vko">
                  <span class="fa fa-calendar-check-o"></span> <?php echo Yii::t('main', 'Hyväksytyt (VKO)'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/palkkataulukko">
                  <span class="fa fa-eur"></span> <?php echo Yii::t('main', 'Tiedot palkanlaskentaan mobiilista'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/palkkataulukko">
                  <span class="fa fa-eur"></span> <?php echo Yii::t('main', 'Tiedot palkanlaskentaan työvuorosta'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/raportit">
                  <span class="fa fa-th-list"></span> <?php echo Yii::t('main', 'Raportit'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/raportit_taulu">
                  <span class="fa fa-th-list"></span> <?php echo Yii::t('main', 'Tuntiraportti'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tyoajan_seuranta">
                  <span class="fa fa-th-list"></span> <?php echo Yii::t('main', 'Vuosityöaika'); ?></a>
              </li>
          <li>
            <a class="accordion-toggle Yhteenvedot" href="#">
              <span class="fa fa-bars"></span>
              <span><?php echo Yii::t('main', 'Yhteenvedot'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/ayhteenveto">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntiyhteenveto asiakkaat'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/ayhteenvetoyht">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tunnit yhteensä asiakkaat'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/yhteenveto?aktiivinen=1">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntiyhteenveto työntekijät'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/kyhteenveto">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntiyhteenveto kohteet'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/kyhteenveto_tuntemattomat">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntiyhteenveto kohteet tuntemattomat'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/yhteenveto_m">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntiyhteenveto matkat'); ?></a>
              </li>
            </ul>
          </li>

            </ul>
          </li>
	  <?php endif; ?>


          <li>
            <a class="accordion-toggle tyontekijoidenHallinta" href="#">
              <span class="fa fa-male"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Työntekijöiden hallinta'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/index">
                  <span class="fa fa-male"></span> <?php echo Yii::t('main', 'Työntekijät'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/tyoryhmat_hallinta">
                  <span class="fa fa-male"></span> <?php echo Yii::t('main', 'Työryhmät hallinta'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/verotustiedot">
                  <span class="fa fa-male"></span> <?php echo Yii::t('main', 'Verotustiedot'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyosuhdet/index">
                  <span class="fa fa-list"></span> <?php echo Yii::t('main', 'Työsuhdetiedot'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/merkkipaivat">
                  <span class="fa fa-indent"></span> <?php echo Yii::t('main', 'Merkkipäivät'); ?></a>
              </li>


          <li>
            <a class="accordion-toggle tyosuhdelomakkeet" href="#">
              <span class="fa fa-bars"></span>
              <span><?php echo Yii::t('main', 'Työsuhdelomakkeet'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">

              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kirjallinenVaroitus/index">
                  <span class="fa fa-indent"></span> <?php echo Yii::t('main', 'Kirjallinen varoitus'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/irtisanomisilmoitukset/index">
                  <span class="fa fa-indent"></span> <?php echo Yii::t('main', 'Irtisanomisilmoitus'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyosuhteenPaattaminen/index">
                  <span class="fa fa-indent"></span> <?php echo Yii::t('main', 'Työsuhteen päättäminen'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyosopimukset/index">
                  <span class="fa fa-indent"></span> <?php echo Yii::t('main', 'Työsopimukset'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyotodistus/index">
                  <span class="fa fa-indent"></span> <?php echo Yii::t('main', 'Työtodistus'); ?></a>
              </li>
            </ul>
          </li>

            </ul>
          </li>


<?php
if($site[0]->UudetMobiiliViestit()){ $uusi_viesti = '<i class="fa fa-bell text-danger"></i>'; } else { $uusi_viesti = ''; }
?>
          <li>
            <a class="accordion-toggle viestinnanHallinta" href="#">
              <span class="fa fa-envelope"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Viestinnän hallinta'); ?> <?=$uusi_viesti?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/viestinta/index">
                  <span class="glyphicon glyphicon-envelope"></span> <?php echo Yii::t('main', 'Viestit'); ?> <?=$uusi_viesti?></a>
              </li>
            </ul>
          </li>

	<?php if(in_array('2',$tas)) : ?>
          <li>
            <a class="accordion-toggle tyovuorojenHallinta" href="#">
              <span class="fa fa-calendar-check-o"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Työvuorojen hallinta'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">

	      <!-- Nakyma -->
              <li class="p10" data-toggle="tooltip">
	      <select class="form-control tvchange" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Valitse näkymä'); ?>">
 	        <?php if($curpage != 'tyovuoroot/beta'): ?>
		<option value=><?php echo Yii::t('main', 'Työvuoro näkymä'); ?></option>
		<?php endif; ?>
            <?php 
              $domain = Yii::app()->user->domain;
              $vkourl = $domain === "kotipuhtaaksi" ? "beta?mode=vko&blank=true" : "beta?mode=vko"; // beta?mode=vko is the original URL
            ?>
 	        <option value="<?=$vkourl ?>" <?php if($curpage == 'tyovuoroot/beta' and !isset($_GET['vapaat']) and isset($_GET['mode']) and $_GET['mode'] == 'vko') echo 'selected'; ?>><?php echo Yii::t('main', 'VIIKKO'); ?></option>
 	        <option value="beta?mode=vko&vapaat=true" <?php if($curpage == 'tyovuoroot/beta' and isset($_GET['vapaat'])) echo 'selected'; ?>><?php echo Yii::t('main', 'VIIKKO VAPAAT AJAAT'); ?></option>
 	        <option value="beta?mode=tt" <?php if($curpage == 'tyovuoroot/beta' and isset($_GET['mode']) and $_GET['mode'] == 'tt') echo 'selected'; ?>><?php echo Yii::t('main', 'TYÖNTEKIJÄ'); ?></option>
	      </select>
              </li>
	      <!-- Nakyma -->
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/lista?uusi_tilaus=1">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Tilaukset'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/siirto">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Siirto'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/lista">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Lista työvuoroista'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/beta?mode=vko">
                  <span class="fa fa-paper-plane"></span> <?php echo Yii::t('main', 'Työvuorojen lähetys'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/kk">
                  <span class="fa fa-calendar-o"></span> <?php echo Yii::t('main', 'Kuukausinäkymä'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/shift_products">
                  <span class="fa fa-calendar-o"></span> <?php echo Yii::t('main', 'Työvuorot tuotteittain'); ?></a>
              </li>
<?php /*
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/vuosilomat/index">
                  <span class="fa fa-calendar-o"></span> <?php echo Yii::t('main', 'Lomat ja poissaolot'); ?></a>
              </li>
*/ ?>
            </ul>
          </li>
	<?php endif; ?>

	<?php if(in_array('3',$tas) || Yii::app()->user->domain == 'staging_kotipuhtaaksi') : ?>
          <li>
            <a class="accordion-toggle laskutuksenHallinta" href="#">
              <span class="glyphicon glyphicon-barcode"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Laskutuksen hallinta'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">

	<?php $a = Asetukset::model()->findbypk(1); ?>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/lasku/index">
                  <span class="glyphicon glyphicon-barcode"></span> <?php echo Yii::t('main', 'Laskut'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/lista?laskutettu=0">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Laskuttamattomat työvuorot'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/lasku/l_asiakkaat">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Laskuttettavat asiakkaat'); ?></a>
              </li>
			  <?php if( 
				Yii::app()->user->domain == 'demo' 
				|| Yii::app()->user->domain == 'sivex' 
				|| Yii::app()->user->domain == 'kotipuhtaaksi'
				|| Yii::app()->user->domain == 'staging_kotipuhtaaksi'
				|| Yii::app()->user->domain == 'seran'
				|| Yii::app()->user->domain == 'kotimaan_huolenpitopalvelut_oy'
				|| Yii::app()->user->domain == 'inkan_kotitalouspalvelut_oy'
			  ): ?>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/lasku/auto">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Laskutuksen automaatio'); ?></a>
              </li>
	      <?php endif; ?>
<!--
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/LaskutusTuotteet/index">
                  <span class="fa fa-shopping-cart"></span> <?php echo Yii::t('main', 'Tuotteet ja palvelut'); ?></a>
              </li>
-->
<!--
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/laskuHistoria/avoimet">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Avoimet laskut'); ?></a>
              </li>
-->

          <li>
            <a class="accordion-toggle raportit" href="#">
              <span class="fa fa-bars"></span>
              <span><?php echo Yii::t('main', 'Raportit'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/laskuHistoria/paivakirja">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Lasku päiväkirjа'); ?></a>
              </li>
<!--
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/laskuHistoria/paakirja">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Lasku pääkirja'); ?></a>
              </li>

              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/laskuHistoria/maksu_paivakirja">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Maksu päiväkirjа'); ?></a>
              </li>

              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/laskuHistoria/maksu_paakirja">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Maksu pääkirja'); ?></a>
              </li>
-->
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/laskuHistoria/alv_raportti">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'ALV-raportti'); ?></a>
              </li>
            </ul>
          </li>

<!--
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/laskuHistoria/reskontraluettelo">
                  <span class="fa fa-shopping-cart"></span> <?php echo Yii::t('main', 'Reskontraluettelo'); ?></a>
              </li>
-->

            </ul>
          </li>
	<?php endif; ?>


          <li>
            <a class="accordion-toggle onlinevaraus" href="#">
              <span class="fa fa-clock-o"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Onlinevaraus'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/onlinevaraus/index?domain=<?=Yii::app()->user->domain?>">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Onlinevaraus'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/onlinevaraus/kaikki">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Kaikki varaukset'); ?></a>
              </li>
            </ul>
          </li>

<?php
if($site[0]->checkEdicoViestit()){ $bell = '<i class="fa fa-bell text-danger"></i>'; } else { $bell = ''; }
?>
	<?php if(in_array('5',$tas)) : ?>
          <li>
            <a class="accordion-toggle crm" href="#">
              <span class="fa fa-users"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'eDico'); ?> <?=$bell?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
<!--
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/yhteystiedot/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Yhteystiedot'); ?></a>
              </li>
-->
              <!--<li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/kohderyhma">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Kohderyhmä'); ?></a>
              </li>-->
<!--
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tarjouslaskenta/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Tarjouslaskenta'); ?></a>
              </li>
-->
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/edicoTilaukset/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Kaikki tilaukset'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyonkuvaus/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Työnkuvaukset'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/crmTarjoukset/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Tarjoukset'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/crmSopimukset/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Sopimukset'); ?></a>
              </li>
<!--
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kirjeidenHallinta/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Kirjeiden hallinta'); ?></a>
              </li>
-->
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/vinkkiExtranet/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Vinkit'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/palautteet/index">
                  <span class="fa fa-map"></span> <?php echo Yii::t('main', 'Palautteet'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/edicoViestinta/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'eDico Viestintä'); ?> <?=$bell?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/kayttajat">
                  <span class="fa fa-user"></span> <?php echo Yii::t('main', 'Käyttäjät'); ?></a>
              </li>
            </ul>
          </li>
	<?php endif; ?>

          <li>
            <a class="accordion-toggle" href="#">
              <span class="fa fa-line-chart"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Management'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">

              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/management">
                  <span class="fa fa-line-chart"></span> <?php echo Yii::t('main', 'Yhteenveto'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/kaaviot">
                  <span class="fa fa-line-chart"></span> <?php echo Yii::t('main', 'Kaaviot'); ?></a>
              </li>
	<?php /* ?>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/management_tunnit">
                  <span class="fa fa-line-chart"></span> <?php echo Yii::t('main', 'Tunnit'); ?></a>
              </li>
	<?php */ ?>
            </ul>
          </li>

	<?php if(in_array('999',$tas)) : ?>
          <li>
            <a class="accordion-toggle crm" href="#">
              <span class="fa fa-users"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'DIGISTEN hallinta'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/ilmoitusKaikkille/create">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Luo uusi ilmoitus'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/ilmoitusKaikkille/admin">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Kaikki ilmoitukset'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etunnin_asiakkaat">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Etunnin domainit'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/digistenTunnitKk">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Digisten tunnit kk'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/digistenTunnitKk/digisten_hinnasto">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Digisten hinnat'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetuksetForAll/update?id=1">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Digisten asetukset'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/DigistenYritysLog/index">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Yritysten loki'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/ohjevideot/admin">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Ohjevideot hallinta'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/errorlog">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Virheloki'); ?></a>
              </li>
            </ul>
          </li>
	<?php endif; ?>


          <li>
            <a class="accordion-toggle crm" href="#">
              <span class="fa fa-money"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Tuotteet ja Palvelut'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tuotteetPalvelut/index">
                  <span class="fa fa-money"></span> <?php echo Yii::t('main', 'Tuotteet ja Palvelut'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/hinnastot/index">
                  <span class="fa fa-money"></span> <?php echo Yii::t('main', 'Hinnastot'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kupongit/index">
                  <span class="fa fa-map"></span> <?php echo Yii::t('main', 'Alennuskoodit'); ?></a>
              </li>
            </ul>
          </li>
          <li>
            <a class="accordion-toggle" href="#">
              <span class="sidebar-title"><?php echo strtoupper(Yii::app()->user->domain); ?></span>
            </a>
          </li>


            </ul>
          </li>


        </ul>
        <!-- End: Sidebar Menu -->

      </div>
      <!-- End: Sidebar Left Content -->

    </aside>

    <!-- Start: Content-Wrapper -->
    <section id="content_wrapper">

      <!-- Start: Topbar-Dropdown -->
      <div id="topbar-dropmenu">

        <div class="topbar-menu row">
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/index" class="metro-tile">
              <span class="metro-icon glyphicon glyphicon-phone"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'TUNNIT'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/index" class="metro-tile">
              <span class="metro-icon fa fa-male"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'TYÖNTEKIJÄT'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">

            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/index" class="metro-tile">
              <span class="metro-icon glyphicon glyphicon-user"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'ASIAKKAAT'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kohteet/index" class="metro-tile">
              <span class="metro-icon glyphicon glyphicon-home"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'KOHTEET'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/viestinta/index" class="metro-tile">
              <span class="metro-icon glyphicon glyphicon-envelope"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'VIESTIT'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetukset/update?id=1" class="metro-tile">
              <span class="metro-icon fa fa-gears"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'ASETUKSET'); ?></p>
            </a>
          </div>
        </div>
      </div>
      <!-- End: Topbar-Dropdown -->

      <!-- Start: Topbar -->
      <header id="topbar" class="hidden">
        <div class="topbar-left">
          <ol class="breadcrumb">
            <li class="crumb-active">
              <a href="dashboard.html">Dashboard</a>
            </li>
            <li class="crumb-icon">
              <a href="dashboard.html">
                <span class="glyphicon glyphicon-home"></span>
              </a>
            </li>
            <li class="crumb-link">
              <a href="dashboard.html">Home</a>
            </li>
            <li class="crumb-trail">Dashboard</li>
          </ol>
        </div>
        <div class="topbar-right">
          <div class="ib topbar-dropdown">
            <label for="topbar-multiple" class="control-label pr10 fs11 text-muted">Reporting Period</label>
            <select id="topbar-multiple" class="hidden">
              <optgroup label="Filter By:">
                <option value="1-1">Last 30 Days</option>
                <option value="1-2" selected="selected">Last 60 Days</option>
                <option value="1-3">Last Year</option>
              </optgroup>
            </select>
          </div>
          <div class="ml15 ib va-m" id="toggle_sidemenu_r">
            <a href="#" class="pl5">
              <i class="fa fa-sign-in fs22 text-primary"></i>
              <span class="badge badge-hero badge-danger">3</span>
            </a>
          </div>
        </div>
      </header>
      <!-- End: Topbar -->



  <!-- BEGIN: PAGE SCRIPTS -->

  <!-- jQuery -->

<?php /*
  <!-- HighCharts Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/highcharts/highcharts.js"></script>

  <!-- Sparklines Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/sparkline/jquery.sparkline.min.js"></script>

  <!-- Simple Circles Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/circles/circles.js"></script>

  <!-- JvectorMap Plugin + US Map (more maps in plugin/assets folder) -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/jvectormap/jquery.jvectormap.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/jvectormap/assets/jquery-jvectormap-us-lcc-en.js"></script> 

nyt ne ovat etusivu.php ssa
*/
?>

  <!-- Theme Javascript -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/utility/utility.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/demo.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/main.js"></script>


  <script type="text/javascript">
  jQuery(document).ready(function() {

    "use strict";

    // Init Theme Core      
    Core.init();

    // Init Demo JS
    Demo.init();

    // Init Widget Demo JS
    // demoHighCharts.init();

    // Because we are using Admin Panels we use the OnFinish 
    // callback to activate the demoWidgets. It's smoother if
    // we let the panels be moved and organized before 
    // filling them with content from various plugins

    // Init plugins used on this page
    // HighCharts, JvectorMap, Admin Panels

    // Init Admin Panels on widgets inside the ".admin-panels" container
    $('.admin-panels').adminpanel({
      grid: '.admin-grid',
      draggable: true,
      preserveGrid: true,
      mobile: false,
      onStart: function() {
        // Do something before AdminPanels runs
      },
      onFinish: function() {
        $('.admin-panels').addClass('animated fadeIn').removeClass('fade-onload');


        // Init the rest of the plugins now that the panels
        // have had a chance to be moved and organized.
        // It's less taxing to organize empty panels
        //demoHighCharts.init();
        //runVectorMaps(); // function below
      },
      onSave: function() {
        $(window).trigger('resize');
      }
    });

/*
    // Widget VectorMap
    function runVectorMaps() {

      // Jvector Map Plugin
      var runJvectorMap = function() {
        // Data set

        var mapData = [900, 700, 350, 500];
        // Init Jvector Map

        $('#WidgetMap').vectorMap({
          map: 'us_lcc_en',
          //regionsSelectable: true,
          backgroundColor: 'transparent',
          series: {
            markers: [{
              attribute: 'r',
              scale: [3, 7],
              values: mapData
            }]
          },
          regionStyle: {
            initial: {
              fill: '#E5E5E5'
            },
            hover: {
              "fill-opacity": 0.3
            }
          },
          markers: [{
            latLng: [37.78, -122.41],
            name: 'San Francisco,CA'
          }, {
            latLng: [36.73, -103.98],
            name: 'Texas,TX'
          }, {
            latLng: [38.62, -90.19],
            name: 'St. Louis,MO'
          }, {
            latLng: [40.67, -73.94],
            name: 'New York City,NY'
          }],
          markerStyle: {
            initial: {
              fill: '#a288d5',
              stroke: '#b49ae0',
              "fill-opacity": 1,
              "stroke-width": 10,
              "stroke-opacity": 0.3,
              r: 3
            },
            hover: {
              stroke: 'black',
              "stroke-width": 2
            },
            selected: {
              fill: 'blue'
            },
            selectedHover: {}
          },
        });
        // Manual code to alter the Vector map plugin to 
        // allow for individual coloring of countries
        var states = ['US-CA', 'US-TX', 'US-MO',
          'US-NY'
        ];
        var colors = [bgWarningLr, bgPrimaryLr, bgInfoLr, bgAlertLr];
        var colors2 = [bgWarning, bgPrimary, bgInfo, bgAlert];
        $.each(states, function(i, e) {
          $("#WidgetMap path[data-code=" + e + "]").css({
            fill: colors[i]
          });
        });
        $('#WidgetMap').find('.jvectormap-marker')
          .each(function(i, e) {
            $(e).css({
              fill: colors2[i],
              stroke: colors2[i]
            });
          });
      }

      if ($('#WidgetMap').length) {
        runJvectorMap();
      }
    }
*/

  });
  </script>



  <!-- MODAL laatiko -->
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
  <div id="showres" class="modal" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>
  <!-- MODAL laatiko -->

  <script type="text/javascript">
  jQuery(document).ready(function() {

    $(".uusiTilaus").click(function(){
	   $.ajax({
		url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/uusitilaus',
		data:$(this).serialize(),
		type:'POST',
		success:function(data){
			//console.log(data);
			$('#showres').modal().html(data);
	   	},
		error:function(data){
			console.log(data);
	    	}
	    });
    });
    $('.tvchange').change(function(){
	var thisVal = $(this).val();
	window.location.href= location.protocol + "//" + location.host + '/index.php/tyovuoroot/' + thisVal;
    });

    var curpage = $('#curpage').val();

    if(
	curpage === 'asiakkaat/index'
	|| curpage === 'kohteet/index'
	|| curpage === 'kohteet/avaimet'
	|| curpage === 'kohteet/googlemap'
	|| curpage === 'mobile/laskutettu'
	|| curpage === 'asiakkaat/tag_report'
    ){  $('.asiakkaidenHallinta').addClass('menu-open'); }
    else if(
	curpage === 'mobile/index' 
	|| curpage === 'toteutuneet/kk'
	|| curpage === 'mobile/palkkataulukko'
	|| curpage === 'mobile/raportit'
    ){  $('.tuntienHallinta').addClass('menu-open'); }
    else if(
	curpage === 'toteutuneet/index' 
	|| curpage === 'toteutuneet/kk'
	|| curpage === 'mobile/lahetys_asiakkaalle'
	|| curpage === 'asiakasHyvaksynta/index'
    ){  $('.tuntienHallinta').addClass('menu-open'); $('.tuntienHyvaksynta').addClass('menu-open'); }
    else if(
	curpage === 'mobile/yhteenveto' 
	|| curpage === 'mobile/kyhteenveto'
	|| curpage === 'mobile/kyhteenveto_tuntemattomat'
	|| curpage === 'mobile/yhteenveto_m'
	|| curpage === 'mobile/raportit_taulu'
    ){  $('.tuntienHallinta').addClass('menu-open'); $('.Yhteenvedot').addClass('menu-open'); }
    else if(
	curpage === 'tyontekijat/index' 
	|| curpage === 'tyontekijat/verotustiedot'
	|| curpage === 'tyosuhdet/index'
	|| curpage === 'mobile/raportit'
	|| curpage === 'tyontekijat/merkkipaivat'
	|| curpage === 'kirjallinenVaroitus/index'
	|| curpage === 'tyotodistus/index'
    ){  $('.tyontekijoidenHallinta').addClass('menu-open'); }
    else if(
	curpage === 'viestinta/index' 
    ){  $('.viestinnanHallinta').addClass('menu-open'); }
    else if(
	curpage === 'tyovuoroot/index'
	|| curpage === 'tyovuoroot/tv2'
	|| curpage === 'tyovuoroot/tv3'
	|| curpage === 'tyovuoroot/tv_kohteet'
	|| curpage === 'tyovuoroot/viikkottain'
	|| curpage === 'tyovuoroot/kk'
	|| curpage === 'vuosilomat/index'
    ){  $('.tyovuorojenHallinta').addClass('menu-open'); }
    else if(
	curpage === 'lasku/index'
	|| curpage === 'laskutusTuotteet/index'
	|| curpage === 'laskuHistoria/index'
	|| curpage === 'laskuHistoria/reskontraluettelo'
    ){  $('.laskutuksenHallinta').addClass('menu-open'); }
    else if(
	curpage === 'laskuHistoria/paivakirja'
	|| curpage === 'laskuHistoria/paakirja'
	|| curpage === 'laskuHistoria/maksu_paivakirja'
	|| curpage === 'laskuHistoria/maksu_paakirja'
	|| curpage === 'laskuHistoria/alv_raportti'
    ){  $('.laskutuksenHallinta').addClass('menu-open');  $('.raportit').addClass('menu-open'); }
    else if(
	curpage === 'onlinevaraus/kaikki'
    ){  $('.onlinevaraus').addClass('menu-open'); }
    else if(
	curpage === 'yhteystiedot/index'
	|| curpage === 'site/kohderyhma'
	|| curpage === 'crmTarjoukset/index'
	|| curpage === 'crmSopimukset/index'
	|| curpage === 'kirjeidenHallinta/index'
	|| curpage === 'vinkkiExtranet/index'
	|| curpage === 'palautteet/index'
	|| curpage === 'tyonkuvaus/index'
	|| curpage === 'tyonkuvaus/update'
	|| curpage === 'tyonkuvaus/create'
    ){  $('.crm').addClass('menu-open'); }




  });
  </script>
  <!-- END: PAGE SCRIPTS -->
