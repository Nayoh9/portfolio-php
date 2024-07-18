 <!-- FOOTER AREA START -->
 <footer class="tj-footer-area">
     <div class="container">
         <div class="row">
             <div class="col-md-12 text-center">
                 <div class="footer-logo-box">
                     <a href="<?= $template_url . "accueil" ?>"> <img class="header-logo" src=<?= $template_url . "assets/img/logo/logo.jpg" ?> alt="logo pixeven" /></a>
                 </div>
                 <div class="footer-menu">
                     <?php
                        if ($page_title === $result_get_settings["meta_title_homepage"]) {
                        ?>
                         <nav>
                             <ul>
                                 <li><a href="#services-section">Services</a></li>
                                 <li><a href="#works-section">Réalisations</a></li>
                                 <li><a href="#skills-section">Compétences</a></li>
                                 <li><a href="#contact-section">Contact</a></li>
                                 <li><a href="<?= $dashboard_url ?>">Dashboard</a></li>
                             </ul>
                         </nav>

                     <?php
                        } else {
                        ?>
                         <nav>
                             <ul>
                                 <li><a href="<?= $template_url . "accueil" ?>"><?= $result_get_settings["meta_title_homepage"] ?></a></li>
                                 <li><a href="<?= $template_url . "projects" ?>">Tous les projets</a></li>
                                 <li><a href="<?= $dashboard_url ?>">Dashboard</a></li>

                             </ul>
                         </nav>
                     <?php } ?>
                 </div>
             </div>
         </div>
     </div>
 </footer>
 <!-- FOOTER AREA END -->

 <!-- CSS here -->
 <script src="<?= $template_url . 'assets/js/jquery.min.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/bootstrap.bundle.min.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/nice-select.min.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/backToTop.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/smooth-scroll.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/appear.min.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/wow.min.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/gsap.min.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/one-page-nav.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/lightcase.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/owl.carousel.min.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/isotope.pkgd.min.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/odometer.min.js'; ?>"></script>
 <script src="<?= $template_url . 'assets/js/main.js'; ?>"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.2.0-beta/noty.min.js"></script>

 </body>

 </html>