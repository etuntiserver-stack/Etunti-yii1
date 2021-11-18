<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';

        public function init()
        {

		if( isset($_POST['UserLogin']['domain']) )
		{
		    $domainit = Domainit::model()->find(" domain!='".$_POST['UserLogin']['domain']."' AND kirjautumistunnus='".$_POST['UserLogin']['domain']."' ");
		    if(isset($domainit->domain))
		    {

			$domain = $domainit->domain;
			$_SESSION['domain'] = $domain;

		        echo '
			<form id="myForm" action="'.Yii::app()->request->baseUrl.'/index.php/user/login" method="post">
			<input type="hidden" name="UserLogin[domain]" value="'.$domain.'">
			<input type="hidden" name="UserLogin[username]" value="'.$_POST['UserLogin']['username'].'">
			<input type="hidden" name="UserLogin[password]" value="'.$_POST['UserLogin']['password'].'">
			</form>
			<script type="text/javascript">
			    document.getElementById("myForm").submit();
			</script>
			';
			exit;
		    }
		}

                parent::init();
        }

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{

		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']) and !isset($_POST['UserLogin']['domain']) and isset($_SERVER['REMOTE_ADDR'])){
				echo $_SERVER['REMOTE_ADDR'].'. Your IP is saved';
				exit;
			}
			if(
				isset($_POST['UserLogin'])
				and trim($_POST['UserLogin']['domain']) != 'superadmin'
				and trim($_POST['UserLogin']['domain']) != 'etusivu'
				and trim($_POST['UserLogin']['domain']) != ''
			)
			{




			if(isset($_SESSION['domain']))
			{
				$domain = $_SESSION['domain'];
			} else {
				$domain = trim(Yii::app()->request->getPost('UserLogin')['domain']);
			}

	       		$criteria = new CDbCriteria();
		        $criteria->condition = " 
				adm_login='".Yii::app()->request->getPost('UserLogin')['username']."' 
				AND token=''
				AND adm_salasana!=''
			";
			$mod=Administrators::model()->find($criteria);


			  if(isset($mod->id))
			  {

				// <-- Check password
				$login = false;

				if( strlen($mod->adm_salasana) < 60 ){

		        		if(md5(Yii::app()->request->getPost('UserLogin')['password']) == $mod->adm_salasana)
					$login = true;

				} elseif( strlen($mod->adm_salasana) == 60 ){

					if (password_verify(Yii::app()->request->getPost('UserLogin')['password'], $mod->adm_salasana))
					$login = true;
				}
				
				if(!$login)
				{
					Yii::app()->user->setFlash('danger', "Tarkasta yritys- ja käyttäjätunnus sekä salasana");
					$this->redirect(Yii::app()->request->baseUrl.'/index.php/site/index');
					exit;
				}
				//     Check password -->


			    Yii::app()->user->setState('id', $mod->id);
			    Yii::app()->user->setState('adminID', $mod->id);
			    Yii::app()->user->setState('adminStatus', $mod->status);
			    Yii::app()->user->setState('username', $mod->adm_login);
			    Yii::app()->user->setState('nimi', $mod->adm_nimi);
				Yii::app()->user->setState('domain', $domain);

				// Whether or not kotipuhtaaksi -specific features are enabled or being tested.
				Yii::app()->user->setState('kp', (in_array($domain, ['kotipuhtaaksi', 'staging_kotipuhtaaksi', 'demo', 'staging_demo', 'sivex'])));
				// flag to indicate if user is actually in kotipuhtaaksi domain
				Yii::app()->user->setState("kotipuhtaaksi", in_array($domain, ["kotipuhtaaksi", 'staging_kotipuhtaaksi', 'demo', 'staging_demo']));

			    $domainit=Domainit::model()->find(" domain = '".$domain."' ");
			    if(isset($domainit->paketti))
			    	Yii::app()->user->setState('adminPaketti', $domainit->paketti);

			    if(strpos($domain, 'staging_') !== false)
			    	Yii::app()->user->setState('adminPaketti', "1,2,3,4,5");

			    $this->redirect(Yii::app()->request->baseUrl.'/index.php/site/etusivu');
			  } else {
			    Yii::app()->user->setFlash('danger', "Tarkasta yritys- ja käyttäjätunnus sekä salasana");
			    $this->redirect(Yii::app()->request->baseUrl.'/index.php/site/index');
			  }
			exit;
			}

			if(
				isset($_POST['UserLogin']) 
				and (trim($_POST['UserLogin']['domain']) == 'superadmin' or trim($_POST['UserLogin']['domain']) == 'etusivu')
			)
			{

				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
				Yii::app()->user->setState(trim($_POST['UserLogin']['domain']), true);
					$this->lastViset();
					if (Yii::app()->user->returnUrl=='/index.php')
						$this->redirect(Yii::app()->request->baseUrl.'/index.php/domainit/admin');
					else
						$this->redirect(Yii::app()->request->baseUrl.'/index.php/domainit/admin'); 
				}
			}

			// display the login form
			//$this->render('/user/login',array('model'=>$model));


			    Yii::app()->user->setFlash('danger', "Tarkasta yritys- ja käyttäjätunnus sekä salasana");
			    $this->redirect(array('/user/logout'));

		} else
			$this->redirect(Yii::app()->controller->module->returnUrl);
	}
	
	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit = time();
		$lastVisit->save();
	}

}

