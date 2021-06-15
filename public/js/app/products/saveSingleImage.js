$(document).ready(() => {
    $('#addTinyImage').click(() => {
        let data = new Date()
        let random = data.getMilliseconds() * Math.floor(Math.random() * 100) + 1;
        let inputValue = 'min' + random;
        let divId = 'div' + random;

        let fileDiv = `<div id="${divId}"><div class="form-group" >
                                           <label class="col-lg-3 control-label">Miniaturas:</label>
                                           <div class="col-lg-9">
                                               <input type="file" name="${inputValue}" class="form-control">
                                           </div>
                                        </div>
                        
                                       <div class="text-center">
                             <button type="button" class="btn btn-danger mb-20" onclick="deleteImage(${divId})">Eliminar esta imagen miniatura
                             <i class="icon-cancel-square position-right"></i></button>
                         </div> <hr>
                         </div>
                         <hr class="sub_line_break">
                        
                         `

        //$('.stock-almacen').html += fileDiv;

        $(fileDiv).appendTo($('#tinyImg'));

        // console.log(fileDiv )
        /* console.log(Math.floor(Math.random() * 100) + 1);*/
        // console.log('m'+random)
        // alert(random)
    })
})

function deleteImage(divId) {
    // console.log("deleteImg")
    $(divId).remove();
}

function AddMediaInput(productId) {
    //console.log(window.location.origin)
    let data = new Date()
    let random = data.getMilliseconds() * Math.floor(Math.random() * 100) + 1;
    let inputValue = 'min' + random;
    let divId = 'div' + random;
    const url = window.location.origin + '/admin/product/' + productId + '/save-picture/min'
    console.log(url)
    let fileDiv = `
    <div id="${divId}" class="col-xs-12">
        <div class="form-group" >
            <form method="post" action="${url}" enctype="multipart/form-data"
                      onsubmit="return confirm('Estas seguro de guardar esta imagen miniatura?');">
                          
                            <div class="col-xs-12">
                            
</div>
                           
                           <div class="col-xs-12 col-sm-4 text-center">
                           <label >Agrega una foto miniatura</label>
                               <input type="file" class="form-control-file" name="min" class="form-control">
                           </div>
                         
                        
                  
                            <div class="col-xs-6 col-sm-4 mb-4 text-center">
                             <button type="submit" class="btn btn-lg btn-primary  mb-20">Guardar</button>
                            </div>
                       
                           <div class="col-xs-6 col-sm-4 mb-4 text-start">
                                <button type="button" class="btn btn-danger mb-20 " onclick="deleteImage(${divId})">Eliminar campo
            <i class="icon-cancel-square position-right"></i></button>
                            </div>
                            
                     
                            
                           
            </form>                              
        </div>               
        <hr class="sub_line_break">
</div>
                        
                         `

 /*       < div
    className = "text-center" >
        < button
    type = "button"
    className = "btn btn-danger mb-20"
    onClick = "deleteImage(${divId})" > Eliminar
    esta
    imagen
    miniatura
    < i
    className = "icon-cancel-square position-right" > < /i></
    button >
    < /div> */

    //$('.stock-almacen').html += fileDiv;
    $('#tinyImg').append($(fileDiv))
  //  $(fileDiv).appendTo($('#tinyImg'));

}