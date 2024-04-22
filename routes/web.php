<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/UserRegistrationForm',[UserController::class,'userRegistrationPage'])->name('userRegistrationPage');

Route::post('/userOperation',[UserController::class,'userRegistrationOperation'])->name('userRegistrationOperation'
);

Route::get('/',[UserController::class,'userLoginPage'])->name('userLoginPage');
//   Login Operations
Route::post('/loginOperation',[UserController::class,'userLoginOperation'])->name('userLoginOperation');
// ====================================================== users routes ========================================================
Route::group(['middleware' => ['user']],function(){
    Route::any('LogoutOperation',[UserController::class,'logoutOperation'])->name('logoutOperation');
    // user dashboard
    Route::get('/UserDashboard',[UserController::class,'userDashboard'])->name('userDashboard');
    // category dashboard
    Route::get('/categoryList',[UserController::class,'categoryListPage'])->name('categoryListPage');
    // category creating pages
    Route::get('/creatingCategoryPage',[UserController::class,'createCategoryPage'])->name('createCategoryPage');
    // create category operation
    Route::post('/creatingCategoryOperation',[UserController::class,'createCategoryOperation'])->name('createCategoryOperation');
    // live search 
    Route::post('/SearchCategoryOperation',[UserController::class,'searchCategories'])->name('searchCategories');
    // feedback list
    Route::get('/feedbackList',[UserController::class,'feedbackListPage'])->name('feedbackListPage');
    // create feedback page
    Route::get('/createFeedbackPage',[UserController::class,'createFeedbackPage'])->name('createFeedbackPage');
    // create feedback operation
    Route::post('/createFeedbackOperation',[UserController::class,'createFeedbackOperation'])->name('createFeedbackOperation');
    // live search 
    Route::post('/SearchFeedbackOperation',[UserController::class,'searchFeedback'])->name('searchFeedback');
    // edit pages feedbacks
    Route::get('/editFeedbackPage/{id}',[UserController::class,'editPagesFeedback'])->name('editPagesFeedback');
    // edit operations
    Route::any('/editOperationsFeedbacks/{id}',[UserController::class,'editFeedbackOperation'])->name('editFeedbackOperation');
    // details feedbacks pages
    Route::get('/detailsFeedbacks/{id}',[UserController::class,'detailsPagesOperation'])->name('detailsPagesOperation');
    // removed operations feedbacks pages
    Route::any('/removedOperationsFeedbacks/{id}',[UserController::class,'removedOperations'])->name('removedOperations');
    // edit page category
    Route::get('/editCategoryPage/{id}',[UserController::class,'editCategoryPages'])->name('editCategoryPages');
     // edit operations
     Route::any('/editOperationsCategory/{id}',[UserController::class,'editCategoryOperation'])->name('editCategoryOperation');
    // remove category operation
    Route::any('/removedOperationCategory/{id}',[UserController::class,'removedCategoryOperation'])->name('removedCategoryOperation');
    // see my feedback list
    Route::get('/feedbackListMy',[UserController::class,'feedbackMyListPage'])->name('feedbackMyListPage');
    // comment page
    Route::get('/commentPage/{id}',[UserController::class,'commentPage'])->name('commentPage');
    });
    // comment operation
    Route::post('/commentOperation',[UserController::class,'commentOperations'])->name('commentOperations');
    // comment list pages
    Route::get('/commentListPage',[UserController::class,'commentListPage'])->name('commentListPage');
    
// ====================================================== users routes ========================================================
require __DIR__.'/auth.php';
