$(document).ready(()=>{
    $('#addTinyImage').click(()=>{
        let data = new Date()
        let random = data.getMilliseconds() * Math.floor(Math.random() * 100) + 1;
        let inputValue = 'min'+random;
        let divId = 'div'+random;

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