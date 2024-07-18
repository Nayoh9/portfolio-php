    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $page_title ?></title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css" integrity="sha512-NXUhxhkDgZYOMjaIgd89zF2w51Mub53Ru3zCNp5LTlEzMbNNAjTjDbpURYGS5Mop2cU4b7re1nOIucsVlrx9fA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Place favicon.ico in the root directory -->
        <link rel="apple-touch-icon" sizes="180x180" href=<?= $dashboard_url_url . "assets/images/favicon/apple-touch-icon.png" ?>>
        <link rel="icon" type="image/png" sizes="32x32" href=<?= $dashboard_url . "assets/images/favicon/favicon-32x32.png" ?>>
        <link rel="icon" type="image/png" sizes="16x16" href=<?= $dashboard_url . "assets/images/favicon/favicon-16x16.png" ?>>
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <!-- CSS HERE -->
        <link rel="stylesheet" href="<?= $dashboard_url . "assets/css/dashboard.css" ?>">
        <link rel="stylesheet" href="<?= $dashboard_url . "assets/css/responsive.css" ?>">
    </head>

    <?php
    // Redirection form
    if (!empty($page_title)) {

        switch ($page_title) {
            case 'Mon projet':
                $action = "project.php";
                break;

            case 'Ma catégorie':
                $action = "category.php";
                break;

            default:
                $action;
                break;
        }
    }
    ?>

    <body>
        <div class="container">
            <div class="wrapper">
                <header class="row">
                    <h1 class="col-md-12 text-center"><?= $page_title ?></h1>

                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="container-fluid">
                            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav mb-2 col-md-12 justify-content-center mb-lg-0 ju">
                                    <li class="nav-item">
                                        <a class="nav-link" href="create_project.php">Ajouter un projet</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="create_category.php">Ajouter une catégorie</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="project.php">Consulter mes projets</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="category.php">Consulter mes categories</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="settings.php">Mes paramètres</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= $template_url;  ?>">Portfolio</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>


                </header>

                <?php
                if (!empty($_GET["error"])) {
                    $error = htmlspecialchars(parse_error($_GET["error"]));

                    echo "<script>
                document.addEventListener('DOMContentLoaded', () => {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        text: '$error',
                        timeout: 5000,
                    }).show();
                });
            </script>";
                };

                if (!empty($_GET["success"])) {

                    $success = htmlspecialchars(parse_success($_GET["success"]));

                    echo "<script>
                    document.addEventListener('DOMContentLoaded', () => {
                        new Noty({
                            type: 'success',
                            layout: 'topRight',
                            text: '$success',
                            timeout: 5000,
                        }).show();
                    });
                </script>";
                }
                ?>