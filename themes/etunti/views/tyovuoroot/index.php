<?php

	//echo $week.' '.$year.'<br>';
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot.css">

<?php

  $paivat=array(
	1=>Yii::t('main', 'Ma'),
	2=>Yii::t('main', 'Ti'),

	3=>Yii::t('main', 'Ke'),
	4=>Yii::t('main', 'To'),
	5=>Yii::t('main', 'Pe'),
	6=>Yii::t('main', 'La'),
	7=>Yii::t('main', 'Su'),
	);



   $dTVfrom = date("Y-m-d",strtotime($year ."W". $week. '1'));
   echo '<input type="hidden" id="fromTV" value="'.$dTVfrom.'">';
   $dTVto = date("Y-m-d",strtotime($year ."W". $week. '7'));
   echo '<input type="hidden" id="toTV" value="'.$dTVto.'">';

?>



<?php if(!isset($_GET['fullscreen'])) : ?>
	<input type="hidden" id="taulunKorko" value="160">
<?php else: ?>
	<input type="hidden" id="taulunKorko" value="140">
<?php endif; ?>

<?php if( count($tyontekijat_model) == 0 ) : ?>
	<h2 class="alert bg-danger"><?php echo Yii::t('main', 'Ei tuloksia, tarkasta haku.'); ?></h2>
<?php endif; ?>




		<!-- Fixed Table -->
		<!-- http://www.jqueryscript.net/table/jQuery-Plugin-For-Fixed-Table-Header-Footer-Columns-TableHeadFixer.html -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/tableHeadFixer.js"></script>
		<script>
			$(document).ready(function() {
				window.onload = function(event) { resizeDiv(); }
				//window.onresize = function(event) { resizeDiv(); }

				function resizeDiv() {
				    vpw = $(window).width()-100; 
				    vph = $(window).height()-150;

				    $('#parent').css({'height': vph + 'px', 'overflow-y' : 'hidden'});

				    $("#fixTable").tableHeadFixer({
					"left" : 1,
					'z-index': 0
				    }); 
				}

			});
		</script>
		<!-- Fixed Table -->





<?php if( !empty($year) and !empty($week) and count($tyontekijat_model) > 0 ) : ?>
<div class="row">
            <div class="admin-form">
              <div class="panel heading-border myBgColors">
                <div class="panel-body bg-light">
                 <div class="row">



<div class="table-responsive" id="parent">
  <table class="table table-bordered" id="fixTable">
     <thead>
     <tr>
	<th><?php echo Yii::t('main', 'Nimi'); ?></th>
        <?php
	for($day= 1; $day <= $numDays; $day++)
	{
  	  $d = strtotime($year ."W". $week . $day);
	  $date = date('d.m.Y',$d);
	  $ispyha = '';
	  $pyhat = $this->pyhat($date);
	  if($pyhat == 'su' or $pyhat == 'pyhapaiva' or $pyhat == 'erikoislauantai')
	  {
		$clPyhat = 'style="background:#ddd"';
	  }

	  if($pyhat == 'pyhapaiva')
	  {
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Pyhäpäivä').'"></i>';
	  }

	  if($pyhat == 'erikoislauantai')
	  {
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Erikoislauantai').'"></i>';
	  }
 
	  echo '<th>'.$paivat[date('N',$d)].', '.$date.$ispyha.'</th>';
	}
        ?>
     </tr>
     </thead>
     <tbody>
        <?php


	// VARAUS
	  echo '<tr>';
	  echo '<td width=1 id="first_0" data-toggle="tooltip" data-placement="right" title="'.Yii::t('main', 'Keskeneräinen varaus').'">';

		echo '
		<div class="row">
		  <div class="col-sm-12">
		    	<b class="text-warning">'.Yii::t('main', 'VARAUS').'</b>
		  </div>
		</div>';


	  echo '</td>';

	  $asetukset = Asetukset::model()->findByPk(1);
	  for($day= 1; $day <= $numDays; $day++)
	  {
  	     $d = strtotime($year ."W". $week . $day);
	     $date = date('d.m.Y',$d);
	     $did = date('Ymd',$d);

	     $clPyhat = '';
	     $pyhat = $this->pyhat($date);
	     if($pyhat == 'su' or $pyhat == 'pyhapaiva' or $pyhat == 'erikoislauantai')
	     $clPyhat = 'style="background:#ddd"';

	     echo '<td '.$clPyhat.' id="'.$did.'_0" valign="top">';
 	     $did = $this->renderPartial('//tyovuoroot/did',array(
					'pvm'=>$date,
					'tid'=>0,
					'from'=>'tvuoro', 
					'kohteet_siivous'=>$kohteet_siivous, 
					'asetukset'=>$asetukset,
					'asiakas'=>$asiakas,
					'kohde'=>$kohde,
	     ), true);
	     echo json_decode($did, true);
	     echo '</td>';
	  }
	  echo '</tr>';
	// VARAUS





	foreach($tyontekijat_model as $t)
	{
	  echo '<tr>';
	  echo '<td width=1 id="first_'.$t->id.'" style="z-index: 999">';

	     $vktyoaika = '';
	     $ts = Tyosuhdet::model()->find(" tid = '".$t->id."' ");
	     if(isset($ts->id) and !empty($ts['vktyoaika']))
	     $vktyoaika = $ts['vktyoaika'];
	     $kokoViikko = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$t->id,'viikko'=>$week,'year'=>$year),true);
 	  
		$cl = '';
		if((int)str_replace(":","",$kokoViikko) > (int)str_replace(":","",$vktyoaika)
			and (int)str_replace(":","",$kokoViikko) > 0
			and (int)str_replace(":","",$vktyoaika) > 0
		)
		$cl = 'class="btn btn-xs btn-danger"';

		$filepath = Yii::app()->getBasePath()."/img/tekijat/noname.jpg";
		if(isset(Yii::app()->user->domain) and file_exists('img/tekijat/'.strtolower(Yii::app()->user->domain).'/'.$t->id.".jpg") and isset(Yii::app()->user->id))
		{

		  //$filepath = dirname(Yii::app()->getBasePath()).'/img/tekijat/'.strtolower(Yii::app()->user->domain).'/'.$t->id.'.jpg';
		}
		$imageData = base64_encode(file_get_contents($filepath));
		$src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;

		echo '
		<div class="row">
		  <div class="col-sm-12">
		    	<a href="#" class="getTekijanTiedot" for="'.$t->id.'"><img src="'.$src.'" alt="avatar" class="mw50 br64 mr15"><br> '.$this->etuSukunimi($t->id).'</a>
			<br>
			<span '.$cl.'><b id="vk_'.$week.'_'.$t->id.'">'.$kokoViikko. '</b> ('.$vktyoaika.')</span>
		  </div>
		</div>';


	  echo '</td>';

	  for($day= 1; $day <= $numDays; $day++)
	  {
  	     $d = strtotime($year ."W". $week . $day);
	     $date = date('d.m.Y',$d);
	     $did = date('Ymd',$d);

	     $clPyhat = '';
	     $pyhat = $this->pyhat($date);
	     if($pyhat == true)
	     $clPyhat = 'style="background:#ddd"';

		if(count($kohteet_siivous) > 0)
		$ks = json_encode($kohteet_siivous);
		else
		$ks = "0";


	     echo '<td '.$clPyhat.' id="'.$did.'_'.$t->id.'" valign="top">';
	     echo '<div class="luolaatiko" for="'.$did.'_'.$t->id.'" pvm="'.$date.'" tid="'.$t->id.'" from="tvuoro" kohteet_siivous="'.$ks.'" asiakas="'.$asiakas.'" kohde="'.$kohde.'"></div>';
/*
 	     $did = $this->renderPartial('//tyovuoroot/did',array(
					'pvm'=>$date,
					'tid'=>$t->id,
					'from'=>'tvuoro', 
					'kohteet_siivous'=>$kohteet_siivous, 
					'asetukset'=>$asetukset,
					'asiakas'=>$asiakas,
					'kohde'=>$kohde,
	     ), true);
	     echo json_decode($did, true);
*/
	     echo '</td>';
	  }
	  echo '</tr>';
	}
        ?>
     </tbody>
  </table>
