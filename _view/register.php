<div class="row">
    <div class="col-12">
        <h6>Furnish HOF Details</h6>
    </div>
</div>
<form method="post" action="">
    <input type="hidden" value="register" name="action" id="action" />
    <input type="hidden" value="<?= $dao->link_to_hof ?>" name="hof_id" id="hof_id">
    <div class='col-xs-12'>
        <div class="mb-3 row">
            <label for="staticEmail" class="col-sm-3 col-form-label">HOF ID</label>
            <div class="col-sm-9">
                <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $dao->link_to_hof ?>">
            </div>
        </div>
        <!-- <div class="mb-3 row">
            <label for="staticEmail" class="col-sm-3 col-form-label">ITS ID</label>
            <div class="col-sm-9">
                <input type="text" title="8 digits ITS" required class="form-control" name="its_id"
                    id="its_id" placeholder="ITS ID" pattern="^[0-9]{8}$" value="<?= $dao->its_id ?>">
            </div>
        </div> -->
        <div class="mb-3 row">
            <label for="full_name" class="col-sm-3 col-form-label">Full name</label>
            <div class="col-sm-9">
                <input type="text" required class="form-control form-control-lg" 
                id="full_name" name="full_name" value="<?= $dao->its_data->full_name ?>">
            </div>
        </div>        
        <div class="mb-3 row">
            <label for="wingflat" class="col-sm-3 col-form-label">Wing/Flat Number</label>
            <div class="col-sm-9">
                <input type="text" required class="form-control form-control-lg" 
                id="wingflat" name="wingflat" value="<?= $dao->wingflat??'' ?>">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="whatsapp" class="col-sm-3 col-form-label">Whatsapp Number</label>
            <div class="col-sm-9">
                <input type="text" required class="form-control form-control-lg" 
                id="whatsapp" name="whatsapp" value="<?= $dao->whatsapp??'' ?>">
            </div>
        </div>

        <div class="form-group" style="font-weight:20px;margin-top: 25px;">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </div>
</form>