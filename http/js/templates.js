/**
 * Created by pi on 12/28/16.
 */


var user = [];
user['user_name'] = $('#userinfo').text().trim();
user['user_picture'] = $('#userinfo').find('img').attr('src');
user['comment_time'] = '1 s ago';
user['username'] = $('#userinfo').attr('href');
user['user_id'] = $('#userinfo').attr('data-id');
var csrf = $('meta[name="csrf-token"]').attr('content');
var screen_size = $(window).width();
var itsme = 0;
var isScrollIn = 0;

function recomendedPeopleTemplate(data, isactive) {

    var active = '';
    if (isactive)
        active = "active";

    var follow = '<button type="submit" class="nav-link btn btn-primary m-1"> ' +
        '<i class="fa fa-user-plus  " aria-hidden="true"></i> Follow ' +
        '</button> ';
    if (data.isfollow) {

        follow = '<button type="submit" class="nav-link btn btn-secondary unfollow m-1"> ' +
            '<i class="fa fa-user-plus  " aria-hidden="true"></i> Following ' +
            '</button> ';

    }
    var template = '<div  class="carousel-item ' + active + ' row px-0_5 my-1"> ' +
        '<ul class="nav  text-xs-center"> ' +
        '<li class="nav-item align-top"> ' +
        '<a class="" href="/@' + data.username + '" data-loc="page"> ' +
        '<img class="rounded-circle pp-100" src="' + data.image + '" alt="' + data.name + '"> ' +
        '</a>' +
        '</li> ' +
        '<li class="nav-item mx-0 "> ' +
        '<div class="lh-1 pl-0_5 "> ' +
        '<p class="p-0 m-0 post_user_link opensanN"> ' +
        '<a class="" href="/@' + data.username + '" data-loc="page">' + data.name + '</a></p> ' +
        '<small class="text-muted">@' + data.username + '</small> <br> ' +
        '<small class="text-muted"> Followers ' + data.followers + ' . Profileviews ' + data.profileviews + ' </small> ' +
        '<br><small class="text-muted"><b>' + data.intro + '</b></small>' +
        '</div> </li> ' +
        '<li class="nav-item px-0 mx-0 "> ' +
        '<form method="POST" action="/follow" accept-charset="UTF-8" class="d-inline form-inline">' +
        '<input name="_token" type="hidden" value="' + csrf + '">' +
        ' <input name="success" type="hidden" value="follow"> ' +
        '<input name="user_id" type="hidden" value="' + data.id + '"> ' +
        follow +
        '</form> ' +
        '</li>' +
        ' </ul> ' +
        '</div>';
    return template;

}

function topPeopleTemplate(data) {

    // var follow = '<button type="submit" class="nav-link btn btn-primary btn-sm"> ' +
    //     '<i class="fa fa-user-plus  " aria-hidden="true"></i> Follow ' +
    //     '</button> ';
    // if (data.isfollow) {
    //
    //     follow = '<button type="submit" class="nav-link btn btn-secondary unfollow btn-sm"> ' +
    //         '<i class="fa fa-user-plus  " aria-hidden="true"></i> Following ' +
    //         '</button> ';
    //
    // }


    var template = '<ul class="nav  nav-inline pb-1">' +
        '<li class="nav-item align-top">' +
        '<a class="" data-loc="page" href="/@' + data.username + '">' +
        '<img class="rounded-circle pp-50" src="' + data.image + '" alt="' + data.name + '">' +
        '</a>' +
        '</li>' +
        '<li class="nav-item mx-0 ">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="p-0 m-0 post_user_link opensanN">' +
        '<a class="" data-loc="page" href="/@' + data.username + '">' + data.name + '  <span class="text-muted">(@' + data.username + ')</span>'+
        '</a></p>' +
        '<small class="text-muted">Followers ' + data.followers + '</small>' +
        '</div>' +
        '</li>' +
        // '<li class="nav-item px-0 mx-0 float-xs-right">' +
        // '<form method="POST" action="/follow" accept-charset="UTF-8" class="d-inline form-inline">' +
        // '<input name="_token" type="hidden" value="' + csrf + '">' +
        // ' <input name="success" type="hidden" value="follow"> ' +
        // '<input name="user_id" type="hidden" value="' + data.id + '"> ' +
        // follow +
        // '</form> ' +
        // '</li>' +
        '</ul>';

    return template;

}

function topHashtag(data) {

    var template = '<ul class="nav  nav-inline pb-1">' +

        '<li class="nav-item mx-0 ">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="p-0 m-0 lead opensanN">' +
        '<a class="" data-loc="page" href="/search/!' + data.tag + '">' + data.tag +
        '</a></p>' +
        '</div>' +
        '</li>' +
        '<li class="nav-item float-xs-right">' +
        '<small class="text-muted"> ' + data.total + ' Post</small>' +
        '</li>' +
        '</ul>';

    return template;

}

function alertTemplate(data) {

    var template = '<div class="alert alert-warning alert-dismissible fade in mb-0 sd-1" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' + data + '</div>';

    return template;
}

