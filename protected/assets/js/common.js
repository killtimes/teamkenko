window.zindex = 1040;
var ProcessPage = (function () {
    var _private = null;
    var _scr = null;
    var _getPopoverContent = function (link, div_id, dom) {
        $.ajax({
            url: link,
            success: function (response) {

                $('#' + div_id).html(response);

                _scr = $('.popover-content');

                _scr.mCustomScrollbar({
                    theme: "minimal-dark",
                    scrollInertia: 0
                });

//                _scr.keyup(function (e) {
//                    e.preventDefault();
//                    if (e.keyCode === 27) {
////                        console.log(1233);
//                    }
//                });

                $('.popover').css('z-index', ++window.zindex);

            }
        });
        return '<div id="' + div_id + '">Loading...</div>';
    };

    return {
        initialize: function () {

            //modal add/edit task
            var modal = $('#editTask');

            //event for button add task
            $('body').on('click', '#addTask', function () {
                modal.modal({backdrop: false, keyboard: false});
                $.get($(this).attr('data-url')).done(function (e) {
                    modal.find(".modal-body").html(e.data).append(e.script);
                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                    modal.modal('hide');

                });
            });

            //init event button update task process
            $('#task-process-grid').on('click', '.update-task', function (e) {
                e.preventDefault();

                var $this = $(this), $url = $this.data("remote") || $this.attr("href"), modal = $("#editTask");

                modal.modal({backdrop: false, keyboard: false});

                $.get($url).done(function (e) {
                    modal.find(".modal-body").html(e.data).append(e.script);
                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                    modal.modal('hide');
                });
            });

            $('#view-container').on('click', 'a.duplicate-template', function (e) {

                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                    };
                $('#process-grid').yiiGridView('update', {
                    type: 'POST',
                    url: $(this).attr('href'),
                    data: {'YII_CSRF_TOKEN': $(this).attr('data-token')},
                    success: function (data) {
                        $('#process-grid').yiiGridView('update');
                        afterDelete(th, true, data);
                    },
                    error: function (XHR) {
                        return afterDelete(th, false, XHR);
                    }
                });
            });

            $('#view-container').on('click', 'a.clone-process', function (e) {

                e.preventDefault();

                //modal clone job
                var modal = $('#mdCloneProcess');
                modal.modal({backdrop: false, keyboard: false});
                $.get($(this).attr('href')).done(function (e) {
                    modal.find('.modal-body').html(e);
                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                    modal.modal('hide');
                });

                return false;
            });

            $('#view-container').on('click', 'a.start-process', function (e) {
                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                    };
                $('#process-grid').yiiGridView('update', {
                    type: 'POST',
                    url: $(this).attr('href'),
                    data: {'YII_CSRF_TOKEN': $(this).attr('data-token')},
                    success: function (data) {
                        $('#process-grid').yiiGridView('update');
                        afterDelete(th, true, data);
                    },
                    error: function (XHR) {
                        return afterDelete(th, false, XHR);
                    }
                });

            });

            $('#view-container').on('click', 'a.duplicate', function (e) {
                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                    };
                $('#process-grid').yiiGridView('update', {
                    type: 'POST',
                    url: $(this).attr('href'),
                    data: {'YII_CSRF_TOKEN': $(this).attr('data-token')},
                    success: function (data) {
                        $('#process-grid').yiiGridView('update');
                        afterDelete(th, true, data);
                    },
                    error: function (XHR) {
                        return afterDelete(th, false, XHR);
                    }
                });

            });

            $('#view-container').on('click', 'a.delete', function (e) {

                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                    };


                swal({
                        title: "Delete",
                        text: "Are you sure you want to delete this item?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Delete",
                        closeOnConfirm: true
                    },
                    function () {

                        $('#process-grid').yiiGridView('update', {
                            type: 'POST',
                            url: $(th).attr('href'),
                            data: {'YII_CSRF_TOKEN': $(th).attr('data-token')},
                            success: function (data) {
                                $('#process-grid').yiiGridView('update');
                                afterDelete(th, true, data);
                            },
                            error: function (XHR) {
                                return afterDelete(th, false, XHR);
                            }
                        });
                    });
            });

            $('#view-container').on('click', 'a.recover', function (e) {

                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                    };


                swal({
                        title: "Recover",
                        text: "Are you sure you want to recover this item?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Recover",
                        closeOnConfirm: true
                    },
                    function () {

                        $('#process-grid').yiiGridView('update', {
                            type: 'POST',
                            url: $(th).attr('href'),
                            data: {'YII_CSRF_TOKEN': $(th).attr('data-token')},
                            success: function (data) {
                                $('#process-grid').yiiGridView('update');
                                afterDelete(th, true, data);
                            },
                            error: function (XHR) {
                                return afterDelete(th, false, XHR);
                            }
                        });
                    });
            });

            $('#view-container').on('click', 'a.delete-task', function (e) {

                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                    };

                swal({
                        title: "Delete",
                        text: "Are you sure you want to delete this item?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Delete",
                        closeOnConfirm: true
                    },
                    function () {

                        $('#task-process-grid').yiiGridView('update', {
                            type: 'POST',
                            url: $(th).attr('data-url'),
                            data: {'YII_CSRF_TOKEN': $(th).attr('data-token')},
                            success: function (data) {
                                $('#task-process-grid').yiiGridView('update');
                                afterDelete(th, true, data);
                            },
                            error: function (XHR) {
                                return afterDelete(th, false, XHR);
                            }
                        });
                    });
            });

            $("#search-process").bind("reset", function () {
                $('#s2id_Process_shop_id').select2('val', null);
                $('#s2id_Process_supplier_id').select2('val', null);
                $('#s2id_Process_stage').select2('val', null);
            });

            TaskPage.taskActivities();


        },
        submitTaskProcess: function (form, data, hasError) {

            if (!hasError) {

                var btnSave = $('#btnSave');
                btnSave.button('loading');
                //modal add/edit task
                var modal = $('#editTask');

                //yiigridview taskprocess
                var grid = $('#task-process-grid');

                //show gridview loading
                grid.addClass('grid-view-loading');

                //submit record
                $.post(form.attr('action'), form.serialize(), function (e) {

                    modal.find('.modal-body').html(e.data).append(e.script);

                    grid.removeClass('grid-view-loading');

                    grid.yiiGridView('update');

                    //destroy sortable instance
                    $("#task-process-grid table tbody").sortable('destroy');

                    //alert succcess
                    swal({
                        title: '',
                        text: "Save Task successful!",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });
                }).always(function () {
                    btnSave.button('reset');
                });
            }

            return false;
        },
        initSortableGridview: function (url, token) {
            var $tbody = $("#task-process-grid table tbody");
            $tbody.sortable({
                opacity: 0.6,
                items: 'tr.sort-enable',
                placeholder: "ui-state-highlight",
                axis: "y",
                containment: "parent",
                cursor: "pointer",
                delay: 100,
                distance: 5,
                forceHelperSize: true,
                forcePlaceholderSize: true,
                tolerance: "pointer",
                helper: function (e, tr) {
                    var $helper = tr.clone();
                    $helper.children().each(function (index) {
                        $(this).width(tr.children().eq(index).outerWidth());
                    });
                    return $helper;
                },
                start: function (event, ui) {
                    /*ui.placeholder.height(ui.item.height());*/
                    ui.helper.toggleClass("drag");
                },
                stop: function (event, ui) {
                    //ui.helper.toggleClass("drag");
                    // Update the row classes
                    $tbody.children().each(function (index) {
                        index % 2 === 0 ? $(this).removeClass("even").addClass("odd") : $(this).removeClass("odd").addClass("even");
                    });
                },
                update: function (e, ui) {
                    $("#task-process-grid").addClass("grid-view-loading");
                    // Run an Ajax request to save the new weights
                    $.post(url, {
                        result: $(e.target).sortable("toArray"),
                        YII_CSRF_TOKEN: token
                    }, function () {
                        $("#task-process-grid").removeClass("grid-view-loading");
                    });

                },
//                sort: function () {
//                    console.log($(this).attr('class'));
////                    if ($(this).hasClass("cancel")) {
////                        return false;
////                    }
//                }
            }).disableSelection();
        },
        /*
         * set default duration for taskprocess, inherit fromm duration of task
         */
        setTaskDuration: function (e) {
            $('#edit-task-form #TaskProcessTemplate_duration').val(e.choice.duration);
            $('#edit-task-form #TaskProcess_duration').val(e.choice.duration);

        },
        setProcessName: function (e) {
            $('#CloneProcessForm_name').val(e.choice.text);
        },
        saveProcess: function (btn) {
            $(btn).button('loading');
            $("#clone-process-form").submit();
        },
        submitProcess: function (form, data, hasError) {
            //modal add/edit task
            var modal = $('#mdCloneProcess');

            if (!hasError) {

                //yiigridview taskprocess
                var grid = $('#process-grid');

                //show gridview loading
                grid.addClass('grid-view-loading');

                //submit record
                $.post(form.attr('action'), form.serialize())
                    .done(function (e) {

                        modal.find('.modal-body').html(e);

                        grid.yiiGridView('update');

                        //alert succcess
                        swal({
                            title: '',
                            text: "Save Job successful!",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }).fail(function (xhr, textStatus, errorThrown) {
                    alert(xhr.responseText);
                }).always(function () {
                    grid.removeClass('grid-view-loading');
                    modal.find('#btnSave').button('reset');
                });
            } else {
                modal.find('#btnSave').button('reset');
            }

            return false;
        },
        initPopover: function () {

            var popup = $(document).popover({
                container: 'body',
                selector: '.show-instructions',
                placement: $(this),
                html: true,
                title: function () {
                    return 'Notes' +
                        '<button class="close">&times</button>';
                },
                content: function () {

                    var div_id = "tmp-id-" + $.now();
                    return _getPopoverContent($(this).attr('data-href'), div_id, this);
                }
            });

            $('body').on('click', function (e) {
                $('.show-instructions').each(function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        if (_scr) {
                            _scr.mCustomScrollbar('destroy');
                        }
                        $(this).popover('destroy');
                    }
                });
            });

            $('body').on('click', '.popover .close', function () {
                if (_scr) {
                    _scr.mCustomScrollbar('destroy');
                }
                $(this).closest('.popover').popover('destroy');
            });
        }
    };
})();

