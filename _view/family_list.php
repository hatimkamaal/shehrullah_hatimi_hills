<div class="card">
    <div class="card-header">

        <div class="mb-3 row">
            <div class="col-sm-8">
                <h5>Family Records</h5>
            </div>
            <div class="col-sm-4">
                <a href="<?= $dao->home_uri ?>/familyList/add" class="btn btn-warning">Add Member</a>
            </div>
        </div>
        <div class='col-xs-12'>

            <br />
            <?php if (isset($dao->error_message) && trim($dao->error_message) !== '') { ?>
                <div class="alert alert-danger" role="alert"><?= htmlspecialchars($dao->error_message) ?></div>
            <?php } ?>
            <form action="" method="post">
                <input type="hidden" name="hof_id" id="hof_id" value="<?= $dao->hof_id ?>">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Attends?</th>
                            <!-- <th>Attends?</th> -->
                            <th>Chair?</th>
                            <th>ITS & Name</th>
                        </tr>
                        <?php
                        foreach ($dao->records as $row) {
                            $attendance_type = $row->attendance_type === 'Y' ? ' checked' : '';
                            $chair_preference = $row->chair_preference === 'Y' ? ' checked' : '';
                            $dropdown_value = $row->atnd_pref;
                            $its_id = $row->its_id;
                            $dropdown_name = "atnd_pref_$its_id";
                            // form-control-lg
                            ?>
                            <tr>
                                <td>
                                    <div class='form-check form-check-flat form-check-primary'>
                                        <label class='form-check-label'>
                                            <input class="form-check-input" type='checkbox' <?= $attendance_type ?>
                                                value='<?= $row->its_id ?>' name='family_its_list[]' id='family_its_list[]'>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class='form-check form-check-flat form-check-primary'><label
                                            class='form-check-label'><input class="form-check-input" type='checkbox'
                                                <?= $chair_preference ?> value='<?= $row->its_id ?>' name='chair_its_list[]'
                                                id='chair_its_list[]'></label></div>
                                </td>
                                <!--
                        <td><input class="form-check-input" type='checkbox' <?= $attendance_type ?> value='<?= $row->its_id ?>'
                name='family_its_list[]' id='family_its_list[]'></td>
                <td><input class="form-check-input" type='checkbox' <?= $chair_preference ?> value='<?= $row->its_id ?>'
                name='chair_its_list[]' id='chair_its_list[]'></td>
                    -->
                                <td><?= $row->its_id . ' : ' . $row->full_name ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>

                <hr/>

                <div class="mb-3 row">
                    <label for="full_name" class="col-sm-3 col-form-label">Pirsa required</label>
                    <div class="col-sm-9">
                        <div class='form-check form-check-flat form-check-primary'>
                            <label class='form-check-label'><input class='form-check-input' type='checkbox' name='pirsa'
                                    value='Y' <?= $dao->pirsa_selected ?>></label>
                        </div>
                        <!-- <input class="form-check-input" <?= $dao->pirsa_selected ?> type='checkbox' value='Y'
                name='pirsa' id='pirsa'> -->
                    </div>
                </div>
                <div class="mb-3" id="pirsa_comment_row"
                    style="display: <?= (isset($dao->pirsa_selected) && trim($dao->pirsa_selected) <> '') ? 'block' : 'none' ?>;">
                    <label for="pirsa_comment" class="col-sm-3 col-form-label">Pirsa comment / reason</label>
                    <div class="col-sm-9">
                        <textarea required name="pirsa_comment" id="pirsa_comment" class="form-control"
                            rows="3"><?= isset($dao->pirsa_comment) ? htmlspecialchars($dao->pirsa_comment) : '' ?></textarea>
                    </div>
                </div>

                <script>
                    (function () {
                        var pirsaCheckbox = document.querySelector('input[name="pirsa"]');
                        var commentRow = document.getElementById('pirsa_comment_row');
                        var commentField = document.getElementById('pirsa_comment');
                        if (!pirsaCheckbox) return;
                        pirsaCheckbox.addEventListener('change', function (e) {
                            if (this.checked) {
                                commentRow.style.display = 'block';
                                commentField.setAttribute('required', 'required');
                            } else {
                                commentRow.style.display = 'none';
                                commentField.removeAttribute('required');
                            }
                        });
                    })();
                </script>
                <div class="form-group"
                    style="vertical-align: middle; font-weight:20px;margin-top: 25px;">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>