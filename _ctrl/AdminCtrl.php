<?php

// enum USER_ROLE {
//     case super_admin;
//     case data_entry;
//     case takhmeen;
//     case reception;

//     public function __toString(): string
//     {
//         return $this->value;
//     }
// }

class NewCtrl extends Ctrl {

    // public function is_user_role(USER_ROLE $role)
    // {
    //     //return true;
    //     $db = new DBService();
    //     $login_data =$db->get_login_data();
    //     $roles = $login_data->roles;

    //     // $userData = getSessionData(THE_SESSION_ID);
    //     return in_array($role, $roles);
    // }

    public function is_user_a(...$expected)
    {
        $db = new DBService();
        $login_data =$db->get_login_data();
        $roles = $login_data->roles;

        // if( is_array($expected) ) {
        //     echo 'HETRE IS THE ID';
        // }

        // $userData = getSessionData(THE_SESSION_ID);
        // return in_array($role, $roles);

        // return true;
        // $userData = getSessionData(THE_SESSION_ID);
        // $roles = $userData->roles;

        $result = array_intersect($expected, $roles);
        return !empty($result) ? true : false;
    }

    // public function is_super_admin()
    // {
    //     return $this->is_user_role('super_admin');
    // }

    // public function is_data_entry()
    // {
    //     return $this->is_user_role('data_entry');
    // }
}

/**
 * Another dimention.
 * 
 */
class AdminCtrl extends Ctrl
{

    protected function handle(Dao $dao)
    {
        // $req_type = strtolower($_SERVER['REQUEST_METHOD']);
        $sub_arg = ucfirst($dao->arg_1 ?? LANDING_PAGE);

        $controller_name = $sub_arg . CONTROLLER;
        $view_name = $sub_arg;
        $controller_full_path = CONTROLLER_LOCATION . '/admin/' . $controller_name . '.php';
        if (file_exists($controller_full_path)) {
            include_once $controller_full_path;
            //$method = strtolower($_SERVER['REQUEST_METHOD']);

            if (class_exists($controller_name) && is_a($controller_name, 'Ctrl', true)) {
                $controller = new $controller_name();
                $controller->handle($dao);
                exit();
            }
        }


        // $method = $req_type . $sub_arg;
        // if (!method_exists($this, $method)) {
        //     $method = $req_type;
        // }
        // $this->$method($dao);
    }


    public function file_user_roles(Dao $dao) {
        $roles = $dao->user_session->roles;
    }

    // public function is_user_role($role)
    // {
    //     return true;
    //     // $userData = getSessionData(THE_SESSION_ID);
    //     // $roles = $userData->roles;
    //     // return in_array($role, $roles);
    // }

    // public function is_user_a(...$expected)
    // {
    //     return true;
    //     // $userData = getSessionData(THE_SESSION_ID);
    //     // $roles = $userData->roles;

    //     // $result = array_intersect($expected, $roles);
    //     // return !empty($result) ? true : false;
    // }

    // public function is_super_admin()
    // {
    //     return $this->is_user_role('super_admin');
    // }

    // public function is_data_entry()
    // {
    //     return $this->is_user_role('data_entry');
    // }
}