<div class="nk-content">
    <div class="nk-block nk-block-middle nk-auth-body wide-xs py-0">
        <?php 
            echo $this->Session->flash(); 
        ?>
        <div class="card card-bordered">
            <div class="card-inner">
                <h3 class="fw-bold text-primary">Login</h3>
                <?php echo $this->Form->create('User', array('url' => 'login')); ?>
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
                    'required' => false
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
                            'required' => false,
                        )); ?>
                    </div>
                </div>
                <?php echo $this->Form->submit('Login', array('class' => 'mt-2 btn btn-primary')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>