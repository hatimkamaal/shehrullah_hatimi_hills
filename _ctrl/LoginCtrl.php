<?php

class LoginCtrl extends Ctrl {

    public function get(Dao $dao) {

        require_once('vendor/Google/autoload.php');
                
        $client = new Google_Client();
        $client->setClientId( GOOGLE_CLIENT_ID );
        $client->setClientSecret( GOOGLE_CLIENT_SECRET );
        $client->setRedirectUri( GOOGLE_REDIRECT_URL );
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

          $_SESSION['fromLogin'] = "true";
          $_SESSION['email'] = $user->email;
          
          $_SESSION[APP_SESSION_KEY] = serialize(['email'=>$user->email , 'fromLogin'=>true]);
          
          $uri = $appData->home_uri . '/' . LANDING_PAGE;
          header('Location: ' . $uri);
        } else {
          $dao->authUrl = $client->createAuthUrl();
          $this->render('login' , $dao);
        //   echo "<h2><a href='$authUrl'>Google Login</a></h2>";
        }

    }

}