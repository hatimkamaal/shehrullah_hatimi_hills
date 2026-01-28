<?php
DEFINE('SUPER_ADMIN', 'super_admin');
DEFINE('FINANCE', 'finance');
DEFINE('RECEPTION', 'reception');
DEFINE('DEEN', 'deen');
DEFINE('DEEN_ASMT', 'deen.assessment');



DEFINE('ROLE', [FINANCE, RECEPTION, DEEN, DEEN_ASMT]);
//DEFINE('ROLE2', [SUPER_ADMIN, FINANCE, RECEPTION]);

function is_user_role($role)
{
    $userData = getSessionData(THE_SESSION_ID);
    if( is_null($userData) ) {
        return false;
    }
    $roles = $userData->roles;

    return in_array($role, $roles);
}

function get_user_login() {
    $userData = getSessionData(THE_SESSION_ID);
    if( is_null($userData) ) {
        return null;
    }
    return $userData->itsid;
}

//--------------------------------------------
function if_result_found($result)
{
    return isset($result) && $result->success && $result->count > 0 ? true : false;
}

function get_current_hijri_year()
{
    return 1446;
}

function get_shehrullah_data_for($hijri_year)
{
    return (object) [
        'year' => 1446,
        'markaz' => 'hatimi markaz',
        'full_niyaz' => 45000,
        'half_niyaz' => 27000,
        'family_niyaz' => 6000,
        'per_kid_niyaz' => 3000,
        'zero_hub_age' => 4,
        'half_hub_age' => 13,
        'sehori' => 14000,
        'iftar' => 0,
        'zabihat' => 5300,
        'fateha' => 7200,
        'khajoor' => 0,
        'chair' => 500,
        'parking' => 0,
        'pirsu' => 500
    ];
}

function get_user_records()
{
    $query = 'SELECT itsid, name, roles FROM hhm_admin_user;';
    $result = run_statement($query);
    return $result->success && $result->count > 0 ? $result->data : [];
}

function get_user_record_for($itsid)
{
    $query = 'SELECT * FROM hhm_admin_user WHERE itsid=?;';
    $result = run_statement($query, $itsid);
    return $result->success && $result->count > 0 ? $result->data[0] : null;
}

function add_user_record($itsid, $name, $password, $roles)
{
    $query = 'INSERT INTO hhm_admin_user (itsid, name, passwd, roles)
    VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE name=?, passwd=?, roles=?;';
    $result = run_statement($query, $itsid, $name, $password, $roles, $name, $password, $roles);
    return $result->success ? null : $result->message;
}





function get_attendees_data($hof_id)
{
    $query = '
    SELECT 
    case when sa.masalla is null then "" else sa.masalla end as masalla, 
    case when sa.attends is null then "Y" else sa.attends end as attendance_type,
    case when sa.chair is null then "N" else sa.chair end as chair_preference,
    m.its_id,m.full_name,m.age,m.gender,m.sector,m.subsector,m.mohallah,m.photo_pending
    FROM hhm_attendees sa
    JOIN hhm_its_data m ON m.its_id = sa.its_id
    WHERE m.hof_id = ? and sa.attends != "No"';

    // $query = 'SELECT 
    // case when sa.masalla is null then "" else sa.masalla end as masalla, 
    // case when sa.attends is null then "Y" else sa.attends end as attendance_type,
    // case when sa.chair is null then "N" else sa.chair end as chair_preference,
    // m.its_id,m.full_name,m.age,m.gender,m.sector,m.subsector,m.mohallah,m.photo_pending
    // FROM hhm_its_data m
    // LEFT JOIN  hhm_attendees sa ON m.its_id = sa.its_id
    // WHERE m.hof_id = ?  ';

    // $params = [];
    // if ($attends) {
    //     $query .= ' and sa.attends <> "No";';
    // }

    $result = run_statement($query, $hof_id);

    if ($result->count > 0) {
        return $result->data;
    }
    return null;
}


