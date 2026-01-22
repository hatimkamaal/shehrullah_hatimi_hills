<?php

class DBService extends Ctrl{

    public function getFamilyDetailsForHOF($hof_id) {
        $query = 'SELECT * FROM its_data where hof_id=?;';
        return $this->execute_query($query , $hof_id);
    }

    public function checkEmail($email) {
        $query = 'SELECT * FROM hh_login_data WHERE email=?;';
        return $this->execute_query($query , $email);
    } 

    public function addEmail($email) {
        $query = 'INSERT INTO hh_login_data (email) VALUES (?);';
        return $this->execute_query($query , $email);
    }

    public function updateHofId($email, $hofid) {
        $query = 'UPDATE hh_login_data SET hof_id =? WHERE email=?;';
        return $this->execute_query($query ,$hofid, $email);
    }
}