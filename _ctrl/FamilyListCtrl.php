<?php 

class FamilyListCtrl extends Ctrl {

    public function get(Dao $dao) {
        $hof_id = $dao->user_session->hof_id;
        
        $dbctrl = new DBService();
        $dao->records = $dbctrl->getFamilyDetailsWithPref($hof_id);

        $takhRecord = $dbctrl->getTakhmeenRecordFor($hof_id);
        $dao->takhRecord = $takhRecord;
        $dao->pirsa_selected = ( $dao->takhRecord && $dao->takhRecord->pirsa_count > 0 ) ? ' checked ' : '';

        $this->render('family_list' , $dao);        
    }

    public function post(Dao $dao) {

        $attendsList = $dao->family_its_list;
        $chairList = $dao->chair_its_list;

        $hof_id = $dao->user_session->hof_id;        
        $dbctrl = new DBService();
        $result = $dbctrl->getFamilyDetailsForHOF($hof_id);
        $records = $result->data;
        $chair_count = 0;     
        $attendees_count = 0;   
        foreach($records as $record) {
            $its_id = $record->its_id;
            $name = "atnd_pref_$its_id";
            $atnd_pref = $dao->$name;
            $attendance_type = isset($attendsList) && in_array($its_id, $attendsList) ? 'Y' : 'N';
            $chair_preference = isset($chairList) && in_array($its_id, $chairList) ? 'Y' : 'N';

            $params = [$its_id, $hof_id, $atnd_pref, 
            $attendance_type, $chair_preference, $atnd_pref, 
            $attendance_type, $chair_preference];

            $dbctrl->addAttendeesRecord($params);

            $chair_count += ($atnd_pref === 'AC'? 1 : 0);
            $attendees_count += ($atnd_pref === 'N'? 0 : 1);
        }
        $pirsa_count = ($dao->pirsa ?? 'N') === 'Y' ? 1 : 0;
        $login_id = $dao->user_session->id;
        $dbctrl->createTakhmeenRecord($login_id, $hof_id, $pirsa_count, $chair_count, $attendees_count);

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