 function SubmitForm(action, formId, inputControl) {
    let c = false;

    let inputValue = parseInt($(inputControl).val());
    if (action  === 'add'){
        if (!isNaN(inputValue) && inputValue >= 0 && inputValue < 100){
            c = confirm("Estas seguro de agregar este descuento?")
        } else {
            alert("El valor debe ser un numero entre el 0 al 100.")
        }

    }
    if (action === 'edit'){
        if (!isNaN(inputValue) && inputValue >= 0 && inputValue < 101) {
            c = confirm("Estas seguro de modificar este descuento?")
        } else {
            alert("El valor debe ser un numero entre el 0 al 100.")
        }

    }

    if (action === 'del'){
        c = confirm("Estas seguro de eliminar este descuento?")
    }
     //   c = confirm("Estas seguro de envia este formulario")
        if (c){
            formId.submit()
        }

    }