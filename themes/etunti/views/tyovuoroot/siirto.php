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
        	        <?php echo CHtml::link(Yii::t('main', 'Keskeytä'), array('siirto'),array('class'=>'btn btn-primary btn-lg siirra btn-block myBgColors')); ?>
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


	<?php if( count($data_kenelta_k) > 0 ) : ?>
	<?php 
		$suorittu = 0; 
		$site = Yii::app()->createController('Site');
	?>
        <!-- begin: .tray-center -->

	<div class="text-center">
		<h2>Saaja: <?=$this->etuSukunimi($_GET['kenelle'])?></h2>
		<form action="#" method="GET">
		<input type="hidden" name="kenelta" value="<?=$_GET['kenelta']?>">
		<input type="hidden" name="kenelle" value="<?=$_GET['kenelle']?>">
		<input type="hidden" name="alkaen" value="<?=$_GET['alkaen']?>">
		<input type="hidden" name="siirra_now" value="true">
		<button type="submit" class="btn btn-primary btn-lg siirra myBgColors"><i class="fa fa-2x fa-arrow-right"></i></button>
		</form>
	</div>
	<hr>
	<div class="text-center">
		<h2>Keneltä: <?=$this->etuSukunimi($_GET['kenelta'])?></h2>
	</div>
        <div class="tray-center row">
	    <!-- / Toistuvat -->
            <div class="admin-form col-sm-6">
	      <legend><h3>Toistuvat ketjut</h3></legend>
              <div class="panel heading-border">
                <div class="panel-body bg-light">
		<p class="text-danger">Huomio! Poistetut ketjussa olevat päivät tulee saajallekin poistettuna.</p>
		<table class="table table-striped">
		<tr>
		<th><?=Yii::t('main', 'Ketju')?></th>
		<th><?=Yii::t('main', 'Aika')?></th>
		<th><?=Yii::t('main', 'Osoite')?></th>
		</tr>
		<?php foreach($data_kenelta_k as $item) : ?>
		<?php if( isset($_GET['siirra_now']) ): ?>
		<?php
			$suorittu++;
			$edellinen_model 	= $item->attributes;
			$edelliset_tyoparit 	= json_decode($edellinen_model['tyopaari'], true);
			$edelliselle_new_tyoparit = [];
			$new_tid_edelliselle = $item->tid; // ensin vanha
			foreach($edelliset_tyoparit as $tid)
				if($_GET['kenelta'] != $tid){
					$edelliselle_new_tyoparit[$tid] = $tid;
					$new_tid_edelliselle = $tid; // ihan sama minkäläinen olevasta työparista
				}

			if( count($edelliselle_new_tyoparit) == 1 and isset($edelliselle_new_tyoparit[$item->tid]) )
				unset($edelliselle_new_tyoparit[$item->tid]);

			$edelliselle_new_tyoparit = (count($edelliselle_new_tyoparit) > 0)?json_encode(array_values($edelliselle_new_tyoparit)):'';
			ToistuvatTyovuorot::model()->updatebypk($item->id, array('tid' => $new_tid_edelliselle, 'tyopaari' => $edelliselle_new_tyoparit));

			// <-- Vanha ketju lopetetaan Keneltä
			$tv_new = new ToistuvatTyovuorot;
			$tv_new->attributes = $item->attributes;
			$tv_new->pto = date("d.m.Y",strtotime($alkaen.' -1 day'));
			$tv_new->tid = $_GET['kenelta'];
			$tv_new->tyopaari = '';
			if($tv_new->save()){

			}

			// <-- Viikkoja laskenta alkuperäisestä
			$startday 	= date("Y-m-d", strtotime($item->pfrom));
			$stopday 	= date("Y-m-d", strtotime($item->pto));
			$alkaen_YW	= date("YW",strtotime($alkaen.' -1 day'));
			$saa_aloita 	= $alkaen;

			$date = new \DateTime($startday, new DateTimeZone('Europe/Helsinki'));
			$date->modify('this week monday');
			$date_end = (new \DateTime($stopday, new DateTimeZone('Europe/Helsinki')))->getTimestamp();
			
			while ($date->getTimestamp() <= $date_end){
				$this_week_sunday = date("YW", strtotime($date->format("d.m.Y").' this week sunday'));
				if( $this_week_sunday >= $alkaen_YW ){
					$saa_aloita = date("d.m.Y", strtotime($date->format("d.m.Y").' this week monday'));
					break;
				}
				$date->modify("+{$item->viikkoja}week");
			}

			// <-- Kenelle uusi ketju
			$tv_new = new ToistuvatTyovuorot;
			$tv_new->attributes = $item->attributes;
			$tv_new->pfrom = $saa_aloita;
			$tv_new->tid = $_GET['kenelle'];
			$tv_new->tyopaari = '';
			if($tv_new->save()){

			}

		?>
		<?php endif; ?>
		<tr>
		<td><?=$alkaen?>-<?=$item->pto?></td>
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
	    <!-- / Toistuvat -->

	    <!-- / Tavalliset -->
            <div class="admin-form col-sm-6">
	      <legend><h3>Työvuorot</h3></legend>
              <div class="panel heading-border">
                <div class="panel-body bg-light">
		<table class="table table-striped">
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
