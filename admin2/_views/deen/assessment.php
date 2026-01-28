<?php

do_for_post('__handle_post_operation');

function __handle_post_operation()
{
    
    $action = $_POST['action'] ?? '';
    $its_id = $_POST['its_id'];

    if ($action == 'save_assessment') {
        $asmt_record = get_assessment_for($its_id);

        $azan_date = '0000-00-00';
        $taqbira_date = '0000-00-00';
        $joshan_date = '0000-00-00';
        $yaseen_date = '0000-00-00';

        $azan = $_POST['azan'] ?? 'N';        
        $taqbira = $_POST['taqbira'] ?? 'N';
        $joshan = $_POST['joshan'] ?? 'N';
        $yaseen = $_POST['yaseen'] ?? 'N';

        if( isset( $asmt_record->its_id ) ) {
            if( $azan == 'Y' && $asmt_record->azaan != 'Y' ) {
                $azan_date = date('Y-m-d');
            }


        } else {

            if( $azan == 'Y' ) {
                $azan_date = date('Y-m-d');
            }
            if( $taqbira == 'Y' ) {
                $taqbira_date = date('Y-m-d');
            }
            if( $joshan == 'Y' ) {
                $joshan_date = date('Y-m-d');
            }
            if( $yaseen == 'Y' ) {
                $yaseen_date = date('Y-m-d');
            }
    
    
            $flag = add_assessment_for($its_id, $azan, $taqbira, $joshan, $yaseen
                , $azan_date, $taqbira_date, $joshan_date, $yaseen_date);
            if ($flag) {
                $msg = 'Shukran! Assessment saved successfully.';
            } else {
                $msg = 'Error! Unable to save assessment.';
            }

        }



        

        do_redirect_with_message('/home', $msg);
    }
}

function show_check_box($name, $title, $value) {
    $checked = $value == 'Y' ? ' checked' : '';
    ?>
    <div class="form-group row">
        <label for="<?=$name?>" class="col-sm-2 col-form-label"><?=$title?></label>
        <div class="col-sm-10">
            <div class='form-check form-check-flat form-check-primary'>
                <label class='form-check-label'><input class='form-check-input' type='checkbox'
                        name='<?=$name?>' id='<?=$name?>' value='Y' <?=$checked?>></label>
            </div>
        </div>
    </div>
    <?php
}

function content_display()
{
    $its_id = $_POST['its_id'] ?? '';
    $asmt_record = get_assessment_for($its_id);
    $azaan_checked = $asmt_record->azaan == 'Y' ? ' checked' : '';

    ?>
    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                <input type='hidden' name='its_id' value="<?= $its_id ?>" />
                <input type='hidden' name='action' value="save_assessment" />
                <div class="form-group row">
                    <label for="itsid" class="col-sm-2 col-form-label">ITS ID</label>
                    <div class="col-sm-10">
                        <?= $its_id . " - " . $asmt_record->joshan . ' ' . ($asmt_record->full_name ?? '') ?>
                    </div>
                </div>
                <?php
                show_check_box('azan', 'Azan', $asmt_record->azaan);
                show_check_box('taqbira', 'Taqbeera', $asmt_record->taqbeera);
                show_check_box('yaseen', 'Yaseen', $asmt_record->yaseen);
                show_check_box('joshan', 'Joshan', $asmt_record->joshan);
                ?>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-gradient-success btn-rounded btn-fw">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php

}