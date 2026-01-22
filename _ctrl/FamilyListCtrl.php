<?php 

class FamilyListCtrl extends Ctrl {

    public function get(Dao $dao) {
        $hof_id = $dao->signin_hof_id;
        
        $dbctrl = new DBService();
        $result = $dbctrl->getFamilyDetailsWithPref($hof_id);
        $dao->records = $result->data;
        $this->render('family_list' , $dao);        
    }

    public function post(Dao $dao) {

        $attendsList = $dao->family_its_list;
        $chairList = $dao->chair_its_list;

        $hof_id = $dao->signin_hof_id;        
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

        echo "All done";
        //echo serialize($array);
    }

    public function getAdd(Dao $dao) {
        $this->render('add_member', $dao);
    }

    public function postAdd(Dao $dao) {
        $db = new DBService();
        $result = $db->addNewMember($dao);
        if( $result->state == DB_STATE::UNQ_ERR ) {
            $ssn = new Ssn();
            $ssn->transit_data = 'ITS already in use.';            
            $this->render('add_member', $dao);
        } else if( $result->success ) {
            $this->do_redirect('familyList', $dao);
        }        
    }

}