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
      } else {
        $loginData->state = 'LINKED';
        $db->setUserSession($loginData);
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
    $ssn->destroy();
    $this->do_redirect('home', $dao);
  }

}