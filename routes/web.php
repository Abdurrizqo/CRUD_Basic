<?php

use App\Http\Controllers\DraftController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Route::post('/register', [UserController::class, 'register']);
// Route::post('/login', [UserController::class, 'login']);
// Route::group(
//     ['middleware' => ['jwt.auth']],
//     function () {
//         Route::get('/user/myprofile/{idUser}', [UserController::class, "myProfile"]);
//         Route::post('/user/edit-profile/{idUser}', [UserController::class, "editProfile"]);
//         Route::post('/user/edit-photo-profile/{idUser}', [UserController::class, 'editPhotoProfile']);
//         Route::put('/user/edit-description/{idUser}', [UserController::class, 'editDescription']);

//         //News Route
//         Route::get('/news/all-news', [NewsController::class, 'getAllNews']);
//         Route::get('/news/detail-news/{idnews}', [NewsController::class, 'detailNews']);
//         Route::post('/news/create-news', [NewsController::class, 'createNews']);
//         Route::post('/news/edit-news/{idnews}', [NewsController::class, 'editNews']);
//         Route::delete('/news/delete-news/{idnews}', [NewsController::class, 'deleteNews']);

//         //draft Route
//         Route::get('/draft/all-draft', [DraftController::class, 'getAllDraft']);
//         Route::get('/draft/detail-draft/{iddraft}', [DraftController::class, 'detailDraft']);
//         Route::post('/draft/create-draft', [DraftController::class, 'createDraft']);
//         Route::post('/draft/edit-draft/{iddraft}', [DraftController::class, 'editDraft']);
//         Route::delete('/draft/delete-draft/{iddraft}', [DraftController::class, 'deleteDraft']);
//     }
// );
