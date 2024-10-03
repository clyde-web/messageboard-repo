<div class="nk-content">
    <div class="nk-block nk-block-middle mt-5">
        <div class="d-flex justify-content-center align-items-center flex-column">
            <h2 class="mb-4">Thank you for registering</h2>
            <?php echo $this->Html->link('Back to homepage', 
                array('controller' => 'messages', 'action' => 'index'),
                array('class' => 'btn btn-success')
                ); ?>
        </div>
    </div>
</div>