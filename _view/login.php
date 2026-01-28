<div class="content mt-4 text-center">
    <div class="container">
        <div class="row">
            <div class="col-12 offset-sm-2 col-sm-8 offset-lg-3 col-lg-6">
                <div class="card">
                    <div class="card-body">



                        <img class="img-fluid mx-auto d-block" src="<?= $dao->home_url ?>/assets/img/ramzan_login.png"
                            alt="Hatimi Hills" />

                        <form method="post" action="<?=$dao->home_uri . '/login/hof'?>">
                            <div class="form-group mb-3 row">
                                <label for="full_name" class="col-sm-3 col-form-label">Enter HOF ID</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control" id="hof_id"
                                        name="hof_id">
                                </div>
                            </div>
                            <div class="form-group" style="font-weight:20px;margin-top: 25px;">
                                <button type="submit" class="btn btn-success">Sign-in</button>
                            </div>
                    </div>
                    </form>
                    <!-- <hr>
                        <h3>Hatimi Hills Markaz</h3>
                        <a class="btn btn-light" href="<?= $dao->authUrl; ?>"><img
            src="<?= $dao->home_url ?>/assets/img/google-login-button.png" /></a> -->
                </div>
            </div>
        </div>
    </div>
</div>
</div>