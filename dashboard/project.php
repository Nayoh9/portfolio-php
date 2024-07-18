    <?php
    include "includes/functions.php";

    $ok_project = $dashboard_url . "project.php";
    $not_ok_project = $dashboard_url . "project.php";

    $error = false;


    if (!empty($_POST["direction"])) {

        switch ($_POST["direction"]) {
            case 'create':

                if (empty($_POST["project_title"])) {
                    $error = "no_project_title";
                };

                if (empty($_POST["project_description"])) {
                    $error = "no_project_description";
                }

                if (empty($_POST["project_categories"])) {
                    $error = "no_categories_selected";
                }

                if (empty($_POST["project_hook"])) {
                    $error = "no_project_hook";
                }

                // if (empty($_POST["project_link"])) {
                //     $error = "no_project_link";
                // }

                if (!empty($_FILES["project_pictures"]["tmp_name"]["0"])) {

                    $file_counter = count($_FILES["project_pictures"]["tmp_name"]);
                    $files_array = [];

                    for ($i = 0; $i < $file_counter; $i++) {
                        $files_array[] = [
                            'name' => $_FILES["project_pictures"]["name"][$i],
                            'type' => $_FILES["project_pictures"]["type"][$i],
                            'tmp_name' => $_FILES["project_pictures"]["tmp_name"][$i],
                            'error' => $_FILES["project_pictures"]["error"][$i],
                            'size' => $_FILES["project_pictures"]["size"][$i],
                        ];
                    }

                    $picture_list = [];

                    foreach ($files_array as $file) {
                        if ($file["size"] > 5000000) {
                            $error = "invalid_file_size";
                            header("location: $not_ok_project?error=$error");
                            die();
                        }

                        switch ($file["type"]) {
                            case 'image/jpeg':
                            case 'image/jpg':
                            case 'image/png':
                                try {
                                    $result = $s3Client->putObject([
                                        'Bucket' => $bucket,
                                        'Key'    => $picture_uid = uniqid("project_list_img_"),
                                        'Body'   => fopen($file['tmp_name'], 'r'),
                                        'ContentType' => $file["type"],
                                        'ACL' => 'public-read'
                                    ]);

                                    array_push($picture_list, $result["ObjectURL"]);
                                } catch (\Throwable $e) {
                                    $error = "something_went_wrong_during_the_file_upload";
                                }
                                break;

                            default:
                                $error = "image_format_not_allowed";
                                break;
                        }
                    }
                } else {
                    $error = "no_file_downloaded";
                    header("location: $not_ok_project?error=$error");
                    die();
                }

                if (!empty($_FILES["project_img"]["tmp_name"])) {

                    if ($_FILES["project_img"]["size"] > 5000000) {

                        $error = "invalid_file_size";
                        header("location: $not_ok_project?error=$error");
                        die();
                    };

                    switch ($_FILES["project_img"]["type"]) {
                        case 'image/jpeg':
                        case 'image/jpg':
                        case 'image/png':
                            try {

                                $result = $s3Client->putObject([
                                    'Bucket' => $bucket,
                                    'Key'    => $picture_uid = uniqid("project_img_"),
                                    'Body'   => fopen($_FILES["project_img"]['tmp_name'], 'r'),
                                    'ContentType' => $_FILES["project_img"]["type"],
                                    'ACL' => 'public-read'
                                ]);

                                $picture = $result["ObjectURL"];
                            } catch (\Throwable $e) {
                                $error = "something_went_wrong_during_the_file_upload";
                            }
                            break;

                        default:
                            $error = "image_format_not_allowed";
                            break;
                    }
                } else {
                    $error = "no_file_downloaded";
                    header("location: $not_ok_project?error=$error");
                    die();
                }

                $title = htmlspecialchars($_POST["project_title"]);
                $picture = $result["ObjectURL"];
                $picture_list = implode(",", $picture_list);
                $description = $_POST["project_description"];
                $categories = implode(",", $_POST["project_categories"]);
                $link = htmlspecialchars($_POST["project_link"]);
                $hook = htmlspecialchars($_POST["project_hook"]);
                $slug = $title;

                if (empty($error)) {
                    try {
                        $create_project = $db->prepare("INSERT INTO projects (
                            title,
                            picture, 
                            picture_list,
                            picture_uid,
                            description, 
                            categories,
                            link,
                            hook,
                            slug
                            ) VALUES (
                            :title,
                            :picture,
                            :picture_list,
                            :picture_uid,
                            :description,
                            :categories,
                            :link,
                            :hook,
                            :slug
                        )");

                        $create_project->execute([
                            'title' => $title,
                            'picture' => $picture,
                            'picture_list' => $picture_list,
                            'picture_uid' => $picture_uid,
                            'description' => $description,
                            'categories' => $categories,
                            'link' => $link,
                            'hook' => $hook,
                            'slug' => $slug
                        ]);
                    } catch (PDOException $e) {
                        echo $error_db;
                        header("location: $not_ok_project?error=$error_db");
                        die();
                    }

                    header("location: $ok_project?success=project_created");
                    die();
                } else {
                    header("location: $not_ok_project?error=$error");
                    die();
                };
                break;

            case 'modify':

                if (empty($_POST["project_title"])) {
                    $error = "no_project_title";
                }

                if (empty($_POST["project_categories"])) {
                    $error = "no_categories_selected";
                }

                if (empty($_POST["project_description"])) {
                    $error = "no_project_description";
                }

                if (empty($_POST["project_id"])) {
                    $error = "cant_find_var";
                }

                if (empty($_POST["project_hook"])) {
                    $error = "no_project_hook";
                }

                // if (empty($_POST["project_link"])) {
                //     $error = "no_project_link";
                // }

                if (!empty($error)) {
                    header("location:$not_ok_project?error=$error");
                    die();
                }

                $id = $_POST["project_id"];

                try {
                    $get_project = $db->query("SELECT * FROM projects WHERE projects.id = $id");
                    $result_get_project = $get_project->fetch(PDO::FETCH_ASSOC);
                } catch (\PDOException $e) {
                    var_dump($error_db);
                    die();
                }


                if (!empty($_FILES["project_img"]["tmp_name"])) {

                    if ($_FILES["project_img"]["size"] > 5000000) {

                        $error = "invalid_file_size";
                        header("location: $not_ok_project?error=$error");
                        die();
                    };

                    switch ($_FILES["project_img"]["type"]) {
                        case 'image/jpeg':
                        case 'image/jpg':
                        case 'image/png':
                            try {

                                $result = $s3Client->putObject([
                                    'Bucket' => $bucket,
                                    'Key' => $picture_uid = uniqid("project_img_"),
                                    'Body' => fopen($_FILES["project_img"]['tmp_name'], 'r'),
                                    'ContentType' => $_FILES["project_img"]["type"],
                                    'ACL' => 'public-read'
                                ]);

                                $picture = $result["ObjectURL"];
                            } catch (\Throwable $e) {

                                var_dump($e);
                                die();
                                $error = "something_went_wrong_during_the_file_upload";
                            }
                            break;

                        default:
                            $error = "image_format_not_allowed";
                            break;
                    }
                }

                if (!empty($_FILES["project_pictures"]["tmp_name"]["0"])) {

                    $file_counter = count($_FILES["project_pictures"]["tmp_name"]);
                    $files_array = [];

                    for ($i = 0; $i < $file_counter; $i++) {
                        $files_array[] = [
                            'name' => $_FILES["project_pictures"]["name"][$i],
                            'type' => $_FILES["project_pictures"]["type"][$i],
                            'tmp_name' => $_FILES["project_pictures"]["tmp_name"][$i],
                            'error' => $_FILES["project_pictures"]["error"][$i],
                            'size' => $_FILES["project_pictures"]["size"][$i],
                        ];
                    }

                    $picture_list = [];

                    foreach ($files_array as $file) {
                        if ($file["size"] > 5000000) {
                            $error = "invalid_file_size";
                            header("location: $not_ok_project?error=$error");
                            die();
                        }

                        switch ($file["type"]) {
                            case 'image/jpeg':
                            case 'image/jpg':
                            case 'image/png':
                                try {
                                    $result = $s3Client->putObject([
                                        'Bucket' => $bucket,
                                        'Key'    => $picture_uid = uniqid("project_list_img_"),
                                        'Body'   => fopen($file['tmp_name'], 'r'),
                                        'ContentType' => $file["type"],
                                        'ACL' => 'public-read'
                                    ]);

                                    array_push($picture_list, $result["ObjectURL"]);
                                } catch (\Throwable $e) {
                                    $error = "something_went_wrong_during_the_file_upload";
                                }
                                break;

                            default:
                                $error = "image_format_not_allowed";
                                break;
                        }
                    }

                    $picture_list = implode(",", $picture_list);
                }

                if (!empty($error)) {
                    header("location: $not_ok_project?error=$error");
                    die();
                }

                empty($picture) && $picture = $result_get_project["picture"];
                empty($picture_list) && $picture_list = $result_get_project["picture_list"];
                empty($picture_uid) && $picture_uid = $result_get_project["picture_uid"];
                $title = htmlspecialchars($_POST["project_title"]);
                $description = $_POST["project_description"];
                $categories = implode(",", $_POST["project_categories"]);
                $link = htmlspecialchars($_POST["project_link"]);
                $hook = $_POST["project_hook"];
                $slug = $title;
                $last_modification = date("Y-m-d H:i:s");

                try {
                    $update_project = $db->prepare(
                        "UPDATE 
                                projects 
                            SET 
                                title = :title,
                                picture = :picture,
                                picture_list = :picture_list,
                                picture_uid = :picture_uid,
                                description = :description,
                                categories = :categories,
                                link = :link,
                                slug = :slug,
                                hook = :hook,
                                last_modification = :last_modification
                            WHERE 
                                projects.id = :id"
                    );

                    $update_project->execute([
                        'title' => $title,
                        'picture' => $picture,
                        'picture_list' => $picture_list,
                        'picture_uid' => $picture_uid,
                        'description' => $description,
                        'categories' => $categories,
                        'link' => $link,
                        'slug' => $slug,
                        'hook' => $hook,
                        'last_modification' => $last_modification,
                        'id' => $id
                    ]);
                } catch (PDOException $e) {
                    header("location: $not_ok_project?error=$error_db");
                    die();
                }

                header("location: $ok_project?success=project_modified");
                die();
                break;

            case "delete":

                if (empty($_POST["project_id"])) {
                    $error = "cant_find_var";
                    header("location:$not_ok_project?error=$error");
                    die();
                }

                $id = htmlspecialchars($_POST["project_id"]);

                try {
                    $deleted_project = $db->query(
                        "UPDATE 
                        projects 
                    SET 
                        projects.deleted = 1
                    WHERE 
                        projects.id = $id"
                    );
                } catch (PDOException $e) {
                    $error = "something_went_wrong_while_deleting_project";
                }

                if (!empty($error)) {
                    header("location: $not_ok_project?error=$error");
                    die();
                }

                header("location: $ok_project?success=project_deleted");
                break;

            case 'restore':

                if (empty($_POST["project_id"])) {
                    $error = "cant_find_var";
                    header("location: $not_ok_project?error=$error");
                    die();
                }

                $id = htmlspecialchars($_POST["project_id"]);

                try {
                    $restore_project = $db->query(
                        "UPDATE 
                    projects
                SET 
                    deleted = 0
                WHERE 
                    projects.id = $id"
                    );
                } catch (\PDOException $e) {
                    var_dump($error_db);
                    header("location: $not_ok_project?error=$error");
                    die();
                }

                header("location:$ok_project?success=project_restored");
                die();
                break;

            default:
        }
    }

    if (!empty($_GET["id"])) {

        $project_id = (int) htmlspecialchars($_GET["id"]);

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
            $get_project->closeCursor();
        } catch (PDOException $e) {
            header("location: $not_ok_project?error=$error_db");
            die();
        }

        if (empty($result_get_project["id"])) {
            $error = "invalid_project_id";
            header("location:$not_ok_project?error=$error");
            die();
        }

        $picture_list = explode(",", $result_get_project["picture_list"]);

        try {
            $get_categories = $db->query("SELECT * FROM categories");
            $result_get_categories = $get_categories->fetchALL(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            header("location: $not_ok_project?error=$error_db");
            die();
        }

        $page_title = "Mon projet";
        include "header.php";
    ?>

        <div class="row d-flex project">
            <form method="POST" class="col-md-12 d-flex flex-column align-items-center" id="modify_target_form" enctype="multipart/form-data">

                <input type="hidden" name="direction" id="direction" value="">
                <input type="hidden" name="project_id" value="<?= $project_id; ?>">

                <div class="col-lg-8 col-12">
                    <label for="project_title" class="form-label">Titre du projet :</label>
                    <input class="form-control col-auto" id="project_title" name="project_title" placeholder="ex : Goodtime.." value="<?= htmlspecialchars($result_get_project["title"]); ?>" required>
                </div>

                <div class="col-lg-8 col-12 text-center">
                    <label for="file_to_upload">
                        <div id="preview">
                            <img id="preview_child" class="form_asset rounded-1" src="<?= $result_get_project["picture"]; ?>" alt="Photo/vidéo d'un projet">
                        </div>
                    </label>

                    <input class="col-md-6 mt-2 accordion form-control" name="project_img" type="file" id="file_to_upload" accept="image/png, image/jpeg, image/jpg">
                </div>



                <div class="col-lg-8 col-12">
                    <label for="files_to_upload" class="form-label">Photos du projet</label>
                    <input type="file" class="form-control" name="project_pictures[]" id="files_to_upload" accept="image/png, image/jpeg, image/jpg" multiple>

                    <p class="fs-6 fw-bold mb-0">Taille maximum des fichiers: 5 MO</p>
                </div>

                <div class="text-center mt-4">
                    <p class="mb-0">Catégories :</p>
                    <p><?= htmlspecialchars($result_get_project["GROUP_CONCAT(categories.name)"]);  ?></p>
                </div>

                <div class="col-lg-8 col-12">
                    <select class="project_categories_list form-select mb-3" aria-label="multiple select example" size="3" name="project_categories[]" multiple required>
                        <?php
                        if (!empty($result_get_categories)) {
                            foreach ($result_get_categories as $category) {
                                if ($category["deleted"] === 0) {
                                    $is_selected = str_contains($result_get_project["categories"], $category["id"]) ? 'selected' : '';
                        ?>
                                    <option <?= $is_selected ?> value="<?= $category["id"] ?>" id="categories_<?= $category["id"] ?>">
                                        <?= $category["name"];  ?>
                                    </option>
                        <?php
                                }
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-lg-8 col-12">
                    <label class="form-label" for="project_hook">Courte description du projet</label>
                    <input type="text" class="form-control" id="project_hook" name="project_hook" placeholder="ex : Ce projet est un..." value="<?= $result_get_project["hook"]; ?>" required>
                </div>

                <div class="col-lg-8 col-12">
                    <label for="form-label">Lien du projet</label>
                    <input type="text" class="form-control" name="project_link" placeholder="ex : https://monprojet.com" id="project_link" value="<?= htmlspecialchars($result_get_project["link"]) ?>">
                </div>

                <div class="col-12 col-lg-8 mb-3">
                    <textarea name="project_description" rows="15" class="col-12" placeholder="ex : Voici un nouveau design.. (possibilité d'inserer des images et des vidéos) "><?= $result_get_project["description"]; ?></textarea>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" id="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="modal-body">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" id="modal-save"></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center col-md-12" id="target_data_container" data-title="<?= htmlspecialchars($result_get_project["title"]) ?>">

                    <!-- Button trigger modal -->
                    <button type="button" id="delete_button" class="btn btn-danger me-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Effacer le projet
                    </button>

                    <button type="button" id="modify_button" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#exampleModal" name="project_id">
                        Modifier le projet
                    </button>
                </div>
            </form>
        </div>

    <?php
    } else {

        try {
            $get_projects = $db->query(
                "SELECT
                -- Tout selectionner dans projects
                projects.*,
                -- Rassembler dans une chaine de caractère les categories.name
                GROUP_CONCAT(categories.name)
            FROM
                projects
                -- Chercher dans project_categories si il ya des categories_id
            INNER JOIN categories ON FIND_IN_SET(
                    categories.id,
                    projects.categories
                )
            GROUP BY
                projects.id
            ORDER BY 
            projects.id 
            DESC",
            );
            $result_get_projects = $get_projects->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $error_db;
            die();
        }

        $page_title = "Mes projets";
        include "header.php";
    ?>
        <div class="row">

            <?php
            if (!empty($result_get_projects)) {
                foreach ($result_get_projects as $project) { ?>

                    <div class="col-md-4 mb-2 ">

                        <a href="<?= $dashboard_url . 'project.php?id=' . $project['id']; ?>">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">
                                            <p class="mb-0 fw-bold"><?= htmlspecialchars($project["title"]); ?></p>
                                        </div>
                                        <div class="col-auto">
                                            <?= $project["deleted"] === 0 ? "<p class='visible fw-bold mb-0'>Visible</p>" : "<p class='deleted fw-bold mb-0'>Non visible</p>" ?>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body project_card_body text-center">
                                    <img src="<?= $project["picture"] ?>" alt="photo d'un projet créé" class="consult_projects_picture rounded-1 ">
                                    <p class="mb-0">Catégories :</p>
                                    <p><?= $project["GROUP_CONCAT(categories.name)"];  ?></p>

                                    <?php
                                    if ($project["deleted"] === 1) {
                                    ?>
                                        <form action="project.php" method="POST" class="col-md-12 d-flex justify-content-center">
                                            <input type="hidden" name="direction" value="restore">
                                            <input type="hidden" name="project_id" value="<?= $project["id"]; ?>">
                                            <button type="submit" class="btn btn-success mt-1">Restaurer</button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </a>


                    </div>
                <?php }
            } else {
                ?>
                <p class="col-md-12 text-center fw-bold">Vous n'avez pas encore crée de projets pour l'instant.</p>
            <?php } ?>
        </div>

    <?php
    }
    include "footer.php"
    ?>