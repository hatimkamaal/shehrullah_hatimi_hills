<?php

class DBService extends Ctrl{

    public function getFamilyDetailsForHOF($hof_id) {
        $query = 'SELECT * FROM its_data where hof_id=?;';
        return $this->execute_query($query , $hof_id);
    }

}