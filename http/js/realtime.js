var messagelist = [];
var chatlist = [];
var chat_app_conversation_id;

var Rmessagelist = [];

var popup1 = [];

function getFeeds() {

    $.getJSON("/feeds", function (data) {

        var i = 0;
        $.each(data, function (key, val) {
            var ids = {id: 'button' + i, open: false};
            popup1[i] = (ids);
            if ((val.sourceid ).indexOf('@') >= 0)
                var template = '<a target="_blank" data-filter="' + val.message + '" id="button' + i + '" href="' + val.sourceid + '"  class="ba-0 px-0 py-0 square-corner list-group-item cursor-pointer' +
                    ' list-group-item-action">' +
                    feedsTemplate(val) +
                    '</a>';
            else
                var template = '<div data-filter="' + val.message + '" id="button' + i + '" data-popup="' + val.sourceid + '"  data-content="a" data-placement="left"  class="ba-0 px-0 py-0 square-corner list-group-item cursor-pointer feedsPopup' +
                    ' list-group-item-action">' +
                    feedsTemplate(val) +
                    '</div>';
            $("#feeds-data").prepend(template);
            i++;
        });

        $("#feed-tab").scrollbar();

    }).done(function () {

        $('.feedsPopup').popover({
            html: true,
        })

    });
}

function getFeeds_new() {
    $.getJSON("/feeds/new", function (data) {
        $.each(data, function (key, val) {


            var ids = {id: 'button' + popup1.length, open: false};
            popup1[popup1.length] = (ids);
            if ((val.sourceid ).indexOf('@') >= 0)
                var template = '<a target="_blank" data-filter="' + val.message + '" id="button' + popup1.length + '" href="' + val.sourceid + '"  class="ba-0 px-0 py-0 square-corner list-group-item cursor-pointer ' +
                    ' list-group-item-action">' +
                    feedsTemplate(val) +
                    '</a>';
            else
                var template = '<div data-filter="' + val.message + '" id="button' + popup1.length + '" data-popup="' + val.sourceid + '"  data-content="a" data-placement="left"  class="ba-0 px-0 py-0 square-corner list-group-item cursor-pointer feedsPopup' +
                    ' list-group-item-action">' +
                    feedsTemplate(val) +
                    '</div>';

            $("#feeds-data").prepend(template);
        });
    }).done(function () {

        $('.feedsPopup').popover({
            html: true,
        })

    });
}

function getNotification() {
    $.getJSON("/notification", function (data) {
        var i = 0;
        var notification = "";
        $.each(data, function (key, val) {
            if ((val.sourceid ).indexOf('@') >= 0)
                var template = '<a target="_blank" href="' + val.sourceid + '" class=" ba-0 px-1 my-1 py-0 square-corner list-group-item cursor-pointer ' +
                    '">' +
                    notificationTemplate(val, val.count) +
                    '</a>';
            else
                var template = '<div data-slide="' + val.sourceid + '" class=" notificationMakeSlide ba-0 px-1 my-1 py-0 square-corner list-group-item cursor-pointer ' +
                    '">' +
                    notificationTemplate(val, val.count) +
                    '</div>';
            notification += template;
            if (val.count == 1)
                i++;
        });
        $("#notifications").html(notification);
        if (i > 0) {
            if ($('#notificationcount').hasClass('hidden-xs-up')) {
                $('#notificationcount').removeClass('hidden-xs-up');
                $('#notificationTop').removeClass('hidden-xs-up');
            }
            $('#notificationcount').text(i);
            $('#notificationTop').text(i);
        }

        $('#notification-scroll').scrollbar();

    });
}

