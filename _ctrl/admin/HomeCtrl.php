<?php

class HomeCtrl extends NewCtrl {

    public function get(Dao $dao) {
        //echo 'What is';
        try {
           $this->render('admin/home', $dao);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        
    }

    public function post(Dao $dao) {
        echo 'POST Admin home';
    }

}