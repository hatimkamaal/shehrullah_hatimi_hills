<?php 

class FamilyListCtrl extends Ctrl {

    public function get(Dao $dao) {
        $ssn = new Ssn();
        $hof_id = $ssn->hof_id;

        $dbctrl = new DBService();
        $result = $dbctrl->getFamilyDetailsForHOF($hof_id);
        $dao->records = $result->data;
        $this->render('family_list' , $dao);        
    }

    public function post(Dao $dao) {
        $array = $dao->family_its_list;
        echo serialize($array);
    }

}