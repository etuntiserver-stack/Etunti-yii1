<?php

?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v3.css">

<?php
/* @var $this TyovuorootController */
/* @var $dataProvider CActiveDataProvider */


   $pvmtid = Yii::app()->request->getParam('pvmtid', 0);
   if(!empty($pvmtid)){
	$expl = explode("_",$pvmtid);
	Yii::app()->session['from'] = date("Y-m-d",strtotime($expl['0']));
	Yii::app()->session['to'] = date("Y-m-d",strtotime($expl['0']." +1 week"));
	Yii::app()->session['Tekija'] = array($expl['1']);
	?>
	<script type="text/javascript">
	$(document).ready(function(){
	
	  $('#<?php echo $pvmtid; ?>').addClass("alert alert-info");
	
	});
	</script>
	<?php
   }



 // <-- tyovuorot.js tyovuoroot/siivous_tyonimike
 echo '<input type="hidden" id="fromTV" value="'.Yii::app()->session['from'].'">';
 echo '<input type="hidden" id="toTV" value="'.Yii::app()->session['to'].'">';
 //     tyovuorot.js tyovuoroot/siivous_tyonimike -->


 function dateDiff($start, $end) {
	$start_ts = strtotime($start);
	$end_ts = strtotime($end);
	$diff = $end_ts - $start_ts;
	return round($diff / 86400);
 }
	$dateDiff = dateDiff($from, $to);

?>




<?php if(!isset($_GET['fullscreen'])) : ?>
	<input type="hidden" id="taulunKorko" value="190">
<?php else: ?>
	<input type="hidden" id="taulunKorko" value="140">
<?php endif; ?>

<?php if( count($tyontekijat_model) == 0 ) : ?>
	<div class="alert alert-danger"><?php echo Yii::t('main', 'Ei tuloksia, tarkasta haku.'); ?></div>
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
					"foot" : 1,
					'z-index': 0
				    }); 
				}

			});
		</script>
		<!-- Fixed Table -->




<?php if( !empty($from) and !empty($to) and count($tyontekijat_model) > 0 ) : ?>
<div class="row">
            <div class="admin-form">
              <div class="panel heading-border myBgColors">
                <div class="panel-body bg-light">
                 <div class="row">