function image_uploadTemplate(data) {


    var template = '<li>' +
        '<button type="button" class="close remove_upload" style="position: relative;top: 24px;" data-id="' + data.image_id + '" csrf-id="' + csrf + '">' +
        ' <span aria-hidden="true">×</span>' +
        ' </button>' +
        ' <img class="pp-100" src="' + data.image + '" alt="image"></li>';
    return template;
}

function search_result(data) {
    var template = '<div class="container-fluid bb-1 border-light "> ' +
        '<div class="row px-0_5 my-1 "> ' +
        '<ul class="nav  nav-inline text-xs-left"> ' +
        '<li class="nav-item align-top"> ' +
        '<a  href="/@' + data.username + '" data-loc="page-menu"> ' +
        '<img class="rounded-circle pp-60" src="' + data.image + '" ' +
        'alt="' + data.name + '"> </a>' +
        '</li> ' +
        '<li class="nav-item mx-0 ">' +
        ' <div class="lh-1 pl-0_5 "> ' +
        '<p class="p-0 m-0 post_user_link opensanN">' +
        '<a href="/@' + data.username + '" data-loc="page-menu">' + data.name + '</a></p> ' +
        '<small class="text-muted"> @' + data.username + '</small><br>' +
        ' <small class="text-muted"> Followers ' + data.followers + ' . Following ' + data.following + ' </small> ' +
        '</div> </li> <li class="nav-item px-0 mx-0 float-xs-right hidden-md-down"> ' +
        ' </li> ' +
        '</ul> ' +
        '</div> ' +
        '</div>';
    return template;
}

function people_result(data, i) {
    var selected = "";
    if (i == 1) {
        selected = "selected";
    }

    var template = '<li class="p-0_5 bb-1 border-light ' + selected + '"> ' +
        '<img class="rounded-circle pp-30" src="' + data.image + '" ' +
        'alt="' + data.name + '">' +
        '<span class="pl-0_5 m-0 post_user_link opensanN">' +
        data.name + '</span><small> @' + data.username + '</small>' +
        '</li>';
    return template;
}

function tag_result(data) {
    var template = '<div class="container-fluid bb-1 border-light "> ' +
        '<div class="row px-0_5 my-1 "> ' +
        '<ul class="nav  nav-inline text-xs-center text-md-left"> ' +
        '<li class="nav-item mx-0 ">' +
        ' <div class="lh-1 pl-0_5 "> ' +
        '<p class="p-0 m-0  opensanN">' +
        '<a href="/search/!' + data.tag + '" data-loc="page-menu">' + data.tag + '</a></p> ' +
        '<small class="text-muted"> ' + data.total + ' Posts</small><br>' +
        '</div> </li>' +
        '</ul> ' +
        '</div> ' +
        '</div>';
    return template;
}

function messageTemplate(data, type, tag, prev) {
    var template = '';
    var me = '';
    // prev = prev || false;
    if (type == 0)
        me = 'me';
    if (!prev) {
        if (tag) {

            template += '<div class="w-100  text-xs-center ">' +
                '<small class="tag tag-default text-xs-center tag-pill">' + tag + '</small>' +
                '</div>';
        }
        template += '<div id="message-' + data.id + '" class="chat-msg-box ' + me + '"> ' +
            '<div class="chat-message' + data.status + ' breakit ' + me + '"> ' + postTextTemplate(data.message) +
            '<div class="detailmsg ' + me + '">' + data.time + ' ' + data.status + '</div>' +
            ' </div> ' +
            '</div>';
    }
    else {

        template += '<div id="message-' + data.id + '" class="chat-msg-box ' + me + '"> ' +
            '<div class="chat-message' + data.status + ' breakit ' + me + '"> ' + smilies(data.message) +
            '<div class="detailmsg ' + me + '">' + data.time + ' ' + data.status + '</div>' +
            ' </div> ' +
            '</div>';

        if (tag) {

            template += '<div class="w-100  text-xs-center ">' +
                '<small class="tag tag-default text-xs-center tag-pill">' + tag + '</small>' +
                '</div>';
        }

    }
    return template;
}

function chatAjaxSession(chatdata, update) {

    var x = chatdata.X;
    var y = chatdata.Y;
    var id = parseInt(chatdata.id);
    var picture = chatdata.picture;
    var name = chatdata.name;
    var href = chatdata.href;
    var auth = chatdata.auth;


    $.ajax({
        type: "POST",
        url: "/chatSession",
        data: {
            'X': x,
            'Y': y,
            'id': id,
            'picture': picture,
            'name': name,
            'href': href,
            'auth': auth,
            'update': update,
            '_token': csrf
        },

        success: function (data) {

        },
        error: function (data) {
        },
        complete: function () {

        }
    });

}

function getRandomArbitrary(min, max) {
    return Math.random() * (max - min) + min;
}

