<?php

function content_display()
{
    $url = getAppData('BASE_URI');
    ?>
    <div class="row">
        <?php
        if (is_user_role(SUPER_ADMIN) || is_user_role(FINANCE)) {
            ?>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-success">
                    <div class="card-body">
                        <h5 class="card-title">Takhmeen Report</h5>
                        <p class="card-text">Takhmeen and Paid Amount</p>
                        <a href="<?= $url ?>/report/takhmeen_report" class="btn btn-primary">GO >></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-success">
                    <div class="card-body">
                        <h5 class="card-title">Other Hub Report</h5>
                        <p class="card-text">Zabihat, Pirsa, Chair, Sehori counts</p>
                        <a href="<?= $url ?>/report/other_hub" class="btn btn-primary">GO >></a>
                    </div>
                </div>
            </div>



            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">Payment List</h5>
                        <p class="card-text">List of Payments</p>
                        <a href="<?= $url ?>/finance.payment_list" class="btn btn-primary">GO >></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">ADD Payment</h5>
                        <p class="card-text">Enter the payment details</p>
                        <a href="<?= $url ?>/finance.payment" class="btn btn-primary">GO >></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">Payment Category</h5>
                        <p class="card-text">Create Payment Category</p>
                        <a href="<?= $url ?>/finance.payment_category" class="btn btn-primary">GO >></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">Fund Collection</h5>
                        <p class="card-text">Collection Details</p>
                        <form action="finance.collection" method="post">
                            <input type="hidden" name="action" value="SEARCH">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="hof_id" name="hof_id" id="hof_id"
                                    aria-label="HOF ID" aria-describedby="button-addon2" required>
                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">GO >></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">All Receipts</h5>
                        <p class="card-text">List of receipts</p>
                        <a href="<?= $url ?>/finance.receipt_list" class="btn btn-primary">GO >></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">Takhmeen Entry</h5>
                        <p class="card-text">Enter Takhmeen Details</p>
                        <form action="finance.takhmeen" method="post">
                            <input type="hidden" name="action" value="SEARCH">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="hof_id" name="hof_id" id="hof_id"
                                    aria-label="HOF ID" aria-describedby="button-addon2" required>
                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">GO >></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">HOF Details</h5>
                        <p class="card-text">HOF and Flat number list</p>
                        <a href="<?= $url ?>/reports.hof_list" class="btn btn-primary">GO >></a>
                    </div>
                </div>
            </div>

            <?php
        }
        if (is_user_role(SUPER_ADMIN) || is_user_role(RECEPTION)) {
            ?>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">Printing</h5>
                        <p class="card-text">Shehrullah Niyaz Form Print</p>
                        <form action="print-form-for" method="post">
                            <input type="hidden" name="action" value="SEARCH">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="HOF" name="hof_id" id="hof_id"
                                    aria-label="HOF ID" aria-describedby="button-addon2" pattern="^[0-9]{8}$" required>
                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">GO</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">Search</h5>
                        <p class="card-text">Flat Number / ITS ID</p>
                        <form action="reports.lookup" method="post">
                            <input type="hidden" name="action" value="SEARCH">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Flat / HOF" name="input" id="input"
                                    aria-label="HOF ID" aria-describedby="button-addon2" required>
                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">GO >></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php
        }
        if (is_user_role(SUPER_ADMIN) || is_user_role(DEEN) || is_user_role(DEEN_ASMT)) {
            ?>

            <!-- <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">Assessment</h5>
                        <p class="card-text">Azan, Taqbira Assessment</p>
                        <form action="deen.assessment" method="post">
                            <input type="hidden" name="action" value="SEARCH">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="its_id" name="its_id" id="its_id"
                                    aria-label="HOF ID" aria-describedby="button-addon2" pattern="^[0-9]{8}$" required>
                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">GO</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">Allotment Report</h5>
                        <p class="card-text">Report of allotment</p>
                        <a href="<?= $url ?>/deen.allocation_report" class="btn btn-primary">GO >></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-warning">
                    <div class="card-body">
                        <h5 class="card-title">Assessment - NEW</h5>
                        <p class="card-text">Azan, Taqbira Assessment</p>
                        <form action="deen.assessment2" method="post">
                            <input type="hidden" name="action" value="SEARCH">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="its_id" name="its_id" id="its_id"
                                    aria-label="HOF ID" aria-describedby="button-addon2" pattern="^[0-9]{8}$" required>
                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">GO</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
        if (is_user_role(SUPER_ADMIN) || is_user_role(DEEN)) {
            ?>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card bg-gradient-info">
                    <div class="card-body">
                        <h5 class="card-title">Allotment</h5>
                        <p class="card-text">Allotment for</p>
                        <form action="deen.allotment" method="post">
                            <input type="hidden" name="action" value="SEARCH">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="ITS ID" name="its_id" id="its_id"
                                    aria-label="HOF ID" aria-describedby="button-addon2" required>
                                <button class="btn btn-outline-primary" type="submit" id="button-addon2">GO >></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        if (is_user_role(SUPER_ADMIN)) {
            ?>

        <div class="col-md-4 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text">User management</p>
                    <a href="<?= $url ?>/users" class="btn btn-primary">GO >></a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Zabihats</h5>
                    <p class="card-text">Zabihat Data</p>
                    <a href="<?= $url ?>/reports.zabihat" class="btn btn-primary">GO >></a>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
    <?php

}