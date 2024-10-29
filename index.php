
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = "";
require_once('conf/funciones.php');
require_once(__DIR__ . '/admin/Models/user.php');

$user = new User();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    if (hasEmptyField([$user_name, $password])) {
        $error = "Debe llenar todos los campos";
    } else {
        $userData = $user->get_user($user_name, $password);

        if (!empty($userData)) {
            session_start();
            $_SESSION['user'] = $userData['firstName'];
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_role'] = $userData['role_id'];
            $_SESSION['user_email'] = $userData['email'];
            $_SESSION['user_name'] = $userData['userName'];
            header("Location: admin/index.php");
        } else {
            $error = "Contraseña o usuario incorrectos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Forms - Kaiadmin Bootstrap 5 Admin Dashboard</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="admin/views/assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="admin/views/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["admin/views/assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="admin/views/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="admin/views/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="admin/views/assets/css/kaiadmin.min.css" />

</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Sign In Start -->
        <div class="container">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <form action="index.php" method="post" style="max-width: 600px;" class="card rounded">
                    <div class="p-4 pb-1 my-4 mb-2 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="" class="">
                                <h3 class="text-primary"><i class="fa fa-solid fa-swatchbook me-2"></i>Global Tech Solutions</h3>
                            </a>
                        </div>
                        <div class="form-group mb-3">
                            <label for="username">Nombre de Usuario</label>
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Username"
                              aria-label="Username"
                              aria-describedby="basic-addon1"
                              name="user_name"
                            />
                          </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input
                              type="password"
                              class="form-control"
                              id="password"
                              placeholder="Password"
                              name="password"
                            />
                          </div>
                        <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Ingresar</button>
                        <p class="text-center mb-0">Olvidaste tu contraseña? <a href="#">Click aqui</a></p>
                        <p class="text-center text-danger mt-0"><?= $error ?></p>
                    </div>
                </form>
            </div>
        </div>
        <!-- Sign In End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>