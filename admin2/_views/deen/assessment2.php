<?php
echo 'not allowed';
exit;
if_not_post_redirect('/home');

do_for_post(function () {
    $its_id = $_POST['its_id'] ?? '';
    setAppData('its_id', $its_id);

    $hof_data = get_hof_data($its_id);
    if (is_null($hof_data)) {        
        do_redirect_with_message('/home', 'Invalid ITS ID.');
    }
    setAppData('hof_data', $hof_data);

    $pref_data = get_azar_pref_data($its_id);
    if (is_null($pref_data) || !isset($pref_data->its_id)) {
        setSessionData(TRANSIT_DATA, 'Azan Taqbeera Preference not filled.');
    }

    $action = $_POST['action'];
    if ($action === 'save_allotment') {
        $type = $_POST['type'];

        $result = add_assessment_data_for($its_id, $type);
        if ($result) {
            setSessionData(TRANSIT_DATA, 'Assessment for '.$type.' is saved.');
        } else {
            setSessionData(TRANSIT_DATA, 'Error! Could not save assessment.');
        }
    }
});


function content_display()
{
    $its_id = getAppData('its_id');
    $records = get_assessment_data_for($its_id);
    $hof_data = getAppData('hof_data');
    ?>
    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                <input type='hidden' name='its_id' value="<?= $its_id ?>" />
                <input type='hidden' name='action' value="save_allotment" />
                <div class="mb-3 row">
                    <label for="hof_id" class="col-sm-12 col-form-label"><?=  "$hof_data->its_id - $hof_data->full_name<br/>$hof_data->gender - $hof_data->age"?></label>                    
                </div>
                <div class="mb-3 row">
                    <label for="hof_id" class="col-sm-3 col-form-label">Type</label>
                    <div class="col-sm-9">
                        <?= util_get_dropdown('type', [
                            'azan' => 'Azan',
                            'taqbeera' => 'Taqbeera',
                            'yaseen' => 'Yaseen',
                            'joshan' => 'Due-e-Joshan'
                        ], '', true) ?>
                    </div>
                </div>                
                <div class="form-group" style="font-weight:20px;margin-top: 25px;">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
            <?php
            util_show_data_table($records, [
                '__show_row_sequence' => 'Sr#',
                'type' => 'Assessment',
                'date' => 'Date'
            ]);
}