<?php

?>

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
	<br>
      </div>
      <div class="modal-body">
        <p>
	   <?php
		$m = ucfirst($taulu);
		$model = new $m;
		echo $this->renderPartial($taulu.'_form',array('model'=>$model, 'pvm'=>$pvm, 'tid'=>$tid)); 
	   ?>
	</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary myBgColors" data-dismiss="modal">Sulje</button>
      </div>
    </div>
  </div>