var TaskPage = (function () {
    var _modalUrl = '';
    var _button = null;
    var _formatFileSize = function (bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }
        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }
        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }
        return (bytes / 1000).toFixed(2) + ' KB';
    };
    var _summaryUrl = false;
    return {
        initRequestPage: function () {
            $('#view-container').on('click', 'a.accept-task', function (e) {
                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                        TaskPage.getTaskSummary();
                        $.titleAlert.stop();
                        Tinycon.setBubble(0);
                    };
                var url = $(th).data('href');

                $('#task-grid').yiiGridView('update', {
                    type: 'POST',
                    url: url,
                    data: {'YII_CSRF_TOKEN': $(this).attr('data-token')},
                    success: function (data) {
                        $('#task-grid').yiiGridView('update');
                        afterDelete(th, true, data);
                    },
                    error: function (XHR) {
                        return afterDelete(th, false, XHR);
                    }
                });

            });

            $('#view-container').on('click', 'a.reject-task', function (e) {

                e.preventDefault();

                var th = this,
                    afterDelete = function () {
                        setTimeout(function () {
                            swal.close();
                        }, 100);

                        TaskPage.getTaskSummary();

                    };
                var url = $(th).data('href');

                swal({
                    title: "Reject task",
                    text: "Are you sure? Please enter the reason.",
                    type: "input",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    closeOnConfirm: false,
                    inputPlaceholder: "Short description"
                }, function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false;
                    }

                    $('#task-grid').yiiGridView('update', {
                        type: 'POST',
                        url: url,
                        data: {
                            'YII_CSRF_TOKEN': $(th).attr('data-token'),
                            'reason': inputValue
                        },
                        success: function (data) {
                            $('#task-grid').yiiGridView('update');
                            afterDelete(th, true, data);
                        },
                        error: function (XHR) {
                            return afterDelete(th, false, XHR);
                        }
                    });

                });

            });

            var modal = $('#mdConfirmComplete');

            $('#view-container').on('click', 'a.complete-task', function (e) {

                e.preventDefault();
                var th = this;

                modal.modal({backdrop: false, keyboard: false});
                var url = $(th).attr('data-instruction-url');
                var btnComplete = modal.find('.complete');

                $.get(url).done(function (e) {

                    modal.find(".content-placeholder").html(e);
                    btnComplete.attr('data-url', $(th).attr('data-url'));
                    btnComplete.attr('data-token', $(th).attr('data-token'));

                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                    modal.modal('hide');
                }).always(function () {
                    btnComplete.removeClass('disabled');
                    btnComplete.removeAttr('disabled');
                });

            });

            modal.on('click', '.complete', function (e) {
                var th = $(this);

                th.button('loading');

                $.post($(th).attr('data-url'), {'YII_CSRF_TOKEN': $(th).attr('data-token')})
                    .done(function (s) {
                        modal.modal('hide');
                        $('#task-grid').yiiGridView('update');

                        TaskPage.getTaskSummary();
                    })
                    .error(function (xhr) {
                        alert(xhr.responseText);
                    })
                    .always(function () {
                        th.button('reset');
                    });


            });
        },
        taskActivities: function () {

            $(document).on('show.bs.modal', '.modal', function (event) {
//                var zIndex = 1040 + (10 * $('.modal:visible').length);
                $(this).css('z-index', ++window.zindex);
//                setTimeout(function () {
//                    $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
//                }, 0);
            });

            //modal activities
            var modal = $('#taskActivities'), content = modal.find(".content-placeholder");

            modal.find('.modal-body').mCustomScrollbar({
                theme: "minimal-dark",
                scrollInertia: 0,
                setHeight: true
            });

            modal.on('hidden.bs.modal', function () {
                content.html('<div class="placeholder"><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div></div></div>');
            });

            //modal process
            var modalProcess = $('#mdProcess'), contentProcess = modalProcess.find(".content-placeholder");

            modalProcess.find('.modal-body').mCustomScrollbar({
                theme: "minimal-dark",
                scrollInertia: 0,
                setHeight: true
            });

            modalProcess.on('hidden.bs.modal', function (e) {
                contentProcess.html('<div class="placeholder"><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div></div></div>');
            });

            $(document).on('click', '.view-activity', function (e) {
                e.preventDefault();
                _modalUrl = $(this).attr('data-url');

                modal.modal({backdrop: false, keyboard: false});

                $.get(_modalUrl).done(function (e) {

                    content.html(e);
                    autosize($('textarea'));

                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                    modal.modal('hide');
                });
            }).on('click', '.view-process', function (e) {
                e.preventDefault();

                modalProcess.modal({backdrop: false, keyboard: false});

                $.get($(this).attr('data-url')).done(function (e) {

                    contentProcess.html(e);

                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                    modalProcess.modal('hide');
                });
            });

            $("#search-task").bind("reset", function () {
                $('#s2id_TaskProcess_assign_id').select2('val', null);
                $('#s2id_TaskProcess_process_id').select2('val', null);
            });

            $(document).off('click', '.fileinput-button').on('click', '.fileinput-button', function () {
                $('#XUploadForm_file').trigger('click');
            });


        },
        loadModalActivities: function () {
            var modal = $('#taskActivities');
            modal.modal({backdrop: false, keyboard: false});

            $.get(_modalUrl).done(function (e) {

                modal.find(".content-placeholder").html(e);

                autosize($('textarea'));

            }).fail(function (xhr, b, c) {
                alert(xhr.responseText);
                modal.modal('hide');
            });
        },
        initMessageBox: function () {

            $('#message-form').submit(function () {
                var $form = $(this);
                var $tb = $('#btnPost');
                var formData = $form.serialize();
                $('.error-container').hide();
                $tb.button('loading');
                $.post($form.attr('action'), formData).done(function (s) {
                    try {
                        var json = JSON.parse(s);
                        if (json.hasError) {

                            $('.error-container').html(json.errorHtml).show();
                        } else {
                            TaskPage.loadModalActivities();

                            var $attrsGrid = $('#documents-grid');
                            if ($attrsGrid) {
                                $attrsGrid.yiiGridView('update');
                            }
                        }
                    } catch (e) {
                    }

                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                }).always(function () {
                    $tb.button('reset');
                });
                return false;
            });

            $('#message-form').on('click', '.delete-file', function (e) {
                e.preventDefault();
                var bt = $(this);
                bt.button('loading');
                $.post(bt.attr('data-url'), {
                    YII_CSRF_TOKEN: bt.attr('data-token'),
                    name: bt.attr('data-file')
                }).done(function (s) {
                    $(".fileinput-button").show();
                    $(".dropbox-dropin-btn").show();
                    $(".file-group").hide();
                    $("#MessageActivityForm_file_name").val('');
                    $("#MessageActivityForm_file_label").val('');
                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                }).always(function () {
                    bt.button('reset');
                });
            });

            if (_button === null) {
                _button = Dropbox.createChooseButton({
                    // Required. Called when a user selects an item in the Chooser.
                    success: function (files) {
                        $('.dropbox-dropin-btn').hide();
                        $(".fileinput-button").hide();
                        $("#MessageActivityForm_file_source").val(2);
                        $("#MessageActivityForm_file_label").val(files[0].name);
                        $("#MessageActivityForm_file_name").val(files[0].link);
                        $(".attachment .extension").text("Size: " + _formatFileSize(files[0].bytes) + "");
                        $(".file-group .delete-file-dropbox").show();
                        $(".file-group .delete-file").hide();
                        $(".file-group").show();
                    },
                    // Optional. Called when the user closes the dialog without selecting a file
                    // and does not include any parameters.
                    cancel: function () {

                    },
                    // Optional. "preview" (default) is a preview link to the document for sharing,
                    // "direct" is an expiring link to download the contents of the file. For more
                    // information about link types, see Link types below.
                    linkType: "preview", // or "direct"

                    // Optional. A value of false (default) limits selection to a single file, while
                    // true enables multiple file selection.
                    multiselect: false // or true

                    // Optional. This is a list of file extensions. If specified, the user will
                    // only be able to select files with these extensions. You may also specify
                    // file types, such as "video" or "images" in the list. For more information,
                    // see File types below. By default, all extensions are allowed.
//                extensions: ['.pdf', '.doc', '.docx']
                });
            }
            $(_button).show();
            $(_button).removeClass('dropbox-dropin-success');
            $('.fileinput-button').after(_button);

            $('#message-form').on('click', '.delete-file-dropbox', function (e) {
                e.preventDefault();
                $(".fileinput-button").show();
                $('.dropbox-dropin-btn').removeClass('dropbox-dropin-success');
                $('.dropbox-dropin-btn').show();
                $(".file-group").hide();
                $("#MessageActivityForm_file_name").val('');
                $("#MessageActivityForm_file_label").val('');
                $("#MessageActivityForm_file_source").val('');
            });

            $('#view-container').on('click', '.delete-activity', function (e) {

                var url = $(this).attr('data-url');
                var token = $(this).attr('data-token');

                swal({
                        title: "Delete Message",
                        text: "Are you sure you want to delete this activity?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Delete",
                        closeOnConfirm: true
                    },
                    function () {

                        $.post(url, {
                            YII_CSRF_TOKEN: token
                        }).done(function (s) {
                            TaskPage.loadModalActivities();

                            var $attrsGrid = $('#documents-grid');
                            if ($attrsGrid) {
                                $attrsGrid.yiiGridView('update');
                            }
                        }).fail(function (xhr, b, c) {
                            alert(xhr.responseText);
                        }).always(function () {

                        });

                    });
            });

            $('#message-form').on('fileuploadprogress', function (e, data) {

                var progress = Math.floor(data.loaded / data.total * 100);
                if (data.context) {
                    data.context.each(function () {
                        $(this).find('.text-success').text(progress + '%');
                    });
                }
            }).on('fileuploaddestroy', function (e, data) {
                $(data.context.context).button('loading');
            }).on('fileuploaddestroyfailed', function (e, data) {
                $(data.context.context).button('reset');
            });

        },
        getTaskSummary: function (route) {
            if (route !== undefined) {
                _summaryUrl = route;
            }
            $.get(_summaryUrl).done(function (s) {
                if (s) {
                    if (s.requests > 0) {
                        $('#spBadgeRequest').text(s.requests).visible();
                    } else {
                        $('#spBadgeRequest').text(0).invisible();
                    }
                    if (s.due_today > 0) {
                        $('#spBadgeDueToday').text(s.due_today).visible();
                    } else {
                        $('#spBadgeDueToday').text(0).invisible();
                    }
                    if (s.due_tomorrow > 0) {
                        $('#spBadgeDueTomorrow').text(s.due_tomorrow).visible();
                    } else {
                        $('#spBadgeDueTomorrow').text(0).invisible();
                    }
                    if (s.due_over2days > 0) {
                        $('#spBadgeDueOver2Days').text(s.due_over2days).visible();
                    } else {
                        $('#spBadgeDueOver2Days').text(0).invisible();
                    }
                    if (s.overdue > 0) {
                        $('#spBadgeOverdue').text(s.overdue).visible();
                    } else {
                        $('#spBadgeOverdue').text(0).invisible();
                    }
                    if (s.assigned > 0) {
                        $('#spBadgeWaitForAccept').text(s.assigned).visible();
                    } else {
                        $('#spBadgeWaitForAccept').text(0).invisible();
                    }
                    if (s.completed > 0) {
                        $('#spBadgeCompleted').text(s.completed).visible();
                    } else {
                        $('#spBadgeCompleted').text(0).invisible();
                    }
                }
            });
        },
        fetchTotalTaskByStage: function (route) {
            $.get(route).done(function (s) {
                if (s) {
                    for (var i in s) {
                        $('#spBadge' + i).text(s[i]).visible();
                    }
                }
            });
        }
    };
})();

