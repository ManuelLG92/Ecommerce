$(document).ready(()=>{


    $('#addWarehouses').click(()=>{
        let data = new Date()
        let random = data.getMilliseconds() * Math.floor(Math.random() * 100) + 1;
        let inputValue = 'ws'+random;
        let labelRandomIdValue = 'l'+random
        let UtilRandomValue = 'sw'+random;
       // let UtilIdRandomValue = 'id'+random;
        let divId = 'div'+random;
        let options = ` <option value="">-- Almacen --</option>`;


    async function getWarehousesByAjax() {

       return await $.get("/admin/warehouse/json", () =>{
        })

    }

        getWarehousesByAjax().then( (data) => {

            let initialObject = Object.entries(data['warehouses'])

            for (let [key, value] of Object.entries(initialObject)) {
                for (let [subkey, subvalue] of Object.entries(value)) {
                    if ([subvalue['id']] !== undefined && subvalue['name'] !== undefined) {
                        options +=  ` <option value="${subvalue['id']}">${subvalue['name']}</option>`
                    }
                }
            }

            let newWarehouseDiv = `
                                <div id="${divId}">
                                
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="${UtilRandomValue}" id="${labelRandomIdValue}">Almacen: <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="" class="form-control warehouseControl" name="${UtilRandomValue}" id="${UtilRandomValue}" required="required">                       
                                            ${options}
              
                                        </select>
                                     
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="${inputValue}">Cantidad: <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input type="text" name="${inputValue}" id="${inputValue}" 
                                        class="form-control" placeholder="Ej. ${random}" required >
                                    </div>
                                </div>
          
                                  <div class="text-center">
                             <button type="button" class="btn btn-danger mb-20" onclick="deleteWarehouse(${divId})">Eliminar este almacen
                             <i class="icon-cancel-square position-right"></i></button>
                         </div> 
                         <hr>
                            </div>
                                `;


            $(newWarehouseDiv).appendTo($('.stock-almacen'));

        })


    })


    let optionsSelectSet = '';

    for (let i = 2 ; i < 21; i++) {
        optionsSelectSet +=  ` <option value="${i}">${i}</option>`
    }

    divSelectSet = `
                    <div class="col-xs-3">
                        <label class="control-label mt-10" for="setQuantity">Cantidad: </label>
                    </div>
                    
                    <div class="col-xs-3" >
                    <select name="setQuantity" id="setQuantity" class="mt-5" required>
                    ${optionsSelectSet}
                    </select>
                    </div>
    `
    divNoSelectSet = `
        <div class="col-xs-3">
           
        </div>
        <div class="col-xs-3" >
            <input type="text" name="setQuantity" id="setQuantity" value="1" hidden >
        </div>
    `


    $('#setCheckBox').click(()=>{
        let divContentToReplace = '#quantitySetDiv'


        if ($('#setCheckBox').prop("checked")){

            $(divContentToReplace).html(divSelectSet)
        } else {
            $(divContentToReplace).html(divNoSelectSet)

        }
    })

})

function deleteWarehouse(divId) {
    $(divId).remove();
}



    function getSelectValues() {
        var array = [];
        $('select.warehouseControl:not(":disabled") option:selected').each(function() {
            array[ Math.floor(Math.random() * 100) + 1] =  $(this).val();
        });

        return array;

    }

function getEnabledSelects() {

   var enaledSelects = [];
    $('select.warehouseControl:not(":disabled")').each(function() {
        enaledSelects[ Math.floor(Math.random() * 100) + 1] =  $(this).val();

    });

    return enaledSelects;
}

function CheckForm() {

    if (getEnabledSelects().length === 0){
        alert('Debe haber algun almacen para el producto')
        return false
    }
    if (hasDuplicates(getSelectValues())) {
        alert('Algun almacen esta duplicado.')
    }

   return !hasDuplicates(getSelectValues());


}
function hasDuplicates(arr) {
    return arr.some( function(item) {
        return arr.indexOf(item) !== arr.lastIndexOf(item);
    });
}

