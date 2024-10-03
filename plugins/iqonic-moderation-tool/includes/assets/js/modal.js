document.addEventListener("click", function (e) {
    var element = e.target;
    if (element.classList.contains("imt-close-modal") || element.id == "imt-modal-overlay") {
        var modal = document.querySelector(".imt-modal.active");
        imtCloseModal(modal);
    }

}, true);

document.addEventListener("keyup", function (e) {
    if (e.key.toLowerCase() == "escape") {
        var modal = document.querySelector(".imt-modal.active");
        if (modal)
            imtCloseModal(modal);
    }
});

function imtOpenModal(element) {
    element.classList.add("active");
    document.getElementById("imt-modal-overlay").classList.add("active");
}

function imtCloseModal(element) {
    element.classList.remove("active");
    document.getElementById("imt-modal-overlay").classList.remove("active");
}