$(document).ready(()=>{

    const snackBar = $('#snackbar')
    const regexEmail = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
    let fieldErrors = {}
    let requiredFields = document.getElementById('requiredFields');

    let selectorName = "#name"
    let selectorSurname = "#surname"
    let selectorPhone = "#phone"
    let selectorEmail = "#email"
    let selectorCountry = "#country"
    let selectorRegion = "#region"
    let selectorCity = "#city"
    let selectorAditionalInfo = "#additionalInfo"
    let selectorCheckBox = "#checkBox"

    $(selectorPhone).numeric();


    requiredFields.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("contactFormButton").click();
        }
    });


    $(selectorName).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorName).prop("id")] = !($(selectorName).val().length >= 1 && $(selectorName).val().length <= 50);
       if ( fieldErrors[$(selectorName).prop("id")]){
           $(selectorName).addClass("is-invalid")
           $('.helperName').html(
               "El nombre debe tener mínimo " + 1 +
               " y máximo " + 50 + " caracteres.").addClass("text-danger");
        } else {
           $(selectorName).addClass("is-valid").removeClass("is-invalid")
           $('.helperName').html(
               "Nombre válido.").addClass("text-success").removeClass("text-danger");
       }
    })

    $(selectorSurname).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorSurname).prop("id")] = !($(selectorSurname).val().length >= 1 && $(selectorSurname).val().length <= 50);
        if ( fieldErrors[$(selectorSurname).prop("id")]){
            $(selectorSurname).addClass("is-invalid")
            $('.helperSurname').html(
                "El apellido debe tener mínimo " + 1 +
                " y máximo " + 50 + " caracteres.").addClass("text-danger");
        } else {
            $(selectorSurname).addClass("is-valid").removeClass("is-invalid")
            $('.helperSurname').html(
                "Apellido válido.").addClass("text-success").removeClass("text-danger");
        }
    })

    $(selectorCountry).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorCountry).prop("id")] = !($(selectorCountry).val().length >= 1 && $(selectorCountry).val().length <= 50);
        if ( fieldErrors[$(selectorCountry).prop("id")]){
            $(selectorCountry).addClass("is-invalid")
            $('.helperCountry').html(
                "El país debe tener mínimo " + 1 +
                " y máximo " + 50 + " caracteres.").addClass("text-danger");
        } else {
            $(selectorCountry).addClass("is-valid").removeClass("is-invalid")
            $('.helperCountry').html(
                "País válido.").addClass("text-success").removeClass("text-danger");
        }
    })

    $(selectorRegion).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorRegion).prop("id")] = !($(selectorRegion).val().length >= 1 && $(selectorRegion).val().length <= 50);
        if ( fieldErrors[$(selectorRegion).prop("id")]){
            $(selectorRegion).addClass("is-invalid")
            $('.helperRegion').html(
                "La región debe tener mínimo " + 1 +
                " y máximo " + 50 + " caracteres.").addClass("text-danger");
        } else {
            $(selectorRegion).addClass("is-valid").removeClass("is-invalid")
            $('.helperRegion').html(
                "Región válida.").addClass("text-success").removeClass("text-danger");
        }
    })


    $(selectorCity).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorCity).prop("id")] = !($(selectorCity).val().length >= 1 && $(selectorCity).val().length <= 50);
        if ( fieldErrors[$(selectorCity).prop("id")]){
            $(selectorCity).addClass("is-invalid")
            $('.helperCity').html(
                "La ciudad debe tener mínimo " + 1 +
                " y máximo " + 50 + " caracteres.").addClass("text-danger");
        } else {
            $(selectorCity).addClass("is-valid").removeClass("is-invalid")
            $('.helperCity').html(
                "Ciudad válida.").addClass("text-success").removeClass("text-danger");
        }
    })

    $(selectorAditionalInfo).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorAditionalInfo).prop("id")] = $(selectorAditionalInfo).val().length > 255;
        if ( fieldErrors[$(selectorAditionalInfo).prop("id")]){
            $(selectorAditionalInfo).addClass("is-invalid")
            $('.helperInfo').html(
                "La informacion adicional no puede tener más de 255  caracteres. " +
                $(selectorAditionalInfo).val().length+ "/ 255." ).addClass("text-danger");
        } else {
            $(selectorAditionalInfo).addClass("is-valid").removeClass("is-invalid")
            $('.helperInfo').html(
                "Informacion adicional válida - caracteres: "+
                $(selectorAditionalInfo).val().length+ "/ 255.").addClass("text-success").removeClass("text-danger");
        }
    })

    $(selectorPhone).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorPhone).prop("id")] = !($(selectorPhone).val().length >= 7 && $(selectorPhone).val().length <= 30);
        if ( fieldErrors[$(selectorPhone).prop("id")]){
            $(selectorPhone).addClass("is-invalid")
            $('.helperPhone').html(
                "El teléfono debe tener mínimo " + 7 +
                " y máximo " + 30 + " caracteres.").addClass("text-danger");
        } else {
            $(selectorPhone).addClass("is-valid").removeClass("is-invalid")
            $('.helperPhone').html(
                "Teléfono válido.").addClass("text-success").removeClass("text-danger")
        }
    })

    $(selectorEmail).on('keyup change keypress', ()=>{
        //fieldErrors[$(selectorEmail).prop("id")] = !($(selectorEmail).val().length >= 7 && $(selectorEmail).val().length <= 30);
        fieldErrors[$(selectorEmail).prop("id")] = !regexEmail.test($(selectorEmail).val());
        if ( fieldErrors[$(selectorEmail).prop("id")]){
            $(selectorEmail).addClass("is-invalid")
            $('.helperEmail').html(
                "Email no válido.").addClass("text-danger");
        } else {
            $(selectorEmail).addClass("is-valid").removeClass("is-invalid")
            $('.helperEmail').html(
                "Email válido.").addClass("text-success").removeClass("text-danger")
        }
    })

    $(selectorCheckBox).on('click change', ()=>{
        fieldErrors[$(selectorCheckBox).prop("id")] = $(selectorCheckBox).prop("checked") !== true;
    })

    $('#contactFormButton').on('click', ()=>{
       // console.log(fieldErrors[$(selectorEmail).prop("id")])
        let error = false;
        if (Object.keys(fieldErrors).length > 6) {
        Object.values(fieldErrors).forEach(val => {
          //  console.log(val)
            if(val === true){
                error = true;
                return true
            }

            if (error){
                $(snackBar).html('<span>Rellena todos los campos obligatorios para enviar el formulario</span>')
                $(snackBar).addClass("show");
                setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
            } else {
                fieldErrors[$(selectorEmail).prop("id")] = !regexEmail.test($(selectorEmail).val());
                if ( fieldErrors[$(selectorEmail).prop("id")]){
                    $(snackBar).html('<span>Introduce un email válido.</span>')
                    $(snackBar).addClass("show");
                    setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
                } else {
                    fieldErrors[$(selectorPhone).prop("id")] = !($(selectorPhone).val().length >= 7 && $(selectorPhone).val().length <= 30);
                    if ( fieldErrors[$(selectorPhone).prop("id")]){
                        $(snackBar).html('<span>Introduce un numero teléfono válido.</span>')
                        $(snackBar).addClass("show");
                        setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
                    } else {
                        if($(selectorCheckBox).prop("checked") === true){

                            $('#contactForm').submit()
                            $('#contactFormButton').prop("disabled", true);

                            setTimeout(()=>{
                                $('#contactFormButton').prop("disabled", false);
                                $(snackBar).html('<span>No se pudo enviar el formulario, intentalo en unos minutos.</span>')
                                $(snackBar).addClass("show");
                                setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
                            }, 5000)

                        } else {
                            $(snackBar).html('<span>Debes aceptar los terminos sobre politica y privacidad.</span>')
                            $(snackBar).addClass("show");
                            setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
                        }
                    }

                }

            }
        })} else {
            $(snackBar).html('<span>Rellena todos los campos obligatorios para enviar el formulario</span>')
            $(snackBar).addClass("show");
            setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
        }
    })



})