$(document).ready(()=>{

    $('#region').on('change', function(){

        if ($('#region').val() > 0)  {
            let options = '<select data-placeholder="" class="form-control" name="city" id="city"\n' +
                '                                        required="required" >'
            let url = `/admin/user/cities/${this.value}`
            async function getCitiesByAjax() {
                return await $.get(url, () =>{
                })
            }
            getCitiesByAjax().then( (data) => {
             //   console.log(data)
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
})