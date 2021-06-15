$(document).ready(()=>{
    const passwordFields =
`   
            <div class="form-group">
                <div class="row">
                    <label class="col-md-3 control-label text-right" for="password">Clave <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password" id="password" autocomplete="off">
                        <div class="form-control-feedback">
                            <i id="symbol" class=""></i>
                        </div>
                        <span class="help-block" id="helper-password"></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label class="col-md-3 control-label text-right" for="passwordConfirm">Confirma la clave <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="passwordConfirm" id="passwordConfirm" autocomplete="off">
                        <div class="form-control-feedback">
                            <i id="symbol" class=""></i>
                        </div>
                        <span class="help-block" id="helper-confirm"></span>
                    </div>
                </div>
            </div>
            <p  class="text-center" id="passwordComparisionInfo"></p>
 
`
    const pathControlEmail = 'js/app/users/emailControl.js'
    const pathControlUser = 'js/app/users/estilker_user_form.js'

})