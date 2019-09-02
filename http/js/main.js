$(document).ready(function () {

    $('#myModal').on('shown.bs.modal', function () {
        $('#post-editor').next().children().first().focus();
    })
    if (screen_size > 989) {
        window.addEventListener("popstate", function (e) {

            location.reload();
            // window.removeEventListener("popstate"); // UC browser FIX


        });
    }

    $(document).on('click focus', '.emojionearea-editor', function () {

        if ($(this).next().hasClass('active'))
            $(this).next().trigger("click");
        if (screen_size < 989) {
            $('#floatPostButton').addClass('hidden-xs-up');
            // $('#blueTopbar').addClass('hidden-xs-up');
        }

    });

    $(document).on('blur', '.emojionearea-editor', function () {

        if (screen_size < 989) {
            $('#floatPostButton').removeClass('hidden-xs-up');
            // $('#blueTopbar').removeClass('hidden-xs-up');
        }
    });

    $("#post-editor").emojioneArea({

        pickerPosition: "bottom",
        autocomplete: false,

        events: {

            click: function (editor, event) {
                var text = $('#post-editor').next().children().first().html();


                text = smiliefy(text);
                var template = text;
                template = template.replace(/\n/g, "<br />");
                template = hashTag(template);
                template = attherate(template);
                template = urlify(template);
                template = smilies(template);


                $('#dummy-post').html(template);
            },
        }
    });


    $(window).on("navigate", function (event, data) {
        var direction = data.state.direction;
        if (direction == 'back') {

            location.reload();
            data.state.direction = null;


        }
        if (direction == 'forward') {

            location.reload();
            data.state.direction = null;
        }
    });
    // if (screen_size > 989) {
    //     window.onhashchange = function () {
    //         location.reload();
    //     };
    // }
    var editHelper = '';
    var isIn = 0;

    $(document).on('click', '.comment', function (event) {
        $(this).closest('.container-fluid').next().next().find('.loadmore').trigger('click');

        $(this).closest('.container-fluid').next().next().next().removeClass('hidden-xs-up');
        $(this).closest('.container-fluid').next().next().find('.loadmore').removeClass('hidden-xs-up');
        $(this).closest('.container-fluid').next().next().next().next().children('.container-fluid').removeClass('hidden-xs-up');

        $(this).closest('.container-fluid').next().next().next().next().children('.container-fluid').find("input[type='text']").val('');
        $(this).closest('.container-fluid').next().next().next().next().children('.container-fluid').find("input[type='text']").next().children().first().focus();
        $(this).closest('.container-fluid').next().next().next().next().children('.container-fluid').find("input[type='text']").next().children().first().html('');

        try {
            $('.grid1').masonry();
            $('.grid01').masonry();
            $('.grid02').masonry();
            $('.grid2').masonry();
        }catch(e){}


        var commentBox = $(this).closest('.container-fluid').next().next().next().next().children('.container-fluid').find("input[type='text']");

        if ($(commentBox).next().hasClass('emojionearea')) {

            $(commentBox).unbind();

        }

        $(commentBox).emojioneArea({
            pickerPosition: "top",
            autocomplete: false,
            inline: true,
        });


            if ($(commentBox).next().next().hasClass('emojionearea')) {
                $(commentBox).next().remove();
                $(commentBox).next().children().first().html('');
                $(commentBox).next().children().first().attr('placeholder','Comment');

            }




    });

    $(document).click(function (e) {
        if (!isIn)
            $('#search-result').hide();
        isIn = 0;

    });

    $('#search-result').click(function (e) {
        isIn = 1;
    });

    $('#u-search').click(function (e) {
        isIn = 1;
    });

    $(document).on('mouseover', "#people-result li", function () {
        $("#people-result li").removeClass("selected");
        $(this).addClass("selected");
    });

    $(document).on('click', "#people-result li", function () {
        selectOption($(this));
    });

    $(document).on('click', ".myNotification", function () {
        if($(this).hasClass('b-lightgrey-new'))
            $(this).removeClass('b-lightgrey-new');
    });

    $(document).on('keydown', "#people-result li", function () {
        if (event.which == 13) {
            selectOption($(this));

        }
    });

    $(document).on('keyup', '.emojionearea-editor', function (event) {


        var isID = $(this).parent().prev().attr('id');
        if (isID != 'post-editor') {
            if (event.keyCode == 13) {

                $(this).submit();


            }
        }
    });

    function selectOption(li) {

        var curr = $('#post-editor').next().children().first().html();
        var people = curr.lastIndexOf("@");
        var curr1 = curr.substr(0, people);

        var people_username = $(li).find('small').text();

        $('#post-editor').next().children().first().html(curr1 + people_username + " ");
        $('#dummy-post').html(curr1 + people_username.replace(/\n/g, "<br />").replace(/(#\w+)/g, " <a href='#'>$1</a>").replace(/(@\w+)/g, " <span class='post_user_link '><b><a  href='#'>$1</a></b></span>"));
        $("#post-editor").focus();
        $("#people-result").html('');
        $("#people-result").addClass("hidden-xs-up");

    }

    $(document).on('keyup', $('#post-editor').next().children().first(), function (event) {

        var curr = $('#post-editor').next().children().first().text();
        var people = curr.lastIndexOf("@");
        curr = curr.substr(people + 1, curr.length);

        if (people >= 0) {

            if (curr.indexOf(" ") >= 0) {
                if (!$("#people-result").hasClass("hidden-xs-up"))
                    $("#people-result").addClass("hidden-xs-up");
            } else {
                if ($("#people-result").hasClass("hidden-xs-up"))
                    $("#people-result").removeClass("hidden-xs-up");

                if (event.which == 13) {
                    var selected = $(".selected");

                    selectOption($(selected));

                }

                if (event.keyCode != 38 && event.keyCode != 40) {
                    var search = curr;
                    var url = "/search/" + search + "/true";
                    $.getJSON(url, function (data) {
                        $("#people-result").html('');
                        var i = 0;
                        $.each(data, function (key, val) {
                            $("#people-result").append(people_result(val, ++i));
                        });
                    });
                }
            }
        }

        if (event.keyCode == 38) {

            var selected = $(".selected");
            $("#people-result li").removeClass("selected");
            if (selected.prev().length == 0) {
                selected.siblings().last().addClass("selected");
            } else {
                selected.prev().addClass("selected");
            }
        }

        if (event.keyCode == 40) {

            var selected = $(".selected");
            $("#people-result li").removeClass("selected");
            if (selected.next().length == 0) {
                selected.siblings().first().addClass("selected");
            } else {
                selected.next().addClass("selected");
            }
        }

        var text = $('#post-editor').next().children().first().html();


        text = smiliefy(text);

        var template = text;
        template = template.replace(/\n/g, "<br />");
        template = hashTag(template);
        template = attherate(template);
        template = urlify(template);
        template = smilies(template);


        $('#dummy-post').html(template);
    });

    $(document).on('keyup', "#chat-editor", function (event) {


        if (event.keyCode == 13) {

            $(this).submit();


        }
    });

    $(document).on('keyup', ".chatPing", function (event) {


        if (event.keyCode == 13) {

            $(this).submit();


        }
    });

    $(document).on('click', '.online_users', function (event) {

        $(this).closest('form').submit();

    });

    $(document).on('click', '#chat-n', function (event) {

        $(this).attr('value', true);
        $('#chat-a').attr('value', false);

    });

    $(document).on('click', '#chat-a', function (event) {

        $(this).attr('value', true);
        $('#chat-n').attr('value', false);

    });

    $('#sliderMenu').click(function (event) {

        $('#collapse').toggleClass('in');

        // $( "#collapse" ).toggle( "slide" );
    });

    $('#openSearch').click(function (event) {

        $('#searchDiv').toggleClass('hidden-md-down');
        $('#sliderMenu').toggleClass('hidden-md-down');
        $('#topbarHome').toggleClass('hidden-md-down');
        $('#topbarNotification').toggleClass('hidden-md-down');
        $('#topbarMessage').toggleClass('hidden-md-down');
        $('#topbarSearch').toggleClass('hidden-md-down');
        $('#u-search').focus();


        // $( "#collapse" ).toggle( "slide" );
    });

    $('#closeSearch').click(function (event) {

        $('#searchDiv').toggleClass('hidden-md-down');
        $('#sliderMenu').toggleClass('hidden-md-down');
        $('#topbarNotification').toggleClass('hidden-md-down');
        $('#topbarHome').toggleClass('hidden-md-down');
        $('#topbarMessage').toggleClass('hidden-md-down');
        $('#topbarSearch').toggleClass('hidden-md-down');

        // $( "#collapse" ).toggle( "slide" );
    });

    $(document).on('click', '.remove_upload', function (event) {

        var image_id = $(this).attr('data-id');
        var csrf = $(this).attr('csrf-id');
        var location = $(this);
        $(location).parent().remove();
        $("#dummy-image").find('img').remove();
        $.ajax({
            type: "POST",
            url: '/post_upload_remove',
            data: {image_id: image_id, _token: csrf},
            beforeSend: function () {

            },
            success: function (data) {
                // $(location).parent().remove();
            },
            error: function (data) {

                $.each($.parseJSON(data.responseText), function (idx, obj) {
                    $("#alert_message").html(alertTemplate(obj[0])).hide();
                    $("#alert_message").fadeIn();
                    return false;
                });
            }
        });

    });

    var isNewAppchat = 1;

    $(document).on('click', '.message_app_users', function (event) {

        var name = $(this).children('ul').children('li').next().children().children('p').text().trim();
        var image = $(this).children('ul').children('li').children('img').attr('src');
        if (!$(this).children('ul').children('li').next().next().children('span').hasClass('hidden-xs-up')) {
            $(this).children('ul').children('li').next().next().children('span').addClass('hidden-xs-up');
            $(this).children('ul').children('li').next().next().children('span').text(0);
        }

        var conversation_id = $(this).attr('conversation-id');
        var name1 = anonymousTagTemplate(name);

        if (name1.indexOf('Anonymous') != -1) {

            name1 = name1.substring(0, name1.indexOf('<span'));
            name1 = name1.split(" ")[0];

            name1 += '<span class="tag tag-danger tag-pill">Anonymous</span>';
        }
        else {
            name1 = name1.split(" ")[0];
        }

        if ($('#conversation_' + conversation_id))

            $('#conversation_' + conversation_id).parent().prev().children().first().trigger('click');


        $('#recent-chat').children().each(function (index, value) {

            if (parseInt($(value).attr('conversation-id')) == conversation_id) {
                if (!$(this).children('ul').children('li').next().next().children('span').hasClass('hidden-xs-up')) {
                    $(this).children('ul').children('li').next().next().children('span').addClass('hidden-xs-up');
                    $(this).children('ul').children('li').next().next().children('span').text(0);
                }
            }
        });

        if (conversation_id != chat_app_conversation_id) {
            isNewAppchat = 0;

            $('#chat-app-scroll').attr('data-limit', '0');
            $('#chat-app-scroll').attr('data-load', '1');
            var auth = parseInt($(this).attr('conversation-auth'));
            var conversation = '#conversation_' + conversation_id;


            var username = $(this).attr('user-id');
            $('#chat-app').children('a').next().next().children('span').html(name1);
            $('#chat-app').children('a').next().next().children('small').html('');
            $('#chat-app').children().last().children('form').children().next().next().val(conversation_id);
            $('#chat-app').children('a').next().next().attr('href', '/@' + username);
            $('#chat-app').children('img').attr('src', image);
            $('#chat-app').children('img').attr('alt', name);
            $('#chat-app').next().attr('id', "conversation_" + conversation_id);
            $('#chat-app').next().next().children('form').children('input:nth-child(2)').val(conversation_id);
            // $('#chat-app').next().next().children('form').children('input:nth-child(4)').prop("disabled", false);
            $('#chat-app').children().last().removeClass('hidden-xs-up');
            var token = $('#chat-app').next().next().children('form').children('input:nth-child(1)').val();
            $('#chats-list').addClass('hidden-sm-down');
            $('#chats-content').removeClass('hidden-sm-down');

            $(conversation).find('.chat-content').html('');
            if (auth == 2)
                load_prev_msg(conversation_id, token, true);


            $(conversation).scrollbar();

            if (auth == 1) {
                var messageauthallow = "<div class=' w-100 auth text-xs-center '>" +
                    "<form method='POST' action='/block_message' accept-charset='UTF-8' class ='d-inline form-inline' >" +
                    "<input name='_token' type='hidden' value='" + csrf + "'>" +
                    "<input name='success' type='hidden' value='chat_block1'>" +
                    "<input name='id' type='hidden' value='" + conversation_id + "'>" +
                    "<button type='submit' class='btn btn-secondary mx-3 mt-3 px-3 '>Block</button>" +
                    "</form>" +
                    "<form method='POST' action='/allow_message' accept-charset='UTF-8' class ='d-inline form-inline' >" +
                    "<input name='_token' type='hidden' value='" + csrf + "'>" +
                    "<input name='success' type='hidden' value='chat_allow'>" +
                    "<input name='id' type='hidden' value='" + conversation_id + "'>" +
                    "<button type='submit' class='btn btn-primary mx-3 px-3 mt-3'>Allow</button>" +
                    "</form>" +
                    "</div>";

                $('#chat-app').next().children().prepend(messageauthallow);

            }


            Rmessagelist[conversation_id] = [];
            chat_app_conversation_id = conversation_id;
            chatPersons();
            if (auth == 2) {
                $.ajax({
                    type: "POST",
                    url: "/check-all",
                    data: {conversation_id: conversation_id, '_token': csrf},
                    success: function (data) {

                        var count = parseInt($('#messagecount').html());
                        if (count > 0) {
                            $('#messagecount').html(count - 1);
                            $('#messagecountTop').html(count - 1);
                        }

                        if (count < 2) {
                            if (!$('#messagecount').hasClass('hidden-xs-up')) {
                                $('#messagecount').addClass('hidden-xs-up');
                                $('#messagecountTop').addClass('hidden-xs-up');
                            }
                        }

                    },
                    error: function (data) {
                    },
                    complete: function () {
                        // $('#chat-app-scroll').scrollTop(1500000);

                    }
                });
            }


        }


    });

    // $(document).on('click', '.online_users-recent', function (event) {
    //
    //     var name = $(this).children('ul').children('li').next().children().children('p').text();
    //     var image = $(this).children('ul').children('li').children('img').attr('src');
    //
    //     if (!$(this).children('ul').children('li').next().next().children('span').hasClass('hidden-xs-up')) {
    //         $(this).children('ul').children('li').next().next().children('span').addClass('hidden-xs-up');
    //         $(this).children('ul').children('li').next().next().children('span').text(0);
    //     }
    //
    //     var conversation_id = parseInt($(this).attr('conversation-id'));
    //
    //     $('#chat-app-scroll1').children().first().children().each(function (index, value) {
    //
    //         if (parseInt($(value).attr('conversation-id')) == conversation_id) {
    //             if (!$(this).children('ul').children('li').next().next().children('span').hasClass('hidden-xs-up')) {
    //                 $(this).children('ul').children('li').next().next().children('span').addClass('hidden-xs-up');
    //                 $(this).children('ul').children('li').next().next().children('span').text(0);
    //             }
    //         }
    //     });
    //     var auth = parseInt($(this).attr('conversation-auth'));
    //     var username = $(this).attr('user-id');
    //     var csrf = $(this).attr('csrf-id');
    //     console.log(chatlist);
    //     if (jQuery.inArray(conversation_id, chatlist) == -1 && chat_app_conversation_id != conversation_id) {
    //         chatlist.push(conversation_id);
    //         Rmessagelist[conversation_id] = [];
    //         var chat = [];
    //         chat["csrf"] = csrf;
    //         chat["id"] = conversation_id;
    //         chat["user_name"] = name;
    //         chat["user_picture"] = image;
    //         chat["username"] = username;
    //         chat["auth"] = auth;
    //         $("#chatmanager").append(chatTemplate(chat));
    //         $(".awesome-chat").draggable({
    //             scroll: false,
    //         });
    //         var id = "#conversation_" + chat["id"];
    //         if (auth == 2) {
    //             $.ajax({
    //                 type: "POST",
    //                 url: "/check-all",
    //                 data: {conversation_id: conversation_id, '_token': csrf},
    //                 success: function (data) {
    //
    //                     var count = parseInt($('#messagecount').html());
    //                     if (count > 0) {
    //                         $('#messagecount').html(count - 1);
    //                         $('#messagecountTop').html(count - 1);
    //
    //                     }
    //                     if (count < 2) {
    //                         if (!$('#messagecount').hasClass('hidden-xs-up')) {
    //                             $('#messagecount').addClass('hidden-xs-up');
    //                             $('#messagecountTop').addClass('hidden-xs-up');
    //                         }
    //                     }
    //
    //                 },
    //                 error: function (data) {
    //                 },
    //                 complete: function () {
    //
    //                 }
    //             });
    //         }
    //         $(id).scrollbar();
    //
    //         if ($("input.chatBox1:text").next().hasClass('emojionearea')) {
    //
    //             $("input.chatBox1:text").unbind();
    //
    //         }
    //
    //         $(".chatBox1").emojioneArea({
    //             pickerPosition: "top",
    //             autocomplete: false,
    //             inline: true,
    //         });
    //
    //         $("input.chatBox1:text").each(function (i, obj) {
    //
    //             if ($(this).next().next().hasClass('emojionearea')) {
    //                 $(this).next().remove();
    //                 $(this).next().children().first().html('');
    //                 $(this).next().children().first().attr('placeholder','Message');
    //
    //             }
    ///
    //         });
    //     }
    // });

    $(document).on('keyup', '#u-search', function (event) {

        var search = $('#u-search').val();

        if (search.length > 1) {
            // alert(search);
            $('#search-result').show();
            $('#search-result').width($('#u-search').width());
            $('#search-result').removeClass('hidden-xs-up');
            var search = $('#u-search').val();
            var code = event.keyCode;
            if (code == 13) {
                var url = "/search/" + search;

                if (search.indexOf("#") == 0) {
                    // search = encodeURIComponent(search);
                    url = "/search/!" + search;

                }
                $('#search-result').hide();
                NProgress.start();
                // alert(url);
                $.get(url, function (data) {
                    $('#page').html(data);
                    history.pushState(null, null, url);
                    NProgress.done();
                });

            }
            else {
// alert('at least here');
                if (search.indexOf("#") == 0) {

                    search = encodeURIComponent(search);

                    var url = "/search/" + search + "/true";
                    $.getJSON(url, function (data) {
                        $("#search-result").html('');
                        $.each(data, function (key, val) {
                            $("#search-result").append(tag_result(val));
                        });
                    });
                }

                else {
                    var url = "/search/" + search + "/true";

                    // alert(url);

                    $.getJSON(url, function (data) {

                        $("#search-result").html('');
                        $.each(data, function (key, val) {
                            $("#search-result").append(search_result(val));
                        });

                        var url = "/search/" + search;

                        $("#search-result").append("<div class='container-fluid border-light text-xs-center'> <a class='text-xs-center'  href='" + url + "' data-loc='page-full' >See more</a></div>");
                    });
                }

            }
        }
        else {

            $('#search-result').addClass('hidden-xs-up');

        }
    });

    $(document).on('click', '.chat-close', function (event) {

        var removeItem = parseInt($(this).attr('conversation-data'));
        var i = chatlist.indexOf(parseInt(removeItem));
        if (i != -1) {
            chatlist.splice(i, 1);
        }
        $(this).closest('.float-chat').prev().remove();
        $(this).closest('.float-chat').remove();

        var chatdata = {
            X: 0,
            Y: 0,
            id: removeItem,
            picture: '',
            name: '',
        };

        chatAjaxSession(chatdata, -1);

    });

    $(document).on('mouseenter', '.unfollow', function (event) {

        $(this).removeClass('btn-secondary');
        $(this).addClass('btn-danger');
        $(this).html('<i class="fa fa-user-plus  " aria-hidden="true"></i>  Unfollow');

    });

    $(document).on('mouseleave', '.unfollow', function (event) {
        $(this).removeClass('btn-danger');
        $(this).addClass('btn-secondary');
        $(this).html('<i class="fa fa-user-plus  " aria-hidden="true"></i> Following');

    });

    $('#chat-app-scroll').on('scroll', function (event) {

        // alert(isNewAppchat)
        if (isNewAppchat == 0) {

            $('#chat-app-scroll').scrollTop(1500000);
            isNewAppchat = 1;
        }
        else {
            var scroll = $(this).scrollTop();
            if (scroll == 0) {
                var box = $(this);

                try {
                    var id = $(this).parent().attr('id').split("_")[1];
                }
                catch (e) {

                }

                var load = $(this).attr('data-load');
                var islimit = $(this).attr('data-limit');
                var conversation = '#conversation_' + id;
                if (islimit == "0" && isScrollIn == 0 && id) {
                    $.ajax({
                        type: "POST",
                        url: "/load_prev",
                        data: {'id': id, '_token': csrf, 'load': load},

                        beforeSend: function () {
                            isScrollIn = 1;
                            var template = '<div class=" w-100 text-xs-center "> ' +
                                '<i class="text-xs-center fa fa-circle-o-notch fa-2x fa-spin fa-fw"></i>' +
                                ' </div> ';

                            $(conversation).find('.chat-content').prepend(template)

                        },
                        success: function (data) {
                            if (data.length > 0) {
                                data.reverse();
                                $(conversation).find('.chat-content').children().first().remove();
                                var tag = $(conversation).find('.chat-content').children().first().text();
                                $(conversation).find('.chat-content').children().first().remove();

                                $.each(data, function (key, val) {


                                    if (tag != val.time_tag) {
                                        $(conversation).find('.chat-content').prepend(messageTemplate(val, val.me, tag, true));
                                        tag = val.time_tag;

                                    }
                                    else {
                                        $(conversation).find('.chat-content').prepend(messageTemplate(val, val.me));

                                    }

                                });

                                var template = '<div class="w-100  text-xs-center ">' +
                                    '<small class="tag tag-default text-xs-center tag-pill">' + tag + '</small>' +
                                    '</div>';

                                $(conversation).find('.chat-content').prepend(template);

                                $(box).attr('data-load', (parseInt(load) + 1));
                            }
                            else {
                                $(conversation).find('.chat-content').children().first().remove();

                            }
                            if (data.length < 10) {

                                // $(box).children().first().remove();
                                $(box).attr('data-limit', '1')
                            }

                            $(box).scrollTop(10);
                            isScrollIn = 0;
                        }
                    });
                }
            }
        }
    });

    var lock = 1;

    $(document).on('mouseover keydown', '.float-chat', function (event) {
        if (lock) {

            var id = $(this).children().next().children().attr('id');
            var token = $(this).children().next().next().children().children().children().attr('value');
            var ind = id.indexOf('_');
            id = id.substring(ind + 1, id.length);

            $.each(Rmessagelist[id], function (key, val) {
                lock = 0;
                $.ajax({
                    type: "POST",
                    url: "/check-message",
                    data: {id: val, '_token': token},
                    success: function (data) {
                        var i = Rmessagelist[id].indexOf(parseInt(val));
                        if (i != -1) {
                            Rmessagelist[id].splice(i, 1);
                        }
                    },
                    error: function (data) {
                    },
                    complete: function () {
                        lock = 1;
                    }
                });

            });


        }
    });

    $(document).on('keydown', '.msgbox', function (event) {

        if (event.keyCode == 0 || event.keyCode == 32) {

            var id = $(this).find('form').children().next().attr('value');
            var token = $(this).find('form').children().attr('value');

            $.ajax({
                type: "POST",
                url: "/typing",
                data: {id: id, '_token': token},
                success: function (data) {

                },
                error: function (data) {
                }
            });

        }


    });

    $(document).on('mouseover keydown focus', '#chats-content', function (event) {
        if (lock) {

            var id = $(this).children().next().attr('id');
            var token = $(this).children().next().next().children().children().attr('value');
            try {
                var ind = id.indexOf('_');
                id = id.substring(ind + 1, id.length);
                $.each(Rmessagelist[id], function (key, val) {
                    lock = 0;
                    $.ajax({
                        type: "POST",
                        url: "/check-message",
                        data: {id: val, '_token': token},
                        success: function (data) {
                            var i = Rmessagelist[id].indexOf(parseInt(val));
                            if (i != -1) {
                                Rmessagelist[id].splice(i, 1);
                            }
                        },
                        error: function (data) {
                        },
                        complete: function () {
                            lock = 1;
                        }
                    });

                });
            }
            catch (e) {
            }

        }
    });

    $("#female").click(function () {
        $("#female").addClass('cust_border1');
        $("#male").removeClass('cust_border');
        $("#female").removeClass('cust_border');
        $("#male").removeClass('cust_border1');
        $("#Gender-val").val(0);
    });

    $("#male").click(function () {
        $("#male").addClass('cust_border1');
        $("#female").removeClass('cust_border');
        $("#male").removeClass('cust_border');
        $("#female").removeClass('cust_border1');
        $("#Gender-val").val(1);
    });

    $('#myModal').modal({show: false});

    // $("#sortable").sortable({scroll: false});

    // $("#sortable").disableSelection();


    $('#imageModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('img');
        var modal = $(this);
        modal.find('img').attr('src', recipient);
    });

    $('#reportModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('type');
        var modal = $(this);

        modal.find('textarea').val('');

        $.each(modal.find('input[type="radio"]'), function () {

            $(this).prop('checked', false);

        });

        if (recipient == "post") {
            var id = button.data('post_id');
            modal.find('.post-type').children().first().attr('value', id);
            modal.find('.post-type').removeClass('hidden-xs-up');
            if (!modal.find('.fabits-type').hasClass('hidden-xs-up'))
                modal.find('.fabits-type').addClass('hidden-xs-up');
        }

        if (recipient == "fabits") {
            modal.find('.post-type').children().first().attr('value', '-1');
            modal.find('.fabits-type').removeClass('hidden-xs-up');
            if (!modal.find('.post-type').hasClass('hidden-xs-up'))
                modal.find('.post-type').addClass('hidden-xs-up');
        }
    });

    $('#notificationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        if (!$('#notificationcount').hasClass('hidden-xs-up')) {
            $('#notificationcount').addClass('hidden-xs-up');
            $('#notificationcount').text('0');

            $('#notificationTop').addClass('hidden-xs-up');
            $('#notificationTop').text('0');
        }
    });

    if (screen_size > 989) {
        $('[data-toggle="tooltip_custom"]').tooltip();
    }
    try {
        $('#chat-app-scroll').scrollbar();
    } catch (e) {
    }
    var Nslider = '';

    $(document).on('click', '.notificationMakeSlide', function (event) {

        Nslider = $(this).attr('data-slide');
        $('#notificationSlide').carousel(1);
    });

    $('#notificationModal').on('hidden.bs.modal', function (e) {
        $('#notificationSlide').carousel(0);
    });

    $('#notificationSlide').on('slide.bs.carousel', function (e) {
        $('#notificationSliderPost').html('');

        $.getJSON(Nslider, function (data) {

            $.each(data, function (key, val) {

                $('#notificationSliderPost').html(postTemplate(val, 0, 1, 1));

            });

        }).done(function () {

            commentInit();
        });


    });

    $('body').on('click', function (e) {

        if ($(e.target).data('toggle') !== 'popover' &&
            $(e.target).parents('.feedsPopup').length == 0 &&
            $(e.target).parents('.popover.in').length === 0 &&
            $('body').has('.popover').length) {

            for (var i in popup1) {
                if (popup1[i].open) {
                    popup1[i].open = false;
                    $("#" + popup1[i].id).trigger('click')
                }
            }
        }
    });

    $(document).on('hidden.bs.popover', '.feedsPopup', function () {
        for (var i in popup1) {
            if (popup1[i].open) {
                popup1[i].open = false;
                $("#" + popup1[i].id).trigger('click')
            }
        }

    });

    $(document).on('show.bs.popover', '.feedsPopup', function () {
        for (var i in popup1) {
            if (popup1[i].open) {
                popup1[i].open = false;
                $("#" + popup1[i].id).trigger('click')
            }
        }

        for (var i in popup1) {
            if ($(this).attr('id') == popup1[i].id) {
                popup1[i].open = true;
                console.log(popup1[i].open)
            }
        }
        var Fpopup = $(this).attr('data-popup');

        var popup = $(this);
        $.ajaxSetup({
            async: false
        });


        $.getJSON(Fpopup, function (data) {

            $.each(data, function (key, val) {
                $(popup).attr('data-content', postTemplate(val, 0, 1));


            });

        });


        $.ajaxSetup({
            async: true
        });


    });

    function post_success_manager(data, location, success, submit) {

        switch (success) {
            case 'otp':
                $('#myModal').modal({backdrop: 'static', keyboard: false, show: true,});
                break;

            case 'otp1':
                $('#otpModal').modal({backdrop: 'static', keyboard: false, show: true,});
                break;
            case 'otp2':
                $('#otpModal').modal('hide');
                break;
            case 'load_picture':
                $('#profile_pic_upload').children().first().remove();
                $('#profile_pic_upload').children().first().removeClass("hidden-xs-up");
                $('#profile_pic_upload').children().first().attr("src", data);
                break;

            case 'load_wall':
                $('#wall_pic_upload').children().first().remove();
                $('#wall_pic_upload').children().first().removeClass("hidden-xs-up");
                $('#wall_pic_upload').children().first().attr("src", data);
                // $('#wall_pic_upload').attr("src", data);
                break;
            case 'post-common':
                break;

            case 'comment':
                $(location).prev().prev().children().last().remove();
                var comment_icon = $(location).prev().prev().prev().prev().find('.comment');
                var comment = parseInt($(comment_icon).text());
                $(comment_icon).find('small').text(comment + 1);
                if (!$(comment_icon).hasClass('commented'))
                    $(comment_icon).addClass('commented');
                var template = '';
                if (comment == 0)
                    template += '<div class="mt-0  mb-0 hr-custom"> </div>';
                template += '<div class="pt-0_5 b-lightgrey">';
                user["comment"] = data["comment"];
                user["csrf"] = csrf;
                user["post_id"] = data["post_id"];
                user["comment_id"] = data["id"];
                user["username"] = user["username"].replace("/@", "");

                template += commentTemplate(user);
                template += '</div>';

                $(location).prev().prev().append(template);
                $('.grid1').masonry();
                $(submit).prop('disabled', false);
                break;

            case 'loadmore':

                if (!data.length) {
                    $(location).children('.loadmore').addClass('hidden-xs-up');
                }
                else {

                    if (data.length < 4) {
                        $(location).children('.loadmore').addClass('hidden-xs-up');
                    }
                    var template = '';
                    $.each(data, function (index, value) {
                        template += '<div class="pt-0_5 b-lightgrey">';
                        template += commentTemplate(value);
                        template += '</div>';
                    });
                    var currentxofy = $(location).children('.loadmore').children().next().html();
                    var space = currentxofy.indexOf(" ");
                    var updatexofy = currentxofy.substring(space, currentxofy.length);
                    var x = parseInt(currentxofy.substring(0, space));
                    x += data.length;

                    $(location).children('.loadmore').children().next().html(x + ' ' + updatexofy);

                    $(location).next().prepend(template);
                    $("input[name='load']", location).val(parseInt($("input[name='load']", location).val()) + 1);
                }
                try {
                    $('.grid1').masonry();
                    $('.grid01').masonry();
                    $('.grid02').masonry();
                    $('.grid2').masonry();

                }catch(e){

                }
                break;

            case 'post_init':
                $('#myModal').find("textarea[name='postText']").val('');
                $('#myModal').find("input[type='submit']").prop('disabled', false);
                $("#dummy-post").html('');
                $("#dummy-image").html('');
                $("#sortable").html('');
                $("#dummy_time").html('1 s ago');
                $("#dummy_like").html(0);
                $("#dummy_dislike").html(0);
                $("#dummy_comment").html(0);
                $('#post-editor').next().children().first().html('');
                editHelper = '';
                break;

            case 'post':
                var text = $('#post-editor').next().children().first().html();


                text = smilieEncode(text);
                if (editHelper) {
                    $('#myModal').modal('hide');
                    $(editHelper).closest('.container-fluid').next('.pt-0_5').remove();
                    $(editHelper).closest('.container-fluid').next().children('p').text(text);
                    if (data.post_data.length) {
                        $(editHelper).closest('.container-fluid').after(post_imageTemplate(data.post_data));
                    }
                    $('#post-editor').next().children().first().html('')
                    $("#dummy-post").html('');
                    $("#dummy-image").html('');
                    $("#sortable").html('');
                    editHelper = '';
                    $('.grid1').masonry();
                }
                else {
                    $('#myModal').modal('hide');
                    user["post_text"] = text;
                    user["likes"] = 0;
                    user["dislikes"] = 0;
                    user["comments"] = 0;
                    user["isliked"] = 0;
                    user["isdisliked"] = 0;
                    user["iscommented"] = 0;
                    user["post_id"] = data.id;
                    user["csrf"] = csrf;
                    user["post_data"] = data.post_data;
                    user["post_time"] = '1 s ago';
                    $('#post-editor').next().children().first().html('')
                    var $elems = $(postTemplate(user, 1));
                    $('.grid1').prepend($elems).masonry('prepended', $elems);
                    $("#dummy-post").html('');
                    $("#dummy-image").html('');
                    $("#sortable").html('');
                    // $('.grid1').masonry();
                }
                $(submit).prop('disabled', false);

                commentInit();

                break;


            case 'newPost':

                if (!$("#new_posts").hasClass("hidden-xs-up"))
                    $("#new_posts").addClass("hidden-xs-up");
                if (!$("#homecountTop").hasClass("hidden-xs-up"))
                    $("#homecountTop").addClass("hidden-xs-up");
                if (!$("#homecount").hasClass("hidden-xs-up"))
                    $("#homecount").addClass("hidden-xs-up");

                $('#new_posts').html("0");
                $('#homecountTop').html("0");
                $('#homecount').html("0");

                var lastid = null;
                $.each(data, function (key, val) {

                    lastid = val.post_id;

                    var $elems = $(postTemplate(val));
                    $('.grid1').append($elems).masonry('prepended', $elems);

                });
                $('#new_posts').prev().val(lastid);
                commentInit();
                break;

            case 'chat':

                // if (jQuery.inArray(data[1], chatlist) == -1 && chat_app_conversation_id != data[1]) {
                //     chatlist.push(data[1]);
                //     Rmessagelist[data[1]] = [];
                //     var chat = [];
                //     chat["csrf"] = csrf;
                //     chat["id"] = data[1];
                //     chat["user_name"] = data[2];
                //     chat["user_picture"] = data[3];
                //     chat["username"] = data[4];
                //     chat["auth"] = data[5];
                //
                //
                //     if (screen_size > 989) {
                //         $("#chatmanager").append(chatTemplate(chat));
                //
                //         $(".awesome-chat").draggable({
                //             scroll: false,
                //         });
                //
                //
                //
                //         if ($("input.chatBox1:text").next().hasClass('emojionearea')) {
                //
                //             $("input.chatBox1:text").unbind();
                //
                //         }
                //
                //         $(".chatBox1").emojioneArea({
                //             pickerPosition: "top",
                //             autocomplete: false,
                //             inline: true,
                //         });
                //
                //         $("input.chatBox1:text").each(function (i, obj) {
                //
                //             if ($(this).next().next().hasClass('emojionearea')) {
                //                 $(this).next().remove();
                //                 $(this).next().children().first().html('');
                //                 $(this).next().children().first().attr('placeholder','Message');
                //
                //             }
                //
                //         });
                //
                //
                //         var id = "#conversation_" + chat["id"];
                //         $(id).scrollbar();
                //
                //         var flag = 1;
                //         $('.online_users-recent').each(function (i, obj) {
                //
                //             if (chat["id"] == parseInt($(obj).attr('conversation-id')))
                //                 flag = 0;
                //         });
                //
                //         if (flag == 1) {
                //
                //             var template = '<div  class="online_users-recent cursor-pointer list-group-item bx-0 ba-0 p-0 square-corner list-group-item-action" ' +
                //                 'conversation-id="' + data[1] + '" ' +
                //                 'user-id="' + data[4] + '"> ' +
                //                 '<ul class="nav  nav-inline p-0_5"> ' +
                //                 '<li class="nav-item align-top "> ' +
                //                 '<img class="rounded-circle pp-40" src="' + data[3] + '" ' +
                //                 'alt="' + data[2] + '">' +
                //                 ' </li>' +
                //                 ' <li class="nav-item mx-0 online_width-s">' +
                //                 ' <div class="lh-1 pl-0_5 "> ' +
                //                 '<p class="m-0 p-0 comment_link ">' + anonymousTagTemplate(data[2]) + '</p> ' +
                //                 '<small class="text-muted opensanN"></small> ' +
                //                 '</div> ' +
                //                 '</li> ' +
                //                 '<li class="nav-item float-xs-right pl-0 pr-0_5">' +
                //                 '<span class="tag tag-primary tag-pill hidden-xs-up">0</span>' +
                //                 '<small class="text-muted opensanL">1 S</small> ' +
                //                 '</li> ' +
                //                 '</ul> ' +
                //                 '</div> ';
                //             $('#recent-chat').prepend(template);
                //         }
                //
                //
                //     }
                //     else {
                //
                //         window.location.href = "/messages_all";
                //     }
                // }


                break;

            case 'chat-app':

                var conversation_id = data[1];

                $('#chat-app').children('a').next().next().children('span').text(anonymousTagTemplate(data[2]));
                $('#chat-app').children().last().removeClass('hidden-xs-up');
                $('#chat-app').children('a').next().next().children('small').html('');
                $('#chat-app').children().last().children('form').children().next().next().val(conversation_id);
                $('#chat-app').children('a').next().next().attr('href', '/@' + data[4]);
                $('#chat-app').children('img').attr('src', data[3]);
                $('#chat-app').children('img').attr('alt', data[2]);
                $('#chat-app').next().attr('id', "conversation_" + conversation_id);
                $('#chat-app').next().next().children('form').children('input:nth-child(2)').val(conversation_id);
                // $('#chat-app').next().next().children('form').children('input:nth-child(4)').prop("disabled", false);
                var token = $('#chat-app').next().next().children('form').children('input:nth-child(1)').val();
                $('#chats-list').addClass('hidden-sm-down');
                $('#chats-content').removeClass('hidden-sm-down');
                var conversation = '#conversation_' + conversation_id;
                $(conversation).find('.chat-content').html('');
                $(conversation).find('.chat-content').html('');
                load_prev_msg(conversation_id, token, true);
                Rmessagelist[conversation_id] = [];
                chat_app_conversation_id = conversation_id;

                var flag = 1;
                $('.message_app_users').each(function (i, obj) {

                    if (conversation_id == $(obj).attr('conversation-id'))
                        flag = 0;
                });

                if (flag) {

                    var template = '<div  class="message_app_users cursor-pointer list-group-item p-0 bb-1 border-light square-corner list-group-item-action" ' +
                        'conversation-id="' + conversation_id + '" ' +
                        'user-id="' + data[4] + '"> ' +
                        '<ul class="nav  nav-inline p-0_5"> ' +
                        '<li class="nav-item align-top "> ' +
                        '<img class="rounded-circle pp-40" src="' + data[3] + '" ' +
                        'alt="' + data[2] + '">' +
                        ' </li>' +
                        ' <li class="nav-item mx-0 online_width-s">' +
                        ' <div class="lh-1 pl-0_5 "> ' +
                        '<p class="m-0 p-0 comment_link ">' + data[2] + '</p> ' +
                        '<small class="text-muted opensanN"></small> ' +
                        '</div> ' +
                        '</li> ' +
                        '<li class="nav-item float-xs-right pl-0 pr-0_5">' +
                        '<small class="text-muted opensanL">1 S</small> ' +
                        '</li> ' +
                        '</ul> ' +
                        '</div> ';
                    $('#chat-app-scroll1').find('.list-group').prepend(template);
                }
                chatPersons();
                $.ajax({
                    type: "POST",
                    url: "/check-all",
                    data: {conversation_id: conversation_id, '_token': csrf},
                    success: function (data) {

                        var count = parseInt($('#messagecount').html());
                        if (count > 0) {
                            $('#messagecount').html(count - 1);
                            $('#messagecountTop').html(count - 1);
                        }

                        if (count < 2) {
                            if (!$('#messagecount').hasClass('hidden-xs-up')) {
                                $('#messagecount').addClass('hidden-xs-up');
                                $('#messagecountTop').addClass('hidden-xs-up');
                            }
                        }

                    },
                    error: function (data) {
                    },
                    complete: function () {

                    }
                });

                break;


            case 'chatting':

                $(location).closest('.msgbox').prev()
                    .find('.chat-content')
                    .children('.chat-msg-box.me').last().attr('id', 'message-' + data.id);
                $(location).closest('.msgbox').prev()
                    .find('.chat-content')
                    .children('.chat-msg-box.me').last().find('.detailmsg').html(data.time + ' ' + data.status);
                messagelist.push(data.id);
                jQuery($(location).closest('.msgbox').prev().children().first()).scrollTop(1500000);

                break;
            case 'chatting1':

                $(location).closest('.msgbox').prev()
                    .find('.chat-content')
                    .children('.chat-msg-box.me').last().attr('id', 'message-' + data.id);
                $(location).closest('.msgbox').prev()
                    .find('.chat-content')
                    .children('.chat-msg-box.me').last().find('.detailmsg').html(data.time + ' ' + data.status);
                messagelist.push(data.id);
                jQuery($(location).closest('.msgbox').prev().children().first()).scrollTop(1500000);
                break;

            case 'follow':
                if ($(location).find('button').hasClass('btn-primary')) {
                    $(location).find('button').html('<i class="fa fa-user-plus  " aria-hidden="true"></i> Following');
                    $(location).find('button').removeClass('btn-primary');
                    $(location).find('button').addClass('btn-secondary');
                    $(location).find('button').addClass('unfollow');
                    $('#user_followers').html('<i class="fa fa-users " aria-hidden="true"></i> ' + (parseInt($('#user_followers').text()) + 1));
                }
                else {
                    $(location).find('button').html('<i class="fa fa-user-plus  " aria-hidden="true"></i> Follow');
                    $(location).find('button').addClass('btn-primary');
                    $(location).find('button').removeClass('btn-secondary');
                    $(location).find('button').removeClass('btn-danger');
                    $(location).find('button').removeClass('unfollow');
                    $('#user_followers').html('<i class="fa fa-users " aria-hidden="true"></i> ' + (parseInt($('#user_followers').text()) - 1));

                }
                break;

            case 'block':

                console.log($(location).find('.dropdown-menu  button').text());
                if ($(location).find('.dropdown-menu button').text().trim() == 'Block') {

                    $(location).find('.dropdown-menu button').html('<i class="fa fa-ban" aria-hidden="true"> </i> Unblock');

                }
                else {

                    $(location).find('.dropdown-menu button').html('<i class="fa fa-ban" aria-hidden="true"> </i> Block');
                }
                break;

            case 'unblock':

                $(location).remove();

                break;
            case 'facematch':

                $('#facematchModal').modal('hide');

                break;

            case 'report':

                $('#reportModal').modal('hide');

                break;

            case 'post_upload':
                $('#sortable').children("li").last().remove();
                $('#sortable').append(image_uploadTemplate(data));
                $('#dummy-image').html('<img class="img-fluid w-100" src="' + data.image + '" alt="image">');

                break;
            case 'comment_del':
                if (data) {

                    var comment_icon = $(location).closest('.commentblock').prev().prev().find('.comment');
                    var comment = parseInt($(comment_icon).text());
                    $(comment_icon).find('small').text(comment - 1);
                    if ($(comment_icon).hasClass('commented'))
                        $(comment_icon).removeClass('commented');
                    if (($(location).closest('.commentblock').children('.b-lightgrey')).length == 1)
                        $(location).closest('.container-fluid').remove();
                    else
                        $(location).closest('.container-fluid').parent().remove();
                }
                $('.grid1').masonry();
                break;
            case 'post_del':
                if (data)
                    $(location).closest('.card').remove();
                $('.grid1').masonry();
                break;

            case 'post_req':
                if (data) {
                    $("#myModal").modal('show');
                    $("#myModal").find('textarea').val(data[0].post_text);
                    $("#dummy-post").html(data[0].post_text);
                    $("#dummy_time").html(data[0].post_time);
                    $("#dummy_like").html(data[0].likes);
                    $("#dummy_dislike").html(data[0].dislikes);
                    $("#dummy_comment").html(data[0].comments);
                    editHelper = location;
                    if (data[0].post_data.length) {
                        var new_data = [];
                        new_data.csrf = csrf;
                        new_data.image_id = data[0].post_data[0].source_id;
                        new_data.image = data[0].post_data[0].source;
                        $('#sortable').append(image_uploadTemplate(new_data));

                        $("#dummy-image").html(post_imageTemplate(data[0].post_data));
                    }
                }
                break;

            case 'upload_profile_picture':
                var data = $('#profile_pic_upload').children().first().attr('src');
                $('.myprofile').attr('src', data);
                $('#userinfo').find('img').attr('src', data);
                user['user_picture'] = data;
                $('#profilepicModal').modal('hide');
                break;

            case 'upload_wall_picture':
                var data = $('#wall_pic_upload').children().first().attr('src');
                data = "url('" + data + "')"
                $('.profile-wall').attr('style', '');
                $('.profile-wall').css('background', data);
                $('.profile-wall').css('background-size', 'cover');


                $('#wallModal').modal('hide');
                break;

            case 'Unfollow_Post':

                $(location).remove();
                break;

            case 'followers':

                if (data == "-1") {
                    $('#profileList').html('<div class="text-xs-center w-100"><i class="fa fa-lock fa-3x" aria-hidden="true"></i></div>');
                    $('#profileListModal').find('.modal-title').text("Followers");
                    $('#profileListModal').modal('show');
                }
                else {
                    var template = '';
                    $.each(data, function (index, value) {

                        template += profilelistTemplate(value);
                    });
                    $('#profileList').html(template);
                    $('#profileListModal').find('.modal-title').text("Followers");
                    $('#profileListModal').modal('show');
                }
                break;


            case 'following':

                if (data == "-1") {
                    $('#profileList').html('<div class="text-xs-center w-100"><i class="fa fa-lock fa-3x" aria-hidden="true"></i></div>');
                    $('#profileListModal').find('.modal-title').text("Followers");
                    $('#profileListModal').modal('show');
                }
                else {
                    var template = '';
                    $.each(data, function (index, value) {

                        template += profilelistTemplate(value);
                    });
                    $('#profileList').html(template);
                    $('#profileListModal').find('.modal-title').text("Following");
                    $('#profileListModal').modal('show');
                }
                break;

            case 'facematches':

                if (data == "-1") {
                    $('#profileList').html('<div class="text-xs-center w-100"><i class="fa fa-lock fa-3x" aria-hidden="true"></i></div>');
                    $('#profileListModal').find('.modal-title').text("Followers");
                    $('#profileListModal').modal('show');
                }
                else {
                    var template = '';
                    $.each(data, function (index, value) {

                        template += profilelistTemplate1(value);
                    });
                    $('#profileList').html(template);
                    $('#profileListModal').find('.modal-title').text("FaceMatches");
                    $('#profileListModal').modal('show');
                }
                break;

            case 'chat_allow':
                getMessages();
                $(location).parent().next().removeClass('hidden-xs-up');
                $(location).parent().remove();
                var count = parseInt($('#messagecount').html());
                if (count > 0) {

                    $('#messagecount').html(count - 1);
                    $('#messagecountTop').html(count - 1);
                }

                if (count < 2) {
                    if (!$('#messagecount').hasClass('hidden-xs-up')) {
                        $('#messagecount').addClass('hidden-xs-up');
                        $('#messagecountTop').addClass('hidden-xs-up');
                    }
                }


                break;


            case 'chat_block':

                $(location).parent().parent().parent().prev().children().first().trigger('click');
                $('#chat-app').children().last().addClass('hidden-xs-up');
                $('#chat-app').children().last().children('form').children().next().next().val('-1');
                $('#chat-app').children('a').next().next().children('span').text('');
                $('#chat-app').children('a').next().next().children().last().html('');
                $('#chat-app').children('a').next().next().attr('href', '');
                $('#chat-app').children('img').attr('src', 'http://res.cloudinary.com/fabits-in/image/upload/v1/fabits/blank.jpg');
                $('#chat-app').children('img').attr('alt', '');
                $('#chat-app').next().attr('id', "-1");
                $('#chat-app').next().next().children('form').children('input:nth-child(2)').val('-1');
                chat_app_conversation_id = null;
                // $('#chat-app').next().next().children('form').children('input:nth-child(4)').prop("disabled", true);
                $('#chat-app').next().children().children('.chat-content').html('');
                $('#chat-app').next().children('.chat-content').html('');
                break;

            case 'chat_block1':

                $('#chat-app').children('a').next().next().children('span').text('');
                $('#chat-app').children().last().addClass('hidden-xs-up');
                $('#chat-app').children('a').next().next().children().last().html('');
                $('#chat-app').children('a').next().next().attr('href', '');
                $('#chat-app').children('img').attr('src', 'http://res.cloudinary.com/fabits-in/image/upload/v1/fabits/blank.jpg');
                $('#chat-app').children('img').attr('alt', '');
                $('#chat-app').next().attr('id', "-1");
                $('#chat-app').next().next().children('form').children('input:nth-child(2)').val('-1');
                // $('#chat-app').next().next().children('form').children('input:nth-child(4)').prop("disabled", true);
                $('#chat-app').next().children().children('.chat-content').html('');
                $('#chat-app').next().children('.chat-content').html('');


                var flag = 1;

                $('.message_app_users').each(function (i, obj) {

                    if (chat_app_conversation_id == $(obj).attr('conversation-id')) {
                        obj.remove();
                        var count = parseInt($('#messagecount').html());
                        if (count > 0) {
                            $('#messagecount').html(count - 1);
                            $('#messagecountTop').html(count - 1);

                        }
                        if (count < 2) {
                            if (!$('#messagecount').hasClass('hidden-xs-up')) {
                                $('#messagecount').addClass('hidden-xs-up');
                                $('#messagecountTop').addClass('hidden-xs-up');
                            }
                        }
                    }
                });

                chat_app_conversation_id = null;
                $(location).parent().remove();

                break;

            default:
                if (location)
                    window.location.replace(location);
                break;
        }

    }

    function post_Bsuccess_manager(location, success, data, submit) {

        switch (success) {

            case 'post_upload':

                $('#sortable').append('<li><div class="progress"> ' +
                    '<div id="progressBar" class="progress-bar progressBar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">' +
                    '2% ' +
                    '</div> ' +
                    '</div>' +
                    '</li>');


                break;


            case 'load_picture':
                $('#profile_pic_upload').children().first().addClass("hidden-xs-up");
                $('#profile_pic_upload').prepend('<div class="progress"> ' +
                    '<div id="progressBar" class="progress-bar progressBar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">' +
                    '2% ' +
                    '</div> ' +
                    '</div>');


                break;


            case 'load_wall':
                $('#wall_pic_upload').children().first().addClass("hidden-xs-up");
                $('#wall_pic_upload').prepend('<div class="progress"> ' +
                    '<div id="progressBar" class="progress-bar progressBar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">' +
                    '2% ' +
                    '</div> ' +
                    '</div>');


                break;

            case 'post':
                itsme = 1;
                $(submit).prop('disabled', true);
                break;

            case 'post-common':
                if (data == '1') {
                    var like = $(location);
                    var likecount = $(like).children('small');
                    var dislike = $(location).closest('li').next('li').find('.post-feature-common');
                    var dislikecount = $(dislike).find('small');
                    if ($(dislike).hasClass('disliked')) {
                        $(dislikecount).text(parseInt($(dislikecount).text()) - 1);
                    }
                    if ($(like).hasClass('liked')) {
                        $(likecount).text(parseInt($(likecount).text()) - 1);
                        $(like).removeClass('liked');
                        $(dislike).removeClass('disliked');
                        if (parseInt($(likecount).text()) == 0) {
                            $(location).closest('.container-fluid').next().html('');
                            $('.grid1').masonry();

                        }
                        else {
                            var allikes = $(location).closest('.container-fluid').next().children().children().children();
                            $.each(allikes, function (index, val) {

                                if ($(val).attr('data-me') == 'me') {
                                    $(val).remove();
                                }
                            });
                        }

                    } else {
                        if (parseInt($(likecount).text()) == 0) {
                            var template = '<div class="row mb-1">' +
                                '<div class="col-xs-12 ">';
                            template += '<a href="/like/' + $(location).closest('form').children().next().val() + '" ' + 'data-loc="like_list" ' +
                                'class="d-inline like_list  ba-1 border-light sd-min cursor-pointer"><b>+</b></a>';
                            template += '</div>' +
                                '</div>';
                            $(location).closest('.container-fluid').next().html(template);
                        }
                        var template1 = '<img class="rounded-circle pp-30 mr-0_5" src="' + user['user_picture'] + '" ' +
                            'alt="' + user['user_name'] + '"  data-me="me" data-toggle="tooltip" data-placement="top" title="' + user['user_name'] + '">';
                        $(location).closest('.container-fluid').next().children().children().prepend(template1);
                        $('[data-toggle="tooltip"]').tooltip();
                        $('.grid1').masonry();

                        $(likecount).text(parseInt($(likecount).text()) + 1);
                        $(like).addClass('liked');
                        $(dislike).removeClass('disliked');


                    }
                }
                else if (data == '0') {
                    var dislike = $(location);
                    var dislikecount = $(dislike).children('small');
                    var like = $(location).closest('li').prev('li').find('.post-feature-common');
                    var likecount = $(like).find('small');
                    if ($(like).hasClass('liked')) {
                        $(likecount).text(parseInt($(likecount).text()) - 1);
                    }
                    if ($(dislike).hasClass('disliked')) {
                        $(dislikecount).text(parseInt($(dislikecount).text()) - 1);
                        $(dislike).removeClass('disliked');
                        $(like).removeClass('liked');
                    }
                    else {
                        $(dislikecount).text(parseInt($(dislikecount).text()) + 1);
                        $(dislike).addClass('disliked');
                        $(like).removeClass('liked');

                        if (parseInt($(likecount).text()) == 0) {
                            $(location).closest('.container-fluid').next().html('');
                            $('.grid1').masonry();

                        }
                        else {
                            var allikes = $(location).closest('.container-fluid').next().children().children().children();
                            $.each(allikes, function (index, val) {
                                if ($(val).attr('data-me') == 'me') {
                                    $(val).remove();
                                }
                            });
                        }
                    }
                }

                break;

            case 'facematch':

                $('#facematchModal').modal('hide');

                break;

            case 'comment':
                if (data.length > 0) {
                    var comment_icon = $(location).prev().prev().prev().prev().find('.comment');
                    var comment = parseInt($(comment_icon).text());

                    var template = '';
                    if (comment == 0)
                        template += '<div class="mt-0  mb-0 hr-custom"> </div>';
                    template += '<div class="pt-0_5 b-lightgrey">';
                    user["comment"] = data;
                    user["csrf"] = csrf;
                    user["post_id"] = "";
                    user["comment_id"] = "";
                    template += commentTemplate(user);
                    template += '</div>';

                    $(location).prev().prev().append(template);
                    try {
                        $('.grid1').masonry();
                        $('.grid01').masonry();
                        $('.grid02').masonry();
                        $('.grid2').masonry();

                    }catch(e){

                    }
                    $(submit).prop('disabled', true);
                    // $(location).closest('.emojionearea-editor').html('');
                    // $('.comment-editor').text('');
                }

                break;
            case 'chatting':
                if (data.length > 0) {
                    var data1 = [];
                    data1["message"] = data;
                    data1["id"] = 0;
                    data1["time"] = "1s ago";
                    data1["status"] = "S";

                    $(location).closest('.msgbox').prev()
                        .find('.chat-content')
                        .append(messageTemplate(data1, 0));

                    jQuery($(location).closest('.msgbox').prev().children().first()).scrollTop(1500000);
                }
                break;
            case 'chatting1':
                if (data.length > 0) {
                    var data1 = [];
                    data1["message"] = data;
                    data1["id"] = 0;
                    data1["time"] = "1s ago";
                    data1["status"] = "S";

                    $(location).closest('.msgbox').prev()
                        .find('.chat-content')
                        .append(messageTemplate(data1, 0));

                    jQuery($(location).closest('.msgbox').prev().children().first()).scrollTop(1500000);
                }
                break;

        }
    }

    $(document).on('submit', 'form', function (event) {

        event.preventDefault();
        var location = $("input[name='location']", this).val();
        var submit = $("input[type='submit']", this);
        var success = $("input[name='success']", this).val();
        var likedata = '';
        if (success === 'post-common') {
            likedata = location;
            location = $(this).find('.post-feature-common');
        }

        if (success === 'comment') {
            submit = $("button[type='submit']", this);
        }

        if (success === 'post_upload') {
            submit = $("button[type='button']", this);

        }

        if (success === 'loadmore' || success === 'comment' || success === 'chat' || success === 'follow'
            || success === 'unblock' || success === 'post_upload_remove'
            || success === 'comment_del' || success === 'post_req' || success === 'post_del'
            || success === 'Unfollow_Post' || success === 'block' || success === 'chat_block'
            || success === 'chat_block1' || success === 'chat_allow')
            location = $(this);


        var form_data = new FormData();
        $($(this).prop('elements')).each(function () {
            if (this.type == 'file')
                form_data.append(this.name, this.files[0]);
            else if (this.type == 'checkbox')
                form_data.append(this.name, $(this).prop('checked'));
            else if (this.type == 'radio') {
                if ($(this).prop('checked'))
                    form_data.append(this.name, $(this).attr('value'));
            }
            else {
                if (success === "post" && this.type == 'textarea') {
                    var text = $(this).next().children().first().html();
                    text = smilieEncode(text);
                    form_data.append(this.name, text);
                }

                else if (success === "comment" && this.type == 'text') {
                    var text = $(this).next().children().first().html();
                    text = smilieEncode(text);
                    likedata = text;
                    form_data.append(this.name, text);
                    $(this).next().children().first().html('');

                } else if (success === "chatting" && this.type == 'text') {

                    var text = $(this).next().children().first().html();
                    text = smilieEncode(text);
                    likedata = text;
                    form_data.append(this.name, text);
                    $(this).next().children().first().html('');
                    $(this).next().children().first().focus();

                } else if (success === "chatting1" && this.type == 'text') {
                    var text = $(this).next().children().first().html();
                    text = smilieEncode(text);
                    likedata = text;
                    form_data.append(this.name, text);
                    $(this).next().children().first().html('');
                    $(this).next().children().first().focus();
                }
                else
                    form_data.append(this.name, $(this).val());

            }
        });

        if (success === 'chatting' || success === 'chatting1') {

            location = $(this);
        }

        $.ajax({
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            url: $(this).attr('action'),
            data: form_data,
            beforeSend: function () {
                $("input[type='submit']").prop('disabled', true);
                $("input[type='file']").prop('disabled', true);
                $('#search-result').hide();
                var data = likedata;
                if (!(success === 'comment' || success === 'facematch' || success === 'chatting' || success === 'chatting1' || success === 'post-common'))
                    NProgress.start();
                post_Bsuccess_manager(location, success, data, submit);

            },
            success: function (data) {
                if (!(success === 'comment' || success === 'chatting' || success === 'chatting1' || success === 'post-common'))
                    NProgress.done();
                post_success_manager(data, location, success, submit);
                $("input[type='submit']").prop('disabled', false);
                $("input[type='file']").prop('disabled', false);
            },

            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (success === 'post_upload' || success === 'load_picture' || success === 'load_wall') {
                    if (myXhr.upload) {
                        // For handling the progress of the upload
                        myXhr.upload.addEventListener('progress', function (e) {
                            if (e.lengthComputable) {

                                $(".progressBar").width(((e.loaded / e.total) * 100) + '%'); //update progressbar percent complete
                                if (parseInt((e.loaded / e.total) * 100) == 100) {
                                    $(".progressBar").html('Compressing...'); //update status text

                                } else {
                                    $(".progressBar").html(parseInt((e.loaded / e.total) * 100) + '%'); //update status text

                                }

                                // $('progress').attr({
                                //     value: e.loaded,
                                //     max: e.total,
                                // });
                            }
                        }, false);
                    }
                }
                return myXhr;
            },
            error: function (data) {
                $("input[type='submit']").prop('disabled', false);
                $("input[type='file']").prop('disabled', false);
                if (!(success === 'comment' || success === 'chatting' || success === 'chatting1' || success === 'post-common'))
                    NProgress.done();
                $.each($.parseJSON(data.responseText), function (idx, obj) {
                    $("#alert_message").html(alertTemplate(obj[0]));
                    $("#alert_message").hide();
                    $("#alert_message").fadeIn();
                    $("#alert_message").css('opacity', '1');
                    $("#alert_message").show();
                    $("#alert_message").fadeOut(5000);


                    return false;
                });
            }
        });

    });

    function get_Bsuccess_manager(success) {

        switch (success) {
            case 'page-menu':
                if (!$('#collapse').hasClass('in'))
                    $('#collapse').addClass('in');
                break;
        }
    }

    function get_success_manager(data, success) {

        switch (success) {


            case 'settings':
                if (data) {
                    $('#settings-content').html(data);
                    $('#settings-list').addClass('z-10');
                    $('#settings-list').removeClass('z-11');
                    $('#settings-content').addClass('z-11');
                    $('#settings-content').removeClass('z-10');
                }
                break;
            case 'settings-back':
                $('#settings-list').addClass('z-11');
                $('#settings-list').removeClass('z-10');
                $('#settings-content').addClass('z-10');
                $('#settings-content').removeClass('z-11');
                break;

            case 'chats-back':

                $('#chats-list').removeClass('hidden-sm-down');
                $('#chats-content').addClass('hidden-sm-down');
                chat_app_conversation_id = null;

                break;
            case 'page':
                $(window).off("scroll");
                $('#page').html('');
                $('#page').html(data);
                $('#profileListModal').modal('hide');
                $('#likeModal').modal('hide');
                $(document).prop('title', $('#ajax-title').attr('data-title'));
                break;

            case 'page-menu':
                $(window).off("scroll");
                $('#page').html('');
                $('#page').html(data);
                $('[data-toggle="tooltip_custom"]').tooltip('hide');
                $(document).prop('title', $('#ajax-title').attr('data-title'));
                break;


            case 'like_list':
                var template = '';
                $.each(data, function (key, val) {
                    template += '<a href="/@' + val.username + '" data-loc="page" class="ba-0 px-1 my-1 py-0 square-corner list-group-item ' +
                        '">' +
                        likelistTemplate(val) +
                        '</a>';
                });
                $("#likedby").html(template);
                $('#likeModal').modal('show');
                break;


        }
    }

    $(document).on('click', 'a', function (event) {
        if ($(this).attr('target') == '_blank')
            return true;
        //
        event.preventDefault();
        var success = $(this).attr('data-loc');

        var url = $(this).attr('href');
        if (success == 'page-full')
            window.location.href = url;

        if (success == 'page-exter')
            window.open(url, '_blank');

        if (!(url.indexOf('#')) || window.location.href === url) {

            get_success_manager(null, success);
        }
        else {
            $.ajax({
                type: "GET",
                url: url,
                beforeSend: function () {
                    $('#search-result').hide();
                    NProgress.start();
                    get_Bsuccess_manager(success);

                },
                success: function (data) {
                    NProgress.done();
                    if (success != "like_list")
                        history.pushState(null, null, url);
                    get_success_manager(data, success);
                },
                error: function (data) {
                    NProgress.done();
                    $.each($.parseJSON(data.responseText), function (idx, obj) {
                        $("#alert_message").html(alertTemplate(obj[0]));
                        $("#alert_message").hide();
                        $("#alert_message").fadeIn();
                        $("#alert_message").css('opacity', '1');
                        $("#alert_message").show();
                        $("#alert_message").fadeOut(5000);

                        return false;
                    });
                }
            });
        }
    });

    $(document).on('dragstop', '.awesome-chat', function (event, ui) {

        var currentTop = ui.position.top;
        var currentLeft = ui.position.left;


        autoset(currentTop, currentLeft, this);

    });

    $(document).on('dragstart', '.awesome-chat', function (event, ui) {

        $(this).next().hide();

    });

    $(document).on('click', '.awesome-chat', function (event, ui) {

        var currentTop = $(this).css('top');
        var currentLeft = $(this).css('left');
        currentTop = parseInt(currentTop);
        currentLeft = parseInt(currentLeft);

        if ($(this).next().is(":visible")) {

            $(this).next().fadeToggle();
            autoset(currentTop, currentLeft, this);

        } else {

            var windowHeight = $(window).height();
            var windowWidth = $(window).width();
            if (currentTop >= ( windowHeight - 430 )) {
                currentTop = ( windowHeight - 400 );
            }

            if (currentLeft >= ( windowWidth - 175 )) {
                currentLeft = ( windowWidth - 175 );
            }

            if (currentLeft < 140) {
                currentLeft = 140;
            }

            $(this).next().find("input").focus();
            $(this).next().css('left', currentLeft - 130);
            $(this).next().css('top', currentTop + 50);
            $(this).animate({
                'left': currentLeft + 'px',
                'top': currentTop + 'px'
            });
            $(this).next().fadeToggle();

            var conversation_id = parseInt($(this).next().children().first().children().first().attr('conversation-data'));


            $('.online_users-recent').each(function (i, obj) {
                if (parseInt($(this).attr('conversation-id')) == conversation_id) {


                    if (!$(this).children('ul').children('li').next().next().children('span').hasClass('hidden-xs-up')) {
                        console.log($(this).children('ul').children('li').next().next().children('span'));
                        $(this).children('ul').children('li').next().next().children('span').addClass('hidden-xs-up');
                        $(this).children('ul').children('li').next().next().children('span').text(0);

                        var auth = parseInt($(this).attr('conversation-auth'));
                        if (auth == 2) {
                            $.ajax({
                                type: "POST",
                                url: "/check-all",
                                data: {conversation_id: conversation_id, '_token': csrf},
                                success: function (data) {

                                    var count = parseInt($('#messagecount').html());
                                    if (count > 0) {
                                        $('#messagecount').html(count - 1);
                                        $('#messagecountTop').html(count - 1);
                                    }

                                    if (count < 2) {
                                        if (!$('#messagecount').hasClass('hidden-xs-up')) {
                                            $('#messagecount').addClass('hidden-xs-up');
                                            $('#messagecountTop').addClass('hidden-xs-up');
                                        }
                                    }

                                },
                                error: function (data) {
                                },
                                complete: function () {

                                }
                            });
                        }
                    }
                }
            });


            $('#chat-app-scroll1').children().first().children().each(function (index, value) {


                if (parseInt($(this).attr('conversation-id')) == conversation_id) {
                    if (!$(this).children('ul').children('li').next().next().children('span').hasClass('hidden-xs-up')) {
                        $(this).children('ul').children('li').next().next().children('span').addClass('hidden-xs-up');
                        $(this).children('ul').children('li').next().next().children('span').text(0);

                    }
                }

            });
        }

        jQuery($(this).next().children().next().children().first()).scrollTop(1500000);
        jQuery($(this).next().children().next().children().first()).scroll(function (event) {
            var scroll = $(this).scrollTop();
            if (scroll == 0) {
                var box = $(this);
                try {
                    var id = $(this).attr('id').split("_")[1];
                }
                catch (e) {

                }
                var load = $(this).attr('data-load');
                var conversation = '#conversation_' + id;
                var islimit = $(conversation).find('.chat-content').attr('data-limit');
                if (islimit == "0" && isScrollIn == 0 && id) {
                    $.ajax({
                        type: "POST",
                        url: "/load_prev",
                        data: {'id': id, '_token': csrf, 'load': load},

                        beforeSend: function () {
                            isScrollIn = 1;
                            var template = '<div class=" w-100 text-xs-center "> ' +
                                '<i class="text-xs-center fa fa-circle-o-notch fa-2x fa-spin fa-fw"></i>' +
                                ' </div> ';

                            $(conversation).find('.chat-content').prepend(template)

                        },

                        success: function (data) {

                            if (data.length > 0) {

                                data.reverse();

                                $(conversation).find('.chat-content').children().first().remove();
                                var tag = $(conversation).find('.chat-content').children().first().text();
                                $(conversation).find('.chat-content').children().first().remove();


                                $.each(data, function (key, val) {


                                    if (tag != val.time_tag) {
                                        $(conversation).find('.chat-content').prepend(messageTemplate(val, val.me, tag, true));
                                        tag = val.time_tag;

                                    }
                                    else {
                                        $(conversation).find('.chat-content').prepend(messageTemplate(val, val.me));

                                    }

                                });

                                var template = '<div class="w-100  text-xs-center ">' +
                                    '<small class="tag tag-default text-xs-center tag-pill">' + tag + '</small>' +
                                    '</div>';

                                $(conversation).find('.chat-content').prepend(template);

                                $(box).attr('data-load', (parseInt(load) + 1));
                            }
                            else {
                                $(conversation).find('.chat-content').children().first().remove();

                            }
                            if (data.length < 10) {

                                // $(conversation).find('.chat-content').children().first().remove();
                                $(conversation).find('.chat-content').attr('data-limit', '1')
                            }

                            $(box).scrollTop(10);
                            isScrollIn = 0;
                        }
                    });
                }


            }
        });

        $(this).children().last().find('span').html(0);
        $(this).children().last().find('span').addClass('hidden-xs-up');
        $(this).removeClass('shake-chunk');
        $(this).removeClass('shake-constant');
        $(this).removeClass('shake-constant--hover');


    });

    function autoset(top, left, chatbox) {

        var currentTop = top;
        var currentLeft = left;

        var windowHeight = $(window).height();
        var windowWidth = $(window).width();

        var nextTop = 0;
        var nextLeft = 0;
        var tempTop = currentTop;
        var tempLeft = currentLeft;

        if (currentTop < ( windowHeight / 2 )) {

            nextTop = 0;

        } else {

            nextTop = windowHeight - 60;
        }
        if (currentLeft < ( windowWidth / 2 )) {

            nextLeft = 0;

        } else {

            nextLeft = windowWidth - 60;
        }


        if (tempLeft < tempTop) {
            if (tempLeft < ( ( windowHeight ) - tempTop )) {

                nextTop = tempTop;
            } else {

                nextLeft = tempLeft;
            }
        } else {
            if (( ( windowWidth ) - tempLeft ) > tempTop) {

                nextLeft = tempLeft;
            } else if (( ( windowWidth ) - tempLeft ) > ( ( windowHeight ) - tempTop )) {

                nextLeft = tempLeft;
            } else {
                nextTop = tempTop;

            }
        }
        $(chatbox).animate({
            'left': nextLeft + 'px',
            'top': nextTop + 'px'
        });
        $(chatbox).next().animate({
            'left': nextLeft - 130 + 'px',
            'top': nextTop + 50 + 'px'
        });


        var chatdata = {
            X: nextLeft + 'px',
            Y: nextTop + 'px',
            id: $(chatbox).next().find('button').attr('conversation-data'),
            picture: $(chatbox).find('img').attr('src'),
            name: $(chatbox).next().find('button').next('a').text().trim(),
            href: $(chatbox).next().find('button').next('a').attr('href'),
            auth: $(chatbox).attr('data-auth'),

        };

        chatAjaxSession(chatdata, 1);

    }

    $('a[href="#people-app"]').on('show.bs.tab', function (e) {
        getOnline1();
    });

    $(document).on('keyup', '#filter', function (e) {


        var filter = $('#filter').val();

        $('#recent-chat').children().each(function (index, value) {
            var str = $(value).attr('data-filter');
            if ((str.toLowerCase()).indexOf((filter.toLowerCase())) >= 0) {
                $(value).fadeIn();
            }
            else {

                $(value).fadeOut();

            }
        });

        $('#online').children('form').each(function (index, value) {

            var str = $(value).find('.online_users').attr('data-filter');

            if ((str.toLowerCase()).indexOf((filter.toLowerCase())) >= 0) {
                $(value).fadeIn();
            }
            else {

                $(value).fadeOut();

            }
        });

        $('#feeds-data').children().each(function (index, value) {
            var str = $(value).attr('data-filter');
            if ((str.toLowerCase()).indexOf((filter.toLowerCase())) >= 0) {
                $(value).fadeIn();
            }
            else {

                $(value).fadeOut();

            }
        });


    });

    $(document).on('keyup', '#filter1', function (e) {

        var filter = $(this).val();

        $('#chat-app-scroll1').children().children().each(function (index, value) {
            var str = $(value).attr('data-filter');
            if ((str.toLowerCase()).indexOf((filter.toLowerCase())) >= 0) {
                $(value).fadeIn();
            }
            else {

                $(value).fadeOut();

            }
        });

        $('#online1').children('form').each(function (index, value) {
            var str = $(value).find('.online_users').attr('data-filter');

            if ((str.toLowerCase()).indexOf((filter.toLowerCase())) >= 0) {
                $(value).fadeIn();
            }
            else {

                $(value).fadeOut();

            }
        });


    });

    $(document).on('click', '.more', function () {

        $(this).text("less..").siblings(".complete").show();
        $(this).removeClass('more');
        $(this).addClass('less');
        $('.grid1').masonry();
    });

    $(document).on('click', '.less', function () {

        $(this).text("more..").siblings(".complete").hide();
        $(this).removeClass('less');
        $(this).addClass('more');
        $('.grid1').masonry();
    });

});

