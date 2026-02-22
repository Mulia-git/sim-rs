// public/js/bpjs/api.js

const BPJSApi = (() => {

    function get(url, params = {}) {
        return $.ajax({
            url: url,
            type: "GET",
            data: params,
            dataType: "json"
        });
    }

    function post(url, data = {}) {
        return $.ajax({
            url: url,
            type: "POST",
            data: data,
            dataType: "json"
        });
    }

    return { get, post };

})();