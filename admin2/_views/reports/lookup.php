<?php

do_for_post('__handle_post');
 function __handle_post() {
    $action = $_POST['action'] ?? '';
    if('delete_member' === $action) {

    $hof_id = $_POST['hof'];
    $flat_number = $_POST['flat'];
    setSessionData(REQUEST_SESSION, ['hof_id'=>$hof_id, 'flat_number'=>$flat_number]);
    do_redirect('./../family');
    }
 }

function content_display() {
    $input = $_POST['input'];
    $q = '
    SELECT hof.hof_id as hof, hof.flat_number as flat, its.its_id, its.full_name, its.hof_id, 
    atten.attends
FROM hhm_hofs hof
LEFT JOIN hhm_its_data its ON its.its_id = hof.hof_id
LEFT JOIN hhm_attendees atten ON atten.its_id = its.its_id
WHERE hof.hof_id=? or hof.flat_number=? 
or hof.hof_id = (select hof_id from hhm_its_data where its_id = ?)
UNION
SELECT hof.hof_id, hof.flat_number, its.its_id, its.full_name, its.hof_id, atten.attends
FROM hhm_its_data its
LEFT JOIN hhm_hofs hof ON its.its_id = hof.hof_id
LEFT JOIN hhm_attendees atten ON atten.its_id = its.its_id
WHERE its.hof_id=? OR its.hof_id =(select hof_id from hhm_its_data where its_id = ?)
    ';
    $result = run_statement($q, $input,$input,$input,$input,$input);
    $records = $result->data;
    echo "<h2>Search for input : $input</h2>
    ";
    
    util_show_data_table($records, [
        '__show_row_sequence' => 'Sr#',
        '__link' => 'Fill Form',
        'hof' => 'Head of FLAT',
        'flat' => 'Flat Number',
        'full_name' => 'Name',
        'its_id' => 'ITS ID',
        'hof_id' => 'HOF ID',
        'attends' => 'Attends',
    ]);
   

}

function __link($row, $index) {
    if( !(is_null($row->hof) || is_null($row->flat)) ) {
        return "
        <form method='post' action='' class='forms-sample'>
            <input type='hidden' name='hof' value='$row->hof'>
            <input type='hidden' name='flat' value='$row->flat'>            
            <input type='hidden' name='action' value='delete_member'>
            <button class='btn btn-outline-primary' type='submit' id='button-addon2'>Fill Form</button>
        </form>        
        ";
    }
    return '';
}