;(function (root, factory) {

    if (typeof define === 'function' && define.amd) {
        define(factory);
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        root.NProgress = factory();
    }

})(this, function () {
    var NProgress = {};

    NProgress.version = '0.2.0';

    var Settings = NProgress.settings = {
        minimum: 0.08,
        easing: 'linear',
        positionUsing: '',
        speed: 350,
        trickle: true,
        trickleSpeed: 250,
        showSpinner: true,
        barSelector: '[role="bar"]',
        spinnerSelector: '[role="spinner"]',
        parent: 'body',
        template: '<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'
    };

    /**
     * Updates configuration.
     *
     *     NProgress.configure({
   *       minimum: 0.1
   *     });
     */
    NProgress.configure = function (options) {
        var key, value;
        for (key in options) {
            value = options[key];
            if (value !== undefined && options.hasOwnProperty(key)) Settings[key] = value;
        }

        return this;
    };

    /**
     * Last number.
     */

    NProgress.status = null;

    /**
     * Sets the progress bar status, where `n` is a number from `0.0` to `1.0`.
     *
     *     NProgress.set(0.4);
     *     NProgress.set(1.0);
     */

    NProgress.set = function (n) {
        var started = NProgress.isStarted();

        n = clamp(n, Settings.minimum, 1);
        NProgress.status = (n === 1 ? null : n);

        var progress = NProgress.render(!started),
            bar = progress.querySelector(Settings.barSelector),
            speed = Settings.speed,
            ease = Settings.easing;

        progress.offsetWidth;
        /* Repaint */

        queue(function (next) {
            // Set positionUsing if it hasn't already been set
            if (Settings.positionUsing === '') Settings.positionUsing = NProgress.getPositioningCSS();

            // Add transition
            css(bar, barPositionCSS(n, speed, ease));

            if (n === 1) {
                // Fade out
                css(progress, {
                    transition: 'none',
                    opacity: 1
                });
                progress.offsetWidth;
                /* Repaint */

                setTimeout(function () {
                    css(progress, {
                        transition: 'all ' + speed + 'ms linear',
                        opacity: 0
                    });
                    setTimeout(function () {
                        NProgress.remove();
                        next();
                    }, speed);
                }, speed);
            } else {
                setTimeout(next, speed);
            }
        });

        return this;
    };

    NProgress.isStarted = function () {
        return typeof NProgress.status === 'number';
    };

    /**
     * Shows the progress bar.
     * This is the same as setting the status to 0%, except that it doesn't go backwards.
     *
     *     NProgress.start();
     *
     */
    NProgress.start = function () {
        if (!NProgress.status) NProgress.set(0);

        var work = function () {
            setTimeout(function () {
                if (!NProgress.status) return;
                NProgress.trickle();
                work();
            }, Settings.trickleSpeed);
        };

        if (Settings.trickle) work();

        return this;
    };

    /**
     * Hides the progress bar.
     * This is the *sort of* the same as setting the status to 100%, with the
     * difference being `done()` makes some placebo effect of some realistic motion.
     *
     *     NProgress.done();
     *
     * If `true` is passed, it will show the progress bar even if its hidden.
     *
     *     NProgress.done(true);
     */

    NProgress.done = function (force) {
        if (!force && !NProgress.status) return this;

        return NProgress.inc(0.3 + 0.5 * Math.random()).set(1);
    };

    /**
     * Increments by a random amount.
     */

    NProgress.inc = function (amount) {
        var n = NProgress.status;

        if (!n) {
            return NProgress.start();
        } else if (n > 1) {
            return;
        } else {
            if (typeof amount !== 'number') {
                if (n >= 0 && n < 0.25) {
                    // Start out between 3 - 6% increments
                    amount = (Math.random() * (5 - 3 + 1) + 3) / 100;
                } else if (n >= 0.25 && n < 0.65) {
                    // increment between 0 - 3%
                    amount = (Math.random() * 3) / 100;
                } else if (n >= 0.65 && n < 0.9) {
                    // increment between 0 - 2%
                    amount = (Math.random() * 2) / 100;
                } else if (n >= 0.9 && n < 0.99) {
                    // finally, increment it .5 %
                    amount = 0.005;
                } else {
                    // after 99%, don't increment:
                    amount = 0;
                }
            }

            n = clamp(n + amount, 0, 0.994);
            return NProgress.set(n);
        }
    };

    NProgress.trickle = function () {
        return NProgress.inc();
    };

    /**
     * Waits for all supplied jQuery promises and
     * increases the progress as the promises resolve.
     *
     * @param $promise jQUery Promise
     */
    (function () {
        var initial = 0, current = 0;

        NProgress.promise = function ($promise) {
            if (!$promise || $promise.state() === "resolved") {
                return this;
            }

            if (current === 0) {
                NProgress.start();
            }

            initial++;
            current++;

            $promise.always(function () {
                current--;
                if (current === 0) {
                    initial = 0;
                    NProgress.done();
                } else {
                    NProgress.set((initial - current) / initial);
                }
            });

            return this;
        };

    })();

    /**
     * (Internal) renders the progress bar markup based on the `template`
     * setting.
     */

    NProgress.render = function (fromStart) {
        if (NProgress.isRendered()) return document.getElementById('nprogress');

        addClass(document.documentElement, 'nprogress-busy');

        var progress = document.createElement('div');
        progress.id = 'nprogress';
        progress.innerHTML = Settings.template;

        var bar = progress.querySelector(Settings.barSelector),
            perc = fromStart ? '-100' : toBarPerc(NProgress.status || 0),
            parent = document.querySelector(Settings.parent),
            spinner;

        css(bar, {
            transition: 'all 0 linear',
            transform: 'translate3d(' + perc + '%,0,0)'
        });

        if (!Settings.showSpinner) {
            spinner = progress.querySelector(Settings.spinnerSelector);
            spinner && removeElement(spinner);
        }

        if (parent != document.body) {
            addClass(parent, 'nprogress-custom-parent');
        }

        parent.appendChild(progress);
        return progress;
    };

    /**
     * Removes the element. Opposite of render().
     */

    NProgress.remove = function () {
        removeClass(document.documentElement, 'nprogress-busy');
        removeClass(document.querySelector(Settings.parent), 'nprogress-custom-parent');
        var progress = document.getElementById('nprogress');
        progress && removeElement(progress);
    };

    /**
     * Checks if the progress bar is rendered.
     */

    NProgress.isRendered = function () {
        return !!document.getElementById('nprogress');
    };

    /**
     * Determine which positioning CSS rule to use.
     */

    NProgress.getPositioningCSS = function () {
        // Sniff on document.body.style
        var bodyStyle = document.body.style;

        // Sniff prefixes
        var vendorPrefix = ('WebkitTransform' in bodyStyle) ? 'Webkit' :
            ('MozTransform' in bodyStyle) ? 'Moz' :
                ('msTransform' in bodyStyle) ? 'ms' :
                    ('OTransform' in bodyStyle) ? 'O' : '';

        if (vendorPrefix + 'Perspective' in bodyStyle) {
            // Modern browsers with 3D support, e.g. Webkit, IE10
            return 'translate3d';
        } else if (vendorPrefix + 'Transform' in bodyStyle) {
            // Browsers without 3D support, e.g. IE9
            return 'translate';
        } else {
            // Browsers without translate() support, e.g. IE7-8
            return 'margin';
        }
    };

    /**
     * Helpers
     */

    function clamp(n, min, max) {
        if (n < min) return min;
        if (n > max) return max;
        return n;
    }

    /**
     * (Internal) converts a percentage (`0..1`) to a bar translateX
     * percentage (`-100%..0%`).
     */

    function toBarPerc(n) {
        return (-1 + n) * 100;
    }


    /**
     * (Internal) returns the correct CSS for changing the bar's
     * position given an n percentage, and speed and ease from Settings
     */

    function barPositionCSS(n, speed, ease) {
        var barCSS;

        if (Settings.positionUsing === 'translate3d') {
            barCSS = {transform: 'translate3d(' + toBarPerc(n) + '%,0,0)'};
        } else if (Settings.positionUsing === 'translate') {
            barCSS = {transform: 'translate(' + toBarPerc(n) + '%,0)'};
        } else {
            barCSS = {'margin-left': toBarPerc(n) + '%'};
        }

        barCSS.transition = 'all ' + speed + 'ms ' + ease;

        return barCSS;
    }

    /**
     * (Internal) Queues a function to be executed.
     */

    var queue = (function () {
        var pending = [];

        function next() {
            var fn = pending.shift();
            if (fn) {
                fn(next);
            }
        }

        return function (fn) {
            pending.push(fn);
            if (pending.length == 1) next();
        };
    })();

    /**
     * (Internal) Applies css properties to an element, similar to the jQuery
     * css method.
     *
     * While this helper does assist with vendor prefixed property names, it
     * does not perform any manipulation of values prior to setting styles.
     */

    var css = (function () {
        var cssPrefixes = ['Webkit', 'O', 'Moz', 'ms'],
            cssProps = {};

        function camelCase(string) {
            return string.replace(/^-ms-/, 'ms-').replace(/-([\da-z])/gi, function (match, letter) {
                return letter.toUpperCase();
            });
        }

        function getVendorProp(name) {
            var style = document.body.style;
            if (name in style) return name;

            var i = cssPrefixes.length,
                capName = name.charAt(0).toUpperCase() + name.slice(1),
                vendorName;
            while (i--) {
                vendorName = cssPrefixes[i] + capName;
                if (vendorName in style) return vendorName;
            }

            return name;
        }

        function getStyleProp(name) {
            name = camelCase(name);
            return cssProps[name] || (cssProps[name] = getVendorProp(name));
        }

        function applyCss(element, prop, value) {
            prop = getStyleProp(prop);
            element.style[prop] = value;
        }

        return function (element, properties) {
            var args = arguments,
                prop,
                value;

            if (args.length == 2) {
                for (prop in properties) {
                    value = properties[prop];
                    if (value !== undefined && properties.hasOwnProperty(prop)) applyCss(element, prop, value);
                }
            } else {
                applyCss(element, args[1], args[2]);
            }
        }
    })();

    /**
     * (Internal) Determines if an element or space separated list of class names contains a class name.
     */

    function hasClass(element, name) {
        var list = typeof element == 'string' ? element : classList(element);
        return list.indexOf(' ' + name + ' ') >= 0;
    }

    /**
     * (Internal) Adds a class to an element.
     */

    function addClass(element, name) {
        var oldList = classList(element),
            newList = oldList + name;

        if (hasClass(oldList, name)) return;

        // Trim the opening space.
        element.className = newList.substring(1);
    }

    /**
     * (Internal) Removes a class from an element.
     */

    function removeClass(element, name) {
        var oldList = classList(element),
            newList;

        if (!hasClass(element, name)) return;

        // Replace the class name.
        newList = oldList.replace(' ' + name + ' ', ' ');

        // Trim the opening and closing spaces.
        element.className = newList.substring(1, newList.length - 1);
    }

    /**
     * (Internal) Gets a space separated list of the class names on the element.
     * The list is wrapped with a single space on each end to facilitate finding
     * matches within the list.
     */

    function classList(element) {
        return (' ' + (element && element.className || '') + ' ').replace(/\s+/gi, ' ');
    }

    /**
     * (Internal) Removes an element from the DOM.
     */

    function removeElement(element) {
        element && element.parentNode && element.parentNode.removeChild(element);
    }

    return NProgress;
});

