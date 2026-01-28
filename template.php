<?php
//$ssn = new Ssn();
$message = Ssn::get('transit_data');
if (isset($message)) {
    Ssn::del('transit_data');
}

$isSecured = $this->is_secured();
$home = $dao->home_uri;

$heading = true;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Shehrullah-(hatimi hills)</title>
    <link rel="stylesheet" href="<?= $home ?>/_assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?= $home ?>/_assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="<?= $home ?>/_assets/css/style.css">
    <link rel="stylesheet" href="<?= $home ?>/_assets/css/ui-theme.css">
    <link rel="shortcut icon" href="<?= $home ?>/_assets/images/favicon.ico" />
</head>

<body>
    <div class="container-scroller">
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="<?= $home ?>">
                    Hatimi Hills
                </a>
                <a class="navbar-brand brand-logo-mini" href="<?= $home ?>">Home</a>
            </div>

            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <!-- <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button> -->
                <?php if ($isSecured) { ?>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <!-- <div class="nav-profile-img">
                                    <img src="<?= $url ?>/_assets/images/faces/face1.jpg" alt="image">
                                    <span class="availability-status online"></span>
                                </div> -->
                                <div class="nav-profile-text">
                                    <p class="mb-1 text-black"><?= $dao->user_session->hof_id ?? 'ERR' ?></p>
                                </div>
                            </a>
                            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                                <!-- <a class="dropdown-item" href="#">
                                    <i class="mdi mdi-cached me-2 text-success"></i> Activity Log </a> -->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= $home ?>/login/out">
                                    <i class="mdi mdi-logout me-2 text-primary"></i> Signout </a>
                            </div>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <!-- <div class="main-panel"> -->
            <div class="content-wrapper">
                <?php if ($heading) { ?>
                    <div class="page-header">
                        <h3 class="page-title">Shehrullah <?= HIJRI_YEAR ?>H</h3>
                    </div>
                <?php } ?>

                <?php if (isset($message) && !$suppress_message) { ?>
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong><?= $message ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>

                <?php
                echo $viewfile_content;
                // if (isset($filePath)) {
                //     if (file_exists($filePath)) {
                //         include_once $filePath;
                //     } else {
                //         //http_response_code(404);
                //         echo "404 Not Found";
                //         //exit();
                //     }
                //     //include_once $filePath;
                // } else {
                //     echo "404 - None";
                // }
                ?>

            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright ©
                    Hatimi Hills</span>
                <span class="float-none float-sm-end mt-1 mt-sm-0 text-end">Shehrullah Forms</span>
            </div>
        </footer>

    </div>

    <script src="<?= $home ?>/_assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="<?= $home ?>/_assets/js/misc.js"></script>

    <script>
        $(document).ready(function () {
            the_script();
        });
    </script>

</body>

</html>


<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <title>Shehrullah-1447</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/rsvp/assets/imgs/Logo.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alice&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="<?= $home ?>/assets/css/main.css?v=0.2" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="main">
        <header class="header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-4">
                        <a href="<?= $home ?>">Shehrullah-1447, Hatimi Hills</a>
                        <a href="<?= $home ?>" class="btn btn-light">Home</a>
                    </div>
        <?php if ($isSecured) { ?>
                    <div class="col-8 text-end">
                        <p><a href="<?= $home ?>/login/out">Logout</a></p>
                    </div>
        <?php } ?>
                </div>
            </div>
        </header>
        <div class="content mt-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-head">
                                <h6>
                                    <?= $isSecured ? ("Login: " . $dao->user_session->email) : '' ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (isset($message)) { ?>
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <strong><?= $message ?></strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                <?php }

                                if (isset($filePath)) {
                                    if (file_exists($filePath)) {
                                        include_once $filePath;
                                    } else {
                                        //http_response_code(404);
                                        echo "404 Not Found";
                                        //exit();
                                    }
                                    //include_once $filePath;
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer text-center my-2">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <p><small>&copy; Copyright <?php echo date('Y'); ?> All Rights Reserved.</small></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html> -->