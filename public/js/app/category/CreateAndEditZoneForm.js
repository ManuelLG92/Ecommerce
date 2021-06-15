$(document).ready(()=>{

    const name = $('#name');
    const validSymbol = 'icon-checkmark-circle'
    const noValidSymbol = 'icon-cancel-circle2'
    const symbolSelector = $('#symbol')
    const formIdSelector = '#formZone'
    let error = true;
    name.val().length > 0 && name.val().length < 100 ? error = false : error = true;


    $(formIdSelector).on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            if (!error){
                console.log(error)
                $(formIdSelector).submit()
            } else {
                e.preventDefault();
                alert("El campo nombre no puede estar vacio.")
                return false; }

        }
    });

    $('#zoneFormBtn').click(()=>{
        if (!error){
            $(formIdSelector).submit()
        } else {
            alert("El campo nombre no puede estar vacio.")
        }
    })


    $(name).on('keyup change keypress', ()=>{

        let nameValue = name.val();
        let showError = $('#divValidation')
        let helper = $('#helper')

        if (nameValue.length <1 || nameValue.length>100){
            error = true;

            $(symbolSelector).addClass(noValidSymbol);
            $(symbolSelector).removeClass(validSymbol);
            $(helper).html("La zona debe tener entre 1 a 100 caracteres")
            $(showError).addClass('has-error')
            $(showError).removeClass('has-success')
        } else {

            error = false;
            $(symbolSelector).addClass(validSymbol);
            $(symbolSelector).removeClass(noValidSymbol);
            $(helper).html("Zona valida")
            $(showError).addClass('has-success')
            $(showError).removeClass('has-error')
        }
    })



})