function chatTemplate(data, isOpen, val) {

    isOpen = isOpen || false;
    var hidden = 'display:none';

    if (isOpen) {

        hidden = '';
    }
    var style = '';
    if (val) {
        style = "style='top:" + val.Y + "; left:" + val.X + ";'";

    }
    else {
        var loc = getRandomArbitrary(30, 70);
        style = "style='top:90%; left:" + loc + "%;'";
        var chatdata = {
            X: loc + '%',
            Y: '90%',
            id: data.id,
            picture: data.user_picture,
            name: data.user_name,
            href: data.username,
            auth: data.auth,
        };
        chatAjaxSession(chatdata, 0);
    }


    var messageauth = "";
    var messageauthallow = "";

    if (data.auth == 1) {

        messageauth = "hidden-xs-up";
        messageauthallow = "<div class='w-100 auth'>" +
            "<form method='POST' action='/block_message' accept-charset='UTF-8' class ='d-inline form-inline' >" +
            "<input name='_token' type='hidden' value='" + csrf + "'>" +
            "<input name='success' type='hidden' value='chat_block'>" +
            "<input name='id' type='hidden' value='" + data.id + "'>" +
            "<button type='submit' class='btn btn-secondary mx-0_5 mt-3 px-3'>Block</button>" +
            "</form>" +
            "<form method='POST' action='/allow_message' accept-charset='UTF-8' class ='d-inline form-inline' >" +
            "<input name='_token' type='hidden' value='" + csrf + "'>" +
            "<input name='success' type='hidden' value='chat_allow'>" +
            "<input name='id' type='hidden' value='" + data.id + "'>" +
            "<button type='submit' class='btn btn-primary px-3 mt-3'>Allow</button>" +
            "</form>" +
            "</div>";


    }

    var name = anonymousTagTemplate(data.user_name);


    if (name.indexOf('Anonymous') != -1) {

        var name = name.substring(0, name.indexOf('<span'));
        name = name.split(" ")[0];

        name += '<span class="tag tag-danger tag-pill">Anonymous</span>';
    }
    else {
        name = name.split(" ")[0];
    }


    var template = '<div class="awesome-chat cursor-pointer  " data-auth="' + data.auth + '" ' + style + '>' +
        '<img  class=" pp-60 rounded-circle sd-3" src="' + data.user_picture + '" ' +
        'alt="' + data.user_name + '"  >' +
        '<div class="awesome-chat-count"><span class="tag tag-danger tag-pill sd-1 hidden-xs-up">0</span></div>' +
        '</div>' +
        '<div  class="float-chat" style=" ' + hidden + '"> ' +
        '<div class="list-group-item chat-header bl-0  br-0 bt-0 p-c1"> ' +
        '<button type="button" class="close chat-close float-xs-right" conversation-data="' + data.id + '" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span> ' +
        '</button>' +
        '<a href="/@' + data.username + '" data-loc="page">' + name + '</a> ' +
        '<span class="tag tag-warning tag-pill"></span>' +
        '<div class="nav-item dropdown float-xs-right pr-1">' +
        "<form method='POST' action='/block_message' accept-charset='UTF-8' class ='d-inline form-inline' >" +
        "<input name='_token' type='hidden' value='" + csrf + "'>" +
        "<input name='success' type='hidden' value='chat_block'>" +
        "<input name='id' type='hidden' value='" + data.id + "'>" +
        '<a class="nav-link fa fa-ellipsis-v text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>' +
        '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="responsiveNavbarDropdown">' +
        '<a class="dropdown-item" onclick="$(this).submit();"  href="#">' +
        '<small>Block</small></a>' +
        '</div>' +
        '</form>' +
        '</div>' +
        '</div> ' +
        '<div class="scrollbar-inner" data-load="1"  id="conversation_' + data.id + '">' +
        messageauthallow +
        '<div class="chat-content pr-1 m-0 ' + messageauth + '" data-limit="0"> ' +

        '</div> ' +
        '</div> ' +

        '<div class="msgbox p-c1 ">' +
        '<div class="input-group align-middle "> ' +
        '<form method="POST" action="/message" accept-charset="UTF-8">' +
        '<input name="_token" type="hidden" value="' + csrf + '">' +
        '<input name="conversation_id" type="hidden" value="' + data.id + '">' +
        '<input name="success" type="hidden" value="chatting1">' +
        '<input type="text" name="message" autocomplete="off" placeholder="Message" class="chatBox1 form-control form-control-sm "> ' +
        '</form>' +
        '</div> </div> </div>';

    if (data.auth == 2) {
        load_prev_msg(data.id, csrf);
    }


    chatPersons();
    return template;
}

function anonymousTagTemplate(name) {

    if (name.indexOf('Anonymous') >= 0) {
        // name = name.replace("Anonymous", '');
        var name2 = name.substring(name.indexOf('Anonymous'), name.length);
        var name = name.substring(0, name.indexOf('Anonymous'));

        name += '<span class="tag tag-danger tag-pill">' + name2 + '</span>';

    }
    return name;
}

