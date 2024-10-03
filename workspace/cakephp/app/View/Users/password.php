<div class="nk-content">
    <div class="nk-block">
        <div class="col-lg-4 col-md-5 col-sm-5 mx-auto">
            <?php if (!empty($this->Form->validationErrors['User'])): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($this->Form->validationErrors as $field => $errors): ?>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo h($error[0]); ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <?php echo $this->Form->create('User', array('url' => 'password')); ?>
            <div class="mb-2">
                <label for="UserOldPassword" class="form-label">
                    Old Password <span class="text-danger">*</span>
                </label>
                <div class="form-control-wrap">
                    <a href="#" tabindex="-1" class="form-icon form-icon-right passcode-switch" data-target="UserOldPassword">
                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                    </a>
                    <?php echo $this->Form->input('old_password', array(
                        'type' => 'password',
                        'label' => false,
                        'placeholder' => 'Enter your old password',
                        'class' => 'form-control',
                        'div' => false,
                        'error' => false,
                        'required' => false
                    )); ?>
                </div>
            </div>
            <div class="mb-2">
                <label for="UserPassword" class="form-label">
                    New Password <span class="text-danger">*</span>
                </label>
                <div class="form-control-wrap">
                    <a href="#" tabindex="-1" class="form-icon form-icon-right passcode-switch" data-target="UserPassword">
                        <em class="passcode-icon icon-show icon ni ni-eye"></em>
                        <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                    </a>
                    <?php echo $this->Form->input('password', array(
                        'label' => false,
                        'placeholder' => 'Enter your new password',
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
            <?php echo $this->Form->submit('Change Password', array('class' => 'btn btn-sm btn-warning')); ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>