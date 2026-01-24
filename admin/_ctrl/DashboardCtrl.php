<?php

class DashboardCtrl extends Ctrl {
    public function get(Dao $dao) {
        echo 'JJjj';
        $this->render('dashboard', $dao);
    }

    public function post(Dao $dao) {

    }
}