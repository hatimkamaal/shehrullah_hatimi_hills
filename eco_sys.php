<?php

defined('CONTROLLER') || define('CONTROLLER' , 'Ctrl');
defined('CONTROLLER_LOCATION') || define('CONTROLLER_LOCATION' , '_ctrl');
defined('VIEW_LOCATION') || define('VIEW_LOCATION' , '_view');
defined('MODEL_LOCATION') || define('MODEL_LOCATION' , '_model');

defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_NAME') || define('DB_NAME', 'kalimi_2026');
defined('DB_USER') || define('DB_USER', 'user1');
defined('DB_PASS') || define('DB_PASS', 'user1');
defined('LANDING_PAGE') || define('LANDING_PAGE', 'Home');
defined('SECURE_APP') || define('SECURE_APP', false);
defined('OPEN_PAGE_LIST') || define('OPEN_PAGE_LIST', ['Login', 'Register', 'Forgot_password', 'Reset_password']);
defined('AUTH_REDIRECT') || define('AUTH_REDIRECT', 'login');
defined('AUTH_PAGE') || define('AUTH_PAGE', 'login');
defined('TEMPLATE') || define('TEMPLATE', 'template.php');
defined('APP_SESSION_KEY') || define('APP_SESSION_KEY', 'SOMETHING_5456456');

spl_autoload_register(function ($class_name) {
    $possible_paths = [
        //'bean/' . $class_name . '.php',
        MODEL_LOCATION . '/' . $class_name . '.php',
        CONTROLLER_LOCATION . '/' . $class_name . '.php',
        //VIEW_LOCATION'view/' . $class_name . '.php',
    ];

    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            include_once $path;
            return;
        }
    }
});


class Dao {
    private $application_data = [];
    public function __get($name) {
        if( isset($this->application_data[$name]) ) {
            return $this->application_data[$name];
        } 
        return null;
        // return $this->application_data[$name] ?? null;
    }

    public function __set($name, $value) {
        $this->application_data[$name] = $value;
    }

    public function fill(array $data, $prefix = '')
    {
        foreach ($data as $key => $value) {
            $this->application_data[$prefix . $key] = $value;
        }
    }

    public function toArray()
    {
        return $this->application_data;
    }
}

class Ssn {

    function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function __set($key, $value)
    {
        $_SESSION[$key] = serialize($value);
    }

    public function __get($key)
    {
        if (isset($_SESSION[$key])) {
            return unserialize($_SESSION[$key]);
        }
        return null;
    }

    public function del($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }


    public function destroy()
    {
        session_unset();
        session_destroy();
    }
}

class Ctrl {
    public function get(Dao $dao) {}
    public function post(Dao $dao) {}
    public function put(Dao $dao) {}
    public function delete(Dao $dao) {}

    final public function handle(Dao $dao) {
        $req_type = strtolower($_SERVER['REQUEST_METHOD']);
        $sub_arg = ucfirst($dao->arg_1 ?? '');
        $method = $req_type . $sub_arg;
        if( !method_exists( $this, $method ) ) {
            $method = $req_type;
        }
        $this->$method($dao);
    }

    public function render($view, Dao $dao, $template = TEMPLATE)
    {    
        $filePath = VIEW_LOCATION . "/$view.php";
        include_once $template;
        exit();
    }

    public function json(Dao $dao)
    {
        header('Content-Type: application/json');
        echo json_encode($dao);
        exit();
    }

    public function file($filename, $contentType, $dao) {
        // Set headers for CSV download
        header('Content-Type: '.$contentType.'; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the output stream for writing CSV data
        $output = fopen('php://output', 'w');

        // Write data rows to the CSV
        foreach ($dao as $row) {
            fputcsv($output, $row);
        }

        // Close the output stream
        fclose($output);

        // Exit to prevent any further output
        exit();
    }

    public function do_redirect($dao, $page, $relativePath = true)
    {
        if ($relativePath) {
            $baseUri = $dao->home_uri ?? '';
            //$page = substr($page, 0, 1) == '/' ? $page : '/' . $page;
            $page = $baseUri . (substr($page, 0, 1) == '/' ? '' : '/') . $page;
        }
        header("Location: $page");
        exit();
    }

    public function do_redirect_with_message($dao, $page, $message, $relativePath = true)
    {
        $ssn = new Ssn();
        $ssn->transit_data = $message;
        // $app_session = AppSession::getInstance();
        // $app_session->set("transit_data" , $message);
        $this->do_redirect($dao,$page, $relativePath);
    }

    public function is_secured() {
        $ssn = new Ssn();
        // $app_session = AppSession::getInstance();
        $ssn_key = APP_SESSION_KEY;
        $user_data = $ssn->$ssn_key;
        return $user_data != null;
    }
}

class Eco_sys extends Ctrl{

