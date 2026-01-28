<?php

function content_display() {
    $q = 'SELECT h.flat_number, h.hof_id, i.full_name 
    FROM hhm_hofs h
    JOIN hhm_its_data i ON i.its_id = h.hof_id
    ';

    $result = run_statement($q);
    $records = $result->data;

    util_show_data_table($records, [
        '__show_row_sequence' => 'Sr#',        
        'hof_id' => 'Head of FLAT',
        'flat_number' => 'Flat Number',
        'full_name' => 'Name'       
    ]);

}