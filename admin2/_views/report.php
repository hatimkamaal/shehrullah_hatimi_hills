<?php

function content_display() {
    $arg = getAppData('arg1');
    if( function_exists($arg) ) {
        $arg();
    } else {
        echo 'No report found..';
    }
}

/**
 * Summary of takhmeen_report
 * 
 * @return void
 */
function takhmeen_report() {
    if( !(is_user_role(SUPER_ADMIN) || is_user_role(FINANCE)) ) {
        do_redirect_with_message('/home', 'You are not authorized to view this page');
    }

    $query = 'SELECT h.flat_number, i.full_name, t.hof_id, 
    t.whatsapp, t.takhmeen, t.paid_amount,CAST(t.takhmeen AS SIGNED) - CAST(t.paid_amount AS SIGNED) as pending
    FROM hhm_takhmeen t
    JOIN hhm_its_data i ON i.its_id = t.hof_id
    JOIN hhm_hofs h ON h.hof_id = t.hof_id
    WHERE t.year = 1446
    ORDER BY pending desc';

    $result = run_statement($query);
    $records = $result->data;
    util_show_data_table($records, [
        '__show_row_sequence' => 'Sr#',        
        'hof_id' => 'HOF',
        'flat_number' => 'Flat Number',
        'full_name' => 'Name',
        'takhmeen' => 'Takhmeen',
        'paid_amount' => 'Paid Amount',
        'pending' => 'Pending',
        'whatsapp' => 'Whatsapp'
    ]);

}


function other_hub() {
    if( !(is_user_role(SUPER_ADMIN) || is_user_role(FINANCE)) ) {
        do_redirect_with_message('/home', 'You are not authorized to view this page');
    }

    $query = 'SELECT h.flat_number, i.full_name, t.hof_id, 
    t.whatsapp, t.zabihat_count, t.pirsa_count, t.chair_count, t.sehori_count
    FROM hhm_takhmeen t
    JOIN hhm_its_data i ON i.its_id = t.hof_id
    JOIN hhm_hofs h ON h.hof_id = t.hof_id
    WHERE t.year = 1446 and t.zabihat_count + t.pirsa_count + t.chair_count + t.sehori_count > 0
    ';

    $result = run_statement($query);
    $records = $result->data;
    util_show_data_table($records, [
        '__show_row_sequence' => 'Sr#',        
        'hof_id' => 'HOF',
        'flat_number' => 'Flat Number',
        'full_name' => 'Name',
        'zabihat_count' => 'Zabihat',
        'pirsa_count' => 'Pirsa',
        'chair_count' => 'Chair',
        'sehori_count' => 'Sehori'
    ]);
}