var ShopPage = (function () {
    return {
        initList: function () {
            $('#view-container').on('click', 'a.delete', function (e) {

                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                    };

                swal({
                        title: "Delete",
                        text: "Are you sure you want to delete this item?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Delete",
                        closeOnConfirm: true
                    },
                    function () {

                        $('#shop-grid').yiiGridView('update', {
                            type: 'POST',
                            url: $(th).attr('href'),
                            data: {'YII_CSRF_TOKEN': $(th).attr('data-token')},
                            success: function (data) {
                                $('#shop-grid').yiiGridView('update');
                                afterDelete(th, true, data);
                            },
                            error: function (XHR) {
                                return afterDelete(th, false, XHR);
                            }
                        });
                    });
            });
        }
    };
})();

var SupplierPage = (function () {
    return {
        initList: function () {
            $('#view-container').on('click', 'a.delete', function (e) {

                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                    };

                swal({
                        title: "Delete",
                        text: "Are you sure you want to delete this item?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Delete",
                        closeOnConfirm: true
                    },
                    function () {

                        $('#supplier-grid').yiiGridView('update', {
                            type: 'POST',
                            url: $(th).attr('href'),
                            data: {'YII_CSRF_TOKEN': $(th).attr('data-token')},
                            success: function (data) {
                                $('#supplier-grid').yiiGridView('update');
                                afterDelete(th, true, data);
                            },
                            error: function (XHR) {
                                return afterDelete(th, false, XHR);
                            }
                        });
                    });
            });
        }
    };
})();

