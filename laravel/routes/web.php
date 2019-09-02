<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Login

Route::post('/api/token', 'ApiLoginController@createUser');

//signup

Route::post('/api/{token}/signUpPassword', 'ApiSignUpController@signUpPassword');
Route::post('/api/{token}/signUpPasswordSkip', 'ApiSignUpController@signUpPasswordSkip');
Route::post('/api/{token}/signUpGenderDob', 'ApiSignUpController@signUpGenderDob');
Route::post('/api/{token}/signUpProfilePicSkip', 'ApiSignUpController@signUpProfilePicSkip');
Route::post('/api/{token}/signUpProfilePic', 'ApiSignUpController@signUpProfilePic');



//comment
Route::get('/api/{token}/comment/{postId}', 'ApiCommentController@comments');
Route::post('/api/{token}/comment', 'ApiCommentController@comment');
Route::post('/api/{token}/deleteComment', 'ApiCommentController@deleteComment');

//like

Route::post('/api/{token}/likes', 'ApiLikeController@likes');
Route::post('/api/{token}/like', 'ApiLikeController@like');

//post

Route::get('/api/{token}/posts', 'ApiPostController@posts');
//Route::get('/api/{token}/{operator}/{postID}/posts', 'Fabitsapi@postsControl');
Route::get('/api/{token}/trend', 'ApiPostController@trend');
Route::post('/api/{token}/postText', 'ApiPostController@postText');
Route::post('/api/{token}/postImage', 'ApiPostController@postImage');
Route::get('/api/{token}/postsPool', 'ApiPostController@pools');
Route::post('/api/{token}/deletePost', 'ApiPostController@deletePost');
Route::post('/api/{token}/unFollowPost', 'ApiPostController@unFollowPost');


//user

//Route::get('/api/{token}/my_following', 'Fabitsapi@following');//check it
Route::get('/api/{token}/my_following', 'ApiUserController@my_following');
Route::post('/api/{token}/block', 'ApiUserController@Block');
Route::get('/api/{token}/my_blocks', 'ApiUserController@my_blocks');
Route::get('/api/{token}/my_block_list', 'ApiUserController@my_block_list');
Route::post('/api/{token}/follow', 'ApiUserController@follow');
Route::get('/api/{token}/suggestion', 'ApiUserController@suggestion');


//online

Route::get('/api/{token}/online', 'ApiOnlineController@online');


//notification

Route::get('/api/{token}/notification', 'ApiNotificationController@notification');

//new notification

Route::get('/api/{token}/newNotification', 'ApiNewNotificationController@newNotification');


// new message

Route::get('/api/{token}/newMessage', 'ApiNewMessageController@newMessage');


//profile

Route::get('/api/{token}/@{username}', 'ApiProfileController@profile');
Route::get('/api/{token}/randomProfiles', 'ApiProfileController@randomProfiles');
Route::post('/api/{token}/profileCountLists', 'ApiProfileController@profileCountLists');
Route::post('/api/{token}/profilePic', 'ApiProfileController@profilePic');
Route::post('/api/{token}/profileWall', 'ApiProfileController@profileWall');
Route::post('/api/{token}/bigImage', 'ApiProfileController@bigImage');

//message

Route::post('/api/{token}/simple_message', 'ApiMessageController@SMessage');
Route::post('/api/{token}/complex_message', 'ApiMessageController@CMessage');
Route::post('/api/{token}/readConversation', 'ApiMessageController@readConversation');
Route::post('/api/{token}/conversationInit', 'ApiMessageController@conversationInit');
Route::post('/api/{token}/chatImageUpload', 'ApiMessageController@chatImageUpload');
Route::post('/api/{token}/forceReadConversation', 'ApiMessageController@forceReadConversation');











//message split

Route::get('/api/{token}/chats_list', 'ApiMessageSplitController@chatsList');
Route::get('/api/{token}/messagesList', 'ApiMessageSplitController@MessagesList');
Route::post('/api/{token}/typing', 'ApiMessageSplitController@typing');
Route::post('/api/{token}/chatAllow', 'ApiMessageSplitController@chatAllow');
Route::post('/api/{token}/chatBlock', 'ApiMessageSplitController@chatBlock');
Route::post('/api/{token}/online_conversation', 'ApiMessageSplitController@online_conversation');
Route::post('/api/{token}/conversationDelete', 'ApiMessageSplitController@conversationDelete');
//settings

