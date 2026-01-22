<?php

class DBService extends Ctrl
{

    public function getFamilyDetailsForHOF($hof_id)
    {
        $query = 'SELECT * FROM its_data where hof_id=?;';
        return $this->execute_query($query, $hof_id);
    }

    public function getFamilyDetailsWithPref($hof_id)
    {
        $query = 'SELECT i.*, a.atnd_pref, a.attendance_type,a.chair_preference
        FROM its_data i LEFT JOIN hh_attendees a ON a.its_id = i.its_id
        where i.hof_id=?;';
        return $this->execute_query($query, $hof_id);
    }

    public function addNewMember(Dao $dao)
    {
        $query = 'INSERT INTO its_data(its_id,hof_id,full_name,age,gender,misaq) VALUES (?,?,?,?,?,?);';
        $params = [$dao->its_id, $dao->hof_id, $dao->full_name, $dao->age, $dao->gender, $dao->misaq];
        return $this->execute_query($query, $params);
    }

    public function addAttendeesRecord($params)
    {
        $query = 'INSERT INTO hh_attendees(its_id,hof_id,atnd_pref,attendance_type,chair_preference) values (?,?,?,?,?)
        ON DUPLICATE KEY UPDATE atnd_pref=?,attendance_type=?,chair_preference=?';
        //$params = [$dao->its_id, $dao->hof_id, $dao->atnd_pref, $dao->attendance_type, $dao->chair_preference, $dao->atnd_pref, $dao->attendance_type, $dao->chair_preference];
        return $this->execute_query($query, $params);
    }

    public function checkEmail($email)
    {
        $query = 'SELECT * FROM hh_login_data WHERE email=?;';
        return $this->execute_query($query, $email);
    }

    public function addEmail($email)
    {
        $query = 'INSERT INTO hh_login_data (email) VALUES (?);';
        return $this->execute_query($query, $email);
    }

    public function updateHofId($email, $hofid)
    {
        $query = 'UPDATE hh_login_data SET hof_id =? WHERE email=?;';
        return $this->execute_query($query, $hofid, $email);
    }
}