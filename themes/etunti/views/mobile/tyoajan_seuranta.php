<?php
ini_set('max_execution_time', 900);
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Vuosityöaika'); ?>

   <!-- tulostus -->
   <div class="pull-right">
      <button name="tulosta" class="btn btn-primary btn-sm myBgColors tulosta" value="PDF"><i class="fa fa-print" style="font-size: 120%" aria-hidden="true"></i></button>
   </div>
   <!-- tulostus -->

	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
				<?php
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'tid', // name
						'gui-input', //class
						null, // id
						$tid, //selected
						1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
				<?php
				$start_year = date('Y', strtotime('-10 year'));
				$stop_year = date('Y');
				$options = '';
				if(!empty($year))
				    $options .= '<option value="'.$year.'" selected>'.$year.'</option>';
				for ($i = $start_year; $i <= $stop_year; $i++) {
				    $options .= '<option value="'.$i.'">'.$i.'</option>';
				}
				?>
				<select name="vuosi" class="gui-input">
				 <?php if(empty($year)) : ?>
				  <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
				 <?php endif; ?>
				 <?php echo $options; ?>
				</select>
                            <i class="arrow double"></i>
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


<?php if( !empty($tid) and !empty($year) ) : ?>

<?php
	$ft = FirmanTiedot::model()->findByPk(1);
?>

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

	<div class="row">
	 <div class="table-responsive" id="kokoTaulu">
	 <h3><?php echo $ft->tyonantaja.', '.$this->etuSukunimi($tid).', '.$year; ?>.</h3>
	  <table class="table table-bordered table-striped table-hover"  cellpadding="0" cellspacing="0" >
	   <tr>
	    <th><?php echo Yii::t('main', 'Viikot'); ?></th>
	    <th colspan="14"><?php echo Yii::t('main', 'Tehdyt tunnit'); ?></th>
	    <th><?php echo Yii::t('main', 'Tun-<br>nit<br>yht'); ?></th>
	    <th><?php echo Yii::t('main', 'Sun-<br>nuntai<br>työ'); ?></th>
	    <th><?php echo Yii::t('main', 'Ilta-<br>työ'); ?></th>
	    <th><?php echo Yii::t('main', 'Yö-<br>työ'); ?></th>
	   </tr>


	   <?php
		$startVko = array(1,2);
	   ?>


		<tr>
		  <th></th>
		  <th><?php echo Yii::t('main', 'ma'); ?></th>
		  <th><?php echo Yii::t('main', 'ti'); ?></th>
		  <th><?php echo Yii::t('main', 'ke'); ?></th>
		  <th><?php echo Yii::t('main', 'to'); ?></th>
		  <th><?php echo Yii::t('main', 'pe'); ?></th>
		  <th><?php echo Yii::t('main', 'la'); ?></th>
		  <th><?php echo Yii::t('main', 'su'); ?></th>
		  <th><?php echo Yii::t('main', 'ma'); ?></th>
		  <th><?php echo Yii::t('main', 'ti'); ?></th>
		  <th><?php echo Yii::t('main', 'ke'); ?></th>
		  <th><?php echo Yii::t('main', 'to'); ?></th>
		  <th><?php echo Yii::t('main', 'pe'); ?></th>
		  <th><?php echo Yii::t('main', 'la'); ?></th>
		  <th><?php echo Yii::t('main', 'su'); ?></th>
		  <th></th>
		  <th></th>
		  <th></th>
		  <th></th>
		</tr>

	   <?php for ($i = 1; $i <= 27; $i++) : ?>
	   
	   <tr>
	    <td><?php echo $startVko[0]; ?>-<?php echo $startVko[1]; ?></td>

	  	<?php 
		$tunnitYht = 0;
		for($day= 1; $day <= 7; $day++) {

	  	     	$pvm = date("d.m.Y", strtotime($year ."W". sprintf("%02d", $startVko[0]) . $day));
			$toteutu = $this->toteutuneet($tid,$pvm,3,null);
			$tunnitYht += $toteutu;
			if($toteutu == 0) 
				$toteutu = '<span style="color:#ccc">00:00</span>';
			else
				$toteutu = $this->sprint($toteutu);


			echo '<td><span>'.$toteutu.'</span></td>';

		}
	        ?>
	  	<?php 
		for($day= 1; $day <= 7; $day++) {

	  	     	$pvm = date("d.m.Y", strtotime($year ."W". sprintf("%02d", $startVko[1]) . $day));
			$toteutu = $this->toteutuneet($tid,$pvm,3,null);
			$tunnitYht += $toteutu;
			if($toteutu == 0) 
				$toteutu = '<span style="color:#ccc">00:00</span>';
			else
				$toteutu = $this->sprint($toteutu);

			echo '<td><span>'.$toteutu.'</span></td>';
		}
	        ?>


	    <?php
	  	$from = date("d.m.Y", strtotime($year ."W". sprintf("%02d", $startVko[0]) . '1'));
	  	$to = date("d.m.Y", strtotime($year ."W". sprintf("%02d", $startVko[1]) . '7'));
		$suYoIlta = $this->toteutu($tid,'palkkataulukko',$from,$to)
	    ?>
	    <th><?php echo $this->sprint($tunnitYht); ?></th>
	    <th><?php echo $this->sprint($suYoIlta[3]); ?></th>
	    <th><?php echo $this->sprint($suYoIlta[1]); ?></th>
	    <th><?php echo $this->sprint($suYoIlta[2]); ?></th>
	   </tr>
	   <?php
		$startVko[0]=$startVko[0]+2;
		$startVko[1]=$startVko[1]+2;
	   ?>
	   <?php endfor; ?>

	  </table>
	 </div>
	</div>

   </div>
  </div>
</div>
<?php endif; ?>


<script>
$(document).ready(function(){


 $(document).delegate(".tulosta","click",function(){
	
    var divToPrint = document.getElementById('kokoTaulu');

    var htmlToPrint = '' +
        '<style type="text/css">' +
	'.table tbody>tr>td{' +
	    	'vertical-align: top;' +
	'}' +
        'table {' +
	'border-collapse: collapse;' +
	'border: 0;' +
        '}' +
        'table th, table td {' +
        'border:1px solid #333;' +
        'padding:3px 5px;' +
        '}' +
        '</style>';
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    newWin.print();
    newWin.close();
 });


});
</script>

