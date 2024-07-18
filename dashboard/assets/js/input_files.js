

const multi_input_file = document.getElementById("project_pictures");
const files_name_container = document.getElementById("files_container");

multi_input_file.addEventListener("change", (e) => {

    const files = e.target.files
    const arr = Array.from(files)

    files_name_container.innerHTML = '';

    arr.forEach((file) => {
        const p = document.createElement("p");
        p.setAttribute("class", "displayed_files")
        p.textContent = file.name;
        files_name_container.appendChild(p);

    })


})
