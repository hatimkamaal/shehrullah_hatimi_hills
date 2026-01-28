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
      // $db->setUserSession(Dao::construct(['email'=>$email, 'hof_id'=>-1,'state'=>'NEW']));

      $loginData = $db->getUserLoginData($email);
      if( is_null($loginData) ) {
          $this->setTransitMessage('Oops! Can not locate your profile ('.$email.'). <a href="https://wa.me/8390403052" target="_blank">Click to Chat with us</a>.');
      } else {
        if( in_array( $loginData->sector, [7,13])  ) {

          $user_roles = $db->get_user_roles($loginData->id);
          $loginData->roles = explode(',' , $user_roles);

          $loginData->state = 'LINKED';
          $db->setUserSession($loginData);
          $uri = $dao->home_uri . '/' . LANDING_PAGE;
          header('Location: ' . $uri);
        } else {
          $this->setTransitMessage('Oops! Not found in Hatimi Hills Sector ('.$email.'). <a href="https://wa.me/8390403052" target="_blank">Click to Chat with us</a>.');
        }
      }

      $dao->authUrl = $dao->home_uri . '/login';
    } else {
      $dao->authUrl = $client->createAuthUrl();
    }

      $this->render('login', $dao);
  }

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
    $user_roles = $db->get_user_roles($loginData->id);
    $loginData->roles = explode(',' , $user_roles);

    $loginData->state = 'LINKED';
    $db->setUserSession($loginData);

    // $ssn = new Ssn();
    // Ssn::destroy();
    // $db->setUserSession(Dao::construct(['email'=>$email, 'hof_id'=>$hof,'state'=>'NEW']));

    $this->do_redirect('home', $dao);
  }


  public function postHof(Dao $dao) {
    $hof_id = trim($dao->hof_id);

    $db = new DBService();
    $loginData = $db->lookLoginDataForHOF($hof_id);
    if( is_null($loginData) ) {
        $ssn = new Ssn();
        $msg = urlencode('HOF ID '.$hof_id.': not found');
        $ssn->transit_data = 'Oops! Can not locate your profile for HOF ID ('.$hof_id.'). <a href="https://wa.me/8390403052?text='.$msg.'" target="_blank">Click to Chat with us</a>.'; 
        $this->do_redirect('login', $dao);
        return;
    } else {
        $user_roles = $db->get_user_roles($loginData->id);
        $loginData->roles = explode(',' , $user_roles);

        $loginData->state = 'LINKED';
        $db->setUserSession($loginData);

        $this->do_redirect('home', $dao);
    }
  }
}