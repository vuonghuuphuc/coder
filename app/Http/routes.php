<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/material',function(){
    return view('templates.material');
});

/*Route::get('/', [
    'uses' => '\Coder\Http\Controllers\HomeController@index',
    'as' => 'home',
]);*/

Route::get('/', [
    'uses' => '\Coder\Http\Controllers\HomeController@posts',
    'as' => 'home',
]);

Route::get('/signup', [
    'uses' => '\Coder\Http\Controllers\AuthController@getSignup',
    'as' => 'auth.signup',
    'middleware' => ['guest']
]);

Route::post('/signup', [
    'uses' => '\Coder\Http\Controllers\AuthController@postSignup',
    'middleware' => ['guest']
]);

Route::get('/signin', [
    'uses' => '\Coder\Http\Controllers\AuthController@getSignin',
    'as' => 'auth.signin',
    'middleware' => ['guest']
]);

Route::post('/signin', [
    'uses' => '\Coder\Http\Controllers\AuthController@postSignin',
    'middleware' => ['guest']
]);

Route::get('/signout', [
    'uses' => '\Coder\Http\Controllers\AuthController@getSignout',
    'as' => 'auth.signout'
]);

Route::get('/activate', [
    'uses' => '\Coder\Http\Controllers\AuthController@getActivate',
    'middleware' => ['guest'],
    'as' => 'auth.activate'
]);

Route::get('/recover-password', [
    'uses' => '\Coder\Http\Controllers\AuthController@getRecoverPassword',
    'middleware' => ['guest'],
    'as' => 'password.recover'
]);

Route::post('/recover-password', [
    'uses' => '\Coder\Http\Controllers\AuthController@postRecoverPassword',
    'middleware' => ['guest'],
]);


Route::get('/password-reset', [
    'uses' => '\Coder\Http\Controllers\AuthController@getPasswordReset',
    'middleware' => ['guest'],
    'as' => 'password.reset'
]);

Route::post('/password-reset', [
    'uses' => '\Coder\Http\Controllers\AuthController@postPasswordReset',
    'middleware' => ['guest'],
]);


Route::get('/social/{driver}', [
    'uses' => '\Coder\Http\Controllers\AuthController@getRedirectToProvider',
    'middleware' => ['guest'],
    'as' => 'auth.social'
]);
Route::get('/social-callback/{driver}', [
    'uses' => '\Coder\Http\Controllers\AuthController@getHandleProviderCallback',
    'middleware' => ['guest'],
    'as' => 'auth.social-callback'
]);
//Search

Route::get('/search', [
    'uses' => '\Coder\Http\Controllers\SearchController@getResults',
    'as' => 'search.results'
]);

//User profile


Route::get('/user/{username}', [
    'uses' => '\Coder\Http\Controllers\ProfileController@getProfile',
    'as' => 'profile.index'
]);

Route::get('/profile/edit', [
    'uses' => '\Coder\Http\Controllers\ProfileController@getEdit',
    'as' => 'profile.edit',
    'middleware' => ['auth']
]);
Route::post('/profile/edit', [
    'uses' => '\Coder\Http\Controllers\ProfileController@postEdit',
    'middleware' => ['auth']
]);
Route::post('/profile/skills', [
    'uses' => '\Coder\Http\Controllers\ProfileController@postSkills',
    'as' => 'profile.skills',
    'middleware' => ['auth']
]);
Route::get('/profile/change-password', [
    'uses' => '\Coder\Http\Controllers\ProfileController@getChangePassword',
    'as' => 'profile.change-password',
    'middleware' => ['auth']
]);
Route::post('/profile/change-password', [
    'uses' => '\Coder\Http\Controllers\ProfileController@postChangePassword',
    'middleware' => ['auth']
]);
//Friends

Route::get('/friends', [
    'uses' => '\Coder\Http\Controllers\FriendController@getIndex',
    'as' => 'friend.index',
    'middleware' => ['auth']
]);
Route::get('/friends/add/{username}', [
    'uses' => '\Coder\Http\Controllers\FriendController@getAdd',
    'as' => 'friend.add',
    'middleware' => ['auth']
]);
Route::get('/friends/accept/{username}', [
    'uses' => '\Coder\Http\Controllers\FriendController@getAccept',
    'as' => 'friend.accept',
    'middleware' => ['auth']
]);

//Status

Route::post('/status', [
    'uses' => '\Coder\Http\Controllers\StatusController@postStatus',
    'as' => 'status.post',
    'middleware' => ['auth']
]);

Route::post('/status/{statusId}/reply', [
    'uses' => '\Coder\Http\Controllers\StatusController@postReply',
    'as' => 'status.reply',
    'middleware' => ['auth']
]);

