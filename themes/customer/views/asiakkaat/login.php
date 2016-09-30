<?php

?>
<br><br><br><br>

    <div class="container-fluid">
     <div class="col-sm-4 col-sm-offset-4">
      <form action="#" class="form-signin" method="POST">
        <h2 class="form-signin-heading"><?php echo $dm->yritys; ?></h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="sahkoposti" class="form-control input-lg" placeholder="<?php echo Yii::t('main', 'Sähköposti'); ?>" required autofocus>
	<br>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="salasana" class="form-control input-lg" placeholder="<?php echo Yii::t('main', 'Salasana'); ?>" required>
	<br>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo Yii::t('main', 'Kirjaudu sisään'); ?></button>
      </form>
     </div>
    </div> <!-- /container -->


