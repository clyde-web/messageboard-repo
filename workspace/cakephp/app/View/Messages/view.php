<div class="nk-content">
    <div class="nk-block">

        <div class="border-bottom p-1 d-flex align-items-center mb-1">
            <?php
                echo $this->Html->link(
                    $this->Html->tag('em', null, array('class' => 'icon ni ni-arrow-long-left', 'escape' => true)),
                    array('action' => 'index'),
                    array('class' => 'back-to','escape' => false)
                );
            ?>
            <a href="<?php echo $this->Html->url(
                array(
                    'controller' => 'users', 
                    'action' => 'profile', AuthComponent::user('id') === $room['Room']['receiver_id'] ? $room['Room']['sender_id']: $room['Room']['receiver_id']
                    )
                ) ?>" class="d-flex align-items-center">
                <div class="user-avatar xs">
                     <?php echo $this->Html->image(
                        $this->App->getProfile(AuthComponent::user('id') === $room['Room']['receiver_id'] ? $room['Room']['sender_id']: $room['Room']['receiver_id']),
                        array('alt' => 'User Avatar')
                     );
                    ?>
                </div>
                <h6 class="text-primary ml-1 mb-0">
                    <?php
                        echo (AuthComponent::user('id') === $room['Room']['receiver_id']) ? $room['Sender']['name'] : $room['Receiver']['name'];
                    ?>
                </h6>
            </a>
        </div>
        
        <div class="text-right mb-2">
            <?php echo $this->Form->create('Message', array('url' => 'sendMessage')); ?>
            <?php
                echo $this->Form->input('message', array(
                    'type' => 'textarea',
                    'label' => false,
                    'placeholder' => 'Please write your message',
                    'class' => 'form-control mb-1 col-lg-6 offset-lg-6',
                    'div' => false,
                    'rows' => 5,
                    'cols' => 10,
                    'error' => false,
                    'required' => false,
                ));
            ?>
            <input type="hidden" value="<?php echo $room['Room']['id']?>" name="data[Message][room_id]" />
            <?php echo $this->Form->submit('Reply Message', array('class' => 'btn btn-sm btn-outline-primary')); ?>
            <?php echo $this->Form->end(); ?>
        </div>

        <div id="messages"></div>

        <div class="text-center" id="showMoreLink">
            <?php echo
                $this->Html->link(__('Show More'), 'javascript:void(0);', array('class' => 'fs-12px text-underline d-none'));
            ?>
            <div class="spinner-border d-none" role="status"><span></span></div>
        </div>

    </div>
</div>

<?php echo $this->start('custom_script'); ?>
<script>
    let page = 1;
    loadMessages(page);
    $(document).on("click", ".btn-see-more", function(e) {
        e.preventDefault();
        const content = $(this).data('target');
        const element = $(`${content} .message-item`);
        element.toggleClass('expanded');
        if (element.hasClass('expanded')) {
            $(this).text('See Less');
        } else {
            $(this).text('See More');
        }
    })
    $("#MessageViewForm").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 404) {
                    $.each(response.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            toastr.clear();
                            NioApp.Toast(`${error}`, 'error');
                        });
                    });
                }
                if (response.status === 200) {
                    $("#MessageMessage").val(null);
                    toastr.clear();
                    NioApp.Toast(`${response.message}`, 'success');
                    const div = document.createElement('div');
                    div.classList.add('d-flex', 'border', 'mb-2', 'flex-row-reverse');
                    div.setAttribute('id', `message-${response.data.id}`);
                    var html = '';

                    html = `
                        <a href="${response.data.profile}">
                            <img src="${response.data.image}" alt="User Avatar" width="72" height="72" />
                        </a>
                        <div class="message-wrapper">
                            <div class="message-item ml-1">
                                <p class="mb-0">${response.data.message}</p>
                            </div>
                            <div class="d-flex justify-content-end fs-13px border-top">`;
                                if (response.data.seeMore) {
                                    html += `<a href="javascipt:void(0);" class="text-underline mr-2 btn-see-more" data-target="#message-${response.data.id}">See More</a>`;
                                }
                    html +=`<a href="javascript:void(0);" class="text-underline btn-delete" data-id="${response.data.id}">
                                    Delete
                                </a>
                                <p class="mb-0 mr-1 ml-2">${response.data.created_at}</p>
                            </div>
                        </di>
                    `;
                    div.innerHTML = html;
                    $("#messages").prepend(div);
                }
            },
            error: function() {
                toastr.clear();
                NioApp.Toast(`<h5>Failed</h5><p>Oopss, Something went wrong</p>`, 'error');
            }
        })
    })
    $(document).on("click", ".btn-delete", function(e) {
        e.preventDefault();
        const id = $(this).data("id");
        var isConfirm = confirm("Are you sure you want to delete this message?");
        if (isConfirm) {
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'deleteMessage'))?>',
                type: 'POST',
                dataType: 'json',
                data: { id: id },
                success: function(response) {
                    if (response.status === 204) {
                        toastr.clear();
                        NioApp.Toast(`<h5>Success</h5> <p>${response.message}</p>`, 'success');
                        $(`#message-${id}`).fadeOut(300, function() {
                            $(`#message-${id}`).remove();
                        })
                    }
                    if (response.status === 404) {
                        toastr.clear();
                        NioApp.Toast(`<h5>Failed</h5><p>${response.message}</p>`, 'error');
                    }
                },
                error: function(error) {
                    toastr.clear();
                    NioApp.Toast(`<h5>Failed</h5><p>Oopss, Something went wrong</p>`, 'error');
                }
            })
        }
    })
    $("#showMoreLink a").on("click", function(e) {
        e.preventDefault();
        page++;
        $(this).addClass("d-none");
        $(".spinner-border").removeClass("d-none");
        loadMessages(page);
    })
    function loadMessages(toPage) {
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'view', 'id' => $room['Room']['id'])); ?>',
            type: 'GET',
            dataType: 'json',
            data: { page: toPage },
            success: function(response) {
                $(".spinner-border").addClass("d-none");
                if (response.hasMore) {
                    $("#showMoreLink a").removeClass("d-none");
                }
                response.messages.forEach(item => {
                    const div = document.createElement('div');
                    div.classList.add('d-flex', 'border', 'mb-2');
                    if (item.class) {
                        div.classList.add(item.class);
                    }
                    div.setAttribute('id', `message-${item.id}`);
                    var html = '';

                    html = `
                        <a href="${item.profile}">
                            <img src="${item.image}" alt="User Avatar" width="72" height="72" />
                        </a>
                        <div class="message-wrapper">
                            <div class="message-item ml-1">
                                <p class="mb-0">${item.message}</p>
                            </div>
                            <div class="d-flex justify-content-end fs-13px border-top">`;
                                if (item.seeMore) {
                                    html += `<a href="javascipt:void(0);" class="text-underline mr-2 btn-see-more" data-target="#message-${item.id}">See More</a>`;
                                }
                                if (item.canDelete) {
                                    html += `<a href="javascript:void(0);" class="text-underline btn-delete" data-id="${item.id}">
                                        Delete
                                    </a>`;
                                }
                    html +=`<p class="mb-0 mr-1 ml-2">${item.created_at}</p>
                            </div>
                        </di>
                    `;
                    div.innerHTML = html;
                    $("#messages").append(div);
                });
            },
            error: function(error) {
                $(".spinner-border").addClass("d-none");
                $("showMoreLink").html("<p class='mb-0 text-center text-danger'>Oopss! Something went wrong!</p>");
            }
        })
    }
</script>
<?php echo $this->end(); ?>