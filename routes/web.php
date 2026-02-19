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

// Standalone Views
Route::get('/chatroom', function () {
    return view('chatroom');
})->name('chatroom');

Route::get('/sandika', function () {
    return view('sandika');
})->name('sandika');


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
    Route::get('/vpn-server', 'VPN_server')->name('vpn-server');
    Route::get('/fun', 'fun')->name('fun'); 
    Route::get('/support', 'support')->name('support');
    Route::get('/pages/nigma', 'nigma')->name('nigma');
    Route::get('/pages/chat/chat', 'chat')->name('chat');
    Route::get('/pages/abie-motlagh/abie', 'abie')->name('abie');
    Route::get('/pages/sandika/sandika', 'proj_sandika')->name('sandika');


    // --- Projects ---
    Route::get('/pages/projects/', 'projects')->name('projects');
    Route::get('/pages/projects/blackwall', 'BlackWall')->name('projects.blackwall');
    Route::get('/pages/projects/mlmatrix', 'Mlmatrix')->name('projects.mlmatrix');
    Route::get('/pages/projects/scp', 'SCP')->name('projects.scp');
    Route::get('/pages/projects/ceasar-toolkit', 'CeasarToolkit')->name('projects.ceasartoolkit');
    Route::get('/pages/projects/parsai', 'parsai')->name('projects.parsai');
    Route::get('/pages/projects/netnexus', 'netnexus')->name('projects.netnexus');
    Route::get('/pages/projects/hounaartoolkit', 'hounaartoolkit')->name('projects.hounaartoolkit');
    
    // Note: You now have '/sandika' (above) AND '/pages/projects//sandika' (below). 
    // Both are perfectly fine, they just point to different URLs!
    Route::get('/pages/projects//sandika', 'sandika')->name('projects.sandika'); 

    // --- Publications ---
    Route::get('/pages/publications/', 'publications')->name('publications');
    Route::get('/pages/publications//blackwall-paper', 'blackwall_paper')->name('publications.blackwall_paper');
    Route::get('/pages/publications//moodium', 'moodium')->name('publications.moodium');
    Route::get('/pages/publications//scm', 'scm')->name('publications.scm');
    Route::get('/pages/publications//captcha', 'captcha')->name('publications.captcha');
    Route::get('/pages/publications//ai-blockchain', 'ai_blockchain')->name('publications.ai_blockchain');
    Route::get('/pages/publications//synergy-blockchain', 'synergy_blockchain')->name('publications.synergy_blockchain');
    Route::get('/pages/publications//php-vuls', 'php_vuls')->name('publications.php_vuls');
    Route::get('/pages/publications//crm', 'crm')->name('publications.crm');
    Route::get('/pages/publications//qca', 'qca')->name('publications.qca');

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
