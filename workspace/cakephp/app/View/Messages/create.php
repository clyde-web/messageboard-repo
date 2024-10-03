<div class="nk-content">
    <div class="nk-block">
        <div class="col-lg-6 col-md-7 col-sm-8 col-11 mx-auto">
        <?php if (!empty($this->Form->validationErrors)): ?>
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
            <h4 class="mb-3">New Message</h4>
            <?php echo $this->Form->create('Room', array('url' => 'create')); ?>
            <div class="d-flex align-items-center mb-2">
                <label class="form-label mb-0">
                    User
                </label>
                <?php echo $this->Form->input('Room.receiver_id', array(
                    'type' => 'select',
                    'label' => false,
                    'class' => 'form-control select2-ajax',
                    'div' => 'form-control-wrap flex-grow-1 ml-5',
                    'empty' => false,
                    'options' => array(),
                    'error' => false,
                    'required' => false,
                )); ?>
            </div>
            <div class="d-flex align-items-center mb-2">
                <label for="RoomMessage" class="form-label mb-0">
                    Message
                </label>
                <?php
                echo $this->Form->input('message', array(
                    'type' => 'textarea',
                    'label' => false,
                    'placeholder' => 'Please write your message',
                    'class' => 'form-control',
                    'div' => 'form-control-wrap flex-grow-1 ml-3',
                    'rows' => 5,
                    'cols' => 10,
                    'error' => false,
                    'required' => false,
                ));
                ?>
            </div>
            <?php echo $this->Form->submit('Send Message', array('class' => 'btn btn-sm btn-outline-primary ml-46')); ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<?php echo $this->start('custom_script');?>
<script>
    $('.select2-ajax').select2({
        ajax: {
            url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'search')); ?>',
            data: function(params) {
                return {
                    search : params.term
                }
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.data
                };
            },
            cache: true
        },
        placeholder: 'Search...',
        allowClear: true,
        minimumInputLenght: 1,
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: renderAvatar,
        templateSelection: renderSelectionAvatar
    })

    function renderAvatar(option) {
        if (!option.id) {
            return option.name;
        }

        var $icon = "<div class='d-flex justify-content-start align-items-center'><div class='user-avatar sm mr-1'><img src='"+ option.image +"' class='rounded-circle' alt='Avatar' /></div>" + option.name + "</div>";

        return $icon;
    }

    function renderSelectionAvatar(option) {
        if (!option.id) {
            return option.text;
        }

        var $icon = "<div class='d-flex justify-content-start align-items-center'><div class='user-avatar xs mr-1'><img src='"+ option.image +"' class='rounded-circle' alt='Avatar' /></div><p class='fs-13px mb-0'>" + option.name + "</p></div>";

        return $icon;
    }
</script>
<?php echo $this->end(); ?>