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
        $hof_id = $dao->signin_hof_id;
        if( $hof_id < 0 ) {
            $this->render('home' , $dao);
        } else {
            $this->do_redirect('familyList' , $dao);
        }

        $this->render('home', $dao);
    }

    public function post(Dao $dao) {
        $hof_id = $dao->hof_id;
        $email = $dao->signin_email;
        $dbctrl = new DBService();
        $result = $dbctrl->updateHofId($email, $hof_id);
        if($result->success) {
            $ssn = new Ssn();
            $key = APP_SESSION_KEY;
            $ssn->$key = ['signin_email'=>$email, 'signin_hof'=>$hof_id, 'signin'=>true];
        }



        // $result = $dbctrl->getFamilyDetailsForHOF($hof_id);
        // // $query = 'SELECT * FROM its_data where hof_id=?;';
        // // $result = $this->execute_query($query , $hof_id);
        // if( $result->success && $result->count > 0 ) {
        //     // $dao->records = $result->data;
        //     // $this->render('family_list', $dao);

        //     // $en_hof_id = base64_encode($hof_id);
        //     // $newDao = new Dao();
        //     // $newDao->params = $en_hof_id; 
        //     // $newDao->url = $dao->home_uri . '/familyList';  
        //     // $this->do_redirect('getToPost' , $newDao);

        //     $ssn = new Ssn();
        //     $ssn->hof_id = $hof_id;
        //     $this->do_redirect('FamilyList' , $dao);
        // } else {
        //     $dao->error = 'Oops! This is not a HOF ID. Please make sure HOF ID is entered.';
        //     $this->render('home', $dao);
        // }
    }

    /**
     * Summary of getSearch
     * 
     * @param Dao $dao
     * @return void
     */
    public function getSearch(Dao $dao) {
        $this->do_redirect('home', $dao);
    }
    public function postSearch(Dao $dao)
    {
        $hof_id = $dao->hof_id;

        $query = 'SELECT * FROM its_data where hof_id=?;';
        $result = $this->execute_query($query , $hof_id);
        if( $result->success && $result->count > 0 ) {
            $dao->records = $result->data;
            $this->render('family_list', $dao);
        } else {
            $dao->error = 'Oops! This is not a HOF ID. Please make sure HOF ID is entered.';
            $this->render('home', $dao);
        }
        
    }

    public function getDeleteMember(Dao $dao) {

        $input = $dao->arg_2;
        $value = base64_decode($input);

        list($its_id, $hof_id) = explode('-' , $value);
        // $hof_id = $dao->hof_id;
        // $its_id = $dao->its_id ?? '-1';

        echo "$hof_id - $its_id";

        // $query = 'DELETE FROM its_data where hof_id=? && its_id=?;';
        // $result = $this->execute_query($query , $hof_id, $its_id);
        // if( $result->success && $result->count > 0 ) {
        //     $dao->error = 'Record deleted successfully.';
        //     $this->post($dao);
        // } else {
        //     $dao->error = 'Failed to delete record.';
        //     $this->post($dao);
        // }
    }


}   