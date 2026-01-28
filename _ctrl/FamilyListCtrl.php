<?php 

class FamilyListCtrl extends Ctrl {

    public function get(Dao $dao) {
        $hof_id = $dao->user_session->hof_id;
        
        $dbctrl = new DBService();
        $dao->records = $dbctrl->getFamilyDetailsWithPref($hof_id);

        $takhRecord = $dbctrl->getTakhmeenRecordFor($hof_id);
        $dao->takhRecord = $takhRecord;
        $dao->pirsa_selected = ( isset($takhRecord) && $takhRecord->pirsa_count > 0 ) ? ' checked ' : '';
        $dao->pirsa_comment = isset($takhRecord) && isset($takhRecord->pirsa_comment) ? $takhRecord->pirsa_comment : '';

        $this->render('family_list' , $dao);        
    }

    public function post(Dao $dao) {

        $attendsList = is_array( $dao->family_its_list) ? $dao->family_its_list : [];
        $chairList = is_array($dao->chair_its_list) ? $dao->chair_its_list : [];

        $hof_id = $dao->user_session->hof_id;        
        $dbctrl = new DBService();
        $result = $dbctrl->getFamilyDetailsForHOF($hof_id);
        $records = $result->data;
        $chair_count = 0;     
        $attendees_count = 0; 
        // $attendance_type = '';  
        // $chair_preference = '';
        foreach($records as $record) {
            $its_id = $record->its_id;
            // $name = "atnd_pref_$its_id";
            // $atnd_pref = $dao->$name;
            $attendance_type = 'N';
            $atnd_pref = 'N';

            if( isset($attendsList) && in_array($its_id, $attendsList) ) {
                $attendance_type = 'Y';
                $attendees_count ++;
                $atnd_pref = 'A';
            }
            $chair_preference = 'N';
            if( isset($chairList) && in_array($its_id, $chairList) ) {
                $chair_preference = 'Y';
                $chair_count ++;
                if ( $atnd_pref === 'Y' ) {
                    $atnd_pref = 'AC';
                } 
            }

            // $attendance_type = isset($attendsList) && in_array($its_id, $attendsList) ? 'Y' : 'N';
            // $chair_preference = isset($chairList) && in_array($its_id, $chairList) ? 'Y' : 'N';

            $params = [$its_id, $hof_id, $atnd_pref, 
            $attendance_type, $chair_preference, $atnd_pref, 
            $attendance_type, $chair_preference];

            $result = $dbctrl->addAttendeesRecord($params);
            if( !$result->success ) {
                echo 'OOOPS! failed.....' . $result->message;
                exit();
            }

            // $chair_count += ($atnd_pref === 'AC'? 1 : 0);
            // $attendees_count += ($atnd_pref === 'N'? 0 : 1);
        }
        $pirsa_count = $dao->pirsa === 'Y' ? 1 : 0;
        $pirsa_comment = isset($dao->pirsa_comment) ? $dao->pirsa_comment : '';

        // if( $pirsa_count === 1 && trim($pirsa_comment) === '' ) {
        //     $dao->pirsa_selected = ' checked ';
        //     $dao->pirsa_comment = $pirsa_comment;
        //     $dao->error_message = 'Please provide a comment/reason when Pirsa is selected.';
        //     $this->render('family_list', $dao);
        //     return;
        // }

        $login_id = $dao->user_session->id;
        $result = $dbctrl->createTakhmeenRecord($login_id, $hof_id, $pirsa_count, $chair_count, $attendees_count, $pirsa_comment);
        if( !$result->success ) {
                echo 'OOOPS! createTakhmeenRecord failed.....' . $result->message;
                exit();
            }
        $this->do_redirect('print', $dao);
        //echo "All done";
        //echo serialize($array);
    }

    public function getAdd(Dao $dao) {
        $this->render('add_member', $dao);
    }

    public function postAdd(Dao $dao) {
        $is_hof = false;
        $hof_id = $dao->hof_id;
        if( $hof_id < 0 ) {
            $is_hof = true;
            $dao->hof_id = $dao->its_id;
        }
    
        $db = new DBService();
        $result = $db->addNewMember($dao);
        if( $result->state == DB_STATE::UNQ_ERR ) {
            $ssn = new Ssn();
            $ssn->transit_data = 'This ITS ID ('.$dao->its_id.') is already is use. Please check your details.'; 
            $this->render('add_member', $dao);
        } else if( $result->success ) {
            if( $is_hof ) {
                $db->updateHofId($dao->user_session->email, $dao->hof_id);
                
                $loginData = $db->getUserLoginData($dao->hof_id);
                //$loginData->roles = explode(',' , $user_roles);
                //$loginData->state = 'LINKED';
                $db->setUserSession($loginData);

                //$db->setUserSession($dao->user_session->email, $dao->hof_id);
            }
            $this->do_redirect('familyList', $dao);
        } else {
            $ssn = new Ssn();
            $ssn->transit_data = 'Error occurred while adding member. Please try again later.' . $result->message; 
            $this->render('add_member', $dao);
        }    
    }

}