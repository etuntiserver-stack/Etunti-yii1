<?php

if($tila == 'onlinevaraus'){
   $asiasta = 'onlinevarauksesta';
   $body = '<p>Haluan, että Etunti on minuun yhteydessä online-varaukseen liittyen.</p>';
}
if($tila == 'lasku'){
   $asiasta = 'laskutusta';
   $body = '
	<p>Laskutus toimii sähköisesti ja edellyttää luotettavaa yhteistyökumppania laskujen välittämiseksi.</p>
	<p>Välittäjän tunnukset syötetään asetuksissa. Välittäjätunnukset saat helposti kauttamme.</p>
	<p><b>Haluan että soitatte minulle ja kerrotte tarkemmin: </b></p>';
}

			echo '
			<div class="modal show" id="myModalIlmoitus" role="dialog">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title">Ilmoitus</h5>
			      </div>
			      <div class="modal-body">
			        <p>
				'.$body.'
				</p>
			      </div>
			      <div class="modal-footer">
			        '.CHtml::link('KYLLÄ',array("site/index", "soittaa" => true, 'otsikko' => 'Soittopyyntöviesti', 'viesti' => 'Soittopyyntö koskien '.$asiasta.'. Domain: '.Yii::app()->user->domain), array('class' => 'btn btn-primary')).'
			        '.CHtml::link('EI',array("site/etusivu"), array('class' => 'btn btn-default')).'
			      </div>
			    </div>
			  </div>
			</div>
			';

?>
