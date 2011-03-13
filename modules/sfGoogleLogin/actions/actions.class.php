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
        $sfGuardUser = $this->getUser()->registerGoogleOpenIdUser($googleAccount, $googleOpenID->getEmail());
      }

      $this->getUser()->signIn($sfGuardUser);
      $googleAccount->save();
      $this->success = true;
    }
    $this->setLayout(false);
  }

}