Route::post('/api/{token}/settings', 'ApiSettingController@settings');
Route::post('/api/{token}/changePassword', 'ApiSettingController@changePassword');
Route::post('/api/{token}/contactUs', 'ApiSettingController@contactUS');
Route::get('/api/{token}/logout', 'ApiSettingController@logout');
Route::post('/api/{token}/changePhoneNumber', 'ApiSettingController@follow');
Route::post('/api/{token}/Otp', 'ApiSettingController@otp');


//search

Route::get('/api/{token}/search/{search}/{type}', 'ApiSearchController@search');

//facematch

Route::get('/api/{token}/checkFaceMatch', 'ApiFaceMatchController@checkFaceMatch');
Route::post('/api/{token}/checkFaceMatch', 'ApiFaceMatchController@FaceMatchUpdate');






//Route::get('/api/{token}/postSingle/{postID}', 'Fabitsapi@postSingle');



//Route::get('/api/{token}/posts/{userId}', 'Fabitsapi@posts');








Route::group(['middleware' => ['checkGuest']], function () {

    Route::get('/', 'LoginController@index');
    Route::post('/', 'LoginController@createUser');
    Route::get('/about', 'LoginController@about');
    Route::get('/terms', 'LoginController@terms');
    Route::get('/loginpolicy', 'LoginController@loginpolicy');
    Route::get('/privacy', 'LoginController@privacy');
    Route::post('/resetInit', 'ResetPassword@resetInit');
//    Route::get('/init', 'LoginController@init');
    Route::post('/resetOTP', 'ResetPassword@resetOTP');
    Route::post('/resetPassword', 'ResetPassword@resetPassword');

});



Route::group(['middleware' => ['checkLogin','checkStatus']], function () {

    Route::get('/home', 'HomeController@index');
    Route::post('/home', 'HomeController@index');
    Route::get('/trending', 'HomeController@trending');
    Route::get('/post/single/{id}', 'HomeController@single');
    Route::post('/logout', 'LoginController@logout');

    Route::get('/changepassword', 'SignupController@password');
    Route::get('/phone', 'SignupController@phone');
    Route::get('/info', 'SignupController@info');
    Route::get('/profile', 'SignupController@profile');

    Route::put('/changepassword', 'SignupController@passwordupdate');
    Route::post('/changepassword', 'SignupController@passwordskip');
    Route::put('/phone', 'SignupController@phoneupdateotp');
    Route::post('/phone', 'SignupController@phoneupdate');
    Route::put('/info', 'SignupController@infoupdate');
    Route::put('/profile', 'SignupController@profilesave');
    Route::post('/profile', 'SignupController@profileupload');
    Route::delete('/profile', 'SignupController@profileskip');

    Route::get('/post/{username?}', 'PostController@show');
    Route::get('/post/one/{p_id}', 'PostController@show1');
    Route::post('/post_new', 'PostController@show2');
    Route::get('/post-trending', 'PostController@trending');
    Route::post('/post', 'PostController@create');
    Route::post('/post_req', 'PostController@request');
    Route::post('/Unfollow_Post', 'PostController@Unfollow_Post');
    Route::put('/post', 'PostController@update');
    Route::post('/post_del', 'PostController@delete');
    Route::post('/post_upload', 'PostController@upload');
    Route::post('/post_init', 'PostController@init');
    Route::post('/post_upload_remove', 'PostController@upload_remove');
    Route::post('/like', 'PostController@like');
    Route::get('/like/{id}', 'PostController@like_all');

    Route::post('/comment', 'CommentController@create');
    Route::post('/comment_del', 'CommentController@delete');
    Route::post('/showcomments', 'CommentController@show');

    Route::get('/online', 'UserController@online');
    Route::post('/follow', 'UserController@follow');

    Route::get('/notification/{new?}', 'NotificationController@show');
    Route::get('/notification1/{new?}', 'NotificationController@show1');
    Route::post('/notificationRead', 'NotificationController@read');

//
//    Route::post('/conversation', 'MessageController@create');
//    Route::get('/message', 'MessageController@show');
//    Route::post('/typing', 'MessageController@typing');
//    Route::get('/check-message/{id}', 'MessageController@check');
//    Route::get('/chat-person/{id}', 'MessageController@checkPerson');
//    Route::post('/check-message', 'MessageController@seen');
//    Route::post('/check-all', 'MessageController@seen_all');
//    Route::post('/message', 'MessageController@save');
//    Route::post('/load_prev', 'MessageController@load_prev');
//    Route::get('/messages', 'MessageController@index');
//    Route::get('/messages_all', 'MessageController@index1');
//    Route::post('/chatSession', 'MessageController@chatSession');
//    Route::post('/allow_message', 'MessageController@allow');
//    Route::post('/block_message', 'MessageController@block');
//    Route::get('/chatSession', 'MessageController@chatSessions');
//

    Route::get('/@{username}', 'ProfileController@index');
    Route::post('/followers', 'ProfileController@followers');
    Route::post('/following', 'ProfileController@following');
    Route::post('/facematches', 'ProfileController@facematches');

    Route::get('/search/{search}/{ajax?}', 'SearchController@index');
    Route::get('/recommended', 'SearchController@recommendedPeople');
    Route::get('/topusers', 'SearchController@topPeople');
    Route::get('/toptags', 'SearchController@topHashtag');

    Route::get('/settings/{pageid?}', 'SettingController@index');
    Route::post('/settings/blockedChat', 'MessageController@blockedChat');
    Route::post('/settings/{pageid}', 'SettingController@update');
    Route::post('/phoneOTP', 'SettingController@phoneupdateotp');

    Route::get('/feeds/{new?}', 'NotificationController@feeds');

    Route::get('/facematch', 'FacematchController@index');
    Route::post('/facematch', 'FacematchController@update');

    Route::post('/report', 'HomeController@report');


    Route::put('/update_profile', 'ProfileController@profilesave');
    Route::post('/update_profile', 'ProfileController@profileupload');
    Route::delete('/update_profile', 'ProfileController@profileskip');

    Route::put('/update_wall', 'ProfileController@wallsave');
    Route::post('/update_wall', 'ProfileController@wallupload');
    Route::delete('/update_wall', 'ProfileController@wallskip');
    Route::post('/block', 'ProfileController@block');

    Route::get('/404', 'HomeController@error_404');

});





