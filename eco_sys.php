<?php


spl_autoload_register(function ($class_name) {
    $possible_paths = [
        //'bean/' . $class_name . '.php',
        UTIL_LOCATION . '/' . $class_name . '.php',
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




class Dao
{    

    private $application_data = [];
    
    public static function construct( Array $data ) {
        $instance = new self();
        $instance->fill( $data );
        return $instance;
    }    
    
    public function __get($name)
    {
        if (isset($this->application_data[$name])) {
            return $this->application_data[$name];
        }
        return null;
        // return $this->application_data[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->application_data[$name] = $value;
    }

    public function __isset($name)
    {
        // returns true if the key exists and its value is not NULL
        return isset($this->application_data[$name]);
    }

    public function fill($data = [], $prefix = '')
    {
        if( ! isset($data)) {
            return;
        }

        foreach ($data as $key => $value) {
            $this->application_data[$prefix . $key] = $value;
        }
    }

    public function toArray()
    {
        return $this->application_data;
    }
}

class Ssn
{
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

    public function __isset($key)
    {
        // returns true if the key exists and its value is not NULL
        return isset($_SESSION[$key]);
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

enum DB_STATE {
    case NONE;
    case NO_CONN;
    case NO_DATA;
    case DATA;
    case PDO_ERR;
    case UNQ_ERR;
    case EXP;

}
class DBM
{
    private function get_database_connection()
    {
        $conn = null;

        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected successfully";
        } catch (PDOException $e) {
            echo "DB Connection failed: " . $e->getMessage();
            exit();
        }
        return $conn;
    }

    private function bind_query_values($statement, $value, $counter = 1)
    {
        if (is_array($value)) {
            foreach ($value as $val) {
                $this->bind_query_values($statement, $val, $counter++);
            }
        } else {
            $statement->bindValue($counter, $value, PDO::PARAM_STR);
        }
    }

    function execute_query($query, ...$args)
    {
        $dao = new Dao();
        $dao->success = false;
        $dao->message = 'Invalid request';
        $dao->count = 0;
        $dao->data = array();
        $dao->state = DB_STATE::NONE;
        $conn = $this->get_database_connection();

        if (!isset($conn)) {
            $dao->message = 'No connection found.';
            $dao->state = DB_STATE::NO_CONN;
            return $dao;
        }

        $stmt = null;
        // Execute and Collect the result
        try {
            // $conn = Flight::db(false);
            // $conn = Flight::getDBConn();
            // Generate the statement for the given query.
            $stmt = $conn->prepare($query);

            // $counter = 1;
            // Flight::bindVal($stmt, $counter, $args);
            $this->bind_query_values($stmt, $args);

            $stmt->execute();
            $numRows = $stmt->rowCount();
            $colCount = $stmt->columnCount();

            $dao->insertedID = $conn->lastInsertId() ?? -1;
            $dao->success = true;
            $dao->message = 'Success';
            $dao->count = $numRows;
            $dao->data = array();

            // This is select query..
            if ($colCount > 0 && $numRows > 0) {
                $allRowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $dao->data = json_decode(json_encode($allRowData));
                $dao->state = DB_STATE::DATA;
            } else {
                $dao->state = DB_STATE::NO_DATA;
            }

            $dao->state = 0;//Success
        } catch (PDOException $e) {
            $dao->message = $e->getMessage();
            $dao->success = false;
            $dao->count = 0;
            $dao->state = DB_STATE::PDO_ERR;
            if ($e->errorInfo[1] == 1062) {
                //The INSERT query failed due to a key constraint violation.
                $dao->message = 'Same value is used before.';
                $dao->state = DB_STATE::UNQ_ERR;
            }
        } catch (Exception $e2) {
            $dao->message = $e2->getMessage();
            $dao->success = false;
            $dao->count = 0;
            $dao->state = DB_STATE::EXP;
        } finally {
            $stmt = null;
            $conn = null;
        }

        return $dao;
    }
}

class Ctrl extends DBM
{
    public function get(Dao $dao)
    {
    }
    public function post(Dao $dao)
    {
    }
    public function put(Dao $dao)
    {
    }
    public function delete(Dao $dao)
    {
    }

    final public function handle(Dao $dao)
    {
        $req_type = strtolower($_SERVER['REQUEST_METHOD']);
        $sub_arg = ucfirst($dao->arg_1 ?? '');
        $method = $req_type . $sub_arg;
        if (!method_exists($this, $method)) {
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

    public function file($filename, $contentType, $dao)
    {
        // Set headers for CSV download
        header('Content-Type: ' . $contentType . '; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
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

    public function do_redirect($page, $dao, $relativePath = true)
    {
        if ($relativePath) {
            $baseUri = $dao->home_uri ?? '';
            //$page = substr($page, 0, 1) == '/' ? $page : '/' . $page;
            $page = $baseUri . (substr($page, 0, 1) == '/' ? '' : '/') . $page;
        }
        header("Location: $page");
        exit();
    }

    public function do_redirect_with_message($page, $message, $dao, $relativePath = true)
    {
        $ssn = new Ssn();
        $ssn->transit_data = $message;
        // $app_session = AppSession::getInstance();
        // $app_session->set("transit_data" , $message);
        $this->do_redirect( $page, $dao,$relativePath);
    }

    public function setTransitMessage($message) {
        $ssn = new Ssn();
        $ssn->transit_data = $message;
    }

    public function is_secured()
    {
        $user_data = $this->get_login_data();
        return $user_data != null;
    }

    public function get_login_data()
    {
        $ssn = new Ssn();
        $ssn_key = APP_SESSION_KEY;
        return $ssn->$ssn_key;
    }
}

class Eco_sys extends Ctrl
{

    public function bootstrap()
    {
        $this->load_config();

        $dao = new Dao();

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
        $dao->home_uri = $home_uri;

        //Parse the URL (Ex: http://localhost/inventory-mgmt/inventory_mgmt_fmb/V2/products/list?id=5&page=2)
        $urls = parse_url($_SERVER['REQUEST_URI']);
        //Get the path (Ex: /inventory-mgmt/inventory_mgmt_fmb/V2/products/list)
        $path = $urls['path'] ?? '';
        //Get the query string (Ex: id=5&page=2)
        $query = $urls['query'] ?? null;

        //Full page URI (Ex: http://localhost/inventory-mgmt/inventory_mgmt_fmb/V2/products/list)
        $page_uri = $protocol . $domainName . $path;
        $dao->page_uri = $page_uri;

        if (isset($query)) {
            parse_str($query ?? '', $queryArray);
            $dao->fill($queryArray);
        }

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
        $dao->fill($directory_segments_array, 'arg_');
        $dao->fill($_POST);
        $dao->fill($_GET);

        $page_name = $this->get_page_name($dao->arg_0);
        if (SECURE_APP) {
            $is_secured = $this->is_secured();
            $open_pages = explode(',',OPEN_PAGE_LIST);
            if (
                !in_array($page_name, $open_pages)
                && !$is_secured
            ) {
                $this->do_redirect(AUTH_REDIRECT, $dao);
            }

            $user = $this->get_login_data();
            $dao->user_session = $user;
        }

        $controller_name = $page_name . CONTROLLER;
        $view_name = $page_name;
        $controller_full_path = CONTROLLER_LOCATION . '/' . $controller_name . '.php';
        if (file_exists($controller_full_path)) {
            include_once $controller_full_path;
            //$method = strtolower($_SERVER['REQUEST_METHOD']);

            if (class_exists($controller_name) && is_a($controller_name, 'Ctrl', true)) {
                $controller = new $controller_name();
                $controller->handle($dao);
                exit();
            }
        }

        $this->render($view_name, $dao);
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

    public function load_config() {
        // Specify the path to your .env file
        $env_path = __DIR__ . '/.env';

        // Check if the file exists and is readable
        if (file_exists($env_path)) {
            // Parse the .env file contents into an associative array
            $env_vars = parse_ini_file($env_path);

            // Check if parsing was successful
            if ($env_vars !== false) {
                // Optional: Set variables in the actual environment using putenv()
                // and also in the $_ENV superglobal
                foreach ($env_vars as $key => $value) {
                    defined($key) || define($key, $value);
                }
            }
        } else {
            die("Error: .env file not found at $env_path");
        }        
    }
    
}