function getNotification_new() {
    $.getJSON("/notification/new", function (data) {
        var i = 0;
        var notification = "";
        $.each(data, function (key, val) {
            console.log(val);
            if ((val.sourceid ).indexOf('@') >= 0)
                var template = '<a target="_blank" href="' + val.sourceid + '" class=" ba-0 px-1 my-1 py-0 square-corner list-group-item cursor-pointer ' +
                    '">' +
                    notificationTemplate(val, 1) +
                    '</a>';
            else
                var template = '<div data-slide="' + val.sourceid + '" class=" notificationMakeSlide ba-0 px-1 my-1 py-0 square-corner list-group-item cursor-pointer ' +
                    '">' +
                    notificationTemplate(val, 1) +
                    '</div>';
            notification += template;
            i++;
            alertSound();
            Push.create(val.msg, {
                body: val.time,
                icon: val.user_picture,
                timeout: 8000,
                onClick: function () {
                    window.focus();
                    this.close();
                }
            });

            $("#notifications").prepend(notification);

            if (i > 0) {
                $('#notificationcount').removeClass('hidden-xs-up');
                $('#notificationTop').removeClass('hidden-xs-up');
                $('#notificationcount').text(parseInt($('#notificationcount').text()) + i);
                $('#notificationTop').text(parseInt($('#notificationTop').text()) + i);
            }

        });


    });
}

function getRecent_chat() {
    $("#recent-chat").html('');
    $.getJSON("/messages", function (data) {
        var count = 0;
        $.each(data[1], function (key, val) {
            if (val.count > 0) {

                Rmessagelist[val.conversation_id] = [];
                count++;
            }
            var template = '<div data-filter="' + val.name + '" class="online_users-recent cursor-pointer list-group-item bx-0 ba-0 p-0 square-corner list-group-item-action"' +
                'conversation-id="' + val.conversation_id + '" conversation-auth="' + val.auth + '" csrf-id="' + csrf + '" user-id="' + val.username + '">' +
                onlineTemplate1(val) +
                '</div>';
            $("#recent-chat").append(template);

        });
        if (count > 0) {

            $('#messagecount').html(count);
            $('#messagecountTop').html(count);

            if ($('#messagecount').hasClass('hidden-xs-up')) {
                $('#messagecount').removeClass('hidden-xs-up');
                $('#messagecountTop').removeClass('hidden-xs-up');
            }
        }


    }).done(function(){
        $('#recent-people').scrollbar();


    });
}

function getOnline() {
    $.getJSON("/online", function (data) {
        var filter = '';
        if ($('#filter').val()) {
            filter = $('#filter').val();
        }
        var onlinePeople = "";
        $.each(data[1], function (key, val) {
            var hide = '';
            if (((val.user_name.toLowerCase()).indexOf(filter.toLowerCase()) < 0) && filter.length > 0) {
                hide = "style='display: none;'";

            }

            var template = '<form method="POST" action="/conversation" accept-charset="UTF-8" ' + hide + '>' +
                '<input name="_token" type="hidden" value="' + csrf + '">' +
                '<input name="user_id" type="hidden" value="' + val.id + '">' +
                '<input name="success" type="hidden" value="chat">' +
                '<div data-filter="' + val.user_name + '" class="online_users cursor-pointer list-group-item bx-0 ba-0 p-0 square-corner list-group-item-action" >' +
                onlineTemplate(val) +
                '</div>' +
                '</form>';
            onlinePeople += template;

        });
        $("#online").html(onlinePeople);
        $('#people').scrollbar();


    });

}

function getOnline1() {
    $.getJSON("/online", function (data) {
        var filter = '';
        if ($('#filter1').val()) {
            filter = $('#filter1').val();
        }
        var onlinePeople = "";
        $.each(data[1], function (key, val) {

            var hide = '';
            if (((val.user_name.toLowerCase()).indexOf((filter.toLowerCase())) < 0) && filter.length > 0) {
                hide = "style='display: none;'";

            }

            var template = '<form method="POST" action="/conversation" accept-charset="UTF-8" ' + hide + '>' +
                '<input name="_token" type="hidden" value="' + csrf + '">' +
                '<input name="user_id" type="hidden" value="' + val.id + '">' +
                '<input name="success" type="hidden" value="chat-app">' +
                '<div data-filter="' + val.user_name + '" class="online_users cursor-pointer list-group-item bx-0 ba-0 p-0 square-corner list-group-item-action" >' +
                onlineTemplate(val) +
                '</div>' +
                '</form>';
            onlinePeople += template;

        });

        $("#online1").html(onlinePeople);
        $('#filter1').keyup();
        $('#people2').scrollbar();


    });

}

