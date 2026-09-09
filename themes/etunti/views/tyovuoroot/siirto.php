<?php
ini_set('memory_limit', '256M');
ini_set("max_execution_time", "900");
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */
$site = Yii::app()->createController('Site');
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"><i class="fa fa-calendar-check-o"></i> <?=Yii::t('main', 'Työvuorojen siirto')?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
			<label>Keneltä</label>
                        <div class="section">
                          <label class="field select">
				<?php
				$name_tyontekijat = 'tekijaPaaSivulla';
				(isset($_GET['aktiivinen']))? $aktiivinen = $_GET['aktiivinen'] : $aktiivinen = 1;
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'kenelta', // name
						'gui-input', //class
						'kenelta', // id
						(isset($_GET['kenelta']))? $_GET['kenelta']: '', //selected
						$aktiivinen// aktiivinen
				);
				echo $tyontekiatLista;
				?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
			<label>Kenelle</label>
                        <div class="section">
                          <label class="field select">
				<?php
				(isset($_GET['aktiivinen']))? $aktiivinen = $_GET['aktiivinen'] : $aktiivinen = 1;
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'kenelle', // name
						'gui-input', //class
						'kenelle', // id
						(isset($_GET['kenelle']))? $_GET['kenelle']: '', //selected
						$aktiivinen// aktiivinen
				);
				echo $tyontekiatLista;
				?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
			<label>Alkaen</label>
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="alkaen" class="gui-input datepickerFI" value="<?php if(isset($_GET['alkaen'])) echo date('d.m.Y', strtotime($_GET['alkaen'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
			<br>
			<?php if( isset($_GET['alkaen'])) : ?>
        	        <?php /*echo CHtml::link(Yii::t('main', 'Keskeytä'), array('siirto'),array('class'=>'btn btn-primary btn-lg siirra btn-block myBgColors')); */?>
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors"><?php echo Yii::t('main', 'Päivitä haku'); ?></button>
			<?php else : ?>
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors"><?php echo Yii::t('main', 'Esikatselu'); ?></button>
			<?php endif; ?>
                        </div>
		      </div>


                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


	<?php if( count($data_kenelta) > 0 or count($data_kenelta_k) > 0 ) : ?>
	<?php 
		$suorittu = 0; 
		$site = Yii::app()->createController('Site');
	?>
        <!-- begin: .tray-center -->

	<div class="text-center">
		<h2>Siirrä</h2>
		<form action="#" method="GET">
		<input type="hidden" name="kenelta" value="<?=$_GET['kenelta']?>">
		<input type="hidden" name="kenelle" value="<?=$_GET['kenelle']?>">
		<input type="hidden" name="alkaen" value="<?=$_GET['alkaen']?>">
		<input type="hidden" name="siirra_now" value="true">
		<button type="submit" class="btn btn-primary btn-lg siirra myBgColors"><i class="fa fa-2x fa-arrow-right"></i></button>
		</form>
	</div>
	<hr>
        <div class="tray-center row">
	    <!-- / Toistuvat -->
            <div class="admin-form col-sm-6">
	      <legend><h3>Toistuvat ketjut - Saaja: <?=$this->etuSukunimi($_GET['kenelle'])?></h3></legend>
              <div class="panel heading-border">
                <div class="panel-body">
		<p class="text-danger">Huomio! Poistetut ketjussa olevat päivät tulee saajallekin poistettuna.</p>
		<p class="text-danger">Huomio! Aloitus päivä voi muuttua. Tämä johtuu työvuorojen toistuvuudesta</p>
		<p class="text-danger">Huomio! Menneisyydessä olevat ketjut päättyvät <?=date("d.m.Y",strtotime($alkaen.' -1 day'))?> päivässä</p>
		<table class="table table-striped table-bordered">
		<tr>
		<th><?=Yii::t('main', 'Aloitus')?></th>
		<th><?=Yii::t('main', 'Lopetus')?></th>
		<th><?=Yii::t('main', 'Aika')?></th>
		<th><?=Yii::t('main', 'Osoite')?></th>
		</tr>
		<?php foreach($data_kenelta_k as $item) : ?>
		<?php 
			$aloitus_check 	= [];
			$saa_aloita 	= $alkaen;

			// <-- Viikkoja laskenta alkuperäisestä
			$startday 	= date("Y-m-d", strtotime($item->pfrom));
			$startday_ts	= strtotime($startday);
			$stopday 	= date("Y-m-d", strtotime($item->pto));
			$alkaen_YW	= date("YW",strtotime($alkaen));

			$pvm_lista = [];
			// kuukausiperusteinen toistuvuus (2026-09)
			if ($item->isMonthlyRepeat()) {
				foreach ($item->getMonthlyOccurrenceDates($alkaen, null) as $this_pvm) {
					$pvm_lista[$this_pvm] = $this_pvm;
					if(!isset($aloitus_check['pvm'])) $aloitus_check['pvm'] = $this_pvm;
				}
			} else {
			$date = new \DateTime($startday, new DateTimeZone('Europe/Helsinki'));
			$date->modify('this week monday');
			$date_end = (new \DateTime($stopday, new DateTimeZone('Europe/Helsinki')))->getTimestamp();
			while ($date->getTimestamp() <= $date_end){
				$this_week_sunday = date("YW", strtotime($date->format("d.m.Y").' this week sunday'));
				if( $this_week_sunday >= $alkaen_YW ){
					foreach(json_decode($item->viikko_paivat, true) as $viikko_paiva) {
						$paiva = new \DateTime($date->format('Y-m-d'), new DateTimeZone('Europe/Helsinki'));
						$paiva->modify("+" . ($viikko_paiva - 1) . "day");
						$this_pvm = $paiva->format('d.m.Y');
						if ( (strtotime($this_pvm) < $startday_ts) )
							continue;
						if (strtotime($this_pvm) > strtotime($stopday)){
							break 2;
						}
						if(strtotime($this_pvm) >= strtotime($alkaen)){
							$pvm_lista[$this_pvm] = $this_pvm;
							if(!isset($aloitus_check['pvm']))
								$aloitus_check['pvm'] = $this_pvm;
						}
					}
				}
				$date->modify("+{$item->viikkoja}week");
			}
			}

			if(isset($aloitus_check['pvm']))
				$saa_aloita = $aloitus_check['pvm'];

			// <-- Poistetut pvms uudet arvot alkuperäisen ketjuun
			$poistettu_pvms_alkuperainen_new = [];
			if( !empty($item->new_poistettu_pvm) )
				foreach(json_decode($item->new_poistettu_pvm, true) as $key1 => $val)
					if( strtotime($val['pvm']) >= strtotime($saa_aloita) ){
						//$val['tid'] = $_GET['kenelle'];
					} else {
						$poistettu_pvms_alkuperainen_new[] = $val;
					}

			// <-- Poistetut pvms jatko ketjulle, eli työparille
			$poistettu_pvms_jatkoketjulle = [];
			if( !empty($item->new_poistettu_pvm) )
				foreach(json_decode($item->new_poistettu_pvm, true) as $key1 => $val)
					if( strtotime($val['pvm']) >= strtotime($saa_aloita) ){
						if($val['tid'] == $_GET['kenelta'])
							$val['tid'] = $_GET['kenelle'];
						$poistettu_pvms_jatkoketjulle[] = $val;
					}
			/*
			echo '<pre>';
			print_r($poistettu_pvms_jatkoketjulle);
			echo '</pre>';
			*/
		?>
		<?php if( isset($_GET['siirra_now']) ): ?>
		<?php
			$suorittu++;

			$edellinen_model 	= $item->attributes;
			$edelliset_tyoparit 	= json_decode($edellinen_model['tyopaari'], true);
			$jatko_tids 		= [$_GET['kenelle'] => $_GET['kenelle']];
			foreach($edelliset_tyoparit as $tid)
				if($_GET['kenelta'] != $tid){
					$jatko_tids[$tid] = $tid;
				}

			// <-- Nämät ketjut menee eteenpäin jos oli työparia
			if( count($jatko_tids) == 1 and strtotime($item->pto) >= strtotime($alkaen) ){
				foreach($jatko_tids as $jtid){ // no prbl. se looppa 1 kerta vain
					$tv_new = new ToistuvatTyovuorot;
					$tv_new->attributes = $item->attributes;
					$tv_new->pfrom = $saa_aloita;
					$tv_new->tid = $jtid;
					$tv_new->tyopaari = '';
					$tv_new->new_poistettu_pvm = (count($poistettu_pvms_jatkoketjulle) > 0)?json_encode($poistettu_pvms_jatkoketjulle):'';
					$tv_new->save();
				}
			}
			if( count($jatko_tids) > 1 and strtotime($item->pto) >= strtotime($alkaen) ){
				foreach($jatko_tids as $jtid)
					$new_paatid = $jtid; // ihan sama kuka työparista

					$tv_new = new ToistuvatTyovuorot;
					$tv_new->attributes = $item->attributes;
					$tv_new->pfrom = $saa_aloita;
					$tv_new->tid = $new_paatid;
					$tv_new->tyopaari = json_encode(array_values($jatko_tids));
					$tv_new->new_poistettu_pvm = (count($poistettu_pvms_jatkoketjulle) > 0)?json_encode($poistettu_pvms_jatkoketjulle):'';
					$tv_new->save();
			}
			//     Nämät ketjut menee eteenpäin jos oli työparia -->

			// <-- $alkaen asti vanhat ketjut STOPPATAAN
			if( strtotime($item->pto) >= strtotime($alkaen) ){

				if( date("Ymd", strtotime($alkaen." -1 day")) < date("Ymd", strtotime($item->pfrom)) )
					ToistuvatTyovuorot::model()->deletebypk($item->id);

				ToistuvatTyovuorot::model()->updatebypk($item->id, [
					'pto' =>  date("d.m.Y", strtotime($alkaen." -1 day")),
					'new_poistettu_pvm' => (count($poistettu_pvms_alkuperainen_new) > 0)?json_encode($poistettu_pvms_alkuperainen_new):''
				]);
			}
		?>
		<?php endif; ?>
		<tr>
		<td><?=$saa_aloita?></td>
		<td><?=$item->pto?></td>
		<td><?=$item->alku?>-<?=$item->loppu?></td>
		<td>
			<?=isset($item->kohteet->osoite)?$item->kohteet->osoite:$item->osoite?>
			<?=((isset($tilanteet[$item->status]) and ($item->status == 2 or $item->status == 10))?'<p>'.$tilanteet[$item->status].'</p>':'')?>
		</td>
		</tr>
		<tr>
		<td colspan="4">
			<button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#lista_<?=$item->id?>" aria-expanded="false" aria-controls="collapseExample">
			Pvm. lista <i class="caret"></i>
			</button>
			<div class="collapse" id="lista_<?=$item->id?>">
			  <div class="card card-body">
				<br><p><?php
				foreach($pvm_lista as $pvml)
					echo $pvml.'<br>';
				?></p>
			  </div>
			</div>
		</td>
		</tr>
		<?php endforeach; ?>
		</table>
                </div>
              </div>
            </div>
	    <!-- / Toistuvat -->
