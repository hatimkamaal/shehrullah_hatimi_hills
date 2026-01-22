<?php

class PrintCtrl extends Ctrl {

    public function get(Dao $dao) {
        $hof_id = $dao->signin_hof_id;

        $dbs = new DBService();
        $hofData = $dbs->getITSData($hof_id);

        if( is_null($hofData) ) {
            $this->do_redirect_with_message('login/out' , 'HOF not found. Login again.');
        }

        $dao->hofData = $hofData;

        $dbs = new DBService();
        $dao->attendees_records = $dbs->getFamilyDetailsWithPref($hof_id);
        
        $dao->shehrullah_data = $dbs->getShehrullahFigures();
    }

}