function getSessionChat() {
    $.getJSON("/chatSession", function (data) {
        $.each(data, function (key, val) {
            chatlist.push(parseInt(val.id));
            Rmessagelist[parseInt(val.id)] = [];
            var chat = [];
            chat["csrf"] = csrf;
            chat["id"] = val.id;
            chat["user_name"] = val.name;
            chat["user_picture"] = val.picture;
            chat["username"] = '#';
            chat["auth"] = val.auth;
            if (screen_size > 989) {
                $("#chatmanager").append(chatTemplate(chat, false, val));
                $(".awesome-chat").draggable({
                    scroll: false,
                });

                var id = "#conversation_" + chat["id"];
                chatlist.push(chat["id"]);
                Rmessagelist[chat["id"]] = [];

                $(id).scrollbar();


                if ($("input.chatBox1:text").next().hasClass('emojionearea')) {

                    $("input.chatBox1:text").unbind();

                }

                $(".chatBox1").emojioneArea({
                    pickerPosition: "top",
                    autocomplete: false,
                    inline: true,
                });

                $("input.chatBox1:text").each(function (i, obj) {

                    if ($(this).next().next().hasClass('emojionearea')) {
                        $(this).next().remove();
                        $(this).next().children().first().html('');
                        $(this).next().children().first().attr('placeholder','Message');

                    }

                });



            }

        });

    });

}

function getMessages() {
    $.getJSON("/message", function (data) {
        $.each(data, function (key, val) {
            $.each(val, function (key1, val1) {
                alertSound();
                val1.conversation_id = parseInt(val1.conversation_id);
                if (jQuery.inArray(val1.conversation_id, chatlist) == -1 && chat_app_conversation_id != val1.conversation_id) {
                    chatlist.push(val1.conversation_id);
                    Rmessagelist[val1.conversation_id] = [];
                    var chat = [];
                    chat["user_picture"] = val1.user_picture;
                    chat["user_name"] = val1.user_name;
                    chat["username"] = val1.username;
                    chat["csrf"] = csrf;
                    chat["id"] = val1.conversation_id;
                    chat["auth"] = val1.auth;
                    if (val1.id != -1)
                        Rmessagelist[val1.conversation_id].push(val1.id);
                    if (screen_size > 989) {
                        $("#chatmanager").append(chatTemplate(chat));
                        var conversation = '#conversation_' + val1.conversation_id;
                        if (!$(conversation).closest('.float-chat').is(":visible")) {
                            var count = parseInt($(conversation).closest('.float-chat').prev().children().last().find('span').html());
                            $(conversation).closest('.float-chat').prev().children().last().find('span').html((count + 1));
                            $(conversation).closest('.float-chat').prev().children().last().find('span').removeClass('hidden-xs-up');
                            $(conversation).closest('.float-chat').prev().addClass('shake-chunk');
                            $(conversation).closest('.float-chat').prev().addClass('shake-constant');
                            $(conversation).closest('.float-chat').prev().addClass('shake-constant--hover');

                            Push.create(val1.message, {
                                body: val1.time,
                                icon: $(conversation).closest('.float-chat').prev().find('img').attr('src'),
                                timeout: 8000,
                                onClick: function () {
                                    window.focus();
                                    this.close();
                                }
                            });

                        }

                        $(".awesome-chat").draggable({
                            scroll: false,
                        });
                        var id = "#conversation_" + chat["id"];
                        $(id).scrollbar();

                        if ($("input.chatBox1:text").next().hasClass('emojionearea')) {

                            $("input.chatBox1:text").unbind();

                        }

                        $(".chatBox1").emojioneArea({
                            pickerPosition: "top",
                            autocomplete: false,
                            inline: true,
                        });

                        $("input.chatBox1:text").each(function (i, obj) {

                            if ($(this).next().next().hasClass('emojionearea')) {
                                $(this).next().remove();
                                $(this).next().children().first().html('');
                                $(this).next().children().first().attr('placeholder','Message');

                            }

                        });

                    }

                }
                else {
                    if (screen_size > 989 || val1.conversation_id == chat_app_conversation_id) {
                        var conversation = '#conversation_' + val1.conversation_id;
                        if (val1.id != -1) {
                            Rmessagelist[val1.conversation_id].push(val1.id);

                            if (!$(conversation).closest('.float-chat').is(":visible")) {
                                var count = parseInt($(conversation).closest('.float-chat').prev().children().last().find('span').html());
                                $(conversation).closest('.float-chat').prev().children().last().find('span').html((count + 1));
                                $(conversation).closest('.float-chat').prev().children().last().find('span').removeClass('hidden-xs-up');
                                $(conversation).closest('.float-chat').prev().addClass('shake-chunk');
                                $(conversation).closest('.float-chat').prev().addClass('shake-constant');
                                $(conversation).closest('.float-chat').prev().addClass('shake-constant--hover');

                                if (val1.conversation_id != chat_app_conversation_id) {
                                    Push.create(val1.message, {
                                        body: val1.time,
                                        icon: $(conversation).closest('.float-chat').prev().find('img').attr('src'),
                                        timeout: 8000,
                                        onClick: function () {
                                            window.focus();
                                            this.close();
                                        }
                                    });
                                }
                            }

                            $(conversation).find('.chat-content').append(messageTemplate(val1, 1));
                            if (val1.conversation_id == chat_app_conversation_id)
                                jQuery('#chat-app-scroll').scrollTop(1500000);

                            jQuery(conversation).scrollTop(1500000);

                        }

                    }
                }


                SyncallMessage(val1);

            });
        });

    });
}

