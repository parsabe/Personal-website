<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController; // <--- 1. Import the Class

use App\Http\Controllers\ContactController; // <-- Don't forget to import this!

// ==========================================
// Independent Routes & Closures
// ==========================================

// Contact Form Routes (Moved here so ContactController handles them)
Route::get('/contact', [ContactController::class, 'index'])->name('contact'); // Re-added the name('contact') so your navbar doesn't break!
Route::post('/contact', [ContactController::class, 'store']);



// ==========================================
// PageController Group
// ==========================================
Route::controller(PageController::class)->group(function () {

    // --- Main Navigation Pages ---
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    // (Removed /contact from here since ContactController handles it now)
    Route::get('/search', 'search')->name('search');
    Route::get('/myplaylist', 'myplaylist')->name('myplaylist');
    Route::get('/vpn', 'VPN_server')->name('vpn-server');
    Route::get('/fun', 'fun')->name('fun');
    Route::get('/support', 'support')->name('support');
    Route::get('/nigma', 'nigma')->name('nigma');
    Route::get('/chat', 'chat')->name('chat');
    Route::get('/abie-motlagh', 'abie')->name('abie');
    Route::get('/chat', 'chat')->name('chat');

    Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

    // --- Projects ---
    Route::get('/projects', 'projects')->name('projects');
    Route::get('/projects/blackwall', 'BlackWall')->name('projects.blackwall');
    Route::get('/projects/mlmatrix', 'Mlmatrix')->name('projects.mlmatrix');
    Route::get('/projects/scp', 'SCP')->name('projects.scp');
    Route::get('/projects/ceasartoolkit', 'CeasarToolkit')->name('projects.ceasartoolkit');
    Route::get('/projects/parsai', 'parsai')->name('projects.parsai');
    Route::get('/projects/netnexus', 'netnexus')->name('projects.netnexus');
    Route::get('/projects/hounaartoolkit', 'hounaartoolkit')->name('projects.hounaartoolkit');
    Route::get('/projects/funroot', 'funroot')->name('projects.funroot');
    Route::get('/projects/sandika', 'proj_sandika')->name('projects.sandika');

    // --- Publications ---
    Route::get('/publications', 'publications')->name('publications');
    Route::get('/publications/blackwall-paper', 'blackwall_paper')->name('publications.blackwall_paper');
    Route::get('/publications/moodium', 'moodium')->name('publications.moodium');
    Route::get('/publications/scm', 'scm')->name('publications.scm');
    Route::get('/publications/captcha', 'captcha')->name('publications.captcha');
    Route::get('/publications/ai-blockchain', 'ai_blockchain')->name('publications.ai_blockchain');
    Route::get('/publications/synergy-blockchain', 'synergy_blockchain')->name('publications.synergy_blockchain');
    Route::get('/publications/php-vuls', 'php_vuls')->name('publications.php_vuls');
    Route::get('/publications/crm', 'crm')->name('publications.crm');
    Route::get('/publications/qca', 'qca')->name('publications.qca');

    // --- Blogs ---
    Route::get('/blog', 'blog')->name('blog');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
