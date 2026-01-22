<?php
?>

<h5>Family Records</h5>
<div class='col-xs-12'>    
    <a href="<?=$dao->home_uri?>/familyList/add" class="btn btn-warning">Add Member</a>
    <br />
    <form action="" method="post">
        <input type="hidden" name="hof_id" id="hof_id" value="<?= $dao->hof_id ?>">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>Attends?</th>
                    <th>Chair?</th>
                    <th>ITS & Name</th>
                </tr>                                
                <?php
                foreach ($dao->records as $row) {
                    $selected = $row->its_id === $row->its_id ? ' checked' : '';
                    $dropdown_name = "atnd_pref_{$row->its_id}";
                    $dropdown_value = $dao->$dropdown_name;
                    ?>
                    <tr><td>
                        <select name="<?=$dropdown_name?>" class="form-control form-control-lg" required>
                            <?= UIService::showOptions([''=>'Select..', 'A'=>'Attends', 'AC'=>'Attends + Chair', 'N'=>'Not attending'], $dropdown_value) ?>                    
                        </select>
                    </td>
                        <td><input type='checkbox' <?=$selected?> value='<?=$row->its_id?>'
                name='family_its_list[]' id='family_its_list[]'></td>
                <td><input type='checkbox' <?=$selected?> value='<?=$row->its_id?>'
                name='chair_its_list[]' id='chair_its_list[]'></td>
                <td><?=$row->its_id . ' : '. $row->full_name?></td>
                </tr>
                    <?php } ?>
            </table>
        </div>
        <div class="form-group" style="text-align: right; vertical-align: middle; font-weight:20px;margin-top: 25px;">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </form>
</div>