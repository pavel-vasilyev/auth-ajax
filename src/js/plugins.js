/**
*   Plugins
* */

(function( $ ){

    // Плагин отправки AJAX-запроса (reqajax):

    $.fn.reqajax = function( options ) {
        var settings = $.extend({ // Настройки по-умолчанию:
                url : 'auth',
                type : 'POST',
                dataType : 'json',
                //contentType: "application/json; charset=utf-8",
                headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                timeout : 10000,
                data : null
            }, options),
            body = $('body'),
            btnCont = '',
            btnCoverCont = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Подождите...',
            resp_data;

        if(settings.data === null) {
            return {ok: false};
        }

        settings.url = '/' + settings.url;

        cover(true);

        function cover(act = false){
            let e = $('.modal-body');
            if (e.length){
                let btn = e.find('button[type=submit]');
                btnCont = btn.html();
                switch(act){
                    case true:
                        body.addClass('covered');
                        btn.html(btnCoverCont);
                        break;
                    case false:
                        body.removeClass('covered');
                        btn.html(btnCont);
                        break;
                }
            }
        }

        $.ajax({
            url: settings.url,
            type: settings.type,
            dataType: settings.dataType,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: settings.data,
            timeout: settings.timeout,
            async: false, // Важно! Если true (is default), плагин отправит пустой ответ, не дожидаясь ответа сервера
            success: function (response) {
                cover(false);
                resp_data = response;
            },
            error: function (jqXHR, exception) {
                //errRep(response);
                cover(false);
                if (jqXHR.status === 0) {
                    resp_data = {
                        ok: false,
                        modalTitle: 'Ошибка приложения',
                        modalBody: 'Not connect. Verify Network.'
                    }
                } else if (jqXHR.status == 404) {
                    resp_data = {
                        ok: false,
                        modalTitle: 'Ошибка приложения',
                        modalBody: 'Requested page not found (404).'
                    }
                } else if (jqXHR.status == 500) {
                    resp_data = {
                        ok: false,
                        modalTitle: 'Ошибка приложения',
                        modalBody: 'Internal Server Error (500).'
                    }
                } else if (exception === 'parsererror') {
                    resp_data = {
                        ok: false,
                        modalTitle: 'Ошибка приложения',
                        modalBody: 'Requested JSON parse failed.'
                    }
                } else if (exception === 'timeout') {
                    resp_data = {
                        ok: false,
                        modalTitle: 'Ошибка приложения',
                        modalBody: 'Time out error.'
                    }
                } else if (exception === 'abort') {
                    resp_data = {
                        ok: false,
                        modalTitle: 'Ошибка приложения',
                        modalBody: 'Ajax request aborted.'
                    }
                } else {
                    resp_data = {
                        ok: false,
                        modalTitle: 'Ошибка приложения',
                        modalBody: 'Uncaught Error. ' + jqXHR.responseText
                    }
                }
            }
        });

        return resp_data;
    };

    $.fn.focusEnd = function() {
        // Курсор - в конец содержимого div contenteditable
        $(this).focus();
        var tmp = $('<span />').appendTo($(this)),
            node = tmp.get(0),
            range = null,
            sel = null;
        if (document.selection) {
            range = document.body.createTextRange();
            range.moveToElementText(node);
            range.select();
        } else if (window.getSelection) {
            range = document.createRange();
            range.selectNode(node);
            sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
        tmp.remove();
        return this;
    }

})( jQuery );
