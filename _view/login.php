<!-- <div class="content mt-4 text-center">
    <div class="container">
        <div class="row">
            <div class="col-12 offset-sm-2 col-sm-8 offset-lg-3 col-lg-6"> -->
                <div class="card">
                    <div class="card-header">

                        <img class="img-fluid mx-auto d-block" src="<?= $dao->home_url ?>/assets/img/ramzan_login.png"
                            alt="Hatimi Hills" />
                        <hr />
                        <form method="post" action="<?= $dao->home_uri . '/login/hof' ?>">
                            <!-- <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div> -->
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Enter HOF ID</label>
                                <input type="text" required class="form-control" id="hof_id" name="hof_id">
                            </div>
                            <div class="form-group" style="font-weight:20px;margin-top: 25px;">
                                <button type="submit" class="btn btn-success">Sign-in</button>
                            </div>
                        </form>
                        <!-- <hr>
                        <h3>Hatimi Hills Markaz</h3>
                        <a class="btn btn-light" href="<?= $dao->authUrl; ?>"><img
            src="<?= $dao->home_url ?>/assets/img/google-login-button.png" /></a> -->
                    </div>
                </div>
            <!-- </div>
        </div>
    </div>
</div> -->