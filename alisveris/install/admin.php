<?php
error_reporting(0);
require_once 'functions.php';
require_once 'includes//Bcrypt.php';
require_once 'sql/cities.php';;

$cls = new Bcrypt();


if (isset($_POST["btn_admin"])) {

    $license_code = $_POST["license_code"];
    $purchase_code = $_POST["purchase_code"];

    if (!isset($license_code) || !isset($purchase_code)) {
        header("Location: index.php");
        exit();
    }

    $timezone = trim($_POST['timezone']);
    $admin_username = trim($_POST['admin_username']);
    $admin_email = trim($_POST['admin_email']);
    $admin_password = trim($_POST['admin_password']);

    $password = $cls->hash_password($admin_password);
    $slug = str_slug($admin_username);

    /* Database Credentials */
    defined("DB_HOST") ? null : define("DB_HOST", @$_COOKIE["db_host"]);
    defined("DB_USER") ? null : define("DB_USER", @$_COOKIE["db_user"]);
    defined("DB_PASS") ? null : define("DB_PASS", @$_COOKIE["db_password"]);
    defined("DB_NAME") ? null : define("DB_NAME", @$_COOKIE["db_name"]);

    /* Connect */
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $connection->query("SET CHARACTER SET utf8");
    $connection->query("SET NAMES utf8");

    /* check connection */
    if (mysqli_connect_errno()) {
        $error = 0;
    } else {
        $token = uniqid("", TRUE);
        $token = str_replace(".", "-", $token);
        $token = $token . "-" . rand(10000000, 99999999);

        mysqli_query($connection, "INSERT INTO users (username, slug, email, email_status, token, password, role, user_type) VALUES ('" . $admin_username . "', '" . $slug . "', '" . $admin_email . "',1,'" . $token . "','" . $password . "','admin','registered')");
        mysqli_query($connection, "UPDATE general_settings SET mds_key='" . $license_code . "', purchase_code='" . $purchase_code . "', timezone='" . $timezone . "' WHERE id='1'");

        for ($i = 1; $i <= 30; $i++) {
            mysqli_query($connection, $array_cities[$i]);
        }
        sleep(1);
        for ($i = 31; $i <= 60; $i++) {
            mysqli_query($connection, $array_cities[$i]);
        }
        sleep(1);
        for ($i = 61; $i <= 91; $i++) {
            mysqli_query($connection, $array_cities[$i]);
        }
        sleep(1);
        /* close connection */
        mysqli_close($connection);

        setcookie('db_host', "", time() - 3600);
        setcookie('db_name', "", time() - 3600);
        setcookie('db_user', "", time() - 3600);
        setcookie('db_password', "", time() - 3600);

        $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $redir .= "://" . $_SERVER['HTTP_HOST'];
        $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
        $redir = str_replace('install/', '', $redir);
        header("refresh:5;url=" . $redir);
        $success = 1;
    }

} else {
    $license_code = $_GET["license_code"];
    $purchase_code = $_GET["purchase_code"];

    if (!isset($license_code) || !isset($purchase_code)) {
        header("Location: index.php");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webilgi.Net E-Ticaret - Y??kleme Ekran??</title>
    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font-awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-12 col-md-offset-2">

            <div class="row">
                <div class="col-sm-12 logo-cnt">
                    <h1><img src="assets/img/yenilogo.png" width="275" height="77" alt="Webilgi.Net"/></h1>
                    <p>Y??kleme Paneline Ho??geldiniz</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="install-box">


                        <div class="steps">
                            <div class="step-progress">
                                <div class="step-progress-line" data-now-value="100" data-number-of-steps="5" style="width: 100%;"></div>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-code"></i></div>
                                <p>Ba??la</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-gear"></i></div>
                                <p>Sistem Gereksimleri</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-folder-open"></i></div>
                                <p>Yazma ??zinleri(klas??rler)</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-database"></i></div>
                                <p>Veritaban??</p>
                            </div>
                            <div class="step active">
                                <div class="step-icon"><i class="fa fa-user"></i></div>
                                <p>Y??netici</p>
                            </div>
                        </div>

                        <div class="messages">
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger">
                                    <strong>Ba??lant?? sa??lanamad??! L??tfen veritaban?? kimlik bilgilerinizi kontrol edin.</strong>
                                </div>
                            <?php } ?>
                            <?php if (isset($success)) { ?>
                                <div class="alert alert-success">
                                    <strong>Kurulum tamamlan??yor ... L??tfen bekleyin!</strong>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if (isset($success)) { ?>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="spinner">
                                        <div class="rect1"></div>
                                        <div class="rect2"></div>
                                        <div class="rect3"></div>
                                        <div class="rect4"></div>
                                        <div class="rect5"></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="step-contents">
                            <div class="tab-1">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <input type="hidden" name="license_code" value="<?php echo $license_code; ?>">
                                    <input type="hidden" name="purchase_code" value="<?php echo $purchase_code; ?>">
                                    <div class="tab-content">
                                        <div class="tab_1">
                                            <h1 class="step-title">Sistem Ayarlar??</h1>
                                            <div class="form-group">
                                                <select name="timezone" class="form-control" required>
                                                    <option value="">Select Your Timezone</option>
                                                    <?php $timezones = timezone_identifiers_list();
                                                    if (!empty($timezones)):
                                                        foreach ($timezones as $timezone):?>
                                                            <option value="<?php echo $timezone; ?>"><?php echo $timezone; ?></option>
                                                        <?php endforeach;
                                                    endif; ?>
                                                </select>
                                            </div><br>
                                            <h1 class="step-title">Admin Bilgileri</h1>
                                            <div class="form-group">
                                                <label for="email">Kullan??c?? Ad??</label>
                                                <input type="text" class="form-control form-input" name="admin_username" placeholder="Username" value="<?php echo @$admin_username; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control form-input" name="admin_email" placeholder="Email" value="<?php echo @$admin_email; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">??ifre</label>
                                                <input type="text" class="form-control form-input" name="admin_password" placeholder="Password" value="<?php echo @$admin_password; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="buttons">
                                        <a href="database.php?license_code=<?php echo $license_code; ?>&purchase_code=<?php echo $purchase_code; ?>" class="btn btn-success btn-custom pull-left">Geri</a>
                                        <button type="submit" name="btn_admin" class="btn btn-success btn-custom pull-right">Bitir</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

