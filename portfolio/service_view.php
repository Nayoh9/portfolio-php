    <?php
    include "includes/functions.php";

    try {
        $get_settings = $db->query("SELECT * FROM settings");
        $result_get_settings = $get_settings->fetch(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        var_dump($error_db);
        die();
    }


    $ok_service_view = "accueil";
    $not_ok_service_view = "accueil";

    $error = false;

    if (empty($_GET["id"])) {
        $error = "invalid_service";
        header("location: $template_url" . "$not_ok_service?error=$error");
        die();
    } else {

        $service_id = $_GET["id"];

        try {
            $get_service = $db->query("SELECT * FROM services WHERE services.id = $service_id");
            $result_get_service = $get_service->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            var_dump($error_db);
        }

        if (empty($result_get_service)) {
            $error = "cant_find_var";
            header("location: $template_url" . "$not_ok_service_view?error=$error");
            die();
        }

        $page_title = $result_get_settings["meta_title_homepage"] . " - " . $result_get_service["name"];
        include "header.php";
    ?>
        <div class="background">

            <div class="service_view_gutter"></div>
            <div id="service-wrapper" class="popup_content_area zoom-anim-dialog ">
                <div class="popup_modal_img">
                    <img src="<?= $template_url . "assets/img/logo/logo.jpg" ?>" alt="photo d'une prestation" />
                </div>

                <div class=" popup_modal_content">
                    <div class="service_details">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="service_details_content">
                                    <div class="service_info">
                                        <h6 class="subtitle">PRESTATION</h6>
                                        <h2 class="title"> <?= $result_get_service["name"] ?></h2>
                                        <div class="desc">
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati, saepe consequatur. Magni ea itaque possimus odit at aperiam aliquid eaque illo saepe suscipit quas, aut dolor ipsum voluptatum vel ex voluptatibus, delectus incidunt dolores natus enim nisi? Nobis fuga id suscipit tempore ratione, eveniet maiores animi eum quas ad atque! Adipisci doloremque ipsam odio libero officiis. Nostrum, officia? A eos hic earum sunt minus blanditiis magni dolore commodi ipsum asperiores.
                                            </p>

                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut commodi tempore, in possimus blanditiis doloremque voluptates non ratione qui labore nam mollitia cum? Qui sed laborum saepe, et dicta consequatur voluptatibus iste numquam illum voluptate, sunt quasi veniam neque praesentium nisi nam esse magni explicabo pariatur excepturi. Aliquid, pariatur? Illo adipisci sit magni maxime labore, assumenda officiis, similique animi in consequuntur, nobis tempora autem vitae amet nisi esse ducimus facilis.
                                            </p>

                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam doloremque magnam asperiores temporibus facilis. Accusamus quas minima quidem, tempora voluptatum laudantium unde officia quo accusantium ea quibusdam dolore reiciendis optio consequatur beatae suscipit adipisci expedita, eum nihil velit itaque magnam eveniet sit possimus. Minima, at. Facere id impedit tempore fuga.
                                            </p>
                                        </div>

                                        <h3 class="title">Processus du service</h3>
                                        <div class="desc">
                                            <p>
                                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Maxime aperiam omnis quidem vel modi, magnam voluptatem sint et rerum dolor dolorem, earum natus quo ullam pariatur id cum doloribus? Fugit.
                                            </p>
                                        </div>
                                        <ul>
                                            <li>Décrire le service</li>
                                            <li>Décrire le service</li>
                                            <li>Décrire le service</li>
                                            <li>Décrire le service</li>
                                            <li>Décrire le service</li>
                                            <li>Décrire le service</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    <?php
    }
    include "footer.php";
    ?>