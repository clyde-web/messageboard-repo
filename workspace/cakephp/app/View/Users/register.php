<div class="nk-content">
    <div class="nk-block nk-block-middle nk-auth-body wide-xs py-0">
    <div id="formError" class="alert alert-danger d-none"></div>
        <div class="card card-bordered">
            <div class="card-inner">
                <h3 class="fw-bold text-primary">Registration</h3>
                <?php echo $this->Form->create('User', array('url' => 'register')); ?>
                <div class="form-group">
                    <label for="UserName" class="form-label">
                        Name <span class="text-danger">*</span>
                    </label>
                <?php echo $this->Form->input('name', array(
                    'label' => false,
                    'placeholder' => 'Enter your name',
                    'class' => 'form-control',
                    'div' => 'form-control-wrap',
                    'error' => false,
                    'required' => false
                )); ?>
                </div>
                <div class="form-group">
                    <label for="UserEmail" class="form-label">
                        Email <span class="text-danger">*</span>
                    </label>
                <?php echo $this->Form->input('email', array(
                    'label' => false,
                    'placeholder' => 'Enter your email address',
                    'class' => 'form-control',
                    'div' => 'form-control-wrap',
                    'error' => false,
                    'required' => false,
                )); ?>
                </div>
                <div class="form-group">
                    <label for="UserPassword" class="form-label">
                        Password <span class="text-danger">*</span>
                    </label>
                    <div class="form-control-wrap">
                        <a href="#" tabindex="-1" class="form-icon form-icon-right passcode-switch" data-target="UserPassword">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <?php echo $this->Form->input('password', array(
                            'label' => false,
                            'placeholder' => 'Enter your password',
                            'class' => 'form-control',
                            'div' => false,
                            'error' => false,
                            'required' => false
                        )); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="UserPasswordConfirmation" class="form-label">
                        Confirm Password <span class="text-danger">*</span>
                    </label>
                    <div class="form-control-wrap">
                        <a href="#" tabindex="-1" class="form-icon form-icon-right passcode-switch" data-target="UserPasswordConfirmation">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <?php echo $this->Form->input('password_confirmation', array(
                            'type' => 'password',
                            'label' => false,
                            'placeholder' => 'Confirm your password',
                            'class' => 'form-control',
                            'div' => false,
                            'error' => false,
                            'required' => false
                        )); ?>
                    </div>
                </div>
                
                <?php echo $this->Form->submit('Register', array('class' => 'mt-2 btn btn-primary')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<?php echo $this->start('custom_script');?>
<script>
    $("#UserRegisterForm").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 404) {
                    let errorsHtml = '<ul>';
                    $.each(response.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorsHtml += '<li>' + error + '</li>';
                        });
                    });
                    errorsHtml += '</ul>';
                    $('#formError').removeClass('d-none').html(errorsHtml);
                }
                if (response.status === 200) {
                    window.location.assign(response.action);
                }
            },
            error: function() {
                $('#formError').removeClass('d-none').html('<ul><li>An unexpected error occurred. Please try again.</li></ul>');
            }
        });
    });
</script>
<?php echo $this->end(); ?>