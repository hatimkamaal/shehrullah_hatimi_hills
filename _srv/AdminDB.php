<?php

class AdminDB extends Ctrl {

    function get_all_user_data() {
        $query = 'SELECT l.*,a.roles FROM hh_login_data l 
        JOIN hh_shehrullah_admin_roles a ON a.login_id = l.id;';
        $result = $this->execute_query($query);
        return $result->data;
    }

}