<?php 

class RegisterCtrl extends Ctrl {

    public function get(Dao $dao) {
        $dbs = new DBService();
        $hof_id = $dao->user_session->hof_id;
        if( $hof_id < 0 ) {
            $link_to_hof = $dao->link_to_hof ?? '';
            
            //debig
            //echo $link_to_hof . '<br/>';

            if( strlen($link_to_hof) > 0 ) {
                $its_data = $dbs->getITSData($link_to_hof);

                if( is_null( $its_data ) ) {
                    //This was unexpected.
                    Ssn::del('link_to_hof');
                    $this->do_redirect_with_message('home' , 'Unexpected error occured. Please retry');
                } else {
                    $dao->its_data = $its_data;  
                    //debug 
                    // echo $dao->its_data->full_name . '<br/>';
                    // exit;
                }
            }
            //hof_id = -1 , means email is not linked with any hof_id. Lets ask your to enter that.
            $this->render('register' , $dao);
        } else {
            //hof_id > 0, means, we have user already using this email and linked to hof_id
            $this->do_redirect('familyList' , $dao);
        }

    }

    public function post(Dao $dao) {
        $email = $dao->user_session->email;
        $hof_id = $dao->user_session->hof_id;
        $full_name = $dao->full_name;
        $whatsapp = $dao->whatsapp;
        $contact = $dao->whatsapp;
        $wingflat = $dao->wingflat;
        $sector = 7;

        $params = [$email,$hof_id, $full_name, $contact,$whatsapp, $wingflat,$sector];

        $dbs = new DBService();
        $resp = $dbs->createLoginDataFor($params);
        if( $resp ) {
            $loginData = $dbs->getUserLoginData($email);
            if( is_null($loginData) ) {
                //$db->setUserSession($loginData);
                $this->setTransitMessage('Oops_1! perhaps something went wrong. Try again');
                $this->render('register' , $dao);
            } else {
                $loginData->state = 'LINKED';
                $dbs->setUserSession($loginData);
                $this->do_redirect('familyList', $dao);
            }

        } else {
            $this->setTransitMessage('Oops_2! perhaps something went wrong. Try again');
            $this->render('register' , $dao);
        }

    }
}