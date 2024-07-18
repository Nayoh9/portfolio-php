
const validate_project_button = document.getElementById("valid_project_button")
const validate_project_form = document.getElementById("valid_project_form");

validate_project_form.addEventListener("submit", () => {
    validate_project_button.setAttribute("disabled", true)
})
