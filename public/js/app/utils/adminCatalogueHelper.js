$(document).ready(()=>{
    const divNewCatalogue = $('#newCatalogueDiv')
    divNewCatalogue.hide()
    const btnShowNewCatalogue = $('#newCatalogueBtn')
    const catalogueInput = $('#catalogue')
    const form = $('#newCatalogueForm')

    btnShowNewCatalogue.on('click', ()=>{
        divNewCatalogue.css('display') === 'none' ?
            (
                divNewCatalogue.fadeIn(),
                btnShowNewCatalogue.html(`Descartar   <i class="icon-chevron-up"></i>`))
      :
            (
                form.trigger("reset"),
                    catalogueInput.next().text('Select a File'),
                divNewCatalogue.fadeOut(),
                btnShowNewCatalogue.html(`Agregar   <i class="icon-chevron-down"></i>`)
            )


    })

    $('input:file').change(
        (e) => {
            let typeFile = '';
            let sizeFile = '';
            const files = e.originalEvent.target.files;
           for (let i=0; i<files.length; i++){
                    sizeFile = files[i].size
                    typeFile = files[i].type;
            }
            console.log(sizeFile,typeFile)
        })

})