<?php

function content_display() {

    $q = 'SELECT t.zabihat_count, t.hof_id, i.full_name, t.whatsapp
    FROM hhm_takhmeen t
    JOIN hhm_its_data i ON i.its_id = t.hof_id
    WHERE t.year = 1446
    ';

    $result = run_statement($q);
    $records = $result->data;

    util_show_data_table($records, [
        '__show_row_sequence' => 'Sr#',        
        'zabihat_count' => 'Zabihat Count',
        'hof_id' => 'Head of FLAT',
        'whatsapp' => 'Whjatsapp',
        'full_name' => 'Name'       
    ]);

}