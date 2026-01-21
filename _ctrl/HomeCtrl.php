<?php
class HomeCtrl extends Ctrl{
    public function get(Dao $dao)
    {
        $dao->title = 'Hello';
        $this->render('home', $dao);
    }

    public function post(Dao $dao)
    {
        $dao->debug = "POST method called, its_id: " . ($dao->its_id ?? 'null');
        if (is_numeric($dao->its_id)) {
            $dao->result = "ITS ID submitted: " . $dao->its_id;
        } else {
            $dao->result = "Invalid ITS ID. Please enter digits only.";
        }
        $this->render('home', $dao);
    }

    public function getAdminer(Dao $dao) {
        $this->render('',$dao,'_view/adminer-5.4.1.php');
    }

    public function postAdminer(Dao $dao) {
        $this->render('',$dao,'_view/adminer-5.4.1.php');
    }
}