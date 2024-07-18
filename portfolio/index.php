  <?php
  include "includes/functions.php";

  try {
    $get_settings = $db->query("SELECT * FROM settings");
    $result_get_settings = $get_settings->fetch(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    var_dump($error_db);
    die();
  }

  try {
    $get_services = $db->query("SELECT * FROM services");
    $result_get_services = $get_services->fetchALL(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    var_dump($error_db);
    die();
  }

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

    $result_get_projects = $get_projects->fetchALL(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    var_dump($error_db);
  }


  $page_title = $result_get_settings["meta_title_homepage"];
  $meta_description = $result_get_settings["meta_description_homepage"];

  include "header.php";

  $socials = json_decode($result_get_settings["socials"], true);
  $stats = json_decode($result_get_settings["stats"], true);

  ?>
  <main class="site-content" id="content">

    <!-- HERO SECTION START -->
    <section class="hero-section d-flex align-items-center" id="intro">
      <div class="intro_text">
        <svg viewBox="0 0 1320 300">
          <text x="50%" Y="50%" text-anchor="middle">Bonjour</text>
        </svg>
      </div>
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6">
            <div class="hero-content-box">
              <h1 class="hero-title wow fadeInLeft" data-wow-delay="1.2s">
                <?= $result_get_settings["profile_title"]; ?>
              </h1>

              <div class="hero-image-box d-md-none text-center wow fadeInRight" data-wow-delay="1.3s">
                <img src="<?= $result_get_settings["profile_picture"]; ?>" alt="photo d'un homme" />
              </div>

              <p class="lead wow fadeInLeft" data-wow-delay="1.4s">
                Réflexion, creation, innovation.
              </p>
              <div class="button-box d-flex flex-wrap align-items-center">
                <ul class="ul-reset social-icons wow fadeInLeft" data-wow-delay="1.6s">

                  <?php
                  foreach ($socials as $social) {
                    if (!empty($social["icon"])) {
                  ?>
                      <li>
                        <a href="<?= $social["link"] ?>" target="_blank"><i class="<?= $social["icon"] ?>"></i></a>
                      </li>
                  <?php
                    }
                  }
                  ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6 d-none d-md-block">
            <div class="hero-image-box text-center wow fadeInRight" data-wow-delay="1.5s">
              <img src="<?= $result_get_settings["profile_picture"] ?>" alt="Photo d'un homme" />
            </div>
          </div>
        </div>

        <div class="funfact-area">
          <div class="row">
            <div class="col-6 col-lg-4">
              <div class="funfact-item d-flex flex-column flex-sm-row flex-wrap align-items-center">
                <div class="number">
                  <span class="odometer" data-count="<?= htmlspecialchars($stats["years_of_experience"]); ?>">0</span>
                </div>
                <div class="text">Années <br />d'Experience</div>
              </div>
            </div>
            <div class="col-6 col-lg-4">
              <div class="funfact-item d-flex flex-column flex-sm-row flex-wrap align-items-center">
                <div class="number">
                  <span class="odometer" data-count="<?= htmlspecialchars($stats["achieved_projects"]) ?>">0</span>+
                </div>
                <div class="text">Projets <br />réalisés</div>
              </div>
            </div>
            <div class="col-6 col-lg-4">
              <div class="funfact-item d-flex flex-column flex-sm-row flex-wrap align-items-center">
                <div class="number">
                  <span class="odometer" data-count="<?= htmlspecialchars($stats["satisfied_customers"]) ?>">0</span>K
                </div>
                <div class="text">Clients <br />Satisfaits</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- HERO SECTION END -->

    <!-- SERVICES SECTION START -->
    <section class="services-section" id="services-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header text-center">
              <h2 class="section-title wow fadeInUp" data-wow-delay=".3s">
                Nos prestations
              </h2>
              <p class="wow fadeInUp" data-wow-delay=".4s">
                Nous concrétisons vos idées et donc vos souhaits sous la forme
                d'un projet unique qui vous inspire et inspire vos clients.
              </p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="services-widget position-relative">
              <?php
              $counter = 0;
              foreach ($result_get_services as $service) {
                $counter++;
              ?>
                <a href="<?= $template_url . "service/" . "service" . "-" . $service['id'] . "-" . str_replace(" ", "", strtolower($service["name"])); ?>">

                  <div class="service-item current d-flex flex-wrap align-items-center wow fadeInUp" data-wow-delay=".5s">
                    <div class="left-box d-flex flex-wrap align-items-center">
                      <span class="number"><?= $counter; ?></span>
                      <h3 class="service-title"><?= $service["name"]; ?></h3>
                    </div>
                    <div class="right-box">
                      <p>
                        <?= $service["description"]; ?>
                      </p>
                    </div>
                    <i class="flaticon-up-right-arrow"></i>
                    <button data-mfp-src="#service-wrapper" class="service-link modal-popup"></button>
                  </div>
                </a>

              <?php } ?>
              <div class="active-bg wow fadeInUp" data-wow-delay=".5s"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- SERVICES SECTION END -->

    <!-- PORTFOLIO SECTION START -->
    <section class="portfolio-section" id="works-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header text-center">
              <h2 class="section-title wow fadeInUp" data-wow-delay=".3s">
                Nos Travaux Recents
              </h2>
              <p class="wow fadeInUp" data-wow-delay=".4s">
                Nous concrétisons vos idées et donc vos souhaits sous la forme
                d'un projet web unique qui vous inspire et inspire vos
                clients.
              </p>
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

                      if ($counter < 6) {
                        $counter++;
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
                  }
                  ?>

                </div>
              </div>
            </div>
          </div>

          <?php
          if ($counter === 6) {
          ?>
            <div class="col-md-12 d-flex justify-content-center">
              <a class="col-auto  all_projects" href="<?= $template_url . "projects" ?>">
                <h4 class="mb-0">Voir tous nos travaux</h4>
              </a>
            </div>
          <?php } ?>
    </section>
    <!-- PORTFOLIO SECTION END -->

    <!-- SKILLS SECTION START -->
    <section class="skills-section" id="skills-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header text-center">
              <h2 class="section-title wow fadeInUp" data-wow-delay=".3s">
                Mes compétences
              </h2>
              <p class="wow fadeInUp" data-wow-delay=".4s">
                Nous concrétisons vos idées et donc vos souhaits sous la forme
                d'un projet web unique qui vous inspire et inspire vos
                clients.
              </p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="skills-widget d-flex flex-wrap justify-content-center align-items-center">
              <div class="skill-item wow fadeInUp" data-wow-delay=".3s">
                <div class="skill-inner">
                  <div class="icon-box">
                    <img src="assets/img/icons/figma.svg" alt="" />
                  </div>
                  <!-- <div class="number">92%</div> -->
                </div>
                <p>Procrate</p>
              </div>
              <div class="skill-item wow fadeInUp" data-wow-delay=".4s">
                <div class="skill-inner">
                  <div class="icon-box">
                    <img src="assets/img/icons/sketch.svg" alt="" />
                  </div>
                  <!-- <div class="number">80%</div> -->
                </div>
                <p>Blender</p>
              </div>
              <div class="skill-item wow fadeInUp" data-wow-delay=".5s">
                <div class="skill-inner">
                  <div class="icon-box">
                    <img src="assets/img/icons/xd.svg" alt="" />
                  </div>
                  <!-- <div class="number">85%</div> -->
                </div>
                <p>After Effects</p>
              </div>
              <div class="skill-item wow fadeInUp" data-wow-delay=".6s">
                <div class="skill-inner">
                  <div class="icon-box">
                    <img src="assets/img/icons/wp.svg" alt="" />
                  </div>
                  <!-- <div class="number">99%</div> -->
                </div>
                <p>Final Cut Pro</p>
              </div>
              <div class="skill-item wow fadeInUp" data-wow-delay=".7s">
                <div class="skill-inner">
                  <div class="icon-box">
                    <img src="assets/img/icons/react.svg" alt="" />
                  </div>
                  <!-- <div class="number">89%</div> -->
                </div>
                <p>Illustrator</p>
              </div>
              <div class="skill-item wow fadeInUp" data-wow-delay=".8s">
                <div class="skill-inner">
                  <div class="icon-box">
                    <img src="assets/img/icons/js.svg" alt="" />
                  </div>
                  <!-- <div class="number">93%</div> -->
                </div>
                <p>Logic Pro</p>
                </´div>
              </div>
            </div>
          </div>
        </div>
    </section>
    <!-- SKILLS SECTION END -->

    <!-- CONTACT SECTION START -->
    <section class="contact-section" id="contact-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-7 order-2 order-md-1">
            <div class="contact-form-box wow fadeInLeft" data-wow-delay=".3s">
              <div class="section-header">
                <h2 class="section-title">Travaillons ensemble !</h2>
                <p>
                  Nous concevons des choses magnifiquement designées et nous
                  aimons ce que nous faisons.
                </p>
              </div>

              <div class="tj-contact-form">
                <form action="contact.php" method="POST">
                  <div class="row gx-3">
                    <div class="col-sm-6">
                      <div class="form_group">
                        <input type="text" name="fname" id="fname" placeholder="Prénom" autocomplete="off" required />
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form_group">
                        <input type="text" name="lname" id="lname" placeholder="Nom" autocomplete="off" required />
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form_group">
                        <input type="email" name="email" id="email" placeholder="Adresse E-mail" autocomplete="off" required />
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form_group">
                        <input type="tel" name="phone" id="phone" placeholder="Numéro de téléphone" autocomplete="off" required />
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form_group">
                        <select name="service" id="service" class="tj-nice-select">
                          <option value="" selected disabled>
                            Choisir une prestation
                          </option>
                          <?php
                          foreach ($result_get_services as $service) {
                          ?>
                            <option value="<?= $service["name"]; ?>"><?= $service["name"]; ?></option>
                          <?php } ?>

                        </select>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form_group">
                        <textarea name="message" id="message" placeholder="Message" required></textarea>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form_btn">
                        <button type="submit" class="btn tj-btn-primary">
                          Envoyer
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-lg-5 offset-lg-1 col-md-5 d-flex flex-wrap align-items-center order-1 order-md-2">
            <div class="contact-info-list">
              <ul class="ul-reset">
                <li class="d-flex flex-wrap align-items-center position-relative wow fadeInRight" data-wow-delay=".4s">
                  <div class="icon-box">
                    <i class="flaticon-phone-call"></i>
                  </div>
                  <div class="text-box">
                    <p>Téléphone</p>
                    <a href="tel:0123456789">+33 7 68 24 71 00</a>
                  </div>
                </li>
                <li class="d-flex flex-wrap align-items-center position-relative wow fadeInRight" data-wow-delay=".5s">
                  <div class="icon-box">
                    <i class="flaticon-mail-inbox-app"></i>
                  </div>
                  <div class="text-box">
                    <p>E-mail</p>
                    <a href="mailto:mail@mail.com">y.andre90000@gmail.com</a>
                  </div>
                </li>
                <li class="d-flex flex-wrap align-items-center position-relative wow fadeInRight" data-wow-delay=".6s">
                  <div class="icon-box">
                    <i class="flaticon-location"></i>
                  </div>
                  <div class="text-box">
                    <p>Adresse</p>
                    <a href="#">Rue de la chapelle, <br />25200, Montbéliard</a>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- CONTACT SECTION END -->
  </main>

  <?php include "footer.php" ?>