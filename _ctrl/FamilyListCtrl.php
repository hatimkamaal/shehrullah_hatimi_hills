<?php 

class FamilyListCtrl extends Ctrl {

    public function get(Dao $dao) {
        $hof_id = $dao->signin_hof_id;
        
        $dbctrl = new DBService();
        $result = $dbctrl->getFamilyDetailsForHOF($hof_id);
        $dao->records = $result->data;
        $this->render('family_list' , $dao);        
    }

    public function post(Dao $dao) {
        $array = $dao->family_its_list;
        foreach($array as $its) {

        }

        echo serialize($array);
    }

    public function getAdd(Dao $dao) {
        $this->render('add_member', $dao);
    }

    public function postAdd(Dao $dao) {
        $db = new DBService();
        $result = $db->addNewMember($dao);
        if( $result->state == DB_STATE::UNQ_ERR ) {            
            $this->render('add_member', $dao);
        } else if( $result->success ) {
            $this->do_redirect('familyList', $dao);
        }        
    }

}