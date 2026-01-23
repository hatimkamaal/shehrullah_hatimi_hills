<?php

class DBService extends Ctrl
{

    public function getFamilyDetailsForHOF($hof_id)
    {
        $query = 'SELECT * FROM hh_its_data where hof_id=?;';
        return $this->execute_query($query, $hof_id);
    }

    public function getITSData($its_id)
    {
        $query = 'SELECT * FROM hh_its_data where its_id=?;';
        $result = $this->execute_query($query, $its_id);
        if( $result->success && $result->count > 0 ) {
            return $result->data[0];
        }
        
        return null;
    }
    
    public function getFamilyDetailsWithPref($hof_id)
    {
        $query = 'SELECT i.*, a.atnd_pref, a.attendance_type,a.chair_preference
        FROM hh_its_data i LEFT JOIN hh_attendees a ON a.its_id = i.its_id
        where i.hof_id=?;';
        $result =  $this->execute_query($query, $hof_id);

        if( $result->success && $result->count > 0 ) {
            return $result->data;
        }

        return [];
    }

    public function addNewMember(Dao $dao)
    {
        $query = 'INSERT INTO hh_its_data(its_id,hof_id,full_name,age,gender,misaq, mohallah) VALUES (?,?,?,?,?,?,?);';
        $params = [$dao->its_id, $dao->hof_id, $dao->full_name, $dao->age, $dao->gender, $dao->misaq, 'Other'];
        return $this->execute_query($query, $params);
    }

    public function addAttendeesRecord($params)
    {
        $query = 'INSERT INTO hh_attendees(its_id,hof_id,atnd_pref,attendance_type,chair_preference) values (?,?,?,?,?)
        ON DUPLICATE KEY UPDATE atnd_pref=?,attendance_type=?,chair_preference=?';
        //$params = [$dao->its_id, $dao->hof_id, $dao->atnd_pref, $dao->attendance_type, $dao->chair_preference, $dao->atnd_pref, $dao->attendance_type, $dao->chair_preference];
        return $this->execute_query($query, $params);
    }

    // public function getUserLoginData($email)
    // {
    //     $query = 'SELECT * FROM hh_login_data WHERE email=?;';
    //     $result = $this->execute_query($query, $email);

    //     if( $result->success && $result->count > 0 ) {
    //         return $result->data[0];
    //     }
    //     return null;
    // }

    public function getUserLoginData($email)
    {
        $query = 'SELECT * FROM hh_login_data WHERE email=?;';
        $result = $this->execute_query($query, $email);

        if( $result->success && $result->count > 0 ) {
            return $result->data[0];
        }
        return null;
    }

    public function lookLoginDataForHOF($hof_id)
    {
        $query = 'SELECT * FROM hh_login_data WHERE hof_id=?;';
        $result = $this->execute_query($query, $hof_id);

        if( $result->success && $result->count > 0 ) {
            return $result->data[0];
        }
        return null;
    }

    public function createLoginDataFor($params) {
        $query = 'INSERT INTO hh_login_data(email,hof_id,name,contact,whatsapp,wingflat,sector) values (?,?,?,?,?,?,?);';
        $result = $this->execute_query($query, $params);
        return $result->success;
    }


    public function addEmail($email)
    {
        $query = 'INSERT INTO hh_login_data (email) VALUES (?);';
        return $this->execute_query($query, $email);
    }

    public function updateHofId($email, $hofid)
    {
        $query = 'UPDATE hh_login_data SET hof_id =? WHERE email=?;';
        $result =  $this->execute_query($query, $hofid, $email);
        return $result->success;
    }

    public function setUserSession($login_data) {
        $ssn = new Ssn();
        $key = APP_SESSION_KEY;
        $ssn->$key = $login_data;//['signin_email' => $email, 'signin_hof_id'=>$hof_id, 'signin' => true];
    }

    public function getShehrullahFigures() {        
        $query = 'SELECT * FROM hh_shehrullah_config WHERE year=1447;';
        $result = $this->execute_query($query);

        if( $result->success && $result->count > 0 ) {
            return $result->data[0];
        }
        return null;
    }

    
    public function createTakhmeenRecord($login_id, $hof_id, $pirsa) {
        $year = 1447;
        $query = 'INSERT INTO hh_shehrullah_takhmeen (login_id, hof_id, year, pirsa_count) 
        VALUES (?,?,?,?) ON DUPLICATE KEU UPDATE pirsa_count = ?;';
        $params = [$login_id, $hof_id, $year, $pirsa, $pirsa];
        $result = $this->execute_query($query, $params);
        return $result->success;
    } 

}