    public function bootstrap()
    {
        $app_data = new Dao();

        //Get the protocol (HTTP or HTTPS)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        //Host name (Ex: localhost , www.example.com)
        $domainName = $_SERVER['HTTP_HOST'];

        //Current working directory (Ex: C:\h_apps\xampp\htdocs\inventory-mgmt\inventory_mgmt_fmb\V2)
        $currentWorkingDirectory = getcwd();
        //Current document root (Ex: C:\h_apps\xampp\htdocs)
        $document_root = $_SERVER['DOCUMENT_ROOT'];

        //Length of document root
        $root_document_length = strlen($document_root);
        //app directory (Ex: /inventory-mgmt/inventory_mgmt_fmb/V2)
        $app_uri = substr($currentWorkingDirectory, $root_document_length);

        //Home URI (Ex: http://localhost/inventory-mgmt/inventory_mgmt_fmb/V2)
        $home_uri = $protocol . $domainName . $app_uri;

        //Set the home_uri in application data
        $app_data->home_uri = $home_uri;

        //Parse the URL (Ex: http://localhost/inventory-mgmt/inventory_mgmt_fmb/V2/products/list?id=5&page=2)
        $urls = parse_url($_SERVER['REQUEST_URI']);
        //Get the path (Ex: /inventory-mgmt/inventory_mgmt_fmb/V2/products/list)
        $path = $urls['path'] ?? '';
        //Get the query string (Ex: id=5&page=2)
        $query = $urls['query'] ?? null;

        //Full page URI (Ex: http://localhost/inventory-mgmt/inventory_mgmt_fmb/V2/products/list)
        $page_uri = $protocol . $domainName . $path;
        $app_data->page_uri = $page_uri;

        parse_str($query, $queryArray);

        $app_data->fill($queryArray);

        //Get the requested directory (Ex: C:\h_apps\xampp\htdocs\inventory-mgmt\inventory_mgmt_fmb\V2\home)
        $requested_dir = $document_root . $path;
        $cwd_length = strlen($currentWorkingDirectory);
        //Get the requested directory relative to the current working directory (Ex: /products/list)
        $request_directory = substr($requested_dir, $cwd_length);

        //Split the request directory into segments
        $directory_segments = explode('/', $request_directory);
        //array_filter : removes the blank array elements
        $directory_segments_array = array_filter($directory_segments, 'strlen');
        //Reindex the array        
        $directory_segments_array = array_values($directory_segments_array);
        //extract($directory_segments_array , EXTR_PREFIX_ALL, "arg");
        $app_data->fill($directory_segments_array, 'arg_');
        $app_data->fill($_POST);
        $app_data->fill($_GET);

        $page_name = $this->get_page_name($app_data->arg_0);
        if (SECURE_APP) {
            $is_secured = $this->is_secured();
            if (!in_array($page_name, OPEN_PAGE_LIST) 
                && !$is_secured) {
                $this->do_redirect($app_data, AUTH_REDIRECT);
            }
        }

        $controller_name = $page_name . CONTROLLER;
        $view_name = $page_name;
        $controller_full_path = CONTROLLER_LOCATION . '/' . $controller_name . '.php';
        if (file_exists($controller_full_path)) {
            include_once $controller_full_path;
            //$method = strtolower($_SERVER['REQUEST_METHOD']);

            if (class_exists($controller_name) && is_a($controller_name, 'Ctrl', true)) {
                $controller = new $controller_name();
                //$controller->$method($app_data);
                $controller->handle($app_data);
                exit();
            }
        }
        
        $this->render($view_name, $app_data);
        exit();
    }
private function get_page_name($arg)
    {
        //first segment of uri
        $page = $arg;
        //if that is not set, use the secure landing page
        if (!isset($page) || strlen($page) == 0) {
            $page = LANDING_PAGE;
        }

        //check if requested page ends with .php
        $endsWith = substr($page, -4) == '.php';
        if ($endsWith) {
            $page = substr($page, 0, strlen($page) - 4);
        }

        // if (strpos($page, '.') !== false) {
        //     $dirs = explode('.' , $page);
        //     $class_name = array_pop($dirs);
        //     return implode('/' , $dirs) . '/' . ucfirst($class_name);
        //     //$page = str_replace('.', '/', $page);
        // } else {
        //     return ucfirst($page);
        // }

        return ucfirst($page);
    }
}
