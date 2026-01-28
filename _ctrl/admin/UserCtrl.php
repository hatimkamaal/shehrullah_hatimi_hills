<?php

class UserCtrl extends NewCtrl
{
    public function get(Dao $dao)
    {

        $url = $dao->home_uri;
        $adb = new AdminDB();
        $all_user_data = $adb->get_all_user_data();
        
    }
}