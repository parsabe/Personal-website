<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController; // <--- 1. Import the Class

use App\Http\Controllers\ContactController; // <-- Don't forget to import this!

use Spatie\Sitemap\SitemapGenerator;

Route::get('/generate-sitemap', function () {
    SitemapGenerator::create('https://parsabe.com')->writeToFile(public_path('sitemap.xml'));
    return 'Sitemap generated!';
});
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
    Route::get('/books', 'books')->name('books');
    Route::get('/chat', 'chat')->name('chat');
    Route::get('/sandika', 'sandika')->name('sandika');


    Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

    // --- Projects ---
    Route::get('/projects', 'projects')->name('projects');
    Route::get('/projects/vectra', 'vectra')->name('projects.vectra');
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
    Route::get('/publications/vectra-paper', 'vectra_paper')->name('publications.vectra_paper');
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

// ==========================================
// CS Certificates Download Portal
// ==========================================
Route::get('/cs-portal', [App\Http\Controllers\CsCertificateController::class, 'index'])->name('cs.certificates.index');
Route::post('/cs-portal/search', [App\Http\Controllers\CsCertificateController::class, 'search'])->middleware('throttle:5,1')->name('cs.certificates.search');
Route::post('/cs-portal/clear', [App\Http\Controllers\CsCertificateController::class, 'clear'])->name('cs.certificates.clear');
Route::get('/cs-portal/download/certificate', [App\Http\Controllers\CsCertificateController::class, 'downloadCertificate'])->name('cs.certificates.download');
Route::get('/cs-portal/download/images', [App\Http\Controllers\CsCertificateController::class, 'downloadImages'])->name('cs.certificates.download-images');

// ==========================================
// CS Feedback Portal
// ==========================================
Route::get('/cs-portal/feedback', [App\Http\Controllers\CsFeedbackController::class, 'create'])->name('cs.feedback.create');
Route::post('/cs-portal/feedback', [App\Http\Controllers\CsFeedbackController::class, 'store'])->name('cs.feedback.store');
Route::post('/cs-portal/feedback/verify', [App\Http\Controllers\CsFeedbackController::class, 'verifyEmail'])->name('cs.feedback.verify');
Route::post('/cs-portal/feedback/reset', [App\Http\Controllers\CsFeedbackController::class, 'resetSession'])->name('cs.feedback.reset');

// ==========================================
// Admin Portal (/parsa) with 2FA Protection
// ==========================================
Route::get('/parsa/2fa', [App\Http\Controllers\AdminPortalController::class, 'show2faForm'])->name('parsa.2fa.show');
Route::post('/parsa/2fa/verify', [App\Http\Controllers\AdminPortalController::class, 'verify2fa'])->name('parsa.2fa.verify');

Route::middleware(['auth', 'admin.2fa'])->group(function () {
    Route::get('/parsa', [App\Http\Controllers\AdminPortalController::class, 'dashboard'])->name('parsa.dashboard');
    Route::post('/parsa/contact/{id}/reply', [App\Http\Controllers\AdminPortalController::class, 'replyContact'])->name('parsa.contact.reply');
    Route::post('/parsa/contact/{id}/delete', [App\Http\Controllers\AdminPortalController::class, 'deleteContact'])->name('parsa.contact.delete');
    Route::post('/parsa/contacts/purge-all', [App\Http\Controllers\AdminPortalController::class, 'purgeAllContacts'])->name('parsa.contacts.purge-all');
    Route::post('/parsa/feedback/{id}/reply', [App\Http\Controllers\AdminPortalController::class, 'replyFeedback'])->name('parsa.feedback.reply');
    Route::post('/parsa/feedback/{id}/delete', [App\Http\Controllers\AdminPortalController::class, 'deleteFeedback'])->name('parsa.feedback.delete');
});

require __DIR__ . '/auth.php';

