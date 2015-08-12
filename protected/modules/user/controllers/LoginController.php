<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']))
			{

			$mod=Administrators::model()->find(" adm_login = '".$_POST['UserLogin']['username']."' and adm_salasana = '".md5($_POST['UserLogin']['password'])."' ");

			  if(isset($mod->id))
			  {
			    Yii::app()->user->setState('adminID', $mod->id);
			    Yii::app()->user->setState('adminTunnus', $mod->adm_login);
			    $this->redirect("index.php?r=sivexkuitti/index");
			  } else {
			    $this->render('/user/login',array('model'=>$model));
			  }
			exit;
			}
			// display the login form
			$this->render('/user/login',array('model'=>$model));
		} else
			$this->redirect(Yii::app()->controller->module->returnUrl);
	}
	
	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit = time();
		$lastVisit->save();
	}

}