var TemplatePage = (function () {
    return {
        initFormTask: function () {
            $("#Task_instructions").counter({
                goal: 'sky',
                type: 'word',
                msg: 'word(s)'
            });
        },
        initList: function () {
            $('#view-container').on('click', 'a.delete', function (e) {

                e.preventDefault();
                var th = this,
                    afterDelete = function () {
                    };

                swal({
                        title: "Delete",
                        text: "Are you sure you want to delete this item?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Delete",
                        closeOnConfirm: true
                    },
                    function () {

                        $('#task-grid').yiiGridView('update', {
                            type: 'POST',
                            url: $(th).attr('href'),
                            data: {'YII_CSRF_TOKEN': $(th).attr('data-token')},
                            success: function (data) {
                                $('#task-grid').yiiGridView('update');
                                afterDelete(th, true, data);
                            },
                            error: function (XHR) {
                                return afterDelete(th, false, XHR);
                            }
                        });
                    });
            });
        }
    };
})();

var DashboardPage = (function () {
    var _viewMonth = false;
    var _viewYear = false;
    var _cached = false;
    var _urlParseNumberTask = '';
    var _modalListTask;
    var _modalAddTask;
    var _zindex = 1040;
    var _fillNumber = function () {
        for (var i in _cached) {
            var completed = _cached[i]['completed'];
            var total = _cached[i]['total'];
            var d = $('#spNumberTask-' + i);
            d.text(completed + "/" + total).addClass('badge');

            if (completed === total) {
                d.addClass('badge-success');
            } else {
                d.addClass('badge-warning');
            }
        }
    };

    return {
        parseNumberTask: function (ignoreCached, callback) {


            if (!ignoreCached && _cached) {
                _fillNumber();
            } else {
                if (_viewMonth === false || _viewYear === false) {
                    var date = new Date();
                    _viewYear = date.getFullYear();
                    _viewMonth = date.getMonth() + 1;
                }
                $('#btnRefresh').button('loading');

                $.get(_urlParseNumberTask, {
                    month: _viewMonth,
                    year: _viewYear
                }).done(function (s) {
                    _cached = s;
                    _fillNumber();
                }).always(function () {
                    $('#btnRefresh').button('reset');
                    if (callback !== undefined) {
                        callback();
                    }
                });
            }
        },
        loadTaskByDate: function (date) {

//            var content = _modalListTask.find(".content-placeholder");
            var url = _modalListTask.attr('data-url');
            _modalListTask.modal({backdrop: true, keyboard: true});

            $.get(url, {
                date: date
            }).done(function (ss) {
                _modalListTask.find(".content-placeholder").html(ss);

                _modalListTask.find('.modal-body').mCustomScrollbar({
                    theme: "minimal-dark",
                    scrollInertia: 0,
                    setHeight: true
                });

            }).fail(function (xhr, b, c) {
                alert(xhr.responseText);
                _modalListTask.modal('hide');
            });


        },
        initCalendar: function (url) {
            $('#calendarLoader').remove();
            _urlParseNumberTask = url;
            var datepicker = $('#dCalendar .dashboard-calendar').datepicker({
                calendarWeeks: true,
                todayHighlight: true,
                weekStart: 1,
                disableTouchKeyboard: true,
                enableOnReadonly: true,
                todayBtn: true,
                beforeShowDay: function (date, month, year) {

                    var html = '';
                    var day = date.getDate();
                    var monthAlt = date.getMonth() + 1;
                    if (date.getMonth() === month) {
                        html = day + '<br> <small id="spNumberTask-' + day + '-' + monthAlt + '" class=" number-task parse-number-task">&nbsp;</small>';
                    } else {
                        html = day + '<br> <small>&nbsp;</small>';
                    }

                    return {
                        html: html,
                        enabled: true
                    };
                }
            });

            datepicker.on('changeDate', function (e) {

                var newMonth = e.date.getMonth() + 1;
                if (_viewMonth !== newMonth) {
                    _viewMonth = newMonth;
                    _viewYear = e.date.getFullYear();
//                    $('#btnRefresh').button('loading');
                    DashboardPage.parseNumberTask(true, function () {
//                        $('#btnRefresh').button('reset');
                    });
                } else {
                    DashboardPage.parseNumberTask(false);
                }

                DashboardPage.loadTaskByDate(e.date.getFullYear() + '-' + (e.date.getMonth() + 1) + '-' + e.date.getDate());

            });
            datepicker.on('changeMonth', function (e) {

                _viewMonth = e.date.getMonth() + 1;
                _viewYear = e.date.getFullYear();
//                $('#btnRefresh').button('loading');
                DashboardPage.parseNumberTask(true, function () {
//                    $('#btnRefresh').button('reset');
                });

            });

            this.parseNumberTask(true);

            $('#btnRefresh').click(function (e) {
//                var tb = $(this);
//                tb.button('loading');
                DashboardPage.parseNumberTask(true, function () {
//                    tb.button('reset');
                });
            });

            $(document).on('show.bs.modal', '.modal', function (event) {
//                console.log(event.target.id, _zindex + 1);
//                var zIndex = 1040 + (10 * $('.modal:visible').length);
                $(this).css('z-index', ++window.zindex);
//                setTimeout(function () {
//                    $('.modal-backdrop').not('.modal-stack').css('z-index', _zindex - 10).addClass('modal-stack');
//                }, 0);
            });


            _modalListTask = $('#mdListTask');
            _modalAddTask = $('#mdAddTask');

            _modalListTask.on('hidden.bs.modal', function (e) {
                if (e.target.id === 'mdListTask') {
                    DashboardPage.parseNumberTask(true);
                    var content = _modalListTask.find(".content-placeholder");
                    content.html('<div class="placeholder"><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div></div></div>');
                }
            });

            _modalAddTask.on('hidden.bs.modal', function (e) {
                if (e.target.id === 'mdAddTask') {
                    var contentAddTask = _modalAddTask.find(".content-placeholder");
                    contentAddTask.html('<div class="placeholder"><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div></div></div>');
                }
            });

            $('#mdListTask').on('click', '#addTask', function (e) {
                var contentAddTask = _modalAddTask.find(".modal-body");
                contentAddTask.html('<div class="placeholder"><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div></div></div>');
                var btnSave = $('#mdAddTask #btnSave');
                btnSave.addClass('disabled');
//                var contentAddTask = _modalAddTask.find(".content-placeholder");

                var urlAddTask = $(this).attr('data-url');

                _modalAddTask.modal({backdrop: true, keyboard: true});

                $.get(urlAddTask).done(function (addform) {
                    _modalAddTask.find(".modal-body").html(addform.data).append(addform.script);
                    btnSave.removeClass('disabled');
                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                    _modalAddTask.modal('hide');
                });
            });

            $('#mdAddTask').on('click', '#btnSave', function (e) {

                e.preventDefault();
                var btn = $(this);
                var form = $('#add-task-form');
                var url = form.attr('action');
                btn.button('loading');
                $.post(url, form.serialize()).done(function (addform) {
                    _modalAddTask.find(".modal-body").html(addform.data).append(addform.script);
                }).fail(function (xhr, b, c) {
                    alert(xhr.responseText);
                }).always(function () {
                    btn.button('reset');
                });
            });

            $('#mdAddTask').on('click', '#btnCancel', function (e) {
                _modalAddTask.modal('hide');
            });


        }
    };
})();