function load_prev_msg(id, token, conversationScroll) {


    var time_tag = null;
    $.ajax({
        type: "POST",
        url: "/load_prev",
        data: {id: id, '_token': token, 'load': 0},
        success: function (data) {
            $.each(data, function (key, val) {

                var conversation = '#conversation_' + id;

                if (time_tag != val.time_tag) {
                    time_tag = val.time_tag;
                    $(conversation).find('.chat-content').append(messageTemplate(val, val.me, time_tag));

                }
                else {
                    $(conversation).find('.chat-content').append(messageTemplate(val, val.me));

                }


            });
        },
        complete: function (data) {
            if (conversationScroll) {
                $('#chat-app-scroll').scrollTop(1500000);
            }
        }
    });

}

function notificationTemplate(data, isnew) {
    var icon = '';
    if (data.type == 0) {

        if (data.activity == 1) {
            icon = '<i class="fa fa-thumbs-o-up fa-lg liked" aria-hidden="true"></i>';
        }
        else if (data.activity == 2) {
            icon = '<i class="fa fa-comment-o fa-lg commented" aria-hidden="true"></i>';

        }

    }

    else if (data.type == 1) {

        if (data.activity == 0) {
            icon = '<i class="fa fa-user-plus fa-lg text-primary" aria-hidden="true"></i>';

        }

    }
    else if (data.type == 2) {

        if (data.activity == 0) {
            icon = '<i class="fa fa-caret-down fa-lg text-danger" aria-hidden="true"></i>';

        }
        else if (data.activity == 1) {
            icon = '<i class="fa fa-caret-up fa-lg text-success" aria-hidden="true"></i>';

        }

    }
    else if (data.type == 3) {
        if (data.activity == 0) {
            icon = '<i class="fa fa-eye fa-lg text-warning" aria-hidden="true"></i>';

        }

    }

    var template = '' ;
    if(isnew==1) {
        template = '<div class="b-lightgrey-new myNotification sd-min ba-1 border-light">';
    }
    else{
        template = '<div class="sd-min ba-1 myNotification border-light">';

    }
    template += '<ul class="nav  nav-inline p-0_5">' +
        '<li class="nav-item align-top ">' +
        '<img class="rounded-circle pp-50" src="' + data.user_picture + '" ' +
        'alt="">' +
        //TODO bring the username from notification whose images is shown
        '</li>' +
        '<li class="nav-item mx-0 comment_width-notify">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="m-0 p-0 noti_link ">' + data.msg + ' </p>' +
        '<small class="text-muted">' + data.time + '</small>' +
        '</div>' +
        '</li>' +
        '<li class="float-xs-right">' +
        icon +
        '</li>' +
        '</ul>' +
        '</div>';
    return template;
}

function likelistTemplate(data) {

    var template = '<div class="">' +
        '<ul class="nav  nav-inline p-0_5">' +
        '<li class="nav-item align-top ">' +
        '<img class="rounded-circle pp-50" src="' + data.user_picture + '" ' +
        'alt="' + data.user_name + '">' +
        '</li>' +
        '<li class="nav-item mx-0 comment_width">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="m-0 p-0 noti_link ">' + data.user_name + ' </p>' +
        '<small class="text-muted">' + data.time + '</small>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</div>';
    return template;
}

function profilelistTemplate(data) {

    var template = '<div class="">' +
        '<ul class="nav  nav-inline p-0_5">' +
        '<li class="nav-item align-top ">' +
        '<img class="rounded-circle pp-50" src="' + data.user_picture + '" ' +
        'alt="' + data.user_name + '">' +
        '</li>' +
        '<li class="nav-item mx-0 comment_width">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="m-0 p-0 post_user_link opensanL "><a href="/@' + data.username + '" data-loc="page">' + data.user_name + '</a> </p>' +
        '<small class="text-muted">' + data.time + '</small>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</div>';
    return template;
}

function profilelistTemplate1(data) {

    var template = '<div class="">' +
        '<ul class="nav  nav-inline p-0_5">' +
        '<li class="nav-item align-top ">' +
        '<img class="rounded-circle pp-50" src="' + data.user_picture + '" ' +
        'alt="' + data.user_name + '">' +
        '</li>' +
        '<li class="nav-item mx-0 comment_width">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="m-0 p-0 post_user_link opensanN ">' + data.user_name + '</p>' +
        '<small class="text-muted">' + data.time + '</small>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</div>';
    return template;
}

function feedsTemplate(data) {

    var template =
        '<ul class="nav  nav-inline p-0_5">' +
        '<li class="nav-item align-top ">' +
        '<img class="rounded-circle pp-40" src="' + data.image + '" ' +
        'alt="">' +
        //TODO bring the username from notification whose images is shown
        '</li>' +
        '<li class="nav-item mx-0 comment_width">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="m-0 p-0 comment_link">' + data.message + ' </p>' +
        '<small class="text-muted">' + data.time + '</small>' +
        '</div>' +
        '</li>' +
        '</ul>';
    return template;
}