Route::get('/status/{statusId}/like', [
    'uses' => '\Coder\Http\Controllers\StatusController@getLike',
    'as' => 'status.like',
    'middleware' => ['auth']
]);

//Contact us
Route::get('/contact', [
    'uses' => '\Coder\Http\Controllers\ContactController@getIndex',
    'as' => 'contact',
]);
//Contact us
Route::post('/contact', [
    'uses' => '\Coder\Http\Controllers\ContactController@postSend',
]);
//Admin area
Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [
        'uses' => '\Coder\Http\Controllers\DashboardController@getIndex',
        'as' => 'admin.dashboard',
        'middleware' => ['auth', 'permissions:is_admin']
    ]);
    Route::get('/users', [
        'uses' => '\Coder\Http\Controllers\UserController@getIndex',
        'as' => 'admin.users',
        'middleware' => ['auth', 'permissions:is_admin']
    ]);
    Route::get('/post', [
        'uses' => '\Coder\Http\Controllers\PostController@getPost',
        'as' => 'admin.post',
        'middleware' => ['auth', 'permissions:is_admin']
    ]);
    Route::get('/posts', [
        'uses' => '\Coder\Http\Controllers\PostController@getPosts',
        'as' => 'admin.posts',
        'middleware' => ['auth', 'permissions:is_admin']
    ]);
    Route::post('/post', [
        'uses' => '\Coder\Http\Controllers\PostController@postPost',
        'middleware' => ['auth', 'permissions:is_admin']
    ]);
    Route::get('/post/{post_id}/activate', [
        'uses' => '\Coder\Http\Controllers\PostController@getActivate',
        'as' => 'admin.post.activate',
        'middleware' => ['auth', 'permissions:is_admin']
    ]);

    Route::get('/post/{post_id}/deactivate', [
        'uses' => '\Coder\Http\Controllers\PostController@getDeactivate',
        'as' => 'admin.post.deactivate',
        'middleware' => ['auth', 'permissions:is_admin']
    ]);

    Route::get('/post/{post_id}/edit', [
        'uses' => '\Coder\Http\Controllers\PostController@getEdit',
        'as' => 'admin.post.edit',
        'middleware' => ['auth', 'permissions:is_admin']
    ]);


    Route::post('/post/{post_id}/edit', [
        'uses' => '\Coder\Http\Controllers\PostController@postEdit',
        'middleware' => ['auth', 'permissions:is_admin']
    ]);

    Route::post('/file-upload/{dir}', [
        'uses' => '\Coder\Http\Controllers\FileController@postUpload',
        'middleware' => ['auth', 'permissions:is_admin'],
        'as' => 'file.upload'
    ]);
    Route::post('/file-delete', [
        'uses' => '\Coder\Http\Controllers\FileController@postDelete',
        'middleware' => ['auth', 'permissions:is_admin'],
        'as' => 'file.delete'
    ]);
});
//Skills

Route::group(['prefix' => 'remote'], function () {
    Route::post('/skills', [
        'uses' => '\Coder\Http\Controllers\RemoteController@postSkills',
        'middleware' => ['auth'],
        'as' => 'remote.skills',
    ]);
});

//POST

Route::get('/post/{title_url}', [
    'uses' => '\Coder\Http\Controllers\PostController@getView',
    'as' => 'post.view'
]);
Route::get('/post/{postId}/like', [
    'uses' => '\Coder\Http\Controllers\PostController@getLike',
    'as' => 'post.like',
    'middleware' => ['auth']
]);
Route::get('/post/{postId}/bookmark', [
    'uses' => '\Coder\Http\Controllers\PostController@getBookmark',
    'as' => 'post.bookmark',
    'middleware' => ['auth']
]);
Route::get('/post/{postId}/unbookmarked', [
    'uses' => '\Coder\Http\Controllers\PostController@getUnbookmarked',
    'as' => 'post.unbookmarked',
    'middleware' => ['auth']
]);
Route::get('/bookmark', [
    'uses' => '\Coder\Http\Controllers\PostController@getUserBookmarks',
    'as' => 'bookmark',
    'middleware' => ['auth']
]);
Route::get('/posts-matched-your-skills', [
    'uses' => '\Coder\Http\Controllers\PostController@getMatched',
    'as' => 'post.matched',
    'middleware' => ['auth']
]);
Route::post('/post/{postId}/reply', [
    'uses' => '\Coder\Http\Controllers\PostController@postReply',
    'as' => 'post.reply',
    'middleware' => ['auth']
]);

//Tags
Route::get('/tags/{tag_id}/{tag_name}', [
    'uses' => '\Coder\Http\Controllers\SearchController@getTag',
    'as' => 'search.tag'
])->where(['tag_id' => '[0-9]+']);
