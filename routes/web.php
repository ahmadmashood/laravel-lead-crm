<?php
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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


Route::get('/', fn() => redirect()->route('leads.index'));
Route::middleware(['auth'])->group(function () {

  // Leads
  Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
  Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
  Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
  Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');

  // Create lead (web form)
  Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');

  // Assign/Reassign
  Route::post('/leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');     // admin
  Route::post('/leads/{lead}/reassign', [LeadController::class, 'reassign'])->name('leads.reassign'); // operation/admin
});

require __DIR__.'/auth.php';
