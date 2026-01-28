<?php
//if_not_post_redirect('/home');

do_for_post(function () {
    
    $tarikh = $_POST['tarikh'];
    $type = $_POST['type'];

    // // $query = 'SELECT 
    // -- `ITS Id` its_id,`Full Name` full_name,Sharaf sharaf,`Hijri Date` date
    // -- from vw_get_azaan_takbira_allocation  
    // -- Where Tarikh =?
    // -- AND TypeS=? Or "All"=?
    // -- order by TRIM(`Hijri Date`),aSeq';

    $query = 'SELECT 
    `ITS Id` its_id,`Full Name` full_name,Sharaf sharaf,Tarikh date
    from vw_get_azaan_takbira_allocation  
    Where Tarikh like ?
    AND (TypeS=? Or "All"=?)
    order by Tarikh,aSeq';


    $result = run_statement($query, $tarikh, $type, $type);
    if ($result->count > 0) {
       setAppData('records', $result->data);    
    }
});


function content_display()
{
    $query = 'SELECT distinct Tarikh FROM vw_get_azaan_takbira_allocation';
    $result = run_statement($query);
    $tarikhs = [];
    if ($result->count > 0) {
        foreach ($result->data as $row) {
            $tarikh = $row->Tarikh;
            $tarikhs[$tarikh] = $tarikh;
        }        
    }
    
    $query = 'SELECT distinct TypeS FROM  vw_get_azaan_takbira_allocation';
    $result = run_statement($query);
    $types = ['All'=>'All'];
    if ($result->count > 0) {
        foreach ($result->data as $row) {
            $type = $row->TypeS;
            $types[$type] = $type;
        }        
    }

    ?>
    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3 row">
                    <label for="hof_id" class="col-sm-3 col-form-label">Tarikh</label>
                    <div class="col-sm-9">
                        <?= util_get_dropdown('tarikh', $tarikhs, '', true) ?>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="hof_id" class="col-sm-3 col-form-label">Type</label>
                    <div class="col-sm-9">
                        <?= util_get_dropdown('type', $types, '', true) ?>
                    </div>
                </div>
                <div class="form-group" style="font-weight:20px;margin-top: 25px;">
                    <button type="submit" class="btn btn-success">Search</button>
                </div>
            </form>
            <?php
            $records = getAppData('records');
            if( !is_null($records) ) {
                util_show_data_table($records, [
                    '__show_row_sequence' => 'Sr#',
                    'its_id' => 'ITS ID',
                    'full_name' => 'Full Name',
                    'sharaf' => 'Sharaf',
                    'date' => 'Date',
                ]);
            }  
}