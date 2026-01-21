<?php
$ssn = new Ssn();
$message = $ssn->transit_data ?? null;
if( isset($message) ) {    
    $ssn->del('transit_data');
}

$isSecured = $this->is_secured();
$home = $dao->home_uri;
?>
<!DOCTYPE html>
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
    <link rel="stylesheet" href="<?=$home?>/assets/css/main.css?v=0.2" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="main">
        <header class="header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-4">
                        <a href="<?=$home?>">Hatimi Hills</a>
                    </div>
        <?php if($isSecured) {?>
                    <div class="col-8 text-end">
                        <p><a href="<?=$home?>/project">Projects</a> | <a href="<?=$home?>/vendor">Vendors</a> | <a href="<?=$home?>/login">Logout</a></p>
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
                                        echo "404 Not Found $filePath";
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
                        <!-- <p class="mb-1"><small><strong>Helpline:</strong> <a href="https://wa.me/+918390403052">+91 8390 4030 52</a></small></p> -->
                        <p><small>&copy; Copyright <?php echo date('Y'); ?> All Rights Reserved.</small></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>