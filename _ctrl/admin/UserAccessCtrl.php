<?php

class UserAccessCtrl extends NewCtrl {
    
    public function get(Dao $dao) {
        if( !$this->is_user_a(SUPER_ADMIN) ) {
            $this->show('Unauthorized access', $dao);
        }

        $in_email = $dao->email;
        $in_hof_id = $dao->hof;

        $db = new DBService();
        $login_data_for_email = $db->getUserLoginData($in_email);
        if( is_null( $login_data_for_email ) ) {
            echo "This email ($in_email) is not found in login data.<br/>";
        } else {
            $hof_id = $login_data_for_email->hof_id;
            echo "This email ($in_email) is linked to HOF ($hof_id).<br/>";
        }

        $login_data_for_hof = $db->lookLoginDataForHOF($in_hof_id);
        if( is_null( $login_data_for_hof ) ) {
            echo "This HOF ($in_hof_id) is not found in login data.<br/>";
        } else {
            $email = $login_data_for_hof->email;
            echo "This HOF ($hof_id) is linked to email ($email).<br/>";
        }

        $its_data_for = $db->getITSData($in_hof_id);
        if( is_null( $login_data_for_hof ) ) {
            echo "This ITS ($in_hof_id) is not found in ITS records.<br/>";
        } else {
            $hof_id = $login_data_for_hof->hof_id;
            echo "This ITS ($in_hof_id) has hof ($hof_id).<br/>";
        }
    }

}