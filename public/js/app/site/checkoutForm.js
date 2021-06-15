$(document).ready(()=>{

    let fieldErrors = {}

    let selectorName = "#name"
    let selectorSurname = "#surname"
    let selectorPhone = "#phone"

    $(selectorPhone).numeric();
    let selectorEmail = "#email"
    let selectorRegion = "#region"
    let selectorCity = "#city"
    let selectorAditionalInfo = "#additionalInfo"


    const snackBar = $('#snackbar')


    $(selectorName).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorName).prop("id")] = !($(selectorName).val().length >= 1 && $(selectorName).val().length <= 50);
        if ( fieldErrors[$(selectorName).prop("id")]){
            $(selectorName).addClass("is-invalid")
            $('.helperName').html(
                "Minimo " + 1 +
                " y maximo " + 50 + " caracteres.").addClass("text-danger");
        } else {
            $(selectorName).addClass("is-valid").removeClass("is-invalid")
            $('.helperName').html(
                "Nombre valido.").addClass("text-success").removeClass("text-danger");
        }
    })

    $(selectorSurname).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorSurname).prop("id")] = !($(selectorSurname).val().length >= 1 && $(selectorSurname).val().length <= 50);
        if ( fieldErrors[$(selectorSurname).prop("id")]){
            $(selectorSurname).addClass("is-invalid")
            $('.helperSurname').html(
                "Minimo " + 1 +
                " y maximo " + 50 + " caracteres.").addClass("text-danger");
        } else {
            $(selectorSurname).addClass("is-valid").removeClass("is-invalid")
            $('.helperSurname').html(
                "Apellido valido.").addClass("text-success").removeClass("text-danger");
        }
    })



    $(selectorRegion).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorRegion).prop("id")] = !($(selectorRegion).val().length >= 1 && $(selectorRegion).val().length <= 50);
        if ( fieldErrors[$(selectorRegion).prop("id")]){
            $(selectorRegion).addClass("is-invalid")
            $('.helperRegion').html(
                "Selecciona una región.").addClass("text-danger");
        } else {
            $(selectorRegion).addClass("is-valid").removeClass("is-invalid")
            $('.helperRegion').html(
                "Región valida.").addClass("text-success").removeClass("text-danger");
        }
    })


    $(selectorCity).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorCity).prop("id")] = !($(selectorCity).val().length >= 1 && $(selectorCity).val().length <= 50);
        if ( fieldErrors[$(selectorCity).prop("id")]){
            $(selectorCity).addClass("is-invalid")
            $('.helperCity').html(
                "Selecciona una ciudad.").addClass("text-danger");
        } else {
            $(selectorCity).addClass("is-valid").removeClass("is-invalid")
            $('.helperCity').html(
                "Ciudad valida.").addClass("text-success").removeClass("text-danger");
        }
    })

    $(selectorAditionalInfo).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorAditionalInfo).prop("id")] = $(selectorAditionalInfo).val().length > 255;
        if ( fieldErrors[$(selectorAditionalInfo).prop("id")]){
            $(selectorAditionalInfo).addClass("is-invalid")
            $('.helperInfo').html(
                "La informacion adicional no puede tener mas de 255  caracteres. " +
                $(selectorAditionalInfo).val().length+ "/ 255." ).addClass("text-danger");
        } else {
            $(selectorAditionalInfo).addClass("is-valid").removeClass("is-invalid")
            $('.helperInfo').html(
                "Informacion adicional valida - caracteres: "+
                $(selectorAditionalInfo).val().length+ "/ 255.").addClass("text-success").removeClass("text-danger");
        }
    })

    $(selectorPhone).on('keyup change keypress', ()=>{
        fieldErrors[$(selectorPhone).prop("id")] = !($(selectorPhone).val().length >= 7 && $(selectorPhone).val().length <= 30);
        if ( fieldErrors[$(selectorPhone).prop("id")]){
            $(selectorPhone).addClass("is-invalid")
            $('.helperPhone').html(
                "Minimo " + 7 +
                " y maximo " + 30 + " caracteres.").addClass("text-danger");
        } else {
            $(selectorPhone).addClass("is-valid").removeClass("is-invalid")
            $('.helperPhone').html(
                "Telefono valido.").addClass("text-success").removeClass("text-danger")
        }
    })

    $(selectorEmail).on('keyup change keypress', ()=>{
        //fieldErrors[$(selectorEmail).prop("id")] = !($(selectorEmail).val().length >= 7 && $(selectorEmail).val().length <= 30);
        fieldErrors[$(selectorEmail).prop("id")] = !/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test($(selectorEmail).val());
        if ( fieldErrors[$(selectorEmail).prop("id")]){
            $(selectorEmail).addClass("is-invalid")
            $('.helperEmail').html(
                "Email no valido.").addClass("text-danger");
        } else {
            $(selectorEmail).addClass("is-valid").removeClass("is-invalid")
            $('.helperEmail').html(
                "Email valido.").addClass("text-success").removeClass("text-danger")
        }
    })


    $(selectorRegion).on('change', () => {
        if ($(selectorRegion).val() === ""){
           $('#city').html(`<option value="" selected>-- Selecciona un departamento --</option>`)
            $('#city').removeClass('is-valid')
            $('.helperCity').html('')
        }
        let options;
        const currentValue = $(selectorRegion).val();
        if (currentValue > 0) {
            options = ''
            let url = `/cart/cities/${currentValue}`

            async function getCitiesByAjax() {
                return await $.get(url, () => {
                })
            }

            getCitiesByAjax().then((data) => {
                // console.log(data)
                options += '<option value="">-- Selecciona una ciudad --</option>'
                for (let [key, value] of Object.entries(data)) {
                    options += ` <option value="${value.id}">${value.name}</option>`
                }
                options += ' </select>'
                // console.log(options)
                $('#city').html(options)

            }).catch(err => console.log(err))
        }
    })


    $('#checkoutFormBtn').on('click', ()=>{
        $('#checkoutFormBtn').prop('disabled', true)
     // console.log(fieldErrors)
        // console.log(fieldErrors[$(selectorEmail).prop("id")])
        let error = false;
        if (Object.keys(fieldErrors).length > 5) {
            Object.values(fieldErrors).forEach(val => {

                if(val === true){
                    error = true;
                    return true
                }
            })} else {

            $(snackBar).html('<span>Rellena todos los campos obligatorios marcados con <span class="text-danger">*</span></span>')
            $(snackBar).addClass("show");
            setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
            $('#checkoutFormBtn').prop('disabled', false)
            return false
        }

                if (error){

                    $(snackBar).html('<span>Rellena todos los campos obligatorios marcados con <span class="text-danger">*</span></span>')
                    $(snackBar).addClass("show");
                    setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
                    $('#checkoutFormBtn').prop('disabled', false)

                } else {
                    fieldErrors[$(selectorEmail).prop("id")] = !/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test($(selectorEmail).val());
                    if ( fieldErrors[$(selectorEmail).prop("id")]){
                        $(snackBar).html('<span>Introduce un email valido.</span>')
                        $(snackBar).addClass("show");
                        setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
                        $('#checkoutFormBtn').prop('disabled', false)
                    } else {
                        fieldErrors[$(selectorPhone).prop("id")] = !($(selectorPhone).val().length >= 7 && $(selectorPhone).val().length <= 30);
                        if ( fieldErrors[$(selectorPhone).prop("id")]){
                            $(snackBar).html('<span>Introduce un numero telefono valido.</span>')
                            $(snackBar).addClass("show");
                            setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
                            $('#checkoutFormBtn').prop('disabled', false)


                        } else {
                           // $('#checkoutFormBtn').prop('disabled', false)
                                if ( $(selectorRegion).val() >0 && $(selectorCity).val() >0){
                                    $('#checkoutForm').submit()
                                   // console.log('todo valido')
                                } else {
                                    $(snackBar).html('<span>Rellena todos los campos obligatorios marcados con <span class="text-danger">*</span></span>')
                                    $(snackBar).addClass("show");
                                    setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
                                    $('#checkoutFormBtn').prop('disabled', false)
                                    //console.log('aun falta')
                                }

                                //$(document).find('form').submit();
                                //$('#checkoutForm').submit()

                        }

                    }

                }


    })



})