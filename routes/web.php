<?php

use App\Http\Controllers\Site\AboutController;
use App\Http\Controllers\Site\BlogController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\DonationController;
use App\Http\Controllers\Site\EventController;
use App\Http\Controllers\Site\FaqController;
use App\Http\Controllers\Site\GalleryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\LeadershipController;
use App\Http\Controllers\Site\MemberController;
use App\Http\Controllers\Site\NewsletterController;
use App\Http\Controllers\Site\NewsController;
use App\Http\Controllers\Site\ResourceController;
use App\Http\Controllers\Site\SearchController;
use App\Http\Controllers\Auth\NationalAuthController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\RegisterController as MemberRegisterController;
use App\Http\Controllers\Member\SessionController as MemberSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/leadership', [LeadershipController::class, 'index'])->name('leadership');
Route::get('/members', [MemberController::class, 'index'])->name('members');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/archive', [EventController::class, 'archive'])->name('events.archive');
Route::get('/events/feed.ics', [EventController::class, 'feed'])->name('events.feed');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/calendar.ics', [EventController::class, 'ics'])->name('events.ics');
Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');

Route::get('/donate', [DonationController::class, 'show'])->name('donate');
Route::post('/donate/checkout', [DonationController::class, 'checkout'])->name('donate.checkout');
Route::get('/donate/success', [DonationController::class, 'success'])->name('donate.success');
Route::get('/donate/cancel', [DonationController::class, 'cancel'])->name('donate.cancel');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{album}', [GalleryController::class, 'show'])->name('gallery.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{post}', [NewsController::class, 'show'])->name('news.show');

Route::get('/resources', [ResourceController::class, 'index'])->name('resources');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Member portal
Route::prefix('member')->name('member.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', [MemberRegisterController::class, 'create'])->name('register');
        Route::post('register', [MemberRegisterController::class, 'store'])->name('register.store');
        Route::get('login', [MemberSessionController::class, 'create'])->name('login');
        Route::post('login', [MemberSessionController::class, 'store'])->name('login.store');
    });
});

// National (Unikosa) single sign-on — Laravel Passport OAuth2 client
Route::middleware('guest')->prefix('auth/national')->name('national.')->group(function () {
    Route::get('redirect', [NationalAuthController::class, 'redirect'])->name('redirect');
    Route::get('callback', [NationalAuthController::class, 'callback'])->name('callback');
});

Route::prefix('member')->name('member.')->group(function () {

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::put('profile', [MemberDashboardController::class, 'update'])->name('profile.update');
        Route::post('logout', [MemberSessionController::class, 'destroy'])->name('logout');
    });
});
