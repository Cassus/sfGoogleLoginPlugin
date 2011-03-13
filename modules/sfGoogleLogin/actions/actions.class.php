<?php

/**
 * sfGoogleLogin actions.
 *
 * @package    sfGoogleLoginPlugin
 * @subpackage actions
 * @author     Sebastian Herbermann <sebastian.herbermann@googlemail.com>
 */
class sfGoogleLoginActions extends sfActions
{

  public function executeLogin(sfWebRequest $request)
  {

  }

  public function executeVerify(sfWebRequest $request)
  {
    $this->success = false;

    $user = $this->getUser();
    $googleOpenID = new GoogleOpenID('http://' . $_SERVER['SERVER_NAME']);

    if ($googleOpenID->verifyLogin() && $token = $googleOpenID->getUser())
    {
      $googleAccount = GoogleAccountTable::getInstance()->findOrCreateOneByUserToken($token); /* @var $googleAccount GoogleAccount */


      if (isset($googleAccount->sfGuardUser))
      {
        $sfGuardUser = $googleAccount->sfGuardUser;
      }
      else
      {
        $sfGuardUser = sfGuardUserTable::getInstance()->findOneByEmailAddress($googleOpenID->getEmail());
      }
      if (!$sfGuardUser)
      {
        $sfGuardUser = $this->registerGoogleOpenIdUser($googleAccount, $googleOpenID->getEmail());
      }

      $this->getUser()->signIn($sfGuardUser);
      $googleAccount->save();
      $this->success = true;
    }
    $this->setLayout(false);
  }

  protected function registerGoogleOpenIdUser(GoogleAccount $googleAccount,
                                              $emailAddress)
  {
    if (method_exists($this->getUser(), 'registerGoogleOpenIdUser'))
    {
      return $this->getUser()->registerGoogleOpenIdUser($googleAccount, $emailAddress);
    }
    else
    {
      $sfGuardUser = new sfGuardUser();
      $sfGuardUser->username = $emailAddress;
      $sfGuardUser->email_address = $emailAddress;
      $sfGuardUser->GoogleAccount = $googleAccount;
      $sfGuardUser->save();

      return $sfGuardUser;
    }
  }

}
