$(document).ready(()=>{

    const allFormats = document.getElementById('allFormatsScreen')

    const filterFormMedia = document.getElementById('filterFormMedia')
    filterFormMedia.onchange = () => {
        console.log(allFormats)
        formatsControl(allFormats)
        $('#staticBackdrop').modal('toggle')
        setTimeout(()=>{
            $('#staticBackdrop').modal('hide')
        }, 5000)
        console.log('here desktop')
       filterFormMedia.submit()
    }


    const allFormatsMobile = document.getElementById('allFormatsMobile')
    const filterForm = document.getElementById('filterForm');
     filterForm.onchange = () => {
         formatsControl(allFormatsMobile)
         $('#staticBackdrop').modal('toggle')
         setTimeout(()=>{
             $('#staticBackdrop').modal('hide')
         }, 5000)
         console.log('here mobile')
        filterForm.submit()
     }

    allFormats.onchange = () =>
    {
        formatsControl(allFormats)
    }

    allFormatsMobile.onchange = () =>
    {
        formatsMobileControl(allFormatsMobile)
    }

    const formatOptions = document.getElementById('formatOptions')
        formatOptions.onchange = () =>{
            allFormats.checked = false;
        }

    const formatOptionsMobile = document.getElementById('formatOptionsMobile')
    formatOptionsMobile.onchange = () =>{
        allFormatsMobile.checked = false;
    }
})

function formatsControl(allFormats) {
    if (allFormats.checked){
        $('#formatOptions input[type="checkbox"]:checked').each(function() {
            ($(this).prop('checked', false));
        });
    }
}

function formatsMobileControl(allFormats) {
    if (allFormats.checked){
        $('#formatOptionsMobile input[type="checkbox"]:checked').each(function() {
            ($(this).prop('checked', false));
        });
    }
}
