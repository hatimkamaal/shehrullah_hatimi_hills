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
                    <th>Select</th>
                    <th>Name</th>
                </tr>                                
                <?php
                foreach ($dao->records as $row) {
                    $selected = $row->its_id === $row->its_id ? ' checked' : '';
                    $en_its_id = base64_encode($row->its_id . '-' . $row->hof_id);
                    ?>
                    <tr><td><input type='checkbox' <?=$selected?> value='<?=$row->its_id?>'
                name='family_its_list[]' id='family_its_list[]'></td>
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