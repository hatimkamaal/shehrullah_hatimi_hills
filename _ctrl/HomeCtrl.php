<?php
class HomeCtrl extends Ctrl{
    /**
     * Home URL
     * 
     * @param Dao $dao
     * @return void
     */
    public function get(Dao $dao)
    {
        //Get the session HOF ID.
        $hof_id = $dao->user_session->hof_id;
        if( $hof_id < 0 ) {
            //hof_id = -1 , means email is not linked with any hof_id. Lets ask your to enter that.
            $this->render('home' , dao: $dao);
        } else {
            //hof_id > 0, means, we have user already using this email and linked to hof_id
            $this->do_redirect('familyList' , $dao);
        }

        $this->render('home', $dao);
    }

    public function post(Dao $dao) {
        $hof_id = $dao->hof_id;
        $email = $dao->user_session->email;
        $dbctrl = new DBService();

        //Lets see if the user provided HOF_ID exist in our its_data store
        $hofData = $dbctrl->getITSData($hof_id);
        if( is_null($hofData) ) {
            //If hofdata is null, means hof_id doesn't exist. Lets throw an error for the User. Because we need many much details.
            $this->setTransitMessage('We could not locate this ITS ID : ' . $hof_id . '. Please change or contact Hatim Kamaal on whatsapp.');
            //$this->render('familyList/add', $dao);
            $this->render('home', $dao);
        } else {
            if( $hofData->hof_id == $hofData->its_id ) {
                //HOF Found
                //Check if this HOF is linked already with other email.
                $hofData2 = $dbctrl->lookLoginDataForHOF($hofData->hof_id);
                if( is_null($hofData2) ) {
                    //Link the email to new HOF id
                    //$this->setTransitMessage('UNFURNISHED: We will need This HOF ID : ' . $hof_id . ' is a member of '. $hofData->hof_id .'. Please enter correct HOF ID or conatct Hatim Kamaal.');
                    //$this->render('home', $dao);
                    Ssn::set('link_to_hof' , $hofData->hof_id);
                    
                    $this->setTransitMessage('UNFURNISHED: We will need mode details for : ' . $hof_id . '.');
                    $this->render('home', $dao);


                    //$this->do_redirect('register' , $dao);
                } else {
                    if( $hofData2->email === $email ) {
                        $dbctrl->setUserSession($hofData2);                        
                        $this->do_redirect('familyList', $dao);
                    } else {
                        $this->setTransitMessage('This HOF ID : ' . $hof_id . ' is a linked to other email '. $hofData2->email .'. Please login with correct email or conatct Hatim Kamaal.');
                        //$this->render('familyList/add', $dao);
                        $this->render('home', $dao);
                    }
                }

            } else {
                //Seems member and not a HOF.
                $this->setTransitMessage('This HOF ID : ' . $hof_id . ' is a member of '. $hofData->hof_id .'. Please enter correct HOF ID or conatct Hatim Kamaal.');
                //$this->render('familyList/add', $dao);
                $this->render('home', $dao);
            }

        }
    }

    /**
     * Summary of getSearch
     * 
     * @param Dao $dao
     * @return void
     */
    // public function getSearch(Dao $dao) {
    //     $this->do_redirect('home', $dao);
    // }
    // public function postSearch(Dao $dao)
    // {
    //     $hof_id = $dao->hof_id;

    //     $query = 'SELECT * FROM its_data where hof_id=?;';
    //     $result = $this->execute_query($query , $hof_id);
    //     if( $result->success && $result->count > 0 ) {
    //         $dao->records = $result->data;
    //         $this->render('family_list', $dao);
    //     } else {
    //         $dao->error = 'Oops! This is not a HOF ID. Please make sure HOF ID is entered.';
    //         $this->render('home', $dao);
    //     }
        
    // }

    // public function getDeleteMember(Dao $dao) {

    //     $input = $dao->arg_2;
    //     $value = base64_decode($input);

    //     list($its_id, $hof_id) = explode('-' , $value);
    //     // $hof_id = $dao->hof_id;
    //     // $its_id = $dao->its_id ?? '-1';

    //     echo "$hof_id - $its_id";

    //     // $query = 'DELETE FROM its_data where hof_id=? && its_id=?;';
    //     // $result = $this->execute_query($query , $hof_id, $its_id);
    //     // if( $result->success && $result->count > 0 ) {
    //     //     $dao->error = 'Record deleted successfully.';
    //     //     $this->post($dao);
    //     // } else {
    //     //     $dao->error = 'Failed to delete record.';
    //     //     $this->post($dao);
    //     // }
    // }


}   