function SyncallMessage(val1, active) {


    var count = 0;
    var done = 0;
    $('.message_app_users').each(function (i, obj) {

        if (val1.conversation_id == parseInt($(obj).attr('conversation-id'))) {
            count = $(obj).children().children('li').last().find('.tag-primary').text();
            $(obj).remove();
        }

    });

    var conversation = '#conversation_' + val1.conversation_id;

    var countTemplate = '';
    if (val1.conversation_id != chat_app_conversation_id && !($(conversation).closest('.float-chat').is(":visible"))) {

        countTemplate = '<span class="tag tag-primary tag-pill"> ' + ++count + ' </span>';

        var count1 = 0;

        $.each(Rmessagelist, function (key, val) {

            if (val)
                count1++;

        });

        $('#messagecount').html(count1);
        $('#messagecountTop').html(count1);


        if (count1 > 0) {
            if ($('#messagecount').hasClass('hidden-xs-up')) {
                $('#messagecount').removeClass('hidden-xs-up');
                $('#messagecountTop').removeClass('hidden-xs-up');
            }
        }

    }

    var template = '<div  class="message_app_users cursor-pointer list-group-item p-0 bb-1 border-light square-corner list-group-item-action" ' +
        'conversation-id="' + val1.conversation_id + '"  data-filter="' + val1.user_name + '"  conversation-auth="' + val1.auth + '"' +
        'user-id="' + val1.username + '"> ' +
        '<ul class="nav  nav-inline p-0_5 pr-0"> ' +
        '<li class="nav-item align-top "> ' +
        '<img class="rounded-circle pp-40" src="' + val1.user_picture + '" ' +
        'alt="' + val1.user_name + '">' +
        ' </li>' +
        ' <li class="nav-item mx-0 online_width-s">' +
        ' <div class="lh-1 pl-0_5 "> ' +
        '<p class="m-0 p-0 comment_link ">' + anonymousTagTemplate(val1.user_name) + '</p> ' +
        '<small class="text-muted opensanN">' + smilies(TextLimit(val1.message, 15)) + '</small> ' +
        '</div> ' +
        '</li> ' +
        '<li class="nav-item float-xs-right pl-0 pr-0">' +
        countTemplate +
        '<small class="text-muted opensanL"> ' + val1.time + '</small> ' +
        '</li> ' +
        '</ul> ' +
        '</div> ';
    $('#chat-app-scroll1').find('.list-group').prepend(template);

    count = 0;



    $('.online_users-recent').each(function (i, obj) {

        if (val1.conversation_id == parseInt($(obj).attr('conversation-id'))) {
            count = $(obj).children().children('li').last().find('.tag-primary').text();
            $(obj).remove();
        }
    });


    countTemplate = '';
    if (val1.conversation_id != chat_app_conversation_id && !($(conversation).closest('.float-chat').is(":visible"))) {

        countTemplate = '<span class="tag tag-primary tag-pill"> ' + ++count + ' </span>';

    }


    template = '<div  class="online_users-recent cursor-pointer list-group-item bx-0 ba-0 p-0 square-corner list-group-item-action" ' +
        'conversation-id="' + val1.conversation_id + '"  data-filter="' + val1.user_name + '"  conversation-auth="' + val1.auth + '"' +
        'user-id="' + val1.username + '"  csrf-id="' + csrf + '"> ' +
        '<ul class="nav  nav-inline p-0_5 pr-0"> ' +
        '<li class="nav-item align-top "> ' +
        '<img class="rounded-circle pp-40" src="' + val1.user_picture + '" ' +
        'alt="' + val1.user_name + '">' +
        ' </li>' +
        ' <li class="nav-item mx-0 online_width-s">' +
        ' <div class="lh-1 pl-0_5 "> ' +
        '<p class="m-0 p-0 comment_link ">' + anonymousTagTemplate(val1.user_name) + '</p> ' +
        '<small class="text-muted opensanN">' + smilies(TextLimit(val1.message, 15)) + '</small> ' +
        '</div> ' +
        '</li> ' +
        '<li class="nav-item float-xs-right pl-0 ">' +
        countTemplate +
        '<small class="text-muted opensanL">' + val1.time + '</small> ' +
        '</li> ' +
        '</ul> ' +
        '</div> ';
    $('#recent-chat').prepend(template);


}

