$(document).ready(function(){

    let reportType = 'success';
    let data = {};

    // Callback (AJAX):
    function modalReport(type = 'error', title = 'Ошибка приложения', message = 'Операция не выполнена'){
        // type (norm|success|error|warn|inf) defines modal window styles
        $('.modal-title').text(title);
        $('.modal-body').html(message);
        if (!$('#ajaxModal').hasClass('show')){
            $('#ajaxModal').modal('show');
        }
        reportType = 'success'; // set the default value = "success"
        formTimeout( $('.modal-body form') );
        return true;
    }

    function fnSuc(title = 'Запрос выполнен', message){
        $('.modal-title').text(title);
        $('.modal-body').html(message);
        if (!$('#ajaxModal').hasClass('show')){
            $('#ajaxModal').modal('show');
        }
    }
    function fnErr(title = 'Ошибка приложения', message = 'Извините, запрос не выполнен'){
        if (title instanceof Object){ title = 'Ошибка приложения' }
        $('.modal-title').text(title);
        $('.modal-body').html(message);
        if (!$('#ajaxModal').hasClass('show')){
            $('#ajaxModal').modal('show');
        }
    }

    function errReport(mess){
        //data = { action: 'ereport', mess: mess };
        //req(data,noop,noop);
    }
    function noop(){ return false; } // empty fn

    // Form reset (fn):
    function formReset(id){
        $('#'+id)[0].reset();
        $('#'+id+' input').val('');
        $('.fakeInput').text('');
        $('input[type=checkbox]').val('').prop('checked', false);
        return true;
    }

    // Login-form show:
    $('body').on('click', '.btn-login-form', function (e){
        e.preventDefault();
        let toPath = $(this).data('path');
        if (typeof toPath === typeof undefined || toPath === false || toPath === null) {
            toPath = '';
        }
        let response = $(this).reqajax({
            data: { action: 'log-form', toPath: toPath }
        });
        if (response.ok) {
            modalReport(reportType, response.modalTitle, response.modalBody);
            $('input[name="email"]').val('');
            formReset('loginForm');
        } else {
            modalReport('error', response.modalTitle, response.modalBody);
        }
    });

    // Login-form sending:
    $('body').on('submit', '#loginForm', function (e){
        e.preventDefault();
        let response = $(this).reqajax({
            data: $(this).serialize() + '&action=login' + '&toPath=' + $(this).data('path')
        });
        if (response.ok) {
            window.location.href = response.modalTitle;
        }
        else {
            modalReport('error', response.modalTitle, response.modalBody);
        }
    });

    // Log Out:
    $('body').on('click', '.btn-logout', function (e){
        e.preventDefault();
        let response = $(this).reqajax({
            data: { action: 'logout-form' }
        });
        if (!response.ok) {
            reportType = 'error';
        }
        modalReport(reportType, response.modalTitle, response.modalBody);
    });
    $('body').on('click', '.logout-confirm', function (e){
        e.preventDefault();
        let response = $(this).reqajax({
            data: { action: 'logout' }
        });
        if (response.ok) {
            window.location.reload();
        } else {
            modalReport('error');
        }
    });

    // Forgot-password

    // Forgot-password link click (request forgot-password-form with user email):
    $('body').on('click', '.forgot-password', function (e){
        e.preventDefault();
        let response = $(this).reqajax({
            data: { action: 'forgot-password' }
        });
        if (response.ok) {
            formReset('loginForm');
        } else {
            reportType = 'error';
        }
        modalReport(reportType, response.modalTitle, response.modalBody);
    });
    // Forgot-password-form (user email) sending:
    $('body').on('submit', '#forgotPasswordForm', function (e){
        e.preventDefault();
        data = $(this).serialize() + '&action=forgot-password-data';
        let response = $(this).reqajax({
            data: $(this).serialize() + '&action=forgot-password-data'
        });
        if (!response.ok) {
            reportType = 'error';
        }
        modalReport(reportType, response.modalTitle, response.modalBody);
    });

    // Reset-password-form (new data) sending:
    $('body').on('submit', '#resetPassword', function (e){
        e.preventDefault();
        let response = $(this).reqajax({
            data: $(this).serialize() + '&action=reset-password'
        });
        if (!response.ok) {
            reportType = 'error';
        }
        modalReport(reportType, response.modalTitle, response.modalBody);
    });

    // Registration

    // Register-form show:
    $('body').on('click', '.btn-reg-form', function (){
        let response = $(this).reqajax({
            url: 'reg',
            data: { action: 'reg-form' }
        });
        if (response.ok) {
            if ( $('#loginForm').length ){ formReset('loginForm'); }

        } else {
            reportType = 'error';
        }
        modalReport(reportType, response.modalTitle, response.modalBody);
    });

    // Register-form sending:
    $('body').on('submit', '#registerForm', function (e){
        e.preventDefault();
        let response = $(this).reqajax({
            url: 'reg',
            data: $(this).serialize() + '&action=register'
        });
        if (!response.ok) {
            reportType = 'error';
        }
        modalReport(reportType, response.modalTitle, response.modalBody);
    });

    // New verify-link:
    $('body').on('click', '.new-verify-link', function (){
        let response = $(this).reqajax({
            url: 'reg',
            data: { action: 'new-link', id: $(this).data('id') }
        });
        if (!response.ok) {
            reportType = 'error';
        }
        modalReport(reportType, response.modalTitle, response.modalBody);
    });

    // Submit button countdown (form blocking):
    function formTimeout(form){
        let submBut = form.find('button[type="submit"]');
        if (form.hasClass('formTimeout')){
            submBut.attr("disabled", true).append('<div></div>');
            let remtime = form.data('remtime');
            countDown(form, submBut, remtime);
        }
        else { return false; }
    }
    function countDown(form, submBut, remtime){
        submBut.find('div').text('Подождите (' + remtime + ')');
        setTimeout(function(){
            remtime--;
            form.data('remtime',remtime);
            if (remtime >= 0){
                countDown(form, submBut, remtime); // рекурсия
            }
            else {
                submBut.attr("disabled", false).find('div').remove();
                form.removeClass('formTimeout');
                $('.alert-danger').remove();
                return false;
            }
        },1000);
    }

});