function get_takhmeen_for($year, $hof_id)
{
    $result = run_statement('SELECT takhmeen FROM hhm_takhmeen WHERE year=? and hof_id=?;', $year, $hof_id);
    if ($result->count > 0) {
        return $result->data[0]->takhmeen;
    }
    return 0;
}

function get_shehrullah_takhmeen_for($hof_id, $hijri_year)
{
    $query = 'SELECT * FROM hhm_takhmeen  WHERE hof_id=? and year=?';
    $result = run_statement($query, $hof_id, $hijri_year);
    return $result->success && $result->count > 0 ? $result->data[0] : null;
}

function add_shehrullah_takh_hub($year, $hof_id, $niyaz_hub, $iftar_count, $zabihat_count, $fateha_count, $khajoor_count, $sehori_count, $pirsa_count, $chair_count, $takhmeen)
{
    $login = get_user_login();
    $query = "UPDATE hhm_takhmeen SET
    niyaz_hub=?,iftar_count=?,zabihat_count=?,fateha_count=?,khajoor_count=?, sehori_count=?,pirsa_count=?,chair_count=?,takhmeen=?, login=?
    WHERE year=? and hof_id=?";

    $result = run_statement($query, $niyaz_hub, $iftar_count, $zabihat_count, $fateha_count, $khajoor_count, $sehori_count, $pirsa_count, $chair_count, $takhmeen,$login, $year, $hof_id);
    return $result->success;
}

function get_receipt_data_for($year, $hof_id)
{
    $query = 'SELECT * FROM hhm_collection_record WHERE year=? and hof_id=?;';
    $result = run_statement($query, $year, $hof_id);
    if ($result->success && $result->count > 0) {
        return $result->data;
    }
    return [];
}

function get_all_receipt_data_for($year)
{
    $query = 'SELECT * FROM hhm_collection_record WHERE year=? ORDER BY id DESC;';
    $result = run_statement($query, $year);
    if ($result->success && $result->count > 0) {
        return $result->data;
    }
    return [];
}

function save_collection_record($year, $hof_id, $amount, $payment_mode, $transaction_ref, $remarks)
{
    $login = get_user_login();

    $query = 'INSERT INTO hhm_collection_record (year, hof_id, amount, payment_mode, transaction_ref, remarks, login, created)
    VALUES (?,?,?,?,?,?,?,now()); UPDATE hhm_takhmeen SET paid_amount = paid_amount + ? WHERE year=? and hof_id=?;';

    $result = run_statement($query, $year, $hof_id, $amount, $payment_mode, $transaction_ref, $remarks,$login, $amount, $year, $hof_id);

    if ($result->success && $result->count > 0) {
        return $result->insertedID;
    }
    return -1;
}


function get_collection_record($id, $year)
{
    $query = 'select cr.id,cr.hof_id, cr.amount,cr.payment_mode, cr.transaction_ref, cr.created, cr.remarks, cr.login,t.takhmeen,
    t.paid_amount,m.full_name
    FROM hhm_collection_record cr 
    JOIN hhm_takhmeen t ON t.hof_id = cr.hof_id
    JOIN hhm_its_data m  ON t.hof_id = m.its_id
    where cr.id = ? and cr.year=?';

    $result = run_statement($query, $id, $year);

    if ($result->success && $result->count > 0) {
        return $result->data[0];
    }
    return null;
}

function get_hof_data($hof_id)
{
    $query = 'SELECT     
    m.its_id,m.hof_id,m.full_name,m.age,m.gender,m.sector,m.subsector,m.mohallah,m.email,m.address
    FROM hhm_its_data m    
    WHERE m.its_id = ?;';
    $result = run_statement($query, $hof_id);
    if ($result->count > 0) {
        return $result->data[0];
    }
    return null;
}

function add_payment_categories($label, $description)
{
    $query = 'INSERT INTO hhm_payment_category(label,description) values(?,?);';
    $results = run_statement($query, $label, $description);
    if (!$results->success) {
        return $results->message;
    }
    return null;
}

function get_payment_categories()
{
    $query = 'SELECT * FROM hhm_payment_category;';
    $results = run_statement($query);
    if ($results->count > 0) {
        return $results->data;
    }
    return [];
}


