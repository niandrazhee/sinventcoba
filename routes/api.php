<?php
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
 
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 

//kategori
Route::get('kategori',[KategoriController::class, 'getAPIKategori']);

Route::post('kategori', [KategoriController::class, 'createAPIKategori']);

Route::put('kategori/{id}',[KategoriController::class, 'updateAPIKategori']);

Route::get('kategori/{id}',[KategoriController::class, 'showAPIKategori']);

Route::delete('kategori/{id}',[KategoriController::class, 'deleteAPIKategori']);


//barang
Route::get('barang', [BarangController::class, 'getAPIBarang']);

Route::get('barang/{id}',[BarangController::class, 'showAPIBarang']);

Route::put('barang/{id}',[BarangController::class, 'updateAPIBarang']);