function checkMessages() {
    $.each(messagelist, function (key, val) {

        $.getJSON("/check-message/" + val, function (data) {
            var id = '#message-' + val;
            var detail1 = $(id).find('.detailmsg').parent();

            $(detail1).removeClass('chat-messageS');
            $(detail1).removeClass('chat-messageD');
            $(detail1).removeClass('chat-messageR');
            $(detail1).addClass('chat-message'+ data.status);
            var detail = $(id).find('.detailmsg').text();
            var new_detail = detail.substring(0, detail.length - 1) + data.status;
            $(id).find('.detailmsg').text(new_detail);
            if (data.status == 'R') {

                var i = messagelist.indexOf(parseInt(val));
                if (i != -1) {
                    messagelist.splice(i, 1);
                }

            }
        });

    });
}

function wait(ms) {
    var start = new Date().getTime();
    var end = start;
    while (end < start + ms) {
        end = new Date().getTime();
    }
}

function chatTyping(conversation_id) {


    try {

        var beforeText = '';
        if (chat_app_conversation_id == conversation_id) {
            var id = '#conversation_' + conversation_id;

            beforeText = $(id).prev().children().next().next().children().next().first().html();
            $(id).prev().children().next().next().children().next().first().html('Typing...');

            setTimeout(function () {
                $(id).prev().children().next().next().children().next().first().html('Online');

            }, 3000);
        }
        else {
            var id = '#conversation_' + conversation_id;
            beforeText = $(id).parent().prev().children().next().next().first().html();

            $(id).parent().prev().children().next().next().first().html('Typing...');

            setTimeout(function () {
                $(id).parent().prev().children().next().next().first().html('Online');

            }, 3000);
        }


    }
    catch (e) {
    }
}

function chatPersons() {
    $.each(chatlist, function (key, val) {
        var data1 = '';
        $.get("/chat-person/" + val, function (data) {
            data1 = data;
            var id = '#conversation_' + val;
            if(data1 == "Online"){
                $(id).parent().parent('.float-chat').children().first().children().next().next().first().removeClass('tag-warning');
                $(id).parent().parent('.float-chat').children().first().children().next().next().first().addClass('tag-success');
            }

            else{
                $(id).parent().parent('.float-chat').children().first().children().next().next().first().removeClass('tag-success');
                $(id).parent().parent('.float-chat').children().first().children().next().next().first().addClass('tag-warning');
            }
            $(id).parent().parent('.float-chat').children().first().children().next().next().first().html(data1);
        });
    });


    if (chat_app_conversation_id) {

        var data1 = '';
        $.get("/chat-person/" + chat_app_conversation_id, function (data) {
            data1 = data;
            var id = '#chats-content';

            $(id).children().children('a').last().children('small').html(data1);
        });

    }
}

