<?php
error_reporting(0);
require_once 'functions.php';

if (!function_exists('curl_init')) {
    $error = 'cURL is not available on your server! Please enable cURL to continue the installation. You can read the documentation for more information.';
}
if (isset($_POST["btn_license_code"])) {
    $license_code = "";
    $purchase_code = "";
    $response = "";

//    $current_url = currentUrl($_SERVER);
    $current_url = get_current_domain();
    $data->code = "babiato";


    if (!empty($data)) {
        if ($data->code == "error") {
            $error = "Invalid License Code!";
        } else {
            $purchase_code = $data->code;
            $license_code = @sha1(enc1($purchase_code).site1($current_url));
            header("Location: system-requirements.php?license_code=" . $license_code . "&purchase_code=" . $purchase_code);
            exit();
        }
    } else {
        $error = "Invalid License Code!";
    }
}


if (!isset($license_code)) {
    if (isset($_GET["license_code"])) {
        $license_code = $_GET["license_code"];
    } else {
        $license_code = "";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webilgi.Net E-Ticaret - Yükleme Ekranı</title>
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
                    <p>Yükleme Paneline Hoşgeldiniz</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="install-box">


                        <div class="steps">
                            <div class="step-progress">
                                <div class="step-progress-line" data-now-value="20" data-number-of-steps="5" style="width: 20%;"></div>
                            </div>
                            <div class="step active">
                                <div class="step-icon"><i class="fa fa-code"></i></div>
                                <p>Başla</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-gear"></i></div>
                                <p>Sistem Gereksimleri</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-folder-open"></i></div>
                                <p>Yazma İzinleri(klasörler)</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-database"></i></div>
                                <p>Veritabanı</p>
                            </div>
                            <div class="step">
                                <div class="step-icon"><i class="fa fa-user"></i></div>
                                <p>Yönetici</p>
                            </div>
                        </div>

                        <div class="messages">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger">
                                    <strong><?php echo $error; ?></strong>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="step-contents">
                            <div class="tab-1">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <div class="tab-content">
                                        <div class="tab_1">
                                            <h1 class="step-title">Başla</h1>

                                            <div class="form-group text-center">
                                              <small style="margin-top: 10px;display: block">
                                                Lütfen sistemin kurulduğu yerin (alt alanadi olmadiğina dikkat edin) <br>
                                                <?php echo get_current_domain($_SERVER['SERVER_NAME']); ?>

                                              </small>
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Lisans Kodunu Girin</label>
                                                <textarea name="license_code" class="form-control form-input" style="resize: vertical; height: 100px;line-height: 24px;padding: 10px;" placeholder="Lisans Kodu: webilginet" required><?php echo $license_code; ?></textarea>
                                                <small style="margin-top: 10px;display: block">*Bu Alana lisans kodunu girmelisiniz.(Lisans Kodu: <b>webilginet</b>) bunu yazın.
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-footer">
                                        <button type="submit" name="btn_license_code" class="btn-custom pull-right">İleri</button>
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
