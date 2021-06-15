function updateQuantityMinusClick(inputQuantityId, sumBtnId, minusId) {
    //console.log(inputQuantityId)
    let inputValue = parseInt($(inputQuantityId).val());
    inputValue === 1 ? disabledBtn(minusId) : enableBtn(minusId);
    if (inputValue > 1 ) {
        enableBtn(sumBtnId)
        if (inputValue > 101 ){
            disabledBtn(sumBtnId)
        }
        inputValue--;
        $(inputQuantityId).val(inputValue);
        inputValue < 2 ?  disabledBtn(minusId) : ''
    }
    //console.log($('#'+inputQuantityId).val(),sumBtnId,minusId)
}

function updateQuantitySumClick(inputQuantityId, sumBtnId, minusBtnId) {

    var inputValue = parseInt($(inputQuantityId).val());
    if (inputValue > 99){
        disabledBtn(sumBtnId)
        enableBtn(minusBtnId)
    } else {
        enableBtn(sumBtnId)

    }
    if (inputValue > 0 ) {
        if (inputValue > 0 ) {
            enableBtn(minusBtnId)
        }
        inputValue++;
        $(inputQuantityId).val(inputValue);
        if (inputValue > 99 ){
            disabledBtn(sumBtnId)

        }
    }
}

function updateQuantityInputChange(inputQuantityId, sumBtnId, minusBtnId) {

    const inputValue = parseInt($(inputQuantityId).val());
    if (inputValue === 1 || inputValue < 0) {
        disabledBtn(minusBtnId)
        enableBtn(sumBtnId)
        $(inputQuantityId).val(1);
    }
    if (inputValue > 1){
        enableBtn(minusBtnId)
        enableBtn(sumBtnId)
    }
    if (inputValue < 1){
        disabledBtn(minusBtnId)
        enableBtn(sumBtnId)
        $(inputQuantityId).val(1);
    }
    if (inputValue > 100) {
        disabledBtn(sumBtnId)
        enableBtn(minusBtnId)
        $(inputQuantityId).val(100);
    }
}

function disabledBtn(idSelector) {
    $(idSelector).prop('disabled', true);
}
function enableBtn(idSelector) {
    $(idSelector).prop('disabled', false);
}