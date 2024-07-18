
const fileInput = document.getElementById("file_to_upload");
const preview = document.getElementById("preview");

fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
        file = fileInput.files[0];


        const fr = new FileReader();

        fr.readAsDataURL(file);
        fr.addEventListener("load", () => {
            for (let i = 0; i < preview.children.length; i++) {
                preview.removeChild(preview.children[i]);
            }
            const url = fr.result;
            const img = new Image();

            img.setAttribute("class", "form_asset");
            img.src = url;
            preview.appendChild(img);
        });

    }
});

const filesInput = document.getElementById("files_to_upload");

