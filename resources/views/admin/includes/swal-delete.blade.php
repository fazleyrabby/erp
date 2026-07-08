<script>
    /**
     * Common SweetAlert2 Delete Confirmation (AJAX-based)
     *
     * Usage:
     *   confirmDeleteSwal({
     *       url         : '{{ route('some.route') }}',
     *       id          : id,
     *       itemName    : 'Category',
     *       data        : { extra: 'value' },
     *       useFormData : false,
     *       onSuccess   : function(result) { ... },
     *       onError     : function(resp)   { ... },
     *   });
     */
    function confirmDeleteSwal(options) {
        var settings = $.extend({
            url         : '',
            id          : null,
            itemName    : 'record',
            data        : {},
            useFormData : false,
            onSuccess   : null,
            onError     : null,
        }, options);

        Swal.fire({
            title              : 'Are you sure?',
            text               : 'You will not be able to recover this ' + settings.itemName + '!',
            icon               : 'warning',
            showCancelButton   : true,
            confirmButtonColor : '#e53935',
            cancelButtonColor  : '#6c757d',
            confirmButtonText  : '<i class="fa fa-trash-alt me-1"></i> Yes, delete it!',
            cancelButtonText   : '<i class="fa fa-times me-1"></i> Cancel',
            reverseButtons     : true,
            focusCancel        : true,
        }).then(function (result) {
            if (result.isConfirmed) {
                var _token = $('meta[name="csrf-token"]').attr('content');

                var ajaxOptions = {
                    url    : settings.url,
                    method : 'POST',
                    beforeSend : function () { $('#loading').show(); },
                    complete   : function () { $('#loading').hide(); },
                    success: function (res) {
                        if (typeof settings.onSuccess === 'function') {
                            settings.onSuccess(res);
                        } else {
                            Swal.fire({
                                title             : 'Deleted!',
                                text              : res.success || res.Success || 'Record has been deleted.',
                                icon              : 'success',
                                confirmButtonColor: '#3085d6',
                            }).then(function () {
                                location.reload();
                            });
                        }
                    },
                    error: function (response) {
                        if (typeof settings.onError === 'function') {
                            settings.onError(response);
                        } else {
                            Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                        }
                    }
                };

                if (settings.useFormData) {
                    var fd = new FormData();
                    fd.append('_token', _token);
                    fd.append('id', settings.id);
                    $.each(settings.data, function (key, val) { fd.append(key, val); });
                    ajaxOptions.data        = fd;
                    ajaxOptions.contentType = false;
                    ajaxOptions.processData = false;
                } else {
                    var postData = $.extend({ id: settings.id, _token: _token }, settings.data);
                    ajaxOptions.data = postData;
                }

                $.ajax(ajaxOptions);

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title             : 'Cancelled',
                    text              : 'Your ' + settings.itemName + ' is safe!',
                    icon              : 'info',
                    confirmButtonColor: '#3085d6',
                });
            }
        });
    }

    /**
     * SweetAlert2 confirmation for href-based delete/action links.
     * Replaces the old  onclick="return confirm(...)"  pattern.
     *
     * Usage (in Blade):
     *   <a href="{{ route('roleDelete', $role->id) }}"
     *      onclick="return swalConfirmLink(event, this)"
     *      data-item="Role"
     *      data-action="delete">Delete</a>
     *
     *   <a href="{{ route('changeMemberStatus', $emp->id) }}"
     *      onclick="return swalConfirmLink(event, this)"
     *      data-item="employee"
     *      data-action="status">Change Status</a>
     *
     * data-item   : Item name shown in dialog  (default: 'record')
     * data-action : 'delete' → red  |  'status' → blue  |  other → orange
     */
    function swalConfirmLink(event, element) {
        event.preventDefault();

        var url      = element.href;
        var itemName = element.getAttribute('data-item')   || 'record';
        var action   = element.getAttribute('data-action') || 'delete';

        var isDelete = (action === 'delete');
        var isStatus = (action === 'status');

        var title        = isDelete ? 'Are you sure?' : 'Confirm Action';
        var text         = isDelete
            ? 'You will not be able to recover this ' + itemName + '!'
            : 'Are you sure you want to change the status of this ' + itemName + '?';
        var confirmTxt   = isDelete
            ? '<i class="fa fa-trash-alt me-1"></i> Yes, delete it!'
            : '<i class="fa fa-check me-1"></i> Yes, proceed!';
        var confirmColor = isDelete ? '#e53935' : (isStatus ? '#1976d2' : '#f57c00');

        Swal.fire({
            title              : title,
            text               : text,
            icon               : 'warning',
            showCancelButton   : true,
            confirmButtonColor : confirmColor,
            cancelButtonColor  : '#6c757d',
            confirmButtonText  : confirmTxt,
            cancelButtonText   : '<i class="fa fa-times me-1"></i> Cancel',
            reverseButtons     : true,
            focusCancel        : true,
        }).then(function (result) {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });

        return false;
    }
</script>
