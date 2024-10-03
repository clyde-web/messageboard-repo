<div class="nk-content">
    <div class="nk-block">
        <?php echo $this->Session->flash(); ?>
        <h4>User Profile</h4>
        <div class="float-left">
            <?php echo $this->Html->image($this->App->getProfile($user['User']['id']), array('alt' => 'User Avatar', 'width' => '150', 'height' => '150')); ?>
        </div>
        <div class="d-flex align-items-start justify-content-end flex-column mt-4 pl-3">
            <h5 class="mb-0"><?php echo $user['User']['name']; ?></h5>
            <p class="mb-0">Email: <span><?php echo $user['User']['email']?></span></p>
            <p class="mb-0">Gender: <span><?php echo ucwords($user['User']['gender'])?></span></p>
            <p class="mb-0">Birthdate: <span><?php echo $this->Time->format('F j, Y', $user['User']['birthdate']); ?></span></p>
            <p class="mb-0">Joined: <span><?php echo $this->Time->format('F j, Y ga', $user['User']['created_at']); ?></span></p>
            <p class="mb-0">Last Login: <span><?php echo $this->Time->format('F j, Y ga', $user['User']['last_login_time']); ?></span></p>
        </div>
        <div class="col-12 mb-3 px-0">
            <p class="mb-0 mt-1">Hubby:</p>
            <span><?php echo $user['User']['hubby']; ?></span>
        </div>
        <?php if ($canUpdate) : ?>
        <?php
            echo $this->Html->link(
                __('Update Account'),
                array('controller' => 'users', 'action' => 'edit'),
                array('class' => 'btn btn-sm btn-outline-primary mr-2')
            );

            echo $this->Html->link(
                __('Change Password'),
                array('controller' => 'users', 'action' => 'password'),
                array('class' => 'btn btn-sm btn-outline-primary')
            );
        ?>
        <?php endif; ?>
    </div>
</div>