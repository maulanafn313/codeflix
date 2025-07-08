<?php

use App\Models\Membership;
use Illuminate\Http\Request;
// use GuzzleHttp\Psr7\Request;
use App\Events\MembershipHasExpired;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\TransactionController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [MovieController::class, 'index'])->name('home');
Route::get('/movies', [MovieController::class, 'all'])->name('movies.index');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/movies/movie/{movie:slug}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/categories/category/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');




Route::post('/logout', function(Request $request)
{
    return app(AuthenticatedSessionController::class)->destroy($request);
})->name('logout')->middleware(['auth', 'logout.device']);

Route::get('/subscribe/plans', [SubscribeController::class, 'showPlans'])->name('subscribe.plans');
Route::get('/subscribe/plan/{plan}', [SubscribeController::class, 'checkoutPlan'])->name('subscribe.checkout');
Route::post('/subscribe/checkout', [SubscribeController::class, 'processCheckout'])->name('subscribe.process');
Route::get('/subscribe/success', [SubscribeController::class, 'showSuccess'])->name('subscribe.success');

Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');

Route::get('/text-expired', function(){
    $membership = Membership::find(1); // Assuming you have a membership with ID 1
    event(new MembershipHasExpired($membership));
    return 'Membership expired event triggered.';
});