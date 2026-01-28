<?php
if ( !(is_user_role(SUPER_ADMIN) || is_user_role(RECEPTION) )) {
    do_redirect_with_message('/home', 'Redirected as tried to access unauthorized area.');
}

if_not_post_redirect('/home');

$hof_id = $_POST['hof_id'];
setAppData('hof_id', $hof_id);

$query = "SELECT flat_number FROM hhm_hofs WHERE hof_id=?;";
$result = run_statement($query, $hof_id);
if( $result->count == 0 ) {
    do_redirect_with_message('/home', 'HOF ID not found');
} 
$flat_number = $result->data[0]->flat_number;


$attendees_data = get_attendees_data($hof_id);
if (is_null($attendees_data)) {
    do_redirect_with_message('/home', 'Ops! either you have not filled');
}




// setAppData('arg1', do_encrypt($hof_id));
setAppData('print', true);
setSessionData(REQUEST_SESSION, ['hof_id'=>$hof_id, 'flat_number'=>$flat_number]);
include_once __DIR__ . '/../../_views/print.php';


