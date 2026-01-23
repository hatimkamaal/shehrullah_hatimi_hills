<div class="row">
    <div class="col-12">
        <h6>Add Member</h6>
    </div>
</div>
<form method="post" action="">
    <input type="hidden" value="register" name="action" id="action" />
    <input type="hidden" value="<?= $dao->user_session->hof_id ?>" name="hof_id" id="hof_id">
    <div class='col-xs-12'>
        <div class="mb-3 row">
            <label for="staticEmail" class="col-sm-3 col-form-label">HOF ID</label>
            <div class="col-sm-9">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $dao->user_session->hof_id ?>">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="staticEmail" class="col-sm-3 col-form-label">ITS ID</label>
            <div class="col-sm-9">
                <input type="text" title="8 digits ITS" required class="form-control" name="its_id"
                    id="its_id" placeholder="ITS ID" pattern="^[0-9]{8}$" value="<?= $dao->its_id ?>">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="full_name" class="col-sm-3 col-form-label">Full name</label>
            <div class="col-sm-9">
                <input type="text" required class="form-control form-control-lg" id="full_name" name="full_name" value="<?= $dao->full_name ?>">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="gender" class="col-sm-3 col-form-label">Gender</label>
            <div class="col-sm-9">
                <select name="gender" class="form-control form-control-lg" required>
                    <?= UIService::showOptions([''=>'Select..', 'Male'=>'Male', 'Female'=>'Female'], $dao->gender) ?>
                    <!-- <option value=""  >Select....</option>
                    <option value="Male" <?= $dao->gender == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $dao->gender == 'Female' ? 'selected' : '' ?>>Female</option> -->
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="age" class="col-sm-3 col-form-label">Age</label>
            <div class="col-sm-9">
                <input type="text" pattern="^[0-9]{1,2}$" required class="form-control" id="age" name="age"  value="<?= $dao->age ?>">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="misaq" class="col-sm-3 col-form-label">Misaq</label>
            <div class="col-sm-9">
                <select name="misaq" class="form-control form-control-lg" required>
                    <?= UIService::showOptions([''=>'Select..', 'Done'=>'Done', 'Not Done'=>'Not Done'], $dao->misaq) ?>

                    <!-- <option value="">Select....</option>
                    <option value="Done">Done</option>
                    <option value="Not Done">Not Done</option> -->
                </select>
            </div>
        </div>
        <div class="form-group" style="font-weight:20px;margin-top: 25px;">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </div>
</form>