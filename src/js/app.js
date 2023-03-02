import * as plugins from "./plugins.js";

$(document).ready(function(){

    // Onload modal show (e.g. confirm email, completion of registration, login notification etc.):
    if ( $('.modal').hasClass('onload-show') ){
        $('.modal').removeClass('onload-show');
        $('#ajaxModal').modal('show');
        $('body').on('hidden.bs.modal', function(){
            $('.modal-title, .modal-body').html('');
        });
    }

    // Fake inputs handler (div-contenteditable):
    $('body').on('keydown', '.fakeInput', function(e) { // key interception
        if (e.keyCode === 13) { // is "Enter"
            e.preventDefault();
            $(':submit').click(); // action - submit
        }
    });
    $('body').on('input', '.fakeInput', function(){
        let inp = $(this);
        let inpName = inp.data('name');
        let realInp = $('input[name='+inpName+']');
        let inpVal = inp.text();
        let maxLength = realInp.attr('maxlength');
        let inpValMod = inpVal;
        switch(inpName){
            case 'name':
                inpValMod = inpVal.replace(/[^A-zА-яЁё\d\s\.@`_-]/g, '').replace(/^\s+/, '').replace(/\s{2,}/g, '\xa0');
                break;
            case 'email':
                inpValMod = inpVal.replace(/[^A-zА-яЁё\d\.@`_-]/g, '').replace(/^\./g, '').replace(/\.{2,}/g, '.');
                break;
        }
        if (inpValMod.length > maxLength){
            inpValMod = inpValMod.substring(0,maxLength);
        }
        if (inpValMod !== inpVal){
            realInp.val(inpValMod);
            inp.text(inpValMod);
            inp.focusEnd(); // - move cursor to end (plugin focusEnd)
        } else {
            realInp.val(inpVal);
        }
        if (inp.hasClass('is-invalid')){
            inp.removeClass('is-invalid');
            inp.parent('div').find('.invalid-feedback').css('opacity','0');
        }
    });

    // checkbox handler:
    $('body').on('input', 'input[type=checkbox]', function() {
        let inp = $(this);
        if (inp.is(':checked')){
            inp.val('on').prop('checked', true);
        } else {
            inp.val('').prop('checked', false);
        }
    });

});
