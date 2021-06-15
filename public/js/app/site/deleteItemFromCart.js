$(document).ready(() => {
    $('.cart-zone').find('a').click(() => {
        return confirm("Estas seguro de eliminar este articulo?");
    })
})

/*
function deleteItemFromCart(productId, divId, btnId) {

    var confirm = window.confirm("Estas seguro de eliminar este item?");
    if (confirm) {
    const snackBar = $('#snackbar')
    let buttonId = $(btnId);
        $(buttonId).html('Eliminando...')
        $(buttonId).prop('disabled', true)
    $.post('/cart/remove-item',{ 'product' : productId}, (data)=>{
    console.log(data)
   $(divId).remove();
    $('#cartQuantity').html(data)
        $(snackBar).html('<span >Articulo eliminado satisfactoriamente de tu cesta de compra.</span>')
        $(snackBar).addClass("show");
        setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
        if (parseInt(data) === 0){
            location.reload();
        }


}).error((xhr, status ) => {
        if (xhr.status === 404){
            $(snackBar).html('<span >No se ha podido eliminar el articulo, intentalo en unos minutos.</span>')
            $(snackBar).addClass("show");
            setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
        } else {
            $(snackBar).html('<span >Ha ocurrido un error al eliminar el articulo, intentalo en unos minutos.</span>')
            $(snackBar).addClass("show");
            setTimeout(() => { $(snackBar).removeClass("show"); }, 3000);
            console.log(xhr.status)
        }
        $(buttonId).html('Eliminar')
        $(buttonId).prop('disabled', false)

        }

    )
    }
}*/
