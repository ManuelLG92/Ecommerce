
$(document).ready(()=>{
    const validSymbol = 'icon-checkmark-circle'
    const noValidSymbol = 'icon-cancel-circle2'
    const emailRegex = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
    const formSelector = '#formCreateEstilkerUser'
    const emailSelector = '#email'
    const passwordSelector = '#password'
    const passwordConfirmSelector = '#passwordConfirm'
    const passwordComparisionInfo = '#passwordComparisionInfo'
    const buttonForm = '#createEstilkerUserBtn'
    const rolSelector = '#rol'

    $(formSelector).on('keypress', (e)=>{
        if (e.keyCode === 13){
            $(buttonForm).click()
        }
    })

    $(emailSelector).on('change keyup keypress', (e)=> {
        !validateEmail() ?
            inputStatus(emailSelector,'Introduce un email válido',true) :
            inputStatus(emailSelector,'Email válido',false)
    })

    $(passwordSelector).on('change keyup keypress', (e) => {

        if (checkPasswordLength(e.target.value)){
            inputStatus('#'+ e.target.id,'Contraseña Valida',false)
           passwordComparision() ? ShowPasswordComparisionInfo(false) : ShowPasswordComparisionInfo(true)
        } else {
            inputStatus('#'+ e.target.id,'La contraseña debe tener entre 8 y 32 caracteres',true)
            // inputStatus(passwordConfirmSelector,'Primero introduce una contraseña valida.',true)
        }
    })

    $(passwordConfirmSelector).on('change keyup keypress', (e) => {

        if (checkPasswordLength(e.target.value)){
            inputStatus('#'+ e.target.id,'Contraseña Valida',false)
            passwordComparision() ? ShowPasswordComparisionInfo(false) : ShowPasswordComparisionInfo(true)
        } else {
            inputStatus('#'+ e.target.id,'La contraseña de confirmacion debe tener entre 8 y 32 caracteres.',true)
        }
    })

    $(buttonForm).on('click', (e)=> {
        e.preventDefault();
        validatedForm() ? $(formSelector).submit() : alert('Hay algun error en el formulario, revisa todos los campos.')
    })

     function validatedForm() {
        return validateEmail() && checkPasswordLength($(passwordSelector).val())
            && checkPasswordLength($(passwordConfirmSelector).val()) && passwordComparision()
     }
    function validateEmail() {
        return emailRegex.test($(emailSelector).val());
    }

    function passwordComparision() {
        return $(passwordSelector).val() === $(passwordConfirmSelector).val()
    }

    function checkPasswordLength(password) {
        const min = 8;
        const max = 32;
        return password.length >=min && password.length<=max;
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

    function ShowPasswordComparisionInfo(error) {
        if (error){
            $(passwordComparisionInfo).html('Las contraseñas no coinciden <span style="font-size: x-large; color: orangered !important;">&#9888;</span>.');
            $(passwordComparisionInfo).addClass('text-danger');
            $(passwordComparisionInfo).removeClass('text-success');
        } else {
            $(passwordComparisionInfo).html('Las contraseñas coinciden <span style="font-size: x-large">&#10003;</span> .');
            $(passwordComparisionInfo).addClass('text-success');
            $(passwordComparisionInfo).removeClass('text-danger');
        }
    }

})
