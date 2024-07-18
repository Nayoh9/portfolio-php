</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.2.0-beta/noty.min.js"></script>
<script src="https://cdn.tiny.cloud/1/<?= $tiny_mce_key ?>/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.tiny.cloud/1/isi9rntd97m1oe0d1fvucquulejjzpu490422bohdt15p361/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script src="<?= $dashboard_url . "assets/js/tiny_mce.js" ?> "> </script>
<script src="<?= $dashboard_url . "assets/js/input_files.js" ?> "> </script>
<script src="<?= $dashboard_url . "assets/js/one_click_button.js" ?> "> </script>
<script src="<?= $dashboard_url . "assets/js/file_reader.js" ?> "> </script>

<script>
    document.addEventListener("DOMContentLoaded", (e) => {

        const delete_button = document.getElementById("delete_button");
        const modify_button = document.getElementById("modify_button");
        const modify_target_form = document.getElementById("modify_target_form");

        const modal_header = document.getElementById("modal-header");
        const modal_body = document.getElementById("modal-body");
        const modal_save = document.getElementById("modal-save");

        const form_direction = document.getElementById("direction");
        const target_name = document.getElementById("target_data_container").getAttribute("data-title");
        const action = "<?= $action; ?>"


        modify_button.addEventListener("click", () => {
            modal_header.innerHTML = ` Modification de "${target_name}"`;
            modal_body.innerHTML = `Êtes-vous sûr de vouloir modifier "${target_name}" ?`;
            modal_save.innerHTML = "Sauvegarder les changements";

            modify_target_form.setAttribute("action", action);
            modal_save.setAttribute("class", "btn btn-primary");
            form_direction.setAttribute("value", "modify");

        });

        delete_button.addEventListener("click", () => {
            modal_header.innerHTML = `Suppression de "${target_name}"`;
            modal_body.innerHTML = `Êtes-vous sûr de vouloir supprimer "${target_name}" ?`;
            modal_save.innerHTML = `Supprimer "${target_name}"`

            modify_target_form.setAttribute("action", action);
            modal_save.setAttribute("class", "btn btn-danger");
            form_direction.setAttribute("value", "delete");

        })
    });
</script>

</body>

</html>