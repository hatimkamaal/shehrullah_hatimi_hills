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

      $hof_id = $this->getHOF($email);

      $ssn = new Ssn();
      $key = APP_SESSION_KEY;
      $ssn->$key = ['signin_email' => $email, 'signin_hof_id'=>$hof_id, 'signin' => true];
      $uri = $appData->home_uri . '/' . LANDING_PAGE;
      header('Location: ' . $uri);
    } else {
      $dao->authUrl = $client->createAuthUrl();
      $this->render('login', $dao);
    }

  }

  public function getHOF($email)
  {
    $hof_id = -1;
    $db = new DBService();
    $result = $db->checkEmail($email);
    if ($result->success) {
      if ($result->count > 0) {
        $data = $result->data[0];
        $hof_id = $data->hof_id ?? -1;
      } else {
        //Add email.
        $db->addEmail($email);
      }
    } else {
      echo 'Oops! some error';
    }

    return $hof_id;
  }

}