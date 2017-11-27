<?php

class LogoutController extends Controller
{
	public $defaultAction = 'logout';
	
	/**
	 * Logout the current user and redirect to returnLogoutUrl.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		if(isset($_SESSION['domain'])){ unset($_SESSION['domain']); }
		$this->redirect(Yii::app()->request->baseUrl.'/index.php');
	}

}
