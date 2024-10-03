<div class="nk-content">
    <div class="nk-block">
        <div class="col-lg-5 mx-auto">
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
            <div class="d-flex justify-content-start align-items-center">
                <?php 
                echo $this->Html->image($this->App->getProfile($user['User']['id']), array(
                    'alt' => 'User Avatar', 
                    'width' => '150', 
                    'height' => '150',
                    'id' => 'targetProfile'
                )); 
                ?>
                <button type="button" class="btn btn-sm btn-primary ml-3" id="btn-upload">
                    Upload Pic
                </button>
            </div>
            <?php echo $this->Form->create('User', array('type' => 'file', 'class' => 'mt-2')); ?>
            <div class="d-flex align-items-center mb-2">
                <label for="UserName" class="form-label mb-0">
                    Name
                </label>
                <?php
                echo $this->Form->input('name', array(
                    'label' => false,
                    'placeholder' => 'Enter your name',
                    'class' => 'form-control',
                    'div' => 'form-control-wrap flex-grow-1 ml-5',
                    'error' => false,
                    'required' => false,
                    'value' => $user['User']['name']
                ));
                ?>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label for="UserEmail" class="form-label mb-0">
                    Email
                </label>
                <?php
                echo $this->Form->input('email', array(
                    'label' => false,
                    'placeholder' => 'Enter your email address',
                    'class' => 'form-control',
                    'div' => 'form-control-wrap flex-grow-1 ml-5',
                    'error' => false,
                    'required' => false,
                    'value' => $user['User']['email']
                ));
                ?>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label for="UserBirthdate" class="form-label mb-0">
                    Birthdate
                </label>
                <?php
                echo $this->Form->input('birthdate', array(
                    'type' => 'text',
                    'label' => false,
                    'placeholder' => 'Choose your birthdate',
                    'class' => 'form-control date-picker',
                    'div' => 'form-control-wrap flex-grow-1 ml-13',
                    'error' => false,
                    'required' => false,
                    'data-date-format' => 'yyyy-mm-dd',
                    'value' => $user['User']['birthdate']
                ));
                ?>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label class="form-label mb-0">
                    Gender
                </label>
                <div class="ml-21">
                    <div class="custom-control custom-control-sm custom-radio">
                        <input type="radio" id="maleRadio" name="data[User][gender]" value="male" class="custom-control-input" <?= strtolower($user['User']['gender']) === 'male' ? 'checked' : null ?>/>
                        <label class="custom-control-label" for="maleRadio">Male</label>
                    </div>
                    <div class="custom-control custom-control-sm custom-radio">
                        <input type="radio" id="femaleRadio" name="data[User][gender]" value="female" class="custom-control-input" <?= strtolower($user['User']['gender']) === 'female' ? 'checked' : null ?> />
                        <label class="custom-control-label" for="femaleRadio">Female</label>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label for="UserHubby" class="form-label mb-0">
                    Hubby
                </label>
                <?php
                echo $this->Form->input('hubby', array(
                    'type' => 'textarea',
                    'label' => false,
                    'placeholder' => 'Please write your hubby',
                    'class' => 'form-control',
                    'div' => 'form-control-wrap flex-grow-1 ml-23',
                    'rows' => 5,
                    'cols' => 10,
                    'error' => false,
                    'required' => false,
                    'value' => $user['User']['hubby']
                ));
                ?>
            </div>
            <?php echo $this->Form->input('image', array(
                'type' => 'file',
                'accept' => 'image/*',
                'label' => false,
                'required' => false,
                'error' => false,
                'hidden'
            )); ?>
            <?php echo $this->Form->submit('Update', array('class' => 'btn btn-sm btn-warning w-100px ml-48')); ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<?php $this->start('custom_script'); ?>
<script>
    $("#btn-upload").on("click", function() {
        $("#UserImage").click();
    });
    $("#UserImage").on("change", function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            $("#targetProfile").removeAttr("src");
            $("#targetProfile").attr("src", e.target.result);
        }

        if (file && file.type.match('image.*')) {
            reader.readAsDataURL(file);
        }
    })
</script>
<?php $this->end(); ?>