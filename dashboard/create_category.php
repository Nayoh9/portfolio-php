<?php
$page_title = "Créer une nouvelle catégorie";
include "includes/functions.php";
include "header.php";
?>
<div class="row justify-content-center ">
    <form method="POST" class="col-md-6" action="category.php">
        <div>
            <label for="category_name" class="form-label">Nom de la catégorie</label>
            <input class="form-control mb-2" name="category_name" type="text" placeholder="ex : Motion-Design" required>
            <input type="hidden" name="direction" value="create">
        </div>

        <div class="col-md-12 d-flex justify-content-center ">
            <button type="submit" class="btn btn-primary">Créer la catégorie</button>
        </div>
    </form>
</div>

<?php include "footer.php" ?>