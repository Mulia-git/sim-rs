// public/js/bpjs/utils.js

const BPJSUtils = (() => {

    function showSuccess(msg) {
        Swal.fire("Berhasil", msg, "success");
    }

    function showError(msg) {
        Swal.fire("Gagal", msg, "error");
    }

    function loading(state = true) {
        if (state) {
            $.LoadingOverlay("show");
        } else {
            $.LoadingOverlay("hide");
        }
    }

    function serialize(formId) {
        return $(formId).serialize();
    }

    function setHTML(target, html) {
        $(target).html(html);
    }

    return {
        showSuccess,
        showError,
        loading,
        serialize,
        setHTML
    };

})();