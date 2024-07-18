    <!DOCTYPE html>
    <html class="no-js" lang="fr">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="<? $meta_description ?>" />

        <!-- Site Title -->
        <title><?= $page_title ?></title>

        <!-- Place favicon.ico in the root directory -->
        <link rel="apple-touch-icon" sizes="180x180" href=<?= $template_url . "assets/img/favicon/apple-touch-icon.png" ?>>
        <link rel="icon" type="image/png" sizes="32x32" href=<?= $template_url . "assets/img/favicon/favicon-32x32.png" ?>>
        <link rel="icon" type="image/png" sizes="16x16" href=<?= $template_url . "assets/img/favicon/favicon-16x16.png" ?>>
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css" integrity="sha512-NXUhxhkDgZYOMjaIgd89zF2w51Mub53Ru3zCNp5LTlEzMbNNAjTjDbpURYGS5Mop2cU4b7re1nOIucsVlrx9fA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- CSS here -->
        <link rel="stylesheet" href="<?= $template_url . 'assets/css/animate.min.css' ?>" />
        <link rel="stylesheet" href="<?= $template_url . 'assets/css/bootstrap.min.css' ?>" />
        <link rel=" stylesheet" href="<?= $template_url . 'assets/css/font-awesome-pro.min.css' ?>" />
        <link rel=" stylesheet" href="<?= $template_url . 'assets/css/flaticon_gerold.css' ?>" />
        <link rel=" stylesheet" href="<?= $template_url . 'assets/css/nice-select.css' ?>" />
        <link rel=" stylesheet" href="<?= $template_url . 'assets/css/backToTop.css' ?>" />
        <link rel=" stylesheet" href="<?= $template_url . 'assets/css/owl.carousel.min.css' ?>" />
        <link rel=" stylesheet" href="<?= $template_url . 'assets/css/odometer-theme-default.css' ?>" />
        <link rel=" stylesheet" href="<?= $template_url . 'assets/css/magnific-popup.css' ?>" />
        <link rel="stylesheet" href="<?= $template_url . 'assets/css/main.css'; ?>" />
        <link rel="stylesheet" href="<?= $template_url . 'assets/css/responsive.css'; ?>" />
        <link rel="stylesheet" href="<?= $template_url . 'assets/css/project-view.css'; ?>" />
        <link rel="stylesheet" href="<?= $template_url . 'assets/css/service_view.css'; ?>">
    </head>

    <body>
        <!-- start: Back To Top -->
        <div class="progress-wrap" id="scrollUp">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
        </div>
        <!-- end: Back To Top -->


        <!-- Preloader Area Start -->
        <div class="preloader">
            <svg viewBox="0 0 1000 1000" preserveAspectRatio="none">
                <path id="preloaderSvg" d="M0,1005S175,995,500,995s500,5,500,5V0H0Z"></path>
            </svg>

            <div class="preloader-heading">
                <div class="load-text">
                    <span>C</span>
                    <span>h</span>
                    <span>a</span>
                    <span>r</span>
                    <span>g</span>
                    <span>e</span>
                    <span>m</span>
                    <span>e</span>
                    <span>n</span>
                    <span>t</span>
                </div>
            </div>
        </div>
        <!-- Preloader Area End -->

        <!-- HEADER START -->
        <header class="tj-header-area header-absolute">
            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex flex-wrap align-items-center">
                        <div class="logo-box">
                            <a href="<?= $template_url . "accueil" ?>">
                                <img class="header-logo" src="<?= $template_url . "assets/img/logo/logo.jpg" ?>" alt="" />
                            </a>
                        </div>

                        <?php
                        if ($page_title === $result_get_settings["meta_title_homepage"]) {
                        ?>

                            <div class="header-menu">
                                <nav>
                                    <ul>
                                        <li><a href="#services-section">Services</a></li>
                                        <li><a href="#works-section">Réalisations</a></li>
                                        <li><a href="#skills-section">Compétences</a></li>
                                        <li><a href="#contact-section">Contact</a></li>
                                        <li><a href="<?= $dashboard_url ?>">Dashboard</a></li>
                                    </ul>
                                </nav>
                            </div>

                            <div class="header-button">
                                <a href="#contact-section" class="btn tj-btn-primary">Engagez nous!</a>
                            </div>


                            <div class="menu-bar d-lg-none">
                                <button>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="header-menu">
                                <nav>
                                    <ul>
                                        <li><a href="<?= $template_url . "accueil" ?>"><?= $result_get_settings["meta_title_homepage"] ?></a></li>
                                        <li><a href="<?= $template_url . "projects" ?>">Tous les projets</a></li>
                                        <li><a href="<?= $dashboard_url ?>">Dashboard</a></li>

                                    </ul>
                                </nav>
                            </div>
                            <div class="header-button">

                            </div>


                            <div class="menu-bar d-lg-none">
                                <button>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
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
            };
            ?>
        </header>
        <header class="tj-header-area header-2 header-sticky sticky-out">
            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex flex-wrap align-items-center">
                        <div class="logo-box">
                            <a href="<?= $template_url . "accueil" ?>">
                                <img class="header-logo" src="<?= $template_url . "assets/img/logo/logo.jpg" ?>" alt="logo graphiste" />
                            </a>
                        </div>
                        <?php
                        if ($page_title === $result_get_settings["meta_title_homepage"]) {
                        ?>

                            <div class="header-menu">
                                <nav>
                                    <ul>
                                        <li><a href="#services-section">Services</a></li>
                                        <li><a href="#works-section">Réalisations</a></li>
                                        <li><a href="#skills-section">Compétences</a></li>
                                        <li><a href="#contact-section">Contact</a></li>
                                        <li><a href="<?= $dashboard_url ?>">Dashboard</a></li>


                                    </ul>
                                </nav>
                            </div>
                            <div class="header-button">
                                <a href="#contact-section" class="btn tj-btn-primary">Engagez nous!</a>
                            </div>
                            <div class="menu-bar d-lg-none">
                                <button>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="header-menu">
                                <nav>
                                    <ul>
                                        <li><a href="<?= $template_url . "accueil" ?>"><?= $result_get_settings["meta_title_homepage"] ?></a></li>
                                        <li><a href="<?= $template_url . "projects" ?>">Tous les projets</a></li>
                                        <li><a href="<?= $dashboard_url ?>">Dashboard</a></li>
                                    </ul>
                                </nav>
                            </div>

                            <div class="header-button">

                            </div>

                            <div class="menu-bar d-lg-none">
                                <button>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>


                        <?php } ?>

                    </div>
                </div>
            </div>
        </header>



        <!-- HEADER END -->