<div class="table-responsive" id="parent">
  <table class="table table-bordered" id="fixTable">
     <thead class="">
     <tr>
     <th width="100"></th>
        <?php 
	  echo '<th width="100">';
 	  echo '<b>'.Yii::t('main', 'Aika').'</b>';	
	  echo '</th>';

	// VARAUS
	  echo '<th style="z-index: 999" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Keskeneräinen varaus').'">';
 	  echo '<b class="text-warning">'.Yii::t('main', 'VARAUS').'</b>';	
	  echo '</th>';
	// VARAUS

	$asetukset = Asetukset::model()->findByPk(1);

	foreach($tyontekijat_model as $t){
	  echo '<th style="z-index: 999">';
 	  echo '<a href="#" class="getTekijanTiedot" for="'.$t->id.'">'.$this->etuSukunimi($t->id).'</a>';	
	  echo '</th>';
	}
        ?>
     </tr>
     </thead>
     <tbody>
        <?php
	$arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
    	for ($i = 0; $i <= $dateDiff; $i++) {
	  $plus = "+$i day";
	  $date = '';
	  $date = date("d.m.Y",strtotime($from." ".$plus));
	  $did = date("Ymd",strtotime($from." ".$plus));

	  $columnDate = date("N/d.m",strtotime($date));
	  $explColDate = explode("/",$columnDate);

	  $clPyhat = '';
	  $pyhat = $this->pyhat($date);
	  $ispyha = '';
	  if($pyhat == 'su' or $pyhat == 'pyhapaiva' or $pyhat == 'erikoislauantai')
	  {
		$clPyhat = 'style="background:#ddd"';
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Pyhäpäivä').'"></i>';
	  }

	  if($pyhat == 'pyhapaiva')
	  {
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Pyhäpäivä').'"></i>';
	  }

	  if($pyhat == 'erikoislauantai')
	  {
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Erikoislauantai').'"></i>';
	  }

  	    echo '<tr>';
  		echo '<td '.$clPyhat.' class="fixed-column" id="first_'.$did.'"><b>'.$arrDate[$explColDate[0]].", ".$explColDate[1].$ispyha.'</b></td>';

		echo '<td>';
		echo '<div class="kloajaat text-right">';
		echo '6 - 8<br>';
		echo '8 - 10<br>';
		echo '10 - 12<br>';
		echo '12 - 14<br>';
		echo '14 - 16<br>';
		echo '16 - 18<br>';
		echo '18 - 20<br>';
		echo '20 - 22<br>';
		echo '</div>';
		echo '</td>';

		// VARAUS
		  echo '<td '.$clPyhat.' id="'.$did.'_0">';
		  $tv = $this->renderPartial('//tyovuoroot/did3',array(
					'pvm'=>$date,
					'tid'=>0,
					'from'=>'tvuoro', 
					'kohteet_siivous'=>$kohteet_siivous, 
					'asetukset'=>$asetukset,
					'asiakas'=>$asiakas,
					'kohde'=>$kohde,
		  ), true);
		  echo json_decode($tv, true);
		  echo '</td>';
		// VARAUS


		if(count($kohteet_siivous) > 0)
		$ks = json_encode($kohteet_siivous);
		else
		$ks = "0";


		foreach($tyontekijat_model as $t){
		  echo '<td '.$clPyhat.' id="'.$did.'_'.$t->id.'">';
	     	  echo '<div class="luolaatiko" for="'.$did.'_'.$t->id.'" pvm="'.$date.'" tid="'.$t->id.'" from="tvuoro" kohteet_siivous="'.$ks.'" asiakas="'.$asiakas.'" kohde="'.$kohde.'"></div>';
		  echo '</td>';
		}
	    echo '</tr>';

	    if(date('N', strtotime($date)) == 7)
	    {
  	    echo '<tr>';
  		echo '<td class="text-center myBgColors viikkoRivi fixed-column"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).' <i class="fa fa-arrow-up" aria-hidden="true"></i>
</b></td>';



		// VARAUS
		  echo '<td class="viikkoRivi myBgColors text-center" id="vk_'.date("W",strtotime($date)).'_0">';
		  $kokoViikko = '';
		  $vko = '';
		  $vko = date("W",strtotime($date));
		  $year = date("Y",strtotime($date));
		  $kokoViikko = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>0,'viikko'=>$vko,'year'=>$year),true);

		  echo '<span>'.$kokoViikko.'</span>';
		  echo '</td>';
		// VARAUS



		foreach($tyontekijat_model as $t){
		 $vktyoaika = '';
		 $ts = Tyosuhdet::model()->find(" tid = '".$t->id."' ");
		 if(isset($ts->id) and !empty($ts['vktyoaika']))
		  $vktyoaika = $ts['vktyoaika'];

		  echo '<td class="viikkoRivi myBgColors text-center" id="vk_'.date("W",strtotime($date)).'_'.$t->id.'">';
		  $kokoViikko = '';
		  $vko = '';
		  $vko = date("W",strtotime($date));
		  $year = date("Y",strtotime($date));
		  $kokoViikko = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$t->id,'viikko'=>$vko,'year'=>$year),true);

		  $cl = '';
		  if(	(int)str_replace(":","",$kokoViikko) > (int)str_replace(":","",$vktyoaika)
			and (int)str_replace(":","",$kokoViikko) > 0
			and (int)str_replace(":","",$vktyoaika) > 0
		  )
		  $cl = 'class="btn btn-xs btn-danger"';

		  echo '<span '.$cl.'>'.$kokoViikko. '('.$vktyoaika.')</span>';

		  echo '</td>';
		}
	    echo '</tr>';
	    }

  	}
        ?>
     </tbody>
     <tfoot>
        <?php
  	    echo '<tr class="myBgColors">';
  		echo '<td class="text-center viikkoRivi fixed-column"></td>';

		  echo '<td class="text-center viikkoRivi myBgColors fromto_0" />';
		  $this->renderPartial('//tyovuoroot/fromto',array('tid'=>0));
		  echo '</td>';

		foreach($tyontekijat_model as $t){
		  echo '<td class="text-center viikkoRivi myBgColors fromto_'.$t->id.'" />';
		  $this->renderPartial('//tyovuoroot/fromto',array('tid'=>$t->id));
		  echo '</td>';
		}
	    echo '</tr>';
        ?>
     </tfoot>
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

var xhr = new XMLHttpRequest();
xhr.open("POST", 'didnew3', true);
xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
xhr.onload = function () {
	d = JSON.parse(xhr.responseText);
	//console.log(xhr.responseText)
	$("#"+forThis).html(d);
};
xhr.send('pvm='+pvm+'&tid='+tid+'&from='+from+'&kohteet_siivous='+kohteet_siivous+'&asiakas='+asiakas+'&kohde='+kohde);
});
</script>
<?php endif; ?>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
	<?php Yii::app()->clientScript->registerPackage('tyovuoroot'); ?>





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
