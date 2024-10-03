document.addEventListener("click", function (e) {
    var element = e.target;
    if (element.classList.contains("imt-block-button")) {
        e.stopPropagation();
        var memberId = element.getAttribute("data-id");
        var isBlocked = element.getAttribute("data-blocked");
        var request = new XMLHttpRequest();
        var url = imt_front_ajax_params.ajaxUrl;
        var ajaxData = imt_front_ajax_params.ajaxData;
        var blockAction = ajaxData.blockAction;

        url += "/?action=" + blockAction.action;
        url += "&member_id=" + memberId;
        url += "&is_blocked=" + isBlocked;

        request.open("GET", url, true);
        request.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                // Success!
                var resp = JSON.parse(this.response);
                if (resp.status && isBlocked) {
                    element.setAttribute("data-blocked", '');
                    element.innerHTML = blockAction.blockLabel;
                } else {
                    element.setAttribute("data-blocked", 1);
                    element.innerHTML = blockAction.unblockLabel;
                }
            }
        };

        request.onerror = function () {
            // There was a connection error of some sort

        };

        request.send(null);
        return false;
    }
    
}, true);