</div>


                 </div>
                </div>
              </div>
            </div>
</div>


<script>
$( ".luolaatiko" ).each(function( index ) {

	var forThis = $(this).attr("for");
	$("#"+forThis).html('odota..');
	var pvm = $(this).attr("pvm");
	var tid = $(this).attr("tid");
	var from = $(this).attr("from");
	var kohteet_siivous = $(this).attr("kohteet_siivous");
	var asiakas = $(this).attr("asiakas");
	var kohde = $(this).attr("kohde");

        $.ajax({
           url: "didnew",
           type: "POST",
	   data: { pvm : pvm, tid : tid, from : from, kohteet_siivous : kohteet_siivous, asiakas : asiakas, kohde : kohde },
           success: function(data){
		d = JSON.parse(data);
		//console.log(d)
		$("#"+forThis).html(d);
           },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	//console.log(XMLHttpRequest);
 	   }
        });
});
</script>
<?php endif; ?>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>




     <div id="temaus-modal" class="modal fade" tabindex="-1" role="dialog">
        <!-- Admin Form Popup -->
        <div id="modal-form" class=" popup-basic popup-lg admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"></span>
            </div>
            <!-- end .panel-heading section -->

            <form method="post" action="/" id="comment">
              <div class="panel-body p25">


              </div>
              <!-- end .form-body section -->

              <div class="panel-footer">
		<button type="button" class="button btn-default" data-dismiss="modal" aria-label="Close">Sulje</button>
                <!--<button type="submit" class="button btn-primary">Post Comment</button>-->
              </div>
              <!-- end .form-footer section -->
            </form>
          </div>
          <!-- end: .panel -->
        </div>
        <!-- end: .admin-form -->
     </div>


<script type="text/javascript">
$(document).ready(function(){

  $(".getTekijanTiedot").click(function(e){
	e.preventDefault();
	var id = $(this).attr('for');
        $.ajax({
           url: 'get_tekijantiedot?id='+id,
           success: function(data){
		data = JSON.parse(data);

		if( data['bd'] )
		{
		$('#temaus-modal').find('.panel-title').html('<i class="fa fa-male"></i>'+data['etusuku']);
		$('#temaus-modal').modal().find('.panel-body').html(data['bd']);
		}

           }
        });

  });


});
</script>



<?php /* jos joku avasi samantien sama ikkuna
<script>
$(document).on('show.bs.modal','#showres', function () {
  console.log(this)
});
$(document).on('hidden.bs.modal','#showres', function () {

});
</script>
*/ ?>




