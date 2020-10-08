<?php
ini_set('memory_limit', '512M');
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tyovuorot_v4.css">
<?php
if( isset($_SESSION['skrollaus']) )
	echo '<style>td .latikkoAsetukset{max-height: none;}</style>';
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot_v4.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select_valiko.js"></script>
<?php echo CHtml::button(Yii::t('main', 'Lähetä'),array('class'=>'btn btn-lg btn-success','id'=>'lahetaTyovuoroja', 'style' => 'display: none; position:fixed; bottom: 0; left: 0; margin: 10px')); ?>
<!-- Fixed Table -->
<!-- http://www.jqueryscript.net/table/jQuery-Plugin-For-Fixed-Table-Header-Footer-Columns-TableHeadFixer.html -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/tableHeadFixer.js"></script>
<script>
	$(document).ready(function() {
		window.onload = function(event) { resizeDiv(); }
		//window.onresize = function(event) { resizeDiv(); }
			function resizeDiv() {
		    	    vpw = $(window).width()-100; 
		    	    vph = $(window).height()-100;
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

<input type="hidden" id="week" value="<?=$week?>">
<input type="hidden" id="year" value="<?=$year?>">
<?php $odotus_ikooni = '<img src="../../lib/img/etusivu/odotus.gif" height="20px">'; ?>

<?=((isset($_GET['vapaat']))?'<marquee class="text-danger">HUOMIO! OLET TILASSA, VIIKON VAPAAT AJAT JA NÄET TÄLLÄ AINA TYÖNTEKIJÖIDEN VAPAAT AJAT, KÄYTÄMÄLLÄ HAKU TOIMINTO, SIIRRYT AUTOMAATTISESTI VIIKKO TAULUUN.</marquee>':'')?>
<div id="yht_tv"></div>
<div class="" id="parent">
 <table class="table table-bordered" id="fixTable">
 <thead>
 <tr>
 <th class="bg-default text-center small" width="1"><span class="link small valitseKaikkiLahetettavaksi">Valitse kaikki</span></th>
 <?php
 $f 		= date("d.m.Y", strtotime($from));
 $did_sunday 	= date("Ymd", strtotime($year.'W'.$week.'7'));
 echo '<span class="sunday" sunday="'.date("d.m.Y", strtotime($year.'W'.$week.'7')).'" tids="'.json_encode(array_values($haku_tids)).'"></span>';
 while (strtotime($f) <= strtotime($to)){
	if(!isset(Yii::app()->session['vkolopput']) and date("w", strtotime($f)) == 6)
		$f = date ("d.m.Y", strtotime("+2 day", strtotime($f)));
	$columnDate 	= date("N/d.m",strtotime($f));
	$explColDate 	= explode("/",$columnDate);
	$tr_pyhat 	= '';
	$ispyha 	= '';
	if( isset($pyhapaivat[$f]['su']) or isset($pyhapaivat[$f]['vp']) or isset($pyhapaivat[$f]['el']) ){
		$tr_pyhat = 'tr_pyhat';
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Pyhäpäivä').'"></i>';
	}
	if(isset($pyhapaivat[$f]['el'])){
		$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Erikoislauantai').'"></i>';
	}
	echo '<th class="small bg-default text-center '.$tr_pyhat.'" style="z-index: 999;">
		<div class="laatiko_td"><b>'.$arrDate[$explColDate[0]].'</b>, '.$explColDate[1].$ispyha.'</b></div>
	</th>';
	$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
 } 
 ?>
 </tr>
 </thead>
 <tbody>
 <!-- VARAUKSET -->
 <tr>
    <td class="bg-default text-center" style="z-index: 999; width: 50px">
	<span class="nimi">VARAUS</span>
    </td>
    <?php
	$f = date("d.m.Y", strtotime($from));
	while (strtotime($f) <= strtotime($to)){
		if(!isset(Yii::app()->session['vkolopput']) and date("w", strtotime($f)) == 6)
			$f = date ("d.m.Y", strtotime("+2 day", strtotime($f)));
		$did 		= date("Ymd", strtotime($f));
		$columnDate 	= date("N/d.m",strtotime($f));
		$explColDate 	= explode("/",$columnDate);
		$tr_pyhat 	= '';
		if( isset($pyhapaivat[$f]['su']) or isset($pyhapaivat[$f]['vp']) or isset($pyhapaivat[$f]['el']) )
			$tr_pyhat = 'tr_pyhat';

		echo '<td class="'.$tr_pyhat.'">';
		echo '<div id="'.$did.'_0" class="latikkoAsetukset" pvm="'.$f.'" tid="0"><span class="odotus">'.$odotus_ikooni.'</span></div>';
		echo '</td>';
    		$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
    } 
  ?>
 </tr>
 <!-- VARAUKSET -->
 <?php foreach($tt as $tid=>$item): ?>
 <tr>
	<td class="bg-default td_tyontekija" style="z-index: 999; min-width: 100px; max-width: 160px; white-space: normal; font-size: 90%;">
	<div class="m10 text-center">
		<?php
		echo '
		<div>
		    	<a href="#" class="getTekijanTiedot '.((isset($tyosuhteet[$tid]['loppu']) and !empty($tyosuhteet[$tid]['loppu']) and date("Ymd", strtotime($tyosuhteet[$tid]['loppu'])) < date("Ymd"))? 'text-danger' : '').'" for="'.$tid.'">';
			// <-- Kuva
			if(
				isset(Yii::app()->user->domain) 
				and file_exists(dirname(Yii::app()->getBasePath()).'/img/tekijat/'.strtolower(Yii::app()->user->domain).'/'.$tid.".jpg") 
			){
				echo '<img src="../../img/tekijat/'.strtolower(Yii::app()->user->domain).'/'.$tid.".jpg".'" alt="avatar" class="mw40 br64 mr5">';
			} else {
				echo '<img src="../../img/tekijat/noname.jpg" alt="avatar" class="mw40 br64 mr5">';
			}
			//     Kuva -->
			echo '<br> '.$item['etusukunimi'].'</a>
		</div>';
		?>

		<?php
		$file = $week.'_'.$year.'_'.$tid.'.pdf';
		$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;
		if (file_exists($path.'/'.$file)){
			// <-- file_safe_opener
			$filepath = 'emails/tyovuorot/'.Yii::app()->user->domain.'/'.$file;
			echo '<p class="text-center">'.CHtml::link(Yii::t('main', ' Lähetetty'),
				array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
				array('target'=>'_blank','class'=>'text-danger'
			)).'</p>';
			//     file_safe_opener -->
		}
		if(isset($tyosuhteet[$tid]['vktyoaika']) and !empty($tyosuhteet[$tid]['vktyoaika']))
			$ts_aika = $tyosuhteet[$tid]['vktyoaika'];
		else
			$ts_aika = '00:00';

		$timeArr = explode(':',$ts_aika);
		$decTime = ($timeArr[0]*3600) + ($timeArr[1]);

		echo '<br>
		<span class="text-center odotusweeklaskennan" style="display:block">'.$odotus_ikooni.'</span>
		<div><b id="vko_'.$did_sunday.'_'.$tid.'"" ts_aika="'.$decTime.'">00:00</b>-'.$ts_aika.'</div>';
		?>
		<div class="mt5">
		   <div class="form-inline">
			<b class="form-group">Lähetä: </b> <input type="checkbox" style="margin-top:-1px" class="form-group lahetettava_checkbox" for="<?=$tid?>" data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('main', 'Määrittele lähetettäväksi')?>">
		   </div>
		</div>
	</div>
	</td>
	<?php
	$f = date("d.m.Y", strtotime($from));
	while (strtotime($f) <= strtotime($to)){
		if(!isset(Yii::app()->session['vkolopput']) and date("w", strtotime($f)) == 6)
			$f = date ("d.m.Y", strtotime("+2 day", strtotime($f)));
		$did = date("Ymd", strtotime($f));
		$columnDate 	= date("N/d.m",strtotime($f));
		$explColDate 	= explode("/",$columnDate);
		$tr_pyhat 	= '';
		if( isset($pyhapaivat[$f]['su']) or isset($pyhapaivat[$f]['vp']) or isset($pyhapaivat[$f]['el']) )
			$tr_pyhat = 'tr_pyhat';
		echo '<td class="'.$tr_pyhat.'"><div id="'.$did.'_'.$tid.'" class="latikkoAsetukset" pvm="'.$f.'" tid="'.$tid.'"><span class="odotus">'.$odotus_ikooni.'</span></div></td>';
		$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
	}
	?>
 </tr>
 <?php endforeach; ?>
 </tbody>
 </table>
</div>

<div id="temaus-modal" class="modal fade" tabindex="-1" role="dialog">
        <div id="modal-form" class=" popup-basic popup-lg admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"></span>
            </div>
            <form method="post" action="/" id="comment">
              <div class="panel-body p25">
              </div>
              <input type="hidden" id="tekija-id" name="tekija-id">
              <?php if (!empty(Yii::app()->user->kp)): ?>
              <div class="form-group p25">
                <label for="omasiistija-valinta">Omasiistijävaroitukset:</label>
                <select class="form-control" id="omasiistija-valinta">
                  <option value="1">Näytetään</option>
                  <option value="0">Ei näytetä</option>
                </select>
                <p id="omasiistija-valinta-result" class="text-success" hidden></p>
              </div>
              <?php endif; ?>
              <div class="panel-footer">
		<button type="button" class="button btn-default" data-dismiss="modal" aria-label="Close">Sulje</button>
              </div>
            </form>
          </div>
        </div>
</div>

<!-- Täytetään tauluu -->
<?php echo $this->tv_arrJava($from, $to, $haku_criteria, $haku_tids, 'vko', $customer_tickets ?? []); ?>
