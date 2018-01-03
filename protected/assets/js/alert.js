var AlertModule = (function () {
    var _modalUrl = '';
    var _list = {
        init: function () {
            //modal activities
            var modal = $('#taskAlerts'), content = modal.find(".content-placeholder");

            modal.on('hidden.bs.modal', function () {
                content.html('<div class="placeholder"><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div></div></div>');
            });

            $(document).on('click', '.view-alerts', function (e) {
                e.preventDefault();
                _modalUrl = $(this).attr('data-url');

                modal.modal({backdrop: false, keyboard: false});

                $.get(_modalUrl).done(function (e) {

                    content.html(e);

                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                    modal.modal('hide');
                });
            }).on('click', '#btnSaveAlert', function (e) {
                var $el = $(this);
                var $form = $el.closest('form');
                var data = $form.serialize();

                $el.button('loading');

                $.post($form.attr('action'), data).done(function (res) {
                    content.html('');
                    content.html(res);
                    $('#alertTabs a[href="#new-alert"]').tab('show');
                }).always(function () {
                    $el.button('reset');
                }).error(function (xhr) {
                    alert(xhr.responseText);
                });

                return false;
            }).on('click', '#btnCancel', function (e) {
                var $el = $(this);
                var url = $el.data('url');

                $el.button('loading');

                $.get(url).done(function (res) {
                    content.html(res);
                    $('#alertTabs a[href="#alerts"]').tab('show');
                }).always(function () {
                    $el.button('reset');
                }).error(function (xhr) {
                    alert(xhr.responseText);
                });

                return false;
            }).on('click','a.edit-alert', function(){
                var $el = $(this);
                $el.text('Loading...');
                var url = $el.data('url');
                $.get(url).done(function (e) {
                    content.html(e);
                    $('#alertTabs a[href="#new-alert"]').tab('show');

                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                }).always(function(){
                    $el.text('Delete');
                });
            }).on('click','a.delete-alert', function(){
                var $el = $(this);
                var token = $el.data('token');
                var tokenName = $el.data('token-name');
                var url = $el.data('url');
                var data = {};
                data[tokenName] = token;
                data['delete']=1;
                $el.text('Deleting...')
                $.post(url,data).done(function (e) {

                    content.html(e);

                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                }).always(function(){
                    $el.text('Delete');
                });
            });
        }
    };
    return {
        list: _list
    }
})();