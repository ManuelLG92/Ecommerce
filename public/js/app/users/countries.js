
$(document).ready(()=>{

    const unknownRegionAndCity =
        `      <div class="form-group regionUnknownDiv">
                <label class="col-lg-3 control-label" for="regionUnknown">Region: <span class="text-danger">*</span></label>
                <div class="col-lg-9 mb-20">
                         <input type="text" value=" " class="form-control" placeholder="Introduce una region" id="regionUnknown" name="regionUnknown" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label" for="cityUnknown">Ciudad: <span class="text-danger">*</span></label>
                <div class="col-lg-9 mb-20">
                         <input type="text" value=" "  class="form-control" placeholder="Introduce una ciudad" id="cityUnknown" name="cityUnknown" required>
                </div>
            </div>
`
    const colombiaRegionAndCityDiv = $('#regionAndCityDiv')
    const otherCountriesDiv = $('#otherCountries')
    const countrySelect = $('#country')
    // console.log(typeof countrySelect.val())
    let options = "";
    if (countrySelect.val() === '1'){
        let url = '/admin/user/regions/'+1;
        async function getRegionsByAjaxInit() {
            return await $.get(url, () =>{
            })
        }

        options = '<select data-placeholder="" class="form-control" name="region" id="region"\n' +
            '                                        required="required">'
        getRegionsByAjaxInit().then( (data) => {
            options += '<option value=" ">-- Selecciona una region --</option>'
            // console.log(data)
            for (let [key, value] of Object.entries(data)) {
                options +=  ` <option value="${value.id}">${value.name}</option>`

            }
            $('#region').html(options)

        }).catch(err => console.log(err))

    }

    countrySelect.on('change', function() {
        console.log($('#country').val())
        let url = `/admin/user/regions/${this.value}`
        console.log( this.value, url );
        if (countrySelect.val() === '1'){
            async function getRegionsByAjax() {
                return await $.get(url, () =>{
                })
            }
            colombiaRegionAndCityDiv.show();
            otherCountriesDiv.hide();
            getRegionsByAjax().then( (data) => {
                // console.log(data)
                options = '<select data-placeholder="" class="form-control" name="region" id="region" required="required">'
                options += '<option value=" ">-- Selecciona una region --</option>'
                for (let [key, value] of Object.entries(data)) {

                    options +=  ` <option value="${value.id}">${value.name}</option>`
                }
                options += ' </select>'
                $('#region').html(options)
                $('#city').html(  `<select data-placeholder="" class="form-control" name="city" id="city"
                                        required="required">
                                    <option value=" ">-- Primero selecciona una region --</option>
                </select>`)


            }).catch(err => console.log(err))
        } else {
            colombiaRegionAndCityDiv.hide();
            otherCountriesDiv.show();
            otherCountriesDiv.html(unknownRegionAndCity);
            /*  $('#region').html('<input type="text"  class="form-control" placeholder="Introduce una region" name="regionUnknown">')
              $('#city').html('<input type="text"  class="form-control" placeholder="Introduce una ciudad" name="cityUnknown">')*/
        }
    });





    $('#region').on('change', function(){

        if ($('#region').val() > 0)  {
            options = '<select data-placeholder="" class="form-control" name="city" id="city"\n' +
                '                                        required="required" >'
            let url = `/admin/user/cities/${this.value}`
            async function getCitiesByAjax() {
                return await $.get(url, () =>{
                })
            }
            getCitiesByAjax().then( (data) => {
                // console.log(data)
                options += '<option value="">-- Selecciona una ciudad --</option>'
                for (let [key, value] of Object.entries(data)) {
                    options +=  ` <option value="${value.id}">${value.name}</option>`
                }
                options += ' </select>'
                // console.log(options)
                $('#city').html(options)

            }).catch(err => console.log(err))
        }/* else {

            $('#cityDiv').html('<input type="text"  class="form-control" placeholder="Introduce una ciudad" id="city" name="city">')
        }*/
    })

    const citySelect = $('#city')
    citySelect.on('change', ()=>{
        let postalCode = ""
        let regionValue = $('#region').val();
        let cityValue = citySelect.val();
        postalCode = regionValue.toString() + cityValue.toString().padStart(4,'0');
        // console.log(postalCode)
        $('#postalCode').val(postalCode)

    })

    $('#btnFormUser').on('click', ()=>{
        console.log("clock")
        $('#userFormID').submit()
    })



})