function checkfacematch() {

    $.getJSON("/facematch", function (data) {


        var i = 0;
        $.each(data, function (key, val) {

            if (i == 0) {
                $('#F_user_1_form').children('input').first().next().attr('value', val.facematch_id);
                $('#F_user_1_form').children('input').first().next().next().attr('value', val.id);
                $('#facematchModal').modal('show');
                var follower = val.followers;
                var following = val.following;

                $('#F_user_1_college').text(val.college_name_id + " - " + val.branch_id + " - " + val.college_year);
                $('#F_user_1_name').html('<a href="' + val.username + '">' + val.name + '</a>');

                $('#F_user_1_detail').text('Followers ' + follower + ' . Following ' + following);

                $('#F_user_1_photo').attr('src', val.profile_picture_big);

            }
            else {

                $('#F_user_2_form').children('input').first().next().attr('value', val.facematch_id);
                $('#F_user_2_form').children('input').first().next().next().attr('value', val.id);

                var follower = val.followers;
                var following = val.following;

                $('#F_user_2_college').text(val.college_name_id + " - " + val.branch_id + " - " + val.college_year);
                $('#F_user_2_name').html('<a href="' + val.username + '">' + val.name + '</a>');
                $('#F_user_2_detail').text('Followers ' + follower + ' . Following ' + following);
                $('#F_user_2_photo').attr('src', val.profile_picture_big);

            }
            i++;
        });
    });
}

function alertSound() {
    if (screen_size > 989) {
        var audio = new Audio('/audio/alert.mp3');
        audio.play();
    }
}

// setInterval(checkMessages, 3000);
// setInterval(getMessages, 5000);

getNotification();
checkfacematch();
getOnline();
setInterval(getOnline, 30000);

// getRecent_chat();


if (screen_size > 989) {

    // getSessionChat();
    // setInterval(chatPersons, 30000);
    getFeeds();
    // chatPersons();

}


// function changeTitle() {
//     var title = $(document).prop('title');
//     if (title.indexOf('>>>') == -1) {
//         setTimeout(changeTitle, 3000);
//         $(document).prop('title', '>'+title);
//     }
// }
//
// changeTitle();
// Pusher.logToConsole = true;

var pusher = new Pusher('9dbdbd3b88e6b4a2c2f0', {
    encrypted: true
});
var channel = pusher.subscribe('fabits');
channel.bind('universal', function (data) {
    getFeeds_new();
    getNotification_new();
});

channel.bind('posts', function (data) {
    if (!itsme) {
        if ($("#new_posts").hasClass("hidden-xs-up"))
            $("#new_posts").removeClass("hidden-xs-up");
        if ($("#homecountTop").hasClass("hidden-xs-up"))
            $("#homecountTop").removeClass("hidden-xs-up");
        if ($("#homecount").hasClass("hidden-xs-up"))
            $("#homecount").removeClass("hidden-xs-up");
        var count = (parseInt($('#new_posts').html()) + 1);
        $('#new_posts').html(count + " new posts");
        $('#homecountTop').html(count);
        $('#homecount').html(count);
    }
});

// var channel1 = pusher.subscribe('fabits' + $('meta[name="user_id"]').attr('content') + "chatting");
// channel1.bind('chat', function (data) {
//     getMessages();
// });
// channel1.bind('seen', function (data) {
//     checkMessages();
// });
// channel1.bind('typing', function (data) {
//
//     chatTyping(data.conversationID);
// });

var channel2 = pusher.subscribe('fabits' + $('meta[name="user_id"]').attr('content') + "notify");
channel2.bind('notify', function (data) {
    getNotification_new();


});




