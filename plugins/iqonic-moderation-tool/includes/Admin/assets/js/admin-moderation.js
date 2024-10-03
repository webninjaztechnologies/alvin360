document.addEventListener("click", function (e) {
    var element = e.target;

    if (element.id == "admin-suspend-member") {
        var memberId = element.getAttribute("data-id");
        var isSuspended = element.getAttribute("data-suspended");
        var request = new XMLHttpRequest();
        var url = imt_admin_ajax_url_params.ajaxUrl;
        var suspendAction = imt_admin_ajax_url_params.suspendAction;

        url += "/?action=" + suspendAction.action;
        url += "&member_id=" + memberId;
        url += "&is_suspended=" + isSuspended;

        request.open("GET", url, true);
        request.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                // Success!
                var resp = JSON.parse(this.response);
                if (!(resp.status) && resp.type == "suspend") {
                    element.setAttribute("data-suspended", 1);
                    element.innerHTML = suspendAction.unsuspendButtonLabel;
                } else {
                    element.setAttribute("data-suspended", '');
                    element.innerHTML = suspendAction.suspendButtonLabel;
                }
            }
        };

        request.onerror = function () {
            // There was a connection error of some sort

        };

        request.send(null);
    }
    if (element.id == "imt-moderate") {
        var id = element.getAttribute("data-id");
        var isModerated = element.getAttribute("data-moderated");
        var type = element.getAttribute("data-activity");
        var request = new XMLHttpRequest();
        var url = imt_admin_ajax_url_params.ajaxUrl;
        var moderateAction = imt_admin_ajax_url_params.moderateAction;
        var moderateLabel, unmoderateLabel;
        
        url += "/?action=" + moderateAction.action;
        url += "&id=" + id;
        url += "&is_moderated=" + isModerated;
        url += "&type=" + type;

        if (type == "activity") {
            moderateLabel = moderateAction.moderateActivityLabel;
            unmoderateLabel = moderateAction.ummoderateActivityLabel;
        } else {
            moderateLabel = moderateAction.moderateGroupLabel;
            unmoderateLabel = moderateAction.unmoderateGroupLabel;
        }

        request.open("GET", url, true);
        request.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                // Success!
                var resp = JSON.parse(this.response);
                if (resp.status && resp.type == "moderated") {
                    element.setAttribute("data-moderated", 1);
                    element.innerHTML = unmoderateLabel;
                } else {
                    element.setAttribute("data-moderated", '');
                    element.innerHTML = moderateLabel;
                }
            }
        };

        request.onerror = function () {
            // There was a connection error of some sort

        };

        request.send(null);
    }
}, true);