<?php 
//exit;
?>
	    <!-- / Tavalliset -->
            <div class="admin-form col-sm-6">
	      <legend><h3>Työvuorot - Saaja: <?=$this->etuSukunimi($_GET['kenelle'])?></h3></legend>
              <div class="panel heading-border">
                <div class="panel-body">
		<table class="table table-striped table-bordered">
		<tr>
		<th><?=Yii::t('main', 'Pvm')?></th>
		<th><?=Yii::t('main', 'Aika')?></th>
		<th><?=Yii::t('main', 'Osoite')?></th>
		</tr>
		<?php foreach($data_kenelta as $item) : ?>
		<?php if( isset($_GET['siirra_now']) ): ?>
		<?php
			$suorittu++;
			$kenelta_pvm	= $item->pvm;
			$kenelta_tid	= $item->tid;

			$tv_new = new Tyovuoroot;
			$tv_new->attributes = $item->attributes;
			$tv_new->pvm = date("d.m.Y",strtotime($kenelta_pvm));
			$tv_new->tid = $_GET['kenelle'];
			$tv_new->tyopaari = '';
			if($tv_new->save()){
				if( !$this->tyopari_poisto($item, [$kenelta_tid => $kenelta_tid]) ){
					$this->tvDeleteLog($item);
					$item->deleteByPk($item->id);
				}

				// <-- LOG
				$model_log 	= 'Tyovuoroot';
				$name_log 	= 'Työvuorot';
				$status_log 	= 'Move';	
				$old_values = json_encode($item->attributes);
				$new_values = json_encode($tv_new->attributes);
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->

			} else {
				var_dump($tv_new->getErrors());
				exit;
			}
		?>
		<?php endif; ?>
		<tr>
		<td><?=$item->pvm?></td>
		<td><?=$item->alku?>-<?=$item->loppu?></td>
		<td>
			<?=isset($item->kohteet->osoite)?$item->kohteet->osoite:$item->osoite?>
			<?=((isset($tilanteet[$item->status]) and ($item->status == 2 or $item->status == 10))?'<p>'.$tilanteet[$item->status].'</p>':'')?>
		</td>
		</tr>
		<?php endforeach; ?>
		</table>
                </div>
              </div>
            </div>
	    <!-- / Tavalliset -->


	    <?php if( $suorittu > 0 ){ 
		Yii::app()->user->setFlash('success', "Onnistui!");
		$this->redirect(array('siirto'));
	    } ?>

        </div>
        <!-- loppu: .tray-center -->
	<?php endif; ?>



<script type="text/javascript">
$(document).ready(function(){

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
  }

  if(getUrlVars()["selecter"]){ $(".selecter").attr("disabled", "yes") }

  $(".haemob").click(function(e){
	e.preventDefault(); 
	if( $("#kenelta option:selected").val() === 'kaikki' ){
		$("#kenelta").css({"border" : "1px red solid"});
		return false;
	}
	if( $("#kenelle option:selected").val() === 'kaikki' ){
		$("#kenelle").css({"border" : "1px red solid"});
		return false;
	}
	$("#mobForm").submit();
  });

});
</script>
