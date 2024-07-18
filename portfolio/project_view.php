    <?php
    include "includes/functions.php";

    $ok_project_view = "accueil";
    $not_ok_project_view = "accueil";

    try {
        $get_settings = $db->query("SELECT * FROM settings");
        $result_get_settings = $get_settings->fetch(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        var_dump($error_db);
        die();
    }

    if (!empty($_GET["id"])) {

        $project_id = htmlspecialchars($_GET["id"]);

        try {
            $get_project = $db->query("SELECT
            projects.*,
            GROUP_CONCAT(categories.name)
            FROM
            projects
            INNER JOIN categories ON FIND_IN_SET(
            categories.id,
            projects.categories
            )
            WHERE
            projects.id = $project_id");

            $result_get_project = $get_project->fetch(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            var_dump($error_db);
            header("Location: " . $template_url . "$not_ok_project_view?error=$error_db");
            die();
        };

        if (empty($result_get_project["id"])) {
            header("location: $template_url" . "$not_ok_project_view?error=invalid_project_id");
            die();
        }

        try {
            $get_previous_project = $db->query("SELECT * FROM projects WHERE projects.id < $project_id ORDER BY id DESC
            LIMIT 1");
            $result_get_previous_project = $get_previous_project->fetch(PDO::FETCH_ASSOC);

            $get_next_project = $db->query("SELECT * FROM projects WHERE projects.id > $project_id ORDER BY id ASC LIMIT 1");
            $result_get_next_project = $get_next_project->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            var_dump($error_db);
        }

        $picture_list = explode(",", $result_get_project["picture_list"]);

        $meta_description = $result_get_project["description"];
        $page_title = $result_get_settings["meta_title_homepage"] . " - " . $result_get_project["title"];
        include "header.php";
    ?>
        <div class="background">
            <div class="project-view-gutter"></div>

            <div id="portfolio-wrapper" class="popup_content_area zoom-anim-dialog project_view">

                <div class="col-md-12 text-center ">
                    <p class="title fs-1 fw-bold  mb-0"><?= strtoupper($result_get_project["title"]) ?></p>
                </div>

                <div class="popup_modal_img">
                    <img src="<?= $result_get_project["picture"] ?>" alt="photo d'un projet" />
                </div>

                <div class="popup_modal_content">
                    <div class="portfolio_info">
                        <?php
                        if (!empty($result_get_project["link"])) {
                        ?>

                            <div class="portfolio_info_text">
                                <h2 class="title"></h2>
                                <div class="desc">
                                    <p>

                                    </p>
                                </div>
                                <a href="<?= htmlspecialchars($result_get_project["link"]) ?>" class="btn tj-btn-primary">live preview <i class="fal fa-arrow-right"></i></a>
                            </div>
                        <?php } ?>
                        <div class="portfolio_info_items">
                            <div class="key col-md-12">Catégories :</div>
                            <div class="value categories"><?= htmlspecialchars($result_get_project["GROUP_CONCAT(categories.name)"]); ?></div>
                        </div>
                    </div>

                    <div class="portfolio_gallery owl-carousel">

                        <?php
                        foreach ($picture_list as $picture_link) {
                        ?>
                            <div class="gallery_item">
                                <img src="<?= $picture_link; ?>" alt="photo d'un projet" class="project_picture" />
                            </div>
                        <?php } ?>

                    </div>

                    <div class="portfolio_description">
                        <h2 class="title">Project Description</h2>
                        <div class="desc">
                            <?= $result_get_project["description"] ?>
                        </div>
                    </div>

                    <div class="portfolio_navigation">
                        <div class="navigation_item prev-project">
                            <?php
                            if (!empty($result_get_previous_project["id"])) {
                            ?>
                                <a href="<?= $template_url . "project/" . "project" . "-" . $result_get_previous_project["id"] . "-" . str_replace(" ", "", strtolower($result_get_previous_project["slug"]));  ?>" class="project">
                                    <i class="fal fa-arrow-left"></i>
                                    <div class="nav_project">
                                        <div class="label">Projet précédent</div>
                                        <h3 class="title"><?= $result_get_previous_project["title"]; ?></h3>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>

                        <div class="navigation_item next-project">
                            <?php
                            if (!empty($result_get_next_project["id"])) {
                            ?>

                                <a href="<?= $template_url . "project/" . "project" . "-" . $result_get_next_project["id"] . "-" . str_replace(" ", "", strtolower($result_get_next_project["slug"]));  ?>" class="project">
                                    <div class="nav_project">
                                        <div class="label">Projet suivant</div>
                                        <h3 class="title"><?= $result_get_next_project["title"]; ?></h3>
                                    </div>
                                    <i class="fal fa-arrow-right"></i>
                                </a>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php

    } else {
        try {
            $get_projects_count = $db->query("SELECT id FROM projects");
            $result_get_projects_count = $get_projects_count->fetchALL(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            var_dump($error_db);
        }

        // Pagination 
        @$page = $_GET["page"];
        empty($page) && $page = 1;
        $project_per_page = 6;
        $number_of_page = ceil(count($result_get_projects_count) / $project_per_page);
        $start = ($page - 1) * $project_per_page;

        try {
            $get_projects = $db->query("SELECT * FROM projects ORDER BY projects.id DESC LIMIT $start, $project_per_page");
            $result_get_projects = $get_projects->fetchALL(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            var_dump($error_db);
        }

        if (count($result_get_projects) === 0) {
            header("location:$template_url" . "project_view.php?page=1");
        }

        $meta_description = "Explorez les projets variés de Pixeven : développement web, design graphique, applications mobiles et plus encore. Découvrez comment je transforme des idées créatives en réalisations concrètes.";

        $page_title = "Tous nos projets";
        include "header.php";
    ?>

        <section class="portfolio-section" id="works-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <div class="section-header mt-4 text-center">
                                <h2 class="section-title wow fadeInUp" data-wow-delay=".3s">
                                    Tous Nos Travaux
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="portfolio-filter text-center wow fadeInUp" data-wow-delay=".5s">

                            <div class="portfolio-box wow fadeInUp" data-wow-delay=".6s">
                                <div class="portfolio-sizer"></div>
                                <div class="gutter-sizer"></div>
                                <div class="col-md-12">
                                    <?php
                                    $counter = 0;
                                    foreach ($result_get_projects as $project) {
                                        if ($project["deleted"] === 0) {
                                    ?>
                                            <div class="portfolio-item branding">
                                                <div class="image-box">
                                                    <img src="<?= $project["picture"] ?>" alt="photo d'un projet" />
                                                </div>
                                                <div class="content-box">
                                                    <a href="<?= $template_url . "project/" . "project" . "-" . $project["id"] . "-" . str_replace(" ", "", strtolower($project["slug"]));  ?>">
                                                        <h3 class="portfolio-title"><?= htmlspecialchars($project["title"]) ?></h3>
                                                        <p><?= htmlspecialchars($project["hook"]) ?></p>
                                                        <i class="flaticon-up-right-arrow"></i>
                                                    </a>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 d-flex justify-content-center ">
                        <?php
                        for ($i = 1; $i <= $number_of_page; $i++) {

                            if ($page != $i) {
                                echo "<a href=?page=$i>$i</a>&nbsp";
                            } else {
                                echo "<p class=current_page>$i</p> &nbsp";
                            }
                        }
                        ?>
                    </div>
        </section>
        </main>
    <?php
    }
    include "footer.php";
    ?>