<?php
include_once '../controllers/home_controller.php';

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" href="https://img.icons8.com/stickers/100/overview-pages-3.png" alt="overview-pages-3" type="image/x-icon">
    <link rel="shortcut icon" href="https://img.icons8.com/stickers/100/overview-pages-3.png" alt="overview-pages-3" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link href="assets/css.css">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet"/>

    <title>Todo List Application</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>

<?php
if (!isset($_SESSION['username'])) { ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary p-2 mb-5">
        <!-- Container wrapper -->
        <div class="container">
            <!-- Navbar brand -->
            <a class="navbar-brand me-2" href="https://mdbgo.com/">
                <img width="30" height="30" src="https://img.icons8.com/stickers/100/overview-pages-3.png" alt="overview-pages-3"/>
            </a>

            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <a data-mdb-ripple-init type="button" href="../view/signin_view.php" id="login" class="btn btn-secondary px-3 me-2">
                        Signin
                    </a>
                    <a data-mdb-ripple-init type="button" href="../view/signup_view.php" id="signup" class="btn btn-secondary me-2">
                        Sign up for free
                    </a>
                </div>
            </div>

        </div>
    </nav>
    <!-- Navbar -->

<?php } else { ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary p-2 mb-5">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Navbar brand -->
                <a class="navbar-brand mt-2 mt-lg-0" href="#">
                    <img width="30" height="30" src="https://img.icons8.com/stickers/100/overview-pages-3.png" alt="overview-pages-3"/>
                </a>
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../view/list_task_todo_view.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../view/list_task_todo_view_normal.php">List Todo Tasks</a>
                    </li>
                </ul>
                <!-- Left links -->
            </div>
            <!-- Collapsible wrapper -->

            <!-- Right elements -->
            <div class="d-flex align-items-center">

                <!-- Notifications -->
                <div class="dropdown">
                    <a
                            data-mdb-dropdown-init
                            class="text-reset me-3 dropdown-toggle hidden-arrow"
                            href="#"
                            id="navbarDropdownMenuLink"
                            role="button"
                            aria-expanded="false"
                    >
                        <i class="fas fa-bell"></i>
                        <span class="badge rounded-pill badge-notification bg-danger">1</span>
                    </a>
                    <ul
                            class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="navbarDropdownMenuLink"
                    >
                        <li>
                            <a class="dropdown-item" href="#">Some news</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Another news</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </li>
                    </ul>
                </div>
                <ul class="navbar-nav">
                    <!-- Avatar -->
                    <li class="nav-item dropdown">
                        <a
                                data-mdb-dropdown-init
                                class="nav-link dropdown-toggle d-flex align-items-center"
                                href="#"
                                id="navbarDropdownMenuLink"
                                role="button"
                                aria-expanded="false"
                        >
                            <img
                                    src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/img (31).webp"
                                    class="rounded-circle"
                                    height="22"
                                    alt="Portrait of a Woman"
                                    loading="lazy"
                            />
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="#">Welcome: <?php echo $_SESSION['username'] ?></a>
                            </li>
                            <li>
                                <a class="dropdown-item" id="changePassword" href="#">Change Password</a>
                            </li>
                            <li>
                                <a class="dropdown-item" id="deletedAccount" href="#">Deleted Account</a>
                            </li>
                            <li>
                                <a class="dropdown-item" id="logout" href="#">Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- Right elements -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->

<!--    Hanlder action tab button dropdown-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // handler action logout out
            $('#logout').click(function(event) {
                event.preventDefault();
                $.ajax({
                    url: '../controllers/home_controller.php',
                    method: 'POST',
                    data: {action: 'logout'},
                    success: function(response) {
                        window.location.href = '../view/signin_view.php';
                    },
                    error: function(xhr, status, error) {
                        window.location.href = '../templates/404_page_notfound.php';
                    }
                });
            });

            // handler action login
            $('#changePassword').click(function(event) {
                event.preventDefault();
                window.location.href = '../view/change_password_view.php';
            });

            $('#deletedAccount').click(function(event) {
                event.preventDefault();
                window.location.href = '../view/deleted_account_view.php';
            });
        });
    </script>

<?php } ?>

