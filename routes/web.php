<?php

use App\Models\Reservation;
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

Route::get('/', function () {
    return redirect('dashboard/login');
});

Route::get('/trajectory/{id}', function($id){
    $reservation = Reservation::find($id);
    $airport = $reservation->airport;
    $hotel = $reservation->hotel;
    return view('trajectory', compact('airport', 'hotel'));
})->name('trajectory');

