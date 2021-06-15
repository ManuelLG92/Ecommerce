$(document).ready(()=>{
    const validSymbol = 'icon-checkmark-circle'
    const noValidSymbol = 'icon-cancel-circle2'
    const emailRegex = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
    const formSelector = '#formCreateEstilkerUser'
    const emailSelector = '#email'
    const buttonForm = '#createEstilkerUserBtn'

    $(formSelector).on('keypress', (e)=>{
        if (e.keyCode === 13){
            $(buttonForm).click()
        }
    })

    $(emailSelector).on('change keyup keypress', (e)=> {

        !validateEmail() ?
            inputStatus(emailSelector,'Introduce un email válido.',true) :
            inputStatus(emailSelector,'Email válido.',false)
    })

    $(buttonForm).on('click', (e)=> {
        e.preventDefault();
        validateEmail() ? $(formSelector).submit() : alert('Hay algun error en el formulario, revisa todos los campos.')
        //  if (validateEmail($(emailSelector).val() && checkPasswordLength($(passwordSelector)) &&))
    })

    function validateEmail() {
        return emailRegex.test($(emailSelector).val());
    }
    function inputStatus(idDiv,helperText,error) {
        if (error){
            $(idDiv).next().addClass(noValidSymbol);
            $(idDiv).next().removeClass(validSymbol);
            $(idDiv).nextAll().eq(1).html(helperText)
            $(idDiv).nextAll().eq(1).addClass('text-danger');
            $(idDiv).nextAll().eq(1).removeClass('text-success');
            $(idDiv).parent().addClass('has-error')
            $(idDiv).parent().removeClass('has-success')
        } else {
            $(idDiv).next().removeClass(noValidSymbol);
            $(idDiv).next().addClass(validSymbol);
            $(idDiv).nextAll().eq(1).html(helperText)
            $(idDiv).nextAll().eq(1).removeClass('text-danger');
            $(idDiv).nextAll().eq(1).addClass('text-success');
            $(idDiv).parent().removeClass('has-error')
            $(idDiv).parent().addClass('has-success')
        }
    }

})