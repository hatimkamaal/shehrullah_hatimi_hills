<h6>Hatimi Hills Markaz - Registration</h6>
<?php if( isset($dao->error) ) {?>
<div class="alert alert-primary alert-dismissible fade show" role="alert">
    <strong><?= $dao->error ?></strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php } ?>
<form action="" method="post">
    <div class="mb-3 row">
        <h2>Hello <?= $dao->signin_email ?>, provide your HOF's ITS ID.</h2>
        <label for="hof_id" class="col-sm-3 col-form-label">HOF ID</label>
        <div class="col-sm-9">
            <div class="input-group mb-3">
                <input type="text" title="Please enter at 8 digits" required class="form-control" name="hof_id"
                    id="hof_id" placeholder="HOF ID" pattern="^[0-9]{8}$" aria-label="Sabeel number"
                    aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Submit</button>
            </div>
        </div>
    </div>
</form>