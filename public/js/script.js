document.addEventListener("DOMContentLoaded", function () {
    let successMessage = document.body.getAttribute("data-success");
    if (successMessage && successMessage.trim() !== "") {
        alert(successMessage);
    }
});
