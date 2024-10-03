<div class="nk-content">
    <div class="nk-block">
        <?php echo $this->Session->flash(); ?>
        <h4>Message Lists</h4>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-control-wrap">
                <div class="form-icon form-icon-right">
                    <em class="icon ni ni-search"></em>
                </div>
                <input type="text" class="form-control" id="search" placeholder="Search..." />
            </div>
            <?php
                echo $this->Html->link(
                    __('New Message'),
                    array('controller' => 'messages', 'action' => 'create'),
                    array('class' => 'btn btn-sm btn-primary')
                );
            ?>
        </div>
        
        <div id="rooms"></div>


        <div class="text-center" id="showMoreLink">
            <?php echo
                $this->Html->link(__('Show More'), 'javascript:void(0);', array('class' => 'fs-12px text-underline d-none'));
            ?>
            <div class="spinner-border d-none" role="status"><span></span></div>
        </div>
    </div>
</div>
<?php $this->start('custom_script'); ?>
<script>
    let page = 1;
    loadRooms(page);
    function loadRooms(toPage) {
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'index')); ?>',
            type: 'GET',
            dataType: 'json',
            data: { page: toPage },
            success: function(response) {
                $(".spinner-border").addClass("d-none");
                if (response.hasMore) {
                    $("#showMoreLink a").removeClass("d-none");
                }
                if (response.rooms.length > 0) {
                    response.rooms.forEach(room => {
                        const div = document.createElement('div');
                        div.classList.add('d-flex', 'border', 'mb-2');
                        div.setAttribute('id', `room-item-${room.id}`);
                        var html = '';
                        html = `
                            <a href="${room.profile}">
                                <img src="${room.image}" alt="User Avatar" width="72" height="72" />
                            </a>
                            <div class="message-wrapper">
                                <div class="message-item ml-1">
                                    <p class="mb-0">${room.message}</p>
                                </div>
                                <div class="d-flex justify-content-end fs-13px border-top">
                                    <a href="${room.action}" class="text-underline">Message Details</a>`;
                                    if (room.canDelete) {
                                        html += `<a href="javascript:void(0);" class="text-underline ml-2 btn-delete" data-id="${room.id}">
                                            Delete
                                        </a>`;
                                    }
                        html +=`     <p class="mb-0 mr-1 ml-2">${room.created_at}</p>
                                </div>
                            </di>
                        `;
                        div.innerHTML = html;
                        $("#rooms").append(div);
                    });
                } else {
                    $("#rooms").append(`<h6 class="text-center">No Available</h6>`);
                }
                
            },
            error: function(error) {
                $(".spinner-border").addClass("d-none");
                $("showMoreLink").html("<p class='mb-0 text-center text-danger'>Oopss! Something went wrong!</p>");
            }
        })
    }

    $("#showMoreLink a").on("click", function(e) {
        e.preventDefault();
        page++;
        $(this).addClass("d-none");
        $(".spinner-border").removeClass("d-none");
        loadRooms(page);
    })

    $(document).on("click", ".btn-delete", function(e) {
        e.preventDefault();
        const id = $(this).data("id");
        var isConfirm = confirm("Are you sure you want to delete this message?");
        if (isConfirm) {
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'delete'))?>',
                type: 'POST',
                dataType: 'json',
                data: { id: id },
                success: function(response) {
                    if (response.status === 204) {
                        toastr.clear();
                        NioApp.Toast(`<h5>Success</h5> <p>${response.message}</p>`, 'success');
                        $(`#room-item-${id}`).fadeOut(300, function() {
                            $(`#room-item-${id}`).remove();
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

    $(document).on("keyup.enter", "#search", function(e) {
        e.preventDefault();
        const search = $(this).val().trim();
        $("#showMoreLink a").addClass("d-none");
        $("#rooms").empty();
        $(".spinner-border").removeClass("d-none");
        if (search.length > 0) {
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'searchMessage')) ?>',
                type: 'GET',
                dataType: 'json',
                data: { search: search },
                success: function(response) {
                    $(".spinner-border").addClass("d-none");
                    if (response.rooms.length == 0) {
                        $("#rooms").append(`<h6 class="text-center">No Results found for "${search}"</h6>`);
                    } else {
                        response.rooms.forEach(room => {
                            const div = document.createElement('div');
                            div.classList.add('d-flex', 'border', 'mb-2');
                            div.setAttribute('id', `room-item-${room.id}`);
                            var html = '';
                            html = `
                                <a href="${room.profile}">
                                    <img src="${room.image}" alt="User Avatar" width="72" height="72" />
                                </a>
                                <div class="message-wrapper">
                                    <div class="message-item ml-1">
                                        <p class="mb-0">${room.message}</p>
                                    </div>
                                    <div class="d-flex justify-content-end fs-13px border-top">
                                        <a href="${room.action}" class="text-underline">Message Details</a>`;
                                        if (room.canDelete) {
                                            html += `<a href="javascript:void(0);" class="text-underline ml-2 btn-delete" data-id="${room.id}">
                                                Delete
                                            </a>`;
                                        }
                            html +=`     <p class="mb-0 mr-1 ml-2">${room.created_at}</p>
                                    </div>
                                </di>
                            `;
                            div.innerHTML = html;
                            $("#rooms").append(div);
                        });
                    }
                },
                error: function(error) {
                    $(".spinner-border").addClass("d-none");
                    toastr.clear();
                    NioApp.Toast('Oopss, Something went wrong!');
                }
            })
        } else {
            loadRooms(1);
        }
    })
</script>
<?php $this->end(); ?>