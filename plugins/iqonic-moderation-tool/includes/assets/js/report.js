document.addEventListener("readystatechange", function (e) {
    if (document.readyState == "complete") {
        if (document.querySelectorAll(".imt-modal")) {
            document.querySelectorAll(".imt-modal").forEach(element => {
                element.removeAttribute("style");
            });
        }
    }
});

document.addEventListener("click", function (e) {
    var element = e.target;
    var elementClasses = element.classList;
    if (elementClasses.contains("imt-report-button")) {
        var id = element.getAttribute("data-id");
        var type = element.getAttribute("data-type");
        var modal = document.getElementById(element.getAttribute("data-target"));
        var reportAction = imt_front_ajax_params.ajaxData.reportAction;

        modal.querySelector("#type-id").value = id;
        modal.querySelector("#activity-type").value = type;
        if (reportAction.modalData[type])
            modal.querySelector("#imt-modal-title").innerHTML = reportAction.modalData[type];
        imtOpenModal(modal);
    }
    if (elementClasses.contains("imt-submit-report")) {
        e.preventDefault();
        var request = new XMLHttpRequest();
        var url = imt_front_ajax_params.ajaxUrl;
        var ajaxData = imt_front_ajax_params.ajaxData;
        var reportAction = ajaxData.reportAction;
        var dataMsg = element.getAttribute("data-message");
        url += "/?action=" + reportAction.action;

        var myForm = element.closest("#imt-report-from");
        var formData = new FormData(myForm);

        request.open("POST", url, true);
        request.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                // Success!
                var resp = JSON.parse(this.response);
                if (resp.status) {
                    imtCloseModal(document.getElementById("imt-report-modal"));
                    alert(dataMsg);
                }
            }
        };

        request.onerror = function () {
            // There was a connection error of some sort
        };

        request.send(formData);
    }
}, true);