function get_all_payment()
{
    $query = 'SELECT r.*, c.label FROM hhm_payment_record r
    JOIN hhm_payment_category c ON r.category = c.id;';
    $results = run_statement($query);
    if ($results->count > 0) {
        return $results->data;
    }
    return [];
}

function add_payment($category, $amount, $date, $pay_mode, $upi_ref, $paid_by, $remarks)
{
    $login = get_user_login();
    $query = 'INSERT INTO hhm_payment_record(category,amount,date,pay_mode,upi_ref,paid_by,remarks,login,created) 
    values(?,?,?,?,?,?,?,?,now());';
    $results = run_statement($query, $category, $amount, $date, $pay_mode, $upi_ref, $paid_by, $remarks, $login);
    if (!$results->success) {
        return $results->message;
    }
    return null;
}

function get_azan_allocation($hof_id) {
    $query = 'select its.full_name ,
        alo.Sharaf,
        alo.`Hijri Date` as hijri_date
        from hhm_its_data its
        left join vw_get_azaan_takbira_allocation alo on alo.`ITs id` = its.its_id
        where its.gender="Male" and its.hof_id=?
        order by `Hijri Date`, aSeq';

    $results = run_statement($query, $hof_id);
    if ($results->count > 0) {
        return $results->data;
    }
    return [];
}

function get_azar_pref_data($its_id)
{
    $q = 'SELECT 
    i.full_name, i.gender, i.age, i.its_id as its
    , a.* FROM hhm_its_data i 
    left join hhm_azan_taqbira_pref_data a ON a.its_id = i.its_id
    WHERE i.its_id = ?;';
    $results = run_statement($q, $its_id);
    if ($results->count > 0) {
        return $results->data[0];
    }
    return null;
}

function add_azan_pref_data(
    $its_id,
    $azan_pref,
    $taqbeera_pref,
    $fajr_azan_pref,
    $maghrib_azan_pref,
    $zohar_azan_pref,
    $fajr_taqbeera_pref,
    $maghrib_taqbeera_pref,
    $zohar_taqbeera_pref,
    $yaseen_pref,
    $joshan_pref,
    $daska_pref,
    $remarks
) {
    $q = 'INSERT INTO hhm_azan_taqbira_pref_data(its_id, azan_pref, taqbeera_pref, 
    fajr_azan_pref,maghrib_azan_pref, zohar_azan_pref, fajr_taqbeera_pref, 
    maghrib_taqbeera_pref,zohar_taqbeera_pref, yaseen_pref,joshan_pref , daska_pref, remarks) values (?,?,?,?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE azan_pref=?, taqbeera_pref=?, fajr_azan_pref=?,
maghrib_azan_pref=?, zohar_azan_pref=?, fajr_taqbeera_pref=?, maghrib_taqbeera_pref=?,
zohar_taqbeera_pref=?, yaseen_pref=?,joshan_pref=?,daska_pref=?,remarks=?;';
    $results = run_statement(
        $q,
        $its_id,
        $azan_pref,
        $taqbeera_pref,
        $fajr_azan_pref,
        $maghrib_azan_pref,
        $zohar_azan_pref,
        $fajr_taqbeera_pref,
        $maghrib_taqbeera_pref,
        $zohar_taqbeera_pref,
        $yaseen_pref,
        $joshan_pref,
        $daska_pref,
        $remarks,
        $azan_pref,
        $taqbeera_pref,
        $fajr_azan_pref,
        $maghrib_azan_pref,
        $zohar_azan_pref,
        $fajr_taqbeera_pref,
        $maghrib_taqbeera_pref,
        $zohar_taqbeera_pref,
        $yaseen_pref,
        $joshan_pref,
        $daska_pref,
        $remarks
    );
    if (!$results->success) {
        return $results->message;
    }
    return null;
}