function onlineTemplate(data) {

    var last = data.last_seen;

    if (parseInt(last) <= 30) {

    }

    var template =
        '<ul class="nav  nav-inline p-0_5 pr-0 mr-0 pr-1">' +
        '<li class="nav-item align-top ">' +
        '<img class="rounded-circle pp-40" src="' + data.user_picture + '" ' +
        'alt="' + data.user_name + '">' +
        '</li>' +
        '<li class="nav-item mx-0 online_width-s">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="m-0 p-0 comment_link">' + data.user_name + '</p>' +
        '<small class="text-muted opensanN">' + TextLimit(data.intro, 15) + '</small>' +
        '</div>' +
        '</li>' +
        '<li class="nav-item ml-1 pl-2 mr-0 pr-0">';
    if (data.last_seen == "online")
        template += '<div style="margin-top:10px; background:#5cb85c; height:8px; width:8px; border-radius: 100%;">&nbsp;</div>';
    else
        template += '<small class="text-muted opensanL">' + data.last_seen + '</small>';

    template += '</li>' +
        '</ul>';
    return template;
}

function onlineTemplate1(data) {
    var countTemplate = '<span class="tag tag-primary tag-pill hidden-xs-up">0</span>';
    if (data.count > 0) {
        countTemplate = '<span class="tag tag-primary tag-pill">' + data.count + '</span>';
    }

    var name = anonymousTagTemplate(data.name);

    var template = '<ul class="nav  nav-inline p-0_5  pr-0 mr-0">' +
        '<li class="nav-item align-top ">' +
        '<img class="rounded-circle pp-40" src="' + data.image + '" ' +
        'alt="' + data.name + '">' +
        '</li>' +
        '<li class="nav-item mx-0 online_width-s1">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="m-0 p-0 comment_link">' + name + '</p>' +
        '<small class="text-muted opensanN">' + smilies(TextLimit(data.message, 15)) + '</small>' +
        '</div>' +
        '</li>' +
        '<li class="nav-item  mr-0 pr-0">' +
        countTemplate +
        '<small class="text-muted opensanL"> ' + data.time + '</small>' +
        '</li>' +
        '</ul>';
    return template;
}

function comment_wrapper(data, id, comment) {

    var template = '';
    if (comment > 0) {
        template = '<div class="mt-0  mb-0 hr-custom"> </div>';
        if (comment > 1) {

            template += '<form method="POST" action="/showcomments" accept-charset="UTF-8">' +
                '<input name="_token" type="hidden" value="' + csrf + '">' +
                '<input name="post_id" type="hidden" value="' + id + '"> ' +
                '<input name="load" type="hidden" value="0"> ' +
                '<input name="success" type="hidden" value="loadmore">' +
                '<div class="b-lightgrey  loadmore hidden-xs-up" onclick="$(this).submit();">' +
                '<small class="pl-0_5 text-muted">Previous Comments</small>' +
                '<small class="pr-0_5 float-xs-right text-muted">1 of ' + comment + ' <span class="hidden-sm-down text-muted">comments</span></small>' +
                '</div>' +
                '</form>';
        }

        $.each(data, function (index, value) {

            template += '<div class="pt-0_5 b-lightgrey">';
            template += commentTemplate(value);
            template += '</div>';

        });

    }
    return template;
}

function commentTemplate(data) {

    var template = '<div class="container-fluid">' +
        '<div class="row pb-0_5  px-0_5 ">' +
        '<ul class="nav  nav-inline ">' +
        '<li class="nav-item align-top ">' +
        '<a class="" data-loc="page" href="/@' + data.username + '">' +
        '<img class="rounded-circle pp-35" src="' + data.user_picture + '" ' +
        'alt="' + data.user_name + '">' +
        '</a></li>' +
        '<li class="nav-item mx-0 comment_width">' +
        '<div class="lh-1 pl-0_5 ">' +
        '<p class="m-0 p-0 comment_link breakit opensanN">' +
        '<a data-loc="page" class="" href="/@' + data.username + '">' + data.user_name + '</a>&nbsp; '
        + postTextTemplate(data.comment, 200) +
        '<small class="text-muted"> &nbsp; ' + data.comment_time + ' </small>' +
        '</div>' +
        '</li>' +
        '<li class="nav-item float-xs-right">' +
        '<div class="nav-item dropdown">' +
        '<a class="nav-link fa fa-angle-down text-muted" href="#" data-toggle="dropdown"></a>' +
        dropdown_box_comment(data, data.user_id) +
        '</div>' +
        '</li>' +
        '</ul></div>' +
        '</div>';


    return template;
}