/*toggle */
+function (a) {
    "use strict";
    function b(b) {
        return this.each(function () {
            var d = a(this), e = d.data("bs.toggle"), f = "object" == typeof b && b;
            e || d.data("bs.toggle", e = new c(this, f)), "string" == typeof b && e[b] && e[b]()
        })
    }

    var c = function (b, c) {
        this.$element = a(b), this.options = a.extend({}, this.defaults(), c), this.render()
    };
    c.VERSION = "2.2.0", c.DEFAULTS = {
        on: "On",
        off: "Off",
        onstyle: "primary",
        offstyle: "default",
        size: "normal",
        style: "",
        width: null,
        height: null
    }, c.prototype.defaults = function () {
        return {
            on: this.$element.attr("data-on") || c.DEFAULTS.on,
            off: this.$element.attr("data-off") || c.DEFAULTS.off,
            onstyle: this.$element.attr("data-onstyle") || c.DEFAULTS.onstyle,
            offstyle: this.$element.attr("data-offstyle") || c.DEFAULTS.offstyle,
            size: this.$element.attr("data-size") || c.DEFAULTS.size,
            style: this.$element.attr("data-style") || c.DEFAULTS.style,
            width: this.$element.attr("data-width") || c.DEFAULTS.width,
            height: this.$element.attr("data-height") || c.DEFAULTS.height
        }
    }, c.prototype.render = function () {
        this._onstyle = "btn-" + this.options.onstyle, this._offstyle = "btn-" + this.options.offstyle;
        var b = "large" === this.options.size ? "btn-lg" : "small" === this.options.size ? "btn-sm" : "mini" === this.options.size ? "btn-xs" : "", c = a('<label class="btn">').html(this.options.on).addClass(this._onstyle + " " + b), d = a('<label class="btn">').html(this.options.off).addClass(this._offstyle + " " + b + " active"), e = a('<span class="toggle-handle btn btn-default">').addClass(b), f = a('<div class="toggle-group">').append(c, d, e), g = a('<div class="toggle btn" data-toggle="toggle">').addClass(this.$element.prop("checked") ? this._onstyle : this._offstyle + " off").addClass(b).addClass(this.options.style);
        this.$element.wrap(g), a.extend(this, {
            $toggle: this.$element.parent(),
            $toggleOn: c,
            $toggleOff: d,
            $toggleGroup: f
        }), this.$toggle.append(f);
        var h = this.options.width || Math.max(c.outerWidth(), d.outerWidth()) + e.outerWidth() / 2, i = this.options.height || Math.max(c.outerHeight(), d.outerHeight());
        c.addClass("toggle-on"), d.addClass("toggle-off"), this.$toggle.css({
            width: h,
            height: i
        }), this.options.height && (c.css("line-height", c.height() + "px"), d.css("line-height", d.height() + "px")), this.update(!0), this.trigger(!0)
    }, c.prototype.toggle = function () {
        this.$element.prop("checked") ? this.off() : this.on()
    }, c.prototype.on = function (a) {
        return this.$element.prop("disabled") ? !1 : (this.$toggle.removeClass(this._offstyle + " off").addClass(this._onstyle), this.$element.prop("checked", !0), void(a || this.trigger()))
    }, c.prototype.off = function (a) {
        return this.$element.prop("disabled") ? !1 : (this.$toggle.removeClass(this._onstyle).addClass(this._offstyle + " off"), this.$element.prop("checked", !1), void(a || this.trigger()))
    }, c.prototype.enable = function () {
        this.$toggle.removeAttr("disabled"), this.$element.prop("disabled", !1)
    }, c.prototype.disable = function () {
        this.$toggle.attr("disabled", "disabled"), this.$element.prop("disabled", !0)
    }, c.prototype.update = function (a) {
        this.$element.prop("disabled") ? this.disable() : this.enable(), this.$element.prop("checked") ? this.on(a) : this.off(a)
    }, c.prototype.trigger = function (b) {
        this.$element.off("change.bs.toggle"), b || this.$element.change(), this.$element.on("change.bs.toggle", a.proxy(function () {
            this.update()
        }, this))
    }, c.prototype.destroy = function () {
        this.$element.off("change.bs.toggle"), this.$toggleGroup.remove(), this.$element.removeData("bs.toggle"), this.$element.unwrap()
    };
    var d = a.fn.bootstrapToggle;
    a.fn.bootstrapToggle = b, a.fn.bootstrapToggle.Constructor = c, a.fn.toggle.noConflict = function () {
        return a.fn.bootstrapToggle = d, this
    }, a(function () {
        a("input[type=checkbox][data-toggle^=toggle]").bootstrapToggle()
    }), a(document).on("click.bs.toggle", "div[data-toggle^=toggle]", function (b) {
        var c = a(this).find("input[type=checkbox]");
        c.bootstrapToggle("toggle"), b.preventDefault()
    })
}(jQuery);