var TaskNotify = (function () {

    var _url = '/notify';
    var _deplayTime = 10000;
    var _timeout;
    var _count = 0;


    var _resetCount = function () {
        localStorage.setItem('_countNotify', 0);
    };

    var _incrCount = function () {
        var n = localStorage.getItem('_countNotify');

        if (n === null) {
            localStorage.setItem('_countNotify', 1);
            return;
        }

        n++;
        localStorage.setItem('_countNotify', n);
    };

    var _getCount = function () {
        var n = localStorage.getItem('_countNotify');

        if (n === null) {
            return 0;
        }

        return n;
    };

    return {
        fetchTasks: function (url) {
            if (url !== undefined) {
                _url = url;
                TaskNotify.getTaskRequest();
            } else {
                _timeout = setTimeout(function () {
                    TaskNotify.getTaskRequest();
                }, _deplayTime);
            }

        },
        getTaskRequest: function () {
            $.get(_url).done(function (s) {
                Tinycon.setBubble(s.tasks);

                if (s.tasks > 0) {
                    $.titleAlert.stop();
                    $.titleAlert("(" + s.tasks + ") new task(s)!", {
//                        stopOnFocus: true,
                        interval: 1000
                    });

                    if (s.tasksAssignedOver1Hour > 0 && _getCount() >= 12) {
                        swal({
                                title: "New tasks",
                                text: "<strong>There are some tasks assigned to you one hour ago. Please take time to accept them. Thank you!</strong>",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "OK",
                                closeOnConfirm: true,
                                html: true
                            },
                            function () {
                                setTimeout(function () {
                                    window.location = baseUrl + '/process/task/request';
                                }, 300);
                            });

                        _resetCount();
                    }

                } else {
                    $.titleAlert.stop();
                }

                TaskNotify.fetchTasks();

                _incrCount();
            });
        }
    };
})();