function commentbox(data) {

    var template = '<div class="mt-0  mb-1 hr-custom hidden-xs-up" > </div>' +
        '<form class="form-inline" method="POST" action="/comment" accept-charset="UTF-8"> ' +
        '<input name="_token" type="hidden" value="' + csrf + '">' +
        '<input name="postid" type="hidden" value="' + data.post_id + '"> ' +
        '<input name="success" type="hidden" value="comment">' +
        '<div class="container-fluid hidden-xs-up"> ' +
        '<div class="row pb-1 px-0_5" >' +
        '<ul class="nav  nav-inline ">' +
        '<li class="nav-item align-top ">' +
        '<a class="" href="#">' +
        '<img class="rounded-circle pp-35" src="' + user["user_picture"] + '" ' +
        'alt="' + user["user_name"] + '"> ' +
        '</a>' +
        '</li><li class="nav-item mx-0 pl-0_5 comment_width_c">' +
        '<input class="form-control  px-0 ba-0 w-100 commentBox"  name="comment" autocomplete="off"  placeholder="Comment" type="text"> ' +
        '</li><li class="nav-item p-0 m-0 float-xs-right">' +
        '<button class="form-control hidden-lg-up btn-block btn btn-secondary " type="submit">' +
        '<i class="fa fa-paper-plane" aria-hidden="true"></i> ' +
        '</button>' +
        '</li>' +
        '</ul></div>' +
        '</div>' +
        '</form>';

    return template;
}

function linkbox(data) {
    if (data.indexOf("!*$$$$$*!") != -1) {
        var res = data.split("!*$$$$$*!");
        var desc = res[2];
        if (desc.length > 150)
            desc = desc.substr(0, 150) + "...";

        var title = res[3];
        if (title.length > 50)
            title = title.substr(0, 50) + "...";


        var template = '<div class="col-xs-12 ba-1 border-light ">' +
            '<div class="col-xs-4 p-1"><img class="img-fluid w-100" src="' + res[1] + '" alt="' + res[3] + '"></div>' +
            '<div class="col-xs-8 px-1 pt-0_5 bl-1 breakit border-light"> ' +
            '<a href="' + res[0] + '">' + title + '</a>' +
            '<p class="pt-0_5 ">' + desc + '</p>' +
            '</div>' +
            '</div>';
        return template;
    } else {

        return '';

    }
}

function post_imageTemplate(data, slide) {

    var template = '';
    if (data.length) {

        if (data[0].type == 4) {
            template += '<div class="pt-0_5" >';
            template += data[0].source;
            template += '</div>';
        }
        else if (data[0].type == 5) {
            template += '<div class="pt-0_5" >';
            template += linkbox(data[0].source);
            template += '</div>';
        }
        else {
            template += '<div class="pt-0_5" >';
            $.each(data, function (key, val) {
                var width = parseInt($('.grid-item1').width());

                if (slide == 1) {
                    width = parseInt($('#notificationSliderPost').width());

                }

                var height = width * parseFloat(val.height);
                template += '<img class="img-fluid w-100 cursor-pointer " style="height:' + height + 'px;" data-toggle="modal" data-target="#imageModal" data-img="' + val.source + '" src="' + val.source + '" alt="image">';
            });
            template += '</div>';
        }
    }
    return template;
}

function like_Template(data) {

    var template = '';
    var i = 0;
    if (data) {
        if (data.length) {
            template += '<div class="row mb-1">' +
                '<div class="col-xs-12 ">';

            $.each(data, function (key, val) {
                var me = "";
                if (val.user_id == user['user_id'])
                    me = "me";
                if (i < 3)
                    template += '<img class="rounded-circle pp-30 mr-0_5" src="' + val.user_picture + '" ' +
                        'alt="' + val.user_name + '" data-me="' + me + '" data-toggle="tooltip" data-placement="top" title="' + val.user_name + '">';
                i++;
            });
            // if(data.length >3)
            template += '<a href="/like/' + data[0].post_id + '" data-loc="like_list" ' +
                'class="d-inline like_list  ba-1 border-light sd-min cursor-pointer"><b>+</b></a>';
            template += '</div></div>';
            $('[data-toggle="tooltip"]').tooltip();
        }
    }
    return template;
}

