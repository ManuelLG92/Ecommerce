$(document).ready(()=>{

    $('#catalogue').addClass('active-nav-page-border')

// *********** Medium and higher screens display  ********************* //
    $('#quantity').on('change click touchstart',()=>{
        let inputValue = parseInt($('#quantity').val());
        if (inputValue === 1 || inputValue < 0) {
            disabledBtn('#minus')
            enableBtn('#sum')
            $('#quantity').val(1);
        }
        if (inputValue > 1){
            enableBtn('#minus')
            enableBtn('#sum')
        }
        if (inputValue > 99) {
            disabledBtn('#sum')
            enableBtn('#minus')
            $('#quantity').val(100);
        }
    })

    $('#minus').on('click touchstart', ()=>{
        let inputValue = parseInt($('#quantity').val());
        inputValue === 1 ? disabledBtn('#minus') : enableBtn('#minus');
        if (inputValue > 1 ) {
            enableBtn('#sum')
            if (inputValue > 101 ){
                disabledBtn('#sum')
            }
            inputValue--;
            $('#quantity').val(inputValue);
            inputValue < 2 ?  disabledBtn('#minus') : ''
        }
    })

    $('#sum').on('click touchstart', ()=>{
        var inputValue = parseInt($('#quantity').val());
        if (inputValue > 99){
            disabledBtn('#sum')
            enableBtn('#minus')
        } else {
            enableBtn('#sum')

        }
        if (inputValue > 0 ) {
            if (inputValue > 0 ) {
                enableBtn('#minus')
            }
            inputValue++;
            $('#quantity').val(inputValue);
            if (inputValue > 99 ){
                disabledBtn('#sum')

            }
        }

    })
// *********** End medium and higher screens display  ********************* //

// *********** Small screens display  ********************* //

    $('#quantityMobile').on('change ',()=>{
        let inputValue = parseInt($('#quantityMobile').val());
        if (inputValue === 1 || inputValue < 0) {
            disabledBtn('#minusMobile')
            enableBtn('#sumMobile')
            $('#quantity').val(1);
        }
        if (inputValue > 1){
            enableBtn('#minusMobile')
            enableBtn('#sumMobile')
        }
        if (inputValue > 99) {
            disabledBtn('#sumMobile')
            enableBtn('#minusMobile')
            $('#quantityMobile').val(100);
        }
    })

    $('#minusMobile').on('click ', ()=>{
        let inputValue = parseInt($('#quantityMobile').val());
        inputValue === 1 ? disabledBtn('#minusMobile') : enableBtn('#minusMobile');
        if (inputValue > 1 ) {
            enableBtn('#sumMobile')
            if (inputValue > 100 ){
                disabledBtn('#sumMobile')
            }
            inputValue--;
            $('#quantityMobile').val(inputValue);
            inputValue < 2 ?  disabledBtn('#minusMobile') : ''
        }
    })

    $('#sumMobile').on('click ', ()=>{
        var inputValue = parseInt($('#quantityMobile').val());
        if (inputValue > 99){
            disabledBtn('#sumMobile')
            enableBtn('#minusMobile')
        } else {
            enableBtn('#sumMobile')

        }
        if (inputValue > 0 ) {
            if (inputValue > 0 ) {
                enableBtn('#minusMobile')
            }
            inputValue++;
            $('#quantityMobile').val(inputValue);
            if (inputValue > 99 ){
                disabledBtn('#sumMobile')

            }
        }

    })

})

// *********** End Small screens display  ********************* //


function disabledBtn(idSelector) {
    $(idSelector).prop('disabled', true);
}
function enableBtn(idSelector) {
    $(idSelector).prop('disabled', false);
}


$(window).on('unload',()=>{
    $('#catalogue').removeClass('active-nav-page-border')
})

