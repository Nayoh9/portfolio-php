    <?php
    include "includes/functions.php";
    $ok_settings = $dashboard_url . "settings.php";
    $not_ok_settings = $dashboard_url . "settings.php";

    $error = false;


    if (!empty($_POST)) {

        $projects_to_display = intval($_POST["projects_to_display"]);

        if (empty($_POST["profile_title"])) {
            $error = "invalid_profile_title";
        }

        if (empty($_POST["meta_title"])) {
            $error = "invalid_meta_title";
        }

        if (empty($_POST["meta_description"])) {
            $error = "invalid_meta_description";
        }

        if (!is_int($projects_to_display) || $projects_to_display > 6) {
            $error = "missing_number_projects";
        }

        if (empty($_POST["years_of_experience"])) {
            $error = "missing_years";
        }

        if (empty($_POST["achieved_projects"])) {
            $error = "missing_achieved_projects";
        }

        if (empty($_POST["satisfied_customers"])) {
            $error = "missing_satisfied_customers";
        }

        if (empty($_POST["settings_values"])) {
            $error = "cant_find_var";
        }

        if (!empty($error)) {
            header("location:$not_ok_settings?error=$error");
            die();
        }

        $stats =  json_encode([
            "years_of_experience" => htmlspecialchars($_POST["years_of_experience"]),
            "achieved_projects" => htmlspecialchars($_POST["achieved_projects"]),
            "satisfied_customers" => htmlspecialchars($_POST["satisfied_customers"])
        ]);

        $_POST["settings_values"] = explode(",", $_POST["settings_values"]);

        $current_profile_picture = $_POST["settings_values"][0];
        $id = htmlspecialchars($_POST["settings_values"][1]);
        $profile_picture_uid = uniqid("profile_picture_");
        $profile_title = $_POST["profile_title"];
        $meta_title_homepage = htmlspecialchars($_POST["meta_title"]);
        $meta_description_homepage = htmlspecialchars($_POST["meta_description"]);

        if (!empty($_FILES["profile_picture"]["tmp_name"])) {

            if ($_FILES["profile_picture"]["size"] > 5000000) {
                $error = "invalid_file_size";
                header("location: $not_ok_settings?error=$error");
                die();
            };

            switch ($_FILES["profile_picture"]["type"]) {
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/png':
                    try {

                        $result = $s3Client->putObject([
                            'Bucket' => $bucket,
                            'Key'    => $profile_picture_uid,
                            'Body'   => fopen($_FILES["profile_picture"]['tmp_name'], 'r'),
                            'ContentType' => $_FILES["profile_picture"]["type"],
                            'ACL' => 'public-read'

                        ]);

                        $profile_picture = $result["ObjectURL"];
                    } catch (\Throwable $e) {
                        $error = "something_went_wrong_during_the_file_upload";
                    }
                    break;

                default:
                    $error = "image_format_not_allowed";
                    break;
            }
        } else {
            $profile_picture = $_POST["settings_values"][0];
        }

        $socials = json_encode([
            "social_1" => [
                'icon' => htmlspecialchars($_POST["social_1"]["icon"]),
                'link' => htmlspecialchars($_POST["social_1"]["link"]),
            ],
            "social_2" => [
                'icon' => htmlspecialchars($_POST["social_2"]["icon"]),
                'link' => htmlspecialchars($_POST["social_2"]["link"]),
            ],

            "social_3" => [
                'icon' => htmlspecialchars($_POST["social_3"]["icon"]),
                'link' => htmlspecialchars($_POST["social_3"]["link"]),
            ],

            "social_4" => [
                'icon' => htmlspecialchars($_POST["social_4"]["icon"]),
                'link' => htmlspecialchars($_POST["social_4"]["link"]),
            ],
        ]);


        if (!empty($error)) {
            header("location: $not_ok_settings?error=$error");
            die();
        }

        try {
            $update_settings = $db->prepare(
                "UPDATE 
                            settings
                        SET 
                            profile_picture = :profile_picture,
                            profile_picture_uid = :profile_picture_uid,
                            profile_title = :profile_title,
                            socials = :socials,
                            stats = :stats,
                            projects_to_display = :projects_to_display,
                            meta_title_homepage = :meta_title_homepage,
                            meta_description_homepage = :meta_description_homepage
                        WHERE
                            settings.id = :id"
            );

            $update_settings->execute([
                'id' => $id,
                'profile_picture' => $profile_picture,
                'profile_picture_uid' => $profile_picture_uid,
                'profile_title' => $profile_title,
                'socials' => $socials,
                'stats' => $stats,
                'projects_to_display' => $projects_to_display,
                'meta_title_homepage' => $meta_title_homepage,
                'meta_description_homepage' => $meta_description_homepage
            ]);
        } catch (PDOException $e) {
            header("location: $not_ok_settings?error=$error_db");
            die();
        }

        header("location: $ok_settings?success=settings_updated");
    } else {
        $page_title = "Mes paramètres";
        include "header.php";

        try {
            $get_settings = $db->query('SELECT * FROM settings');
            $result_get_settings = $get_settings->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            var_dump($error_db);
            die();
        }

        if (!empty($result_get_settings)) {
            $stats = json_decode($result_get_settings["stats"]);
            $settings_values = $result_get_settings["profile_picture"] . ',' . $result_get_settings["id"] . ',' . $result_get_settings["profile_picture_uid"];

            $socials = json_decode($result_get_settings["socials"], true);
        }

        try {
            $get_socials = $db->query("SELECT * FROM socials");
            $result_get_socials = $get_socials->fetchALL(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            var_dump($error_db);
            die();
        }

    ?>

        <div class="row">
            <form method="POST" action="settings.php" class="col-md-12 d-flex flex-column align-items-center " enctype="multipart/form-data">

                <div class="col-lg-8 col-12 mb-3 text-center">
                    <label for="file_to_upload" class="form-label">Ma photo de profil
                        <div id="preview" class="text-center">
                            <img id="preview_child" class="form_asset rounded-1" src="<?= $result_get_settings["profile_picture"]; ?>" alt="Photo de profil">
                        </div>
                    </label>

                    <input class="col-md-6 mt-2 form-control" name="profile_picture" type="file" id="file_to_upload" accept="image/png, image/jpeg, image/jpg" value="<?= $result_get_settings["profile_picture"]; ?>">
                    <p class="fs-6 fw-bold text-center">Taille maximum du fichier : 5 MO</p>
                </div>

                <div class="col-lg-8 col-12">
                    <label for="textarea" class="form-label">Mon titre principal</label>
                    <textarea name="profile_title" class="form-control" rows="10"><?= htmlspecialchars($result_get_settings["profile_title"]); ?></textarea>
                </div>

                <div class="col-lg-8 col-12 ">
                    <label for="meta_title" class="form-label mt-3">Titre du portfolio<b>(sera affiché dans l'onglet du navigateur) </b></label>
                    <input type="text" class="form-control" name="meta_title" id="meta_title" value="<?= $result_get_settings["meta_title_homepage"] ?>" required>
                </div>

                <div class="col-lg-8 col-12 ">
                    <label for="projects_to_display" class="form-label mt-3">Nombre de projets à afficher</label>
                    <input type="number" min="0" max="6" class="form-control" name="projects_to_display" id="projects_to_display" value="<?= $result_get_settings["projects_to_display"] ?>" required>
                </div>


                <div class="col-lg-8 col-12">
                    <label for="meta_description" class="form-label mt-3">Description du portfolio <b>(sera affiché en dessous du nom du site dans le moteur de recherche)</b></label>
                    <input type="text" class="form-control" name="meta_description" id="meta_description" value="<?= $result_get_settings["meta_description_homepage"]; ?>" required>
                </div>


                <div class="col-lg-8 col-12">
                    <h2 class="text-center mb-4 mt-4">Statistiques</h2>
                    <div class="d-flex justify-content-center">
                        <div class="col-md-4 me-1 text-center">
                            <label class="form-label" for="years_of_experience">Années d'experience</label>
                            <input class="form-control text-center" id="years_of_experience" name="years_of_experience" step="0.5" type="number" min="1" value="<?= htmlspecialchars($stats->years_of_experience); ?>" required>
                        </div>

                        <div class="col-md-4 ms-1 text-center">
                            <label class="form-label" for="achieved_projects">Projets réalisés</label>
                            <input class="form-control text-center" id="achieved_projects" name="achieved_projects" step="0.5" type="number" min="1" value="<?= htmlspecialchars($stats->achieved_projects); ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center  text-center ">
                        <div class="col-md-8 justify-content-center ">
                            <label class="form-label" for="satisfied_customers">Clients satisfaits</label>
                            <input class="form-control text-center" id="satisfied_customers" name="satisfied_customers" step="0.5" type="number" min="1" value="<?= htmlspecialchars($stats->satisfied_customers); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <h2 class="mb-4 mt-4 text-center">Réseaux sociaux</h2>
                    <div class="d-flex justify-content-center ">
                        <div class="col-md-4 me-2">
                            <label class="form-label" for="social_icon_1">Icône du réseau 1</label>
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="social_1[icon]">
                                <option value="">Sélectionnez un réseau social</option>
                                <?php
                                if (!empty($result_get_socials)) {

                                    foreach ($result_get_socials as $social) {
                                        $is_selected = str_contains($socials["social_1"]["icon"], $social["name"]) ? "selected" : "";
                                ?>
                                        <option <?= $is_selected; ?> value="fa-brands fa-<?= $social["name"]; ?>">
                                            <?= $social["name"]; ?>
                                        </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>

                            <label class="form_label" for="social_link_1">Lien du réseau 1</label>
                            <input class="form-control" id="social_link_1" name="social_1[link]" placeholder="ex : https://instagram.com/pseudo" value="<?= $socials["social_1"]["link"] ?>" type="text">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="social_icon_2">Icône du réseau 2</label>
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="social_2[icon]">
                                <option value="">Sélectionnez un réseau social</option>

                                <?php
                                if (!empty($result_get_socials)) {

                                    foreach ($result_get_socials as $social) {
                                        $is_selected = str_contains($socials["social_2"]["icon"], $social["name"]) ? "selected" : "";
                                ?>
                                        <option <?= $is_selected; ?> value="fa-brands fa-<?= $social["name"]; ?>">
                                            <?= $social["name"]; ?>
                                        </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>

                            <label class="form_label" for="social_link_2">Lien du réseau 2</label>
                            <input class="form-control" id="social_link_2" name="social_2[link]" placeholder="ex : https://instagram.com/pseudo" value="<?= $socials["social_2"]["link"]  ?>" type="text">
                        </div>
                    </div>

                    <div class="d-flex mt-4 justify-content-center">
                        <div class="col-md-4 me-2">
                            <label class="form-label" for="social_icon_3">Icône du réseau 3</label>

                            <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="social_3[icon]">
                                <option value="">Sélectionnez un réseau social</option>
                                <?php
                                if (!empty($result_get_socials)) {

                                    foreach ($result_get_socials as $social) {
                                        $is_selected = str_contains($socials["social_3"]["icon"], $social["name"]) ? "selected" : "";
                                ?>
                                        <option <?= $is_selected; ?> value="fa-brands fa-<?= $social["name"]; ?>">
                                            <?= $social["name"]; ?>
                                        </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>

                            <label class="form_label" for="social_link_3">Lien du réseau 3</label>
                            <input class="form-control" id="social_link_3" name="social_3[link]" placeholder="ex : https://instagram.com/pseudo" value="<?= $socials["social_3"]["link"]; ?>" type="text">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="social_icon_4">Icône du réseau 4</label>

                            <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="social_4[icon]">
                                <option value="">Sélectionnez un réseau social</option>
                                <?php
                                if (!empty($result_get_socials)) {

                                    foreach ($result_get_socials as $social) {
                                        $is_selected = str_contains($socials["social_4"]["icon"], $social["name"]) ? "selected" : "";
                                ?>
                                        <option <?= $is_selected; ?> value="fa-brands fa-<?= $social["name"]; ?>">
                                            <?= $social["name"]  ?>
                                        </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>

                            <label class="form_label" for="social_link_4">Lien du réseau 4</label>
                            <input class="form-control" id="social_link_4" name="social_4[link]" placeholder="ex : https://instagram.com/pseudo" value="<?= $socials["social_4"]["link"]; ?>" type="text">
                        </div>
                    </div>
                </div>
        </div>

        <div class="my-3 col-md-12 d-flex justify-content-center">
            <button type="submit" class="btn btn-primary" name="settings_values" value="<?= $settings_values; ?>">
                Appliquer
            </button>
        </div>

        </form>
        </div>

    <?php
    }
    include "footer.php";
    ?>