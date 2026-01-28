<?php
echo 'not allowed';
exit;
if_not_post_redirect('/home');

do_for_post(function () {
    $its_id = $_POST['its_id'] ?? '';
    setAppData('its_id', $its_id);

    $action = $_POST['action'];
    if ($action === 'save_allotment') {
        $type = $_POST['type'];
        $allotment = $_POST['allotment'];
        //$date = date('Y-m-d');

        $result = add_allotment_for($its_id, $type, $allotment);
        if ($result) {
            setSessionData(TRANSIT_DATA, 'Allotment saved for ITS ID : ' . $its_id);
        } else {
            setSessionData(TRANSIT_DATA, 'Error! Could not save allotment.');
        }
    }
});


function content_display()
{
    $its_id = getAppData('its_id');
    $records = get_allotment_for($its_id);

    ?>
    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                <input type='hidden' name='its_id' value="<?= $its_id ?>" />
                <input type='hidden' name='action' value="save_allotment" />
                <div class="mb-3 row">
                    <label for="hof_id" class="col-sm-3 col-form-label">Type</label>
                    <div class="col-sm-9">
                        <?= util_get_dropdown('type', [
                            'AzanFajr'=>'Azan Fajr',
                            'AzanZohar'=>'Azan Zohar'
                            ,'AzanMagrib'=>'Azan Maghrib'
                            ,'TakbiraFajr'=>'Taqbeera Fajr'
                            ,'TakbiraZohar'=>'Taqbeera Zohar'
                            ,'TakbiraAsar'=>'Taqbeera Asar'
                            ,'TakbiraMagrib'=>'Taqbeera Maghrib'
                            ,'TakbiraIsha'=>'Taqbeera Isha'
                            ,'Yaseen'=>'Yaseen'
                            ,'DuaEJoshn'=>'Joshan'
                        ], '', true) ?>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="hof_id" class="col-sm-3 col-form-label">Allotment</label>
                    <div class="col-sm-9">
                        <input type="text" required class="form-control" id="allotment" name="allotment" value="">
                    </div>
                </div>
                <div class="form-group" style="font-weight:20px;margin-top: 25px;">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
            <?php
            util_show_data_table($records, [
                '__show_row_sequence' => 'Sr#',
                'type' => 'Allotment of',
                'date' => 'Date'
            ]);
}