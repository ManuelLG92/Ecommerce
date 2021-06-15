$(document).ready(()=>{
    const eyePasswordElement = $('#eye-password')
    const emailRegex = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
    const email = $(".email")
    const password = $('.password')
    const emailHelp = $('#emailHelp')
    const passwordHelp = $('#passwordHelp')
    const btnForm = $('#btnAgentSignIn')
    const form = $('#formAgent')
    const snackBar = $('#snackbar')

    $(form).on('keypress', (e)=>{
        if (e.keyCode === 13){
            $(btnForm).click()
        }
    })

    $(btnForm).on('click', (e)=> {
        e.preventDefault();
        validateEmail() && checkPasswordLength? form.submit() : snackBar.html('<span>Debes rellenar los campos obligatorios.</span>').addClass("show")
        setTimeout(()=> {
            snackBar.removeClass("show");
        }, 3000)
    })


    eyePasswordElement.on('click', ()=> {
        let typeInput;
        let eyeClass;
       eyePasswordElement.prev().prop('type') === 'text' ? (typeInput = 'password', eyeClass="icon-eye-blocked")  : (typeInput = 'text', eyeClass="icon-eye")
        eyePasswordElement.prev().prop('type', typeInput)
        eyePasswordElement.find(">:first-child").removeClass().addClass(eyeClass);
    })

    email.on('change keyup keypress', (e)=> {
        !validateEmail() ?
            inputStatus(email,emailHelp,'Introduce un email válido.',true) :
            inputStatus(email,emailHelp,'Email válido.',false)
    })

    password.on('change keyup keypress', (e) => {
        if (e.which === 32){
            return false;
        }
        if (checkPasswordLength()){
            inputStatus(password, passwordHelp,'Contraseña válida.',false)
        } else {
            inputStatus(password, passwordHelp,'La contraseña debe tener entre 8 y 32 caracteres.',true)
        }
    })

    function validateEmail() {
        return emailRegex.test(email.val());
    }

    function inputStatus(input,helper, helperText,error) {
        if (error){
            input.removeClass('is-valid').addClass('is-invalid');
            helper.removeClass('text-success').addClass('text-danger').html(helperText)
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            helper.removeClass('text-danger').addClass('text-success').html(helperText)
        }
    }

    function checkPasswordLength() {
        const min = 8;
        const max = 32;
        return password.val().length >=min && password.val().length<=max;
    }


})