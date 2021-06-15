$(document).ready(()=>{

    const name = $('#name');
    const validSymbol = 'icon-checkmark-circle'
    const noValidSymbol = 'icon-cancel-circle2'
    const symbolSelector = $('#symbol')
    const formIdSelector = '#formWarehouse'
    let error = true;
    name.val().length > 0 && name.val().length < 100 ? error = false : error = true;

 /**/   $(formIdSelector).on('keyup keypress', function(e) {
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

    $('#warehouseFormBtn').click(()=>{
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
            $(helper).html("El almacen debe tener entre 1 a 100 caracteres")
            $(showError).addClass('has-error')
            $(showError).removeClass('has-success')
        } else {

            error = false;
            $(symbolSelector).addClass(validSymbol);
            $(symbolSelector).removeClass(noValidSymbol);
            $(helper).html("Almacen valido")
            $(showError).addClass('has-success')
            $(showError).removeClass('has-error')
        }
    })

    $('#addNewWarehouses').click(()=>{
        console.log("Exec")
        let data = new Date()
        let random = data.getMilliseconds() * Math.floor(Math.random() * 100) + 1;
        let inputValue = 'newWs'+random;
        let labelRandomIdValue = 'l'+random
        let UtilRandomValue = 'newSw'+random;
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



})

