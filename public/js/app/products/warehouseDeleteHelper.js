function DisableWareHouseToDelete(idCheckBox, idSelectWarehouse , idInputquantity , idHelper, idInputHiddenWarehouseId) {

    if ($(idCheckBox).prop("checked")){
        $(idSelectWarehouse).prop("disabled", true)
        $(idInputquantity).prop("disabled", true)
        $(idHelper).html("Este almacen se eliminara al enviar el formulario")
        $(idInputHiddenWarehouseId).prop("disabled", true)
        console.log('Checked.')
    } else {
        $(idSelectWarehouse).prop("disabled", false)
        $(idInputquantity).prop("disabled", false)
        $(idInputHiddenWarehouseId).prop("disabled", false)
        $(idHelper).empty()
    }
}