jQuery.cachedScript = function (url, options) {

    // Allow user to set any option except for dataType, cache, and url
    options = $.extend(options || {}, {
        dataType: "script",
        cache: true,
        url: url
    });

    // Use $.ajax() since it is more flexible than $.getScript
    // Return the jqXHR object so we can chain callbacks
    return jQuery.ajax(options);
};

jQuery.fn.visible = function () {
    return this.css('visibility', 'visible');
};

jQuery.fn.invisible = function () {
    return this.css('visibility', 'hidden');
};

$(function () {
    Tinycon.setBubble(0);
    $(document).on("idle.idleTimer", function (event, elem, obj) {

        swal({
                title: "Idle over 5 minutes",
                text: "<strong>You may need to refresh page to get new data!</strong>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Refresh",
                closeOnConfirm: true,
                html: true
            },
            function () {
                setTimeout(function () {
                    window.location.reload();

                }, 300);
            });

    });
//    $(document).on("active.idleTimer", function (event, elem, obj, e) {
//        console.log('active');
//    });
    $(document).idleTimer(300000);

    $('#view-container').on('click', 'a.delete-item', function (e) {

        e.preventDefault();
        var th = this,
            afterDelete = function () {
            };

        swal({
                title: "Delete",
                text: "Are you sure you want to delete this item?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: true
            },
            function () {
                var $grid = $(th).closest('.grid-view');

                $grid.yiiGridView('update', {
                    type: 'POST',
                    url: $(th).attr('data-url'),
                    data: {'YII_CSRF_TOKEN': $(th).attr('data-token')},
                    success: function (data) {
                        $grid.yiiGridView('update');
                        afterDelete(th, true, data);
                    },
                    error: function (XHR) {
                        return afterDelete(th, false, XHR);
                    }
                });
            });
    }).on('click', 'a.delete-job', function (e) {
        e.preventDefault();
        var $this = $(this);
        var url = $this.data('url');
        var token = $this.data('token');

        swal({
                title: "Delete",
                text: "Are you sure you want to delete this job?",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            },
            function () {
                $.post(url, {
                    YII_CSRF_TOKEN: token
                }).done(function (s) {
                    swal("Job has been deleted.");
                    setTimeout(function () {
                        window.location.href = '/process/admin';
                    }, 500);
                }).error(function (xhr) {
                    alert(xhr.responseText);
                });
            });
    });


});

(function ($, undefined) {

    $.fn.clock = function (options) {

        return this.each(function () {

            var el = $(this);

            var setTimeOffset = function () {
                var serverOffset = moment.unix(options.unixTimestamp).diff(new Date());
                return moment().add(serverOffset, 'milliseconds');
            };

            var updateClock = function () {

                var now = moment.tz(options.timezone);
                el.html(now.format('MMMM Do YYYY, hh:mm:ss A'));
                setTimeout(updateClock, 1000);
            };

            setTimeOffset();
            updateClock();
        });
    };

    return this;

})(jQuery);