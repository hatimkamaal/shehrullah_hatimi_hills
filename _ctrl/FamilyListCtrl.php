<?php 

class FamilyListCtrl extends Ctrl {

    public function get(Dao $dao) {
        $hof_id = $dao->user_session->hof_id;
        
        $dbctrl = new DBService();
        $dao->records = $dbctrl->getFamilyDetailsWithPref($hof_id);
        $this->render('family_list' , $dao);        
    }

    public function post(Dao $dao) {

        $attendsList = $dao->family_its_list;
        $chairList = $dao->chair_its_list;

        $hof_id = $dao->user_session->hof_id;        
        $dbctrl = new DBService();
        $result = $dbctrl->getFamilyDetailsForHOF($hof_id);
        $records = $result->data;
        foreach($records as $record) {
            $name = "atnd_pref_{$record->its_id}";
            $atnd_pref = $dao->$name;
            $its_id = $record->its_id;
            $attendance_type = in_array($its_id, $attendsList) ? 'Y' : 'N';
            $chair_preference = in_array($its_id, $chairList) ? 'Y' : 'N';

            $params = [$its_id, $hof_id, $atnd_pref, 
            $attendance_type, $chair_preference, $atnd_pref, 
            $attendance_type, $chair_preference];
            $dbctrl->addAttendeesRecord($params);
        }
        $pirsa = $dao->pirsa ?? 'N';
        $login_id = $dao->user_session->id;
        $dbctrl->createTakhmeenRecord($login_id, $hof_id, $pirsa);

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
                $db->setUserSession($dao->user_session->email, $dao->hof_id);
            }
            $this->do_redirect('familyList', $dao);
        }        
    }

}