function hashTag(data) {
    return data.replace(/(#\w+)/g, " <a href='/search/!$1' data-loc='page'>$1</a>");
}

function attherate(data) {
    return data.replace(/(@\w+)/g, " <span class='post_user_link '><b>" +
        "<a  href='$1' data-loc='page'>$1</a></b>" +
        "</span>");
}

function smilies(data) {
    return data.replace(/\*\*(.*?)\*\*/g, "<img src='https://cdnjs.cloudflare.com/ajax/libs/emojione/2.1.4/assets/png/$1' class='emojioneemoji pp-20'> ");
}

function htmlencode(str) {
    return str.replace(/</g, "&lt;").replace(/>/g, "&gt;");
}

function smiliefy(data) {

    return data.replace(/<img (.*?) src="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/emojione\/2\.1\.4\/assets\/png\/(.*?)">/g, " **$2**");
}

function smiliesBlank(data) {
    return data.replace(/\*\*(.*?)\*\*/g, "");
}

function urlify(data) {
    var urlRegex = /(https?:\/\/[^\s]+)/g;
    return data.replace(urlRegex, '<a href="$1" data-loc="page-exter">$1</a>')
}

function postTextLimit(data, limit) {

    var template = '';
    var data1 = smiliesBlank(data);

    if (data1.length > limit) {
        template = '<span> ' + data.substring(0, limit) + '</span>';
        template += '<span class="complete"> ' + data.substring(limit, data.length) + '</span> ' +
            ' <span class="more btn btn-primary ba-1 border-light p-0 pl-c5p pr-c5p"> more.. </span>';
    }
    else {
        template = data;
    }
    return template;
}

function TextLimit(data, limit) {

    var finalData = data;
    if (finalData.length > limit)
        finalData = finalData.substr(0, limit) + "...";
    return finalData;
}

function postTextTemplate(data, limit) {

    var template = data;

    template = htmlencode(template);
    template = template.replace(/\n/g, " <br />");
    template = attherate(template);
    template = hashTag(template);
    template = urlify(template);
    template = postTextLimit(template, limit);
    template = smilies(template);

    return template;
}

function smilieEncode(text) {

    console.log(text);
    text = smiliefy(text);
    text = text.replace(/&nbsp;/g, ' ');
    text = text.trim();
    text = text.replace(/<br>/g, '');
    text = text.replace(/<div>/g, '\n');
    text = text.replace(/<\/div>/g, '');
    text = text.replace(/&lt;/g, '<');
    text = text.replace(/&gt;/g, '>');

    return text;
}

function dropdown_box_post(data, user_id) {

    if (user_id == user.user_id) {

        var template = '<form action="/post_del" method="post">' +
            '<input type="hidden" name="_token" value="' + csrf + '">' +
            '<input type="hidden" name="post_id" value="' + data.post_id + '">' +
            '<input type="hidden" name="success" value="post_del">' +
            '<a class="dropdown-item" onclick="$(this).submit();" href="#">' +
            '<small><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</small></a>' +
            '</form>';
        // '<form action="/post_req" method="post">' +
        // '<input type="hidden" name="_token" value="' + csrf + '">' +
        // '<input type="hidden" name="post_id" value="' + data.post_id + '">' +
        // '<input type="hidden" name="success" value="post_req">' +
        // '<a class="dropdown-item" onclick="$(this).submit();" href="#">' +
        // '<small><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</small></a>' +
        // '</form>';

        if (data.isfollow) {

            template += '<form action="/Unfollow_Post" method="post">' +
                '<input type="hidden" name="_token" value="' + csrf + '">' +
                '<input type="hidden" name="post_id" value="' + data.post_id + '">' +
                '<input type="hidden" name="success" value="Unfollow_Post">' +
                '<a class="dropdown-item" onclick="$(this).submit();" href="#">' +
                '<small><i class="fa fa-chain-broken" aria-hidden="true"></i> Unfollow Post</small></a>' +
                '</form>';
        }
        return template;

    }
    else {

        var template = '<a class="dropdown-item" data-post_id="' + data.post_id + '"  data-type="post" data-toggle="modal" data-target="#reportModal" href="#">' +
            '<small><i class="fa fa-flag" aria-hidden="true"></i>  Report</small>' +
            '</a>';

        if (data.isfollow) {

            template += '<form action="/Unfollow_Post" method="post">' +
                '<input type="hidden" name="_token" value="' + csrf + '">' +
                '<input type="hidden" name="post_id" value="' + data.post_id + '">' +
                '<input type="hidden" name="success" value="Unfollow_Post">' +
                '<a class="dropdown-item" onclick="$(this).submit();" href="#">' +
                '<small><i class="fa fa-chain-broken" aria-hidden="true"></i> Unfollow Post</small></a>' +
                '</form>';
        }

        return template;
    }

}

function dropdown_box_comment(data, user_id) {

    if (user_id == user.user_id) {

        return '<div class="dropdown-menu dropdown-menu-right">' +
            '<form action="/comment_del" method="post">' +
            '<input type="hidden" name="_token" value="' + csrf + '">' +
            '<input type="hidden" name="post_id" value="' + data.post_id + '">' +
            '<input type="hidden" name="id" value="' + data.comment_id + '">' +
            '<input type="hidden" name="success" value="comment_del">' +
            '<a class="dropdown-item" onclick="$(this).submit();" href="#">' +
            '<small><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</small></a>' +
            '</form>' +
            '</div>';
    }
    else {
        return '';
        // return '<a class="dropdown-item" href="#">' +
        //     '<small><i class="fa fa-flag" aria-hidden="true"></i> Report</small>' +
        //     '</a>';
    }

}

function postTemplate(data, init, init1, slide) {
    init = init || 0;
    slide = slide || 0;

    var classlike = '';
    var classdislike = '';
    var classcomment = '';
    var SystemData = '';

    if (data.isliked > 0)
        classlike = 'liked';
    if (data.isdisliked > 0)
        classdislike = 'disliked';
    if (data.iscommented > 0)
        classcomment = 'commented';

    try {
        if (data.post_data[0].data != null)
            SystemData = " - " + data.post_data[0].data;
    }
    catch (e) {
    }

    var mygrid = 'grid-item1';
    if (init1)
        mygrid = '';

    var template = '<div class="card ' + mygrid + ' square-corner sd-min mb-2">' +
        '<div class="container-fluid">' +
        '<div class="row px-0_5 mt-1">' +
        '<ul class="nav  nav-inline">' +
        '<li class="nav-item align-top">' +
        '<a class="" data-loc="page" href="/' + data.username + '">' +
        '<img class="rounded-circle pp-50" src="' + data.user_picture + '" ' +
        'alt="' + data.user_name + '">' +
        '</a></li><li class="nav-item mx-0 block_width"><div class="lh-1 pl-0_5 ">' +
        '<p class="p-0 m-0 post_user_link opensanN">' +
        '<a class="" data-loc="page" href="/' + data.username + '">' + data.user_name + '<small class="text-muted">' + SystemData + '</small></a></p>' +
        '<small class="text-muted">' + data.post_time + '</small></div>' +
        '</li><li class="nav-item px-0 mx-0 float-xs-right">' +
        '<div class="nav-item dropdown ">' +
        '<a class="nav-link fa fa-angle-down text-muted" href="#" data-toggle="dropdown" ></a>' +
        '<div class="dropdown-menu dropdown-menu-right " >' +
        dropdown_box_post(data, data.user_id) +
        '</div></div></li>' +
        '</ul>' +
        '</div>' +
        '</div>' + post_imageTemplate(data.post_data, slide) +
        '<div class="col-xs-12 pl-c9p pt-1 breakit">';
        if(data.post_text.length<50)
		template+='<p class="lead">';
	else
		template+='<p>';
        template+= postTextTemplate(data.post_text, 500) +
        '</p></div>' +
        '<div class="container-fluid">' +
        '<div class="row px-1">' +
        '<ul class="nav  nav-inline">' +
        '<li class="nav-item">' +
        '<form method="POST" action="/like" accept-charset="UTF-8">' +
        '<input name="_token" type="hidden" value="' + csrf + '">' +
        '<input name="post_id" type="hidden" value="' + data.post_id + '">' +
        '<input name="like_type" type="hidden" value="1">' +
        '<input name="success" type="hidden" value="post-common">' +
        '<input name="location" type="hidden" value="1">' +
        '<div class="form-group">' +
        '<div class="post-feature-common like ' + classlike + '" onclick="$(this).submit();">' +
        '<i class="fa fa-thumbs-o-up fa-lg post-icons" aria-hidden="true"></i>' +
        '<small class="pl-0_5">' + data.likes + '</small>' +
        '</div>' +
        '</div>' +
        '</form>' +
        '</li>' +
        '<li class="nav-item">' +
        '<form method="POST" action="/like" accept-charset="UTF-8">' +
        '<input name="_token" type="hidden" value="' + csrf + '">' +
        '<input name="post_id" type="hidden" value="' + data.post_id + '">' +
        '<input name="like_type" type="hidden" value="0">' +
        '<input name="success" type="hidden" value="post-common">' +
        '<input name="location" type="hidden" value="0">' +
        '<div class=" post-feature-common dislike ' + classdislike + '" onclick="$(this).submit();">' +
        '<i class="fa fa-thumbs-o-down fa-lg post-icons" aria-hidden="true"></i>' +
        '<small class="pl-0_5">' + data.dislikes + '</small></div></form>' +
        '</li>' +
        '<li class="nav-item float-xs-right">' +
        '<div class="post-feature-common comment ' + classcomment + '" data-id="' + data.post_id + '">' +
        '<i class="fa fa-comment-o fa-lg post-icons" aria-hidden="true"></i>' +
        '<small class="pl-0_5">' + data.comments + '</small>' +
        '</div>' +
        '</li></ul>' +
        '</div>' +
        '</div>' +
        '<div class="container-fluid">' +
        like_Template(data.like_all) +
        '</div>' +
        '<div class="commentblock">';
    if (init == 0)
        template += comment_wrapper(data["post_comment"], data.post_id, data.comments);
    template += '</div>';
    template += commentbox(data);
    template += '</div>';

    return template;
}

function alertTemplate(data) {

    var template = '<div class="alert alert-warning alert-dismissible fade in mb-0 sd-1" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' + data + '</div>';
    return template;
}

function loadingTemplate(data) {

    var template = '<div class="alert alert-warning alert-dismissible fade in mb-0 sd-1" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' + data + '</div>';

    return template;
}

function commentInit() {

    // if ($("input.commentBox:text").next().hasClass('emojionearea')) {
    //
    //     $("input.commentBox:text").unbind();
    //
    // }
    //
    // $(".commentBox").emojioneArea({
    //     pickerPosition: "top",
    //     autocomplete: false,
    //     inline: true,
    // });
    //
    // $("input.commentBox:text").each(function (i, obj) {
    //
    //     if ($(this).next().next().hasClass('emojionearea')) {
    //         $(this).next().remove();
    //         $(this).next().children().first().html('');
    //         $(this).next().children().first().attr('placeholder','Comment');
    //
    //     }
    //
    // });


}