//
//
//<?php
//
///*
//|--------------------------------------------------------------------------
//| Web Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register web routes for your application. These
//| routes are loaded by the RouteServiceProvider within a group which
//| contains the "web" middleware group. Now create something great!
//|
//*/
//
////Login
//
//Route::post('/api/token', 'Fabitsapi@createUser');
//
////signup
//
//Route::post('/api/{token}/signUpPassword', 'Fabitsapi@signUpPassword');
//Route::post('/api/{token}/signUpPasswordSkip', 'Fabitsapi@signUpPasswordSkip');
//Route::post('/api/{token}/signUpGenderDob', 'Fabitsapi@signUpGenderDob');
//Route::post('/api/{token}/signUpProfilePicSkip', 'Fabitsapi@signUpProfilePicSkip');
//Route::post('/api/{token}/signUpProfilePic', 'Fabitsapi@signUpProfilePic');
//
//
//
////comment
//Route::get('/api/{token}/comment/{postId}', 'Fabitsapi@comments');
//Route::post('/api/{token}/comment', 'Fabitsapi@comment');
//Route::post('/api/{token}/deleteComment', 'Fabitsapi@deleteComment');
//
////like
//
//Route::post('/api/{token}/likes', 'Fabitsapi@likes');
//Route::post('/api/{token}/like', 'Fabitsapi@like');
//
////post
//
//Route::get('/api/{token}/posts', 'Fabitsapi@posts');
//Route::get('/api/{token}/{operator}/{postID}/posts', 'Fabitsapi@postsControl');
//Route::get('/api/{token}/trend', 'Fabitsapi@trend');
//Route::post('/api/{token}/postText', 'Fabitsapi@postText');
//Route::post('/api/{token}/postImage', 'Fabitsapi@postImage');
//Route::get('/api/{token}/postsPool', 'Fabitsapi@pools');
//Route::post('/api/{token}/deletePost', 'Fabitsapi@deletePost');
//Route::post('/api/{token}/unFollowPost', 'Fabitsapi@unFollowPost');
//
//
////user
//
//Route::get('/api/{token}/my_following', 'Fabitsapi@following');//check it
//Route::get('/api/{token}/my_following', 'Fabitsapi@my_following');
//Route::post('/api/{token}/block', 'Fabitsapi@Block');
//Route::get('/api/{token}/my_blocks', 'Fabitsapi@my_blocks');
//Route::get('/api/{token}/my_block_list', 'Fabitsapi@my_block_list');
//Route::post('/api/{token}/follow', 'Fabitsapi@follow');
//Route::get('/api/{token}/suggestion', 'Fabitsapi@suggestion');
//
//
////online
//
//Route::get('/api/{token}/online', 'Fabitsapi@online');
//
//
////notification
//
//Route::get('/api/{token}/notification', 'Fabitsapi@notification');
//
////new notification
//
//Route::get('/api/{token}/newNotification', 'Fabitsapi@newNotification');
//
//
//// new message
//
//Route::get('/api/{token}/newMessage', 'Fabitsapi@newMessage');
//
//
////profile
//
//Route::get('/api/{token}/@{username}', 'Fabitsapi@profile');
//Route::get('/api/{token}/randomProfiles', 'Fabitsapi@randomProfiles');
//Route::post('/api/{token}/profileCountLists', 'Fabitsapi@profileCountLists');
//Route::post('/api/{token}/profilePic', 'Fabitsapi@profilePic');
//Route::post('/api/{token}/profileWall', 'Fabitsapi@profileWall');
//Route::post('/api/{token}/bigImage', 'Fabitsapi@bigImage');
//
////message
//
//Route::post('/api/{token}/simple_message', 'Fabitsapi@SMessage');
//Route::post('/api/{token}/complex_message', 'Fabitsapi@CMessage');
//Route::post('/api/{token}/readConversation', 'Fabitsapi@readConversation');
//Route::post('/api/{token}/conversationInit', 'Fabitsapi@conversationInit');
//Route::post('/api/{token}/online_conversation', 'Fabitsapi@online_conversation');
//Route::post('/api/{token}/conversationDelete', 'Fabitsapi@conversationDelete');
//Route::post('/api/{token}/chatImageUpload', 'Fabitsapi@chatImageUpload');
//
////message split
//
//Route::get('/api/{token}/chats_list', 'Fabitsapi@chatsList');
//Route::get('/api/{token}/messagesList', 'Fabitsapi@MessagesList');
//Route::post('/api/{token}/typing', 'Fabitsapi@typing');
//Route::post('/api/{token}/forceReadConversation', 'Fabitsapi@forceReadConversation');
//Route::post('/api/{token}/chatAllow', 'Fabitsapi@chatAllow');
//Route::post('/api/{token}/chatBlock', 'Fabitsapi@chatBlock');
//
////settings
//
//Route::post('/api/{token}/settings', 'Fabitsapi@settings');
//Route::post('/api/{token}/changePassword', 'Fabitsapi@changePassword');
//Route::post('/api/{token}/contactUs', 'Fabitsapi@contactUS');
//Route::get('/api/{token}/logout', 'Fabitsapi@logout');
//Route::post('/api/{token}/changePhoneNumber', 'Fabitsapi@follow');
//Route::post('/api/{token}/Otp', 'Fabitsapi@otp');
//
//
////search
//
//Route::get('/api/{token}/search/{search}/{type}', 'Fabitsapi@search');
//
////facematch
//
//Route::get('/api/{token}/checkFaceMatch', 'Fabitsapi@checkFaceMatch');
//Route::post('/api/{token}/checkFaceMatch', 'Fabitsapi@FaceMatchUpdate');
//
//
//
//
//
////Route::get('/api/{token}/postSingle/{postID}', 'Fabitsapi@postSingle');
//
//
//
////Route::get('/api/{token}/posts/{userId}', 'Fabitsapi@posts');
//
//
//
//
//
//
//
//
//Route::group(['middleware' => ['checkGuest']], function () {
//
//    Route::get('/', 'LoginController@index');
//    Route::post('/', 'LoginController@createUser');
//    Route::get('/about', 'LoginController@about');
//    Route::get('/terms', 'LoginController@terms');
//    Route::get('/loginpolicy', 'LoginController@loginpolicy');
//    Route::get('/privacy', 'LoginController@privacy');
//    Route::post('/resetInit', 'ResetPassword@resetInit');
////    Route::get('/init', 'LoginController@init');
//    Route::post('/resetOTP', 'ResetPassword@resetOTP');
//    Route::post('/resetPassword', 'ResetPassword@resetPassword');
//
//});
//
//
//
//Route::group(['middleware' => ['checkLogin','checkStatus']], function () {
//
//    Route::get('/home', 'HomeController@index');
//    Route::post('/home', 'HomeController@index');
//    Route::get('/trending', 'HomeController@trending');
//    Route::get('/post/single/{id}', 'HomeController@single');
//    Route::post('/logout', 'LoginController@logout');
//
//    Route::get('/changepassword', 'SignupController@password');
//    Route::get('/phone', 'SignupController@phone');
//    Route::get('/info', 'SignupController@info');
//    Route::get('/profile', 'SignupController@profile');
//
//    Route::put('/changepassword', 'SignupController@passwordupdate');
//    Route::post('/changepassword', 'SignupController@passwordskip');
//    Route::put('/phone', 'SignupController@phoneupdateotp');
//    Route::post('/phone', 'SignupController@phoneupdate');
//    Route::put('/info', 'SignupController@infoupdate');
//    Route::put('/profile', 'SignupController@profilesave');
//    Route::post('/profile', 'SignupController@profileupload');
//    Route::delete('/profile', 'SignupController@profileskip');
//
//    Route::get('/post/{username?}', 'PostController@show');
//    Route::get('/post/one/{p_id}', 'PostController@show1');
//    Route::post('/post_new', 'PostController@show2');
//    Route::get('/post-trending', 'PostController@trending');
//    Route::post('/post', 'PostController@create');
//    Route::post('/post_req', 'PostController@request');
//    Route::post('/Unfollow_Post', 'PostController@Unfollow_Post');
//    Route::put('/post', 'PostController@update');
//    Route::post('/post_del', 'PostController@delete');
//    Route::post('/post_upload', 'PostController@upload');
//    Route::post('/post_init', 'PostController@init');
//    Route::post('/post_upload_remove', 'PostController@upload_remove');
//    Route::post('/like', 'PostController@like');
//    Route::get('/like/{id}', 'PostController@like_all');
//
//    Route::post('/comment', 'CommentController@create');
//    Route::post('/comment_del', 'CommentController@delete');
//    Route::post('/showcomments', 'CommentController@show');
//
//    Route::get('/online', 'UserController@online');
//    Route::post('/follow', 'UserController@follow');
//
//    Route::get('/notification/{new?}', 'NotificationController@show');
//    Route::get('/notification1/{new?}', 'NotificationController@show1');
//    Route::post('/notificationRead', 'NotificationController@read');
//
//    Route::post('/conversation', 'MessageController@create');
//    Route::get('/message', 'MessageController@show');
//    Route::post('/typing', 'MessageController@typing');
//    Route::get('/check-message/{id}', 'MessageController@check');
//    Route::get('/chat-person/{id}', 'MessageController@checkPerson');
//    Route::post('/check-message', 'MessageController@seen');
//    Route::post('/check-all', 'MessageController@seen_all');
//    Route::post('/message', 'MessageController@save');
//    Route::post('/load_prev', 'MessageController@load_prev');
//    Route::get('/messages', 'MessageController@index');
//    Route::get('/messages_all', 'MessageController@index1');
//    Route::post('/chatSession', 'MessageController@chatSession');
//    Route::post('/allow_message', 'MessageController@allow');
//    Route::post('/block_message', 'MessageController@block');
//    Route::get('/chatSession', 'MessageController@chatSessions');
//
//
//    Route::get('/@{username}', 'ProfileController@index');
//    Route::post('/followers', 'ProfileController@followers');
//    Route::post('/following', 'ProfileController@following');
//    Route::post('/facematches', 'ProfileController@facematches');
//
//    Route::get('/search/{search}/{ajax?}', 'SearchController@index');
//    Route::get('/recommended', 'SearchController@recommendedPeople');
//    Route::get('/topusers', 'SearchController@topPeople');
//    Route::get('/toptags', 'SearchController@topHashtag');
//
//    Route::get('/settings/{pageid?}', 'SettingController@index');
//    Route::post('/settings/blockedChat', 'MessageController@blockedChat');
//    Route::post('/settings/{pageid}', 'SettingController@update');
//    Route::post('/phoneOTP', 'SettingController@phoneupdateotp');
//
//    Route::get('/feeds/{new?}', 'NotificationController@feeds');
//
//    Route::get('/facematch', 'FacematchController@index');
//    Route::post('/facematch', 'FacematchController@update');
//
//    Route::post('/report', 'HomeController@report');
//
//
//    Route::put('/update_profile', 'ProfileController@profilesave');
//    Route::post('/update_profile', 'ProfileController@profileupload');
//    Route::delete('/update_profile', 'ProfileController@profileskip');
//
//    Route::put('/update_wall', 'ProfileController@wallsave');
//    Route::post('/update_wall', 'ProfileController@wallupload');
//    Route::delete('/update_wall', 'ProfileController@wallskip');
//    Route::post('/block', 'ProfileController@block');
//
//    Route::get('/404', 'HomeController@error_404');
//
//});
//
