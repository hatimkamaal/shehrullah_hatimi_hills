<?php

class PrintCtrl extends Ctrl {

    public function get(Dao $dao) {
        $hof_id = $dao->user_session->hof_id;
        $email = $dao->user_session->email;

        $dbs = new DBService();
        $hofData = $dbs->getITSData($hof_id);
        if( is_null($hofData) ) {
            $this->do_redirect_with_message('login/out' , 'HOF not found. Login again.', $dao);
        }
        
        $dao->hofData = $hofData;

        $takhmeen_data = $dbs->getTakhmeenRecordFor($hof_id, HIJRI_YEAR);
        $dao->chair_count = $takhmeen_data->chair_count;
        $dao->pirsa_count = $takhmeen_data->pirsa_count;

        $dbs = new DBService();
        $dao->attendees_records = $dbs->getFamilyDetailsWithPref($hof_id);
        $family_niyaz = 0;
        $total_hub = 0;
        foreach ($dao->attendees_records as $attendees) {
            $atnd_pref = $attendees->attendance_type;
            if( $atnd_pref === 'N' ) {
                continue;
            }

            if($attendees->age > 5 && $attendees->age < 11 ) {
                $family_niyaz += 3000;
            } else if( $attendees->age > 10 ) {
                $family_niyaz += 6000;
            }
        }
        $dao->shehrullah_data = $dbs->getShehrullahFigures();

        $total_hub = $family_niyaz;
        $total_hub += $dao->chair_count * $dao->shehrullah_data->chair;
        $total_hub += $dao->pirsa_count * $dao->shehrullah_data->pirsu;
        
        $dao->family_niyaz = $family_niyaz;
        $dao->total_hub = $total_hub;


        $prevYearFigure = 0;
        $prevYearRecord = $dbs->getTakhmeenRecordFor($hof_id, HIJRI_YEAR - 1);
        if( !is_null( $prevYearRecord ) ) {
            $prevYearFigure = $prevYearRecord->takhmeen;
        }


        $dao->prevYearFigure =  max($prevYearFigure , $total_hub);
        //$dao->prevYearFigure = $prevYearFigure;

        $dao->print = true;

        $this->render('print', $dao);
    }

}