function get_list_of_gents_attendees($hof_id)
{
    $query = 'SELECT
    a.its_id, i.full_name, i.hof_id, (case when az.its_id is null then "No" else "Yes" end) as filled
    FROM hhm_attendees a 
    JOIN hhm_its_data i ON i.its_id = a.its_id
    LEFT JOIN hhm_azan_taqbira_pref_data az ON az.its_id = i.its_id
    WHERE a.attends != "No" and i.gender = "Male" and i.hof_id = ? and i.age >= 10';
    $results = run_statement($query, $hof_id);
    if ($results->count > 0) {
        return $results->data;
    }
    return null;
}

function get_flat_details($hof_id) {
    $query = 'SELECT h.flat_number, h.society_dues, i.* FROM hhm_hofs h
    JOIN hhm_its_data i ON i.its_id=h.hof_id
    WHERE h.hof_id=?;';
    $results = run_statement($query, $hof_id);
    if ($results->count > 0) {
        return $results->data[0];
    }
    return null;
}


function get_assessment_for($its_id) {
    $query = "SELECT a.*,i.full_name 
    FROM hhm_its_data i 
    LEFT JOIN hhm_deeni_assessment a ON i.its_id = a.its_id
    WHERE i.its_id=?";

    // $query = "SELECT a.*,i.full_name FROM hhm_deeni_assessment a
    // JOIN hhm_its_data i ON i.its_id = a.its_id
    // WHERE a.its_id=?";
    $results = run_statement($query, $its_id);
    if ($results->count > 0) {
        return $results->data[0];
    }
    return [];
}

function add_assessment_data_for($its_id, $type) {

    $query = 'INSERT INTO hhm_deeni_assessment2(its_id, type, date)
    values (?,?,now()) ON DUPLICATE KEY UPDATE date=now();';

    $results = run_statement($query, $its_id, $type);
    return $results->count > 0 ? true : false;
}

function get_assessment_data_for($its_id) {

    $query = 'SELECT * FROM hhm_deeni_assessment2 WHERE its_id=?;';
    $results = run_statement($query, $its_id);
    if ($results->count > 0) {
        return $results->data;
    }
    return [];
}


function add_assessment_for($its_id, $azan, $taqbira, $joshan, $yaseen, $azan_date, $taqbira_date, $joshan_date, $yaseen_date) {

    $query = 'INSERT INTO hhm_deeni_assessment(its_id, azaan, taqbeera, joshan, yaseen,azaan_asmt_date, taqbeera_asmt_date, joshan_asmt_date, yaseen_asmt_date)
    values (?,?,?,?,?,?,?,?,?);';

    $results = run_statement($query, $its_id, $azan, $taqbira, $joshan, $yaseen, $azan_date, $taqbira_date, $joshan_date, $yaseen_date);
    return $results->count > 0 ? true : false;
}

// function add_assessment_for($its_id, $azan, $taqbira, $joshan, $yaseen, $azan_date, $taqbira_date, $joshan_date, $yaseen_date) {

//     $query = 'INSERT INTO hhm_deeni_assessment(its_id, azaan, taqbeera, joshan, yaseen,azaan_asmt_date, taqbeera_asmt_date, joshan_asmt_date, yaseen_asmt_date)
//     values (?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE azaan=?, taqbeera=?, joshan=?, yaseen=?,azaan_asmt_date=?, taqbeera_asmt_date=?, joshan_asmt_date=?, yaseen_asmt_date=?;';

//     $results = run_statement($query, $its_id, $azan, $taqbira, $joshan, $yaseen, $azan_date, $taqbira_date, $joshan_date, $yaseen_date, $azan, $taqbira, $joshan, $yaseen, $azan_date, $taqbira_date, $joshan_date, $yaseen_date);
//     return $results->count > 0 ? true : false;
// }

function get_allotment_for($its_id) {
    $query = "SELECT * FROM hhm_deeni_allotment
    WHERE its_id=?";
    $results = run_statement($query, $its_id);
    if ($results->count > 0) {
        return $results->data;
    }
    return [];
}

function add_allotment_for($its_id, $type, $date) {

    $query = 'INSERT INTO hhm_deeni_allotment(its_id, type, date)
    values (?,?,?);';

    $results = run_statement($query, $its_id, $type, $date);
    return $results->count > 0 ? true : false;
}
