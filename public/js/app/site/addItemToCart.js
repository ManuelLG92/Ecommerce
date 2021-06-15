function addItemToCart(productId, inputQuantity) {
    const btnMedium = $('#btnAddToCartMedium');
    $(btnMedium).prop('disabled', true)
    //  console.log(productId, $(inputQuantity).val())
    const snackBar = $('#snackbar')
    $(btnMedium).html('Agregando...')
    //   $(this).prop('disabled', true)

    //  document.getElementById("btnAddToCart").disabled = true
    // $(btn).prop('disabled', true)
    $(this).html('Agregando..')
    //console.log($(this).text())
    $.post('/cart/add', {'product': productId, 'quantity': $(inputQuantity).val()}, (data) => {
        //console.log(data)
        $(btnMedium).html('Agregar mas')
        $(btnMedium).prop('disabled', false)
        $('#cartQuantity').html(data)

    }).error((xhr, status) => {

            if (xhr.status === 404) {
                $(snackBar).html('<span >No se ha podido encontrar el articulo, intentalo en unos minutos.</span>')
                $(snackBar).addClass("show");
                setTimeout(() => {
                    $(snackBar).removeClass("show");
                }, 3000);
            } else {
                $(snackBar).html('<span >Ha ocurrido un error al agregar el articulo, intentalo en unos minutos.</span>')
                $(snackBar).addClass("show");
                setTimeout(() => {
                    $(snackBar).removeClass("show");
                }, 3000);
                // console.log(xhr.status)
            }
            $(btnMedium).html('Agregar')
            $(btnMedium).prop('disabled', false)

        }
    )
}

function addItemToCartSmall(productId, inputQuantity) {
    const btnSmall = $('#btnAddToCartSmall');
    $(btnSmall).prop('disabled', true)
    //console.log(productId, $(inputQuantity).val())
    const snackBar = $('#snackbar')
    $(btnSmall).html('Agregando...')
    //   $(this).prop('disabled', true)

    //  document.getElementById("btnAddToCart").disabled = true
    // $(btn).prop('disabled', true)
    // $(this).html('Agregando..')

    $.post('/cart/add', {'product': productId, 'quantity': $(inputQuantity).val()}, (data) => {
        // console.log(data)
        $(btnSmall).html('Agregar mas')
        $(btnSmall).prop('disabled', false)
        $('#cartQuantity').html(data)

    }).error((xhr, status) => {

            if (xhr.status === 404) {
                $(snackBar).html('<span >No se ha podido encontrar el articulo, intentalo en unos minutos.</span>')
                $(snackBar).addClass("show");
                setTimeout(() => {
                    $(snackBar).removeClass("show");
                }, 3000);

            } else {

                $(snackBar).html('<span >Ha ocurrido un error al agregar el articulo, intentalo en unos minutos.</span>')
                $(snackBar).addClass("show");
                setTimeout(() => {
                    $(snackBar).removeClass("show");
                }, 3000);
                //console.log(xhr.status)
            }
            $(btnSmall).html('Agregar')
            $(btnSmall).prop('disabled', false)
        }
    )
}

function addItemToCartModal(buttonModal, productId, inputQuantity) {
    //  const btnSmall =  $('#btnAddToCartSmall');
    $(buttonModal).prop('disabled', true)
    //console.log(productId, $(inputQuantity).val())
    const snackBar = $('#snackbar')
    $(buttonModal).html('Agregando...')
    //   $(this).prop('disabled', true)

    //  document.getElementById("btnAddToCart").disabled = true
    // $(btn).prop('disabled', true)
    // $(this).html('Agregando..')

    $.post('/cart/add', {'product': productId, 'quantity': $(inputQuantity).val()}, (data) => {
        // console.log(data)
        $(buttonModal).html('Agregar mas')
        $(buttonModal).prop('disabled', false)
        $('#cartQuantity').html(data)

    }).error((xhr, status) => {

            if (xhr.status === 404) {
                $(snackBar).html('<span >No se ha podido encontrar el articulo, intentalo en unos minutos.</span>')
                $(snackBar).addClass("show");
                setTimeout(() => {
                    $(snackBar).removeClass("show");
                }, 3000);

            } else {

                $(snackBar).html('<span >Ha ocurrido un error al agregar el articulo, intentalo en unos minutos.</span>')
                $(snackBar).addClass("show");
                setTimeout(() => {
                    $(snackBar).removeClass("show");
                }, 3000);
                // console.log(xhr.status)
            }
            $(buttonModal).html('Agregar')
            $(buttonModal).prop('disabled', false)
        }
    )
}

function addItemToCartIntoCartView(buttonForm, productId, inputQuantity) {
    //  const btnSmall =  $('#btnAddToCartSmall');
    $(buttonForm).prop('disabled', true)
    //console.log(productId, $(inputQuantity).val())
    const snackBar = $('#snackbar')
    $(buttonForm).html('Agregando...')

    //   $(this).prop('disabled', true)
    //  document.getElementById("btnAddToCart").disabled = true
    // $(btn).prop('disabled', true)
    // $(this).html('Agregando..')

    $.post('/cart/add', {'product': productId, 'quantity': $(inputQuantity).val()}, (data) => {
        // console.log(data)
        $(buttonForm).html('Agregado')
        $(buttonForm).prop('disabled', false)
        $('#cartQuantity').html(data)

    }).error((xhr, status) => {

            if (xhr.status === 404) {
                $(snackBar).html('<span >No se ha podido encontrar el articulo, intentalo en unos minutos.</span>')
                $(snackBar).addClass("show");
                setTimeout(() => {
                    $(snackBar).removeClass("show");
                }, 3000);

            } else {

                $(snackBar).html('<span >Ha ocurrido un error al agregar el articulo, intentalo en unos minutos.</span>')
                $(snackBar).addClass("show");
                setTimeout(() => {
                    $(snackBar).removeClass("show");
                }, 3000);
                // console.log(xhr.status)
            }
            $(buttonForm).html('Agregar')
            $(buttonForm).prop('disabled', false)
        }
    )
}