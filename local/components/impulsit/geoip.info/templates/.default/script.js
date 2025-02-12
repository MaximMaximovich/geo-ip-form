(function (BX) {
    BX.ready(function () {
        const form = $('#geoForm');
        if (form.length > 0) {
            form.parsley().on("form:submit", function () {

                let dataArParams = form.attr('data-ar-params');
                let addiSigned = form.attr('data-addi-signed');
                let arFormData = form.serializeArray();

                let request = BX.ajax.runComponentAction(
                    'impulsit:geoip.info',
                    'ajaxGetData',
                    {
                        mode:'class',
                        signedParameters: dataArParams,
                        data: {
                            arFormData : arFormData,
                            addiSigned : addiSigned
                        }
                    });

                request.then(function(response){
                    console.log(response);
                    if (response.status === 'success') {
                        if (response.data) {
                            let formMonitor = document.getElementById("formRes");
                            if (!formMonitor) { return };
                            formMonitor.innerHTML = response.data;
                        }
                    }
                });
                return false;
            });
        }

        //маска для инпута ip адреса (jquery.mask.min.js)
        $('#formIp').mask('0ZZ.0ZZ.0ZZ.0ZZ', {translation: {'Z': {pattern: /[0-9]/, optional: true}}});
    });
})(BX);