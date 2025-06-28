<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Models\Banner;
use App\Models\Item;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
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

Route::fallback(function () {
    return view('404');
});

Route::get('/', function () {
    $items = Item::with('rating')->paginate(10);
    return view('home', [
        'route' => Request::route()->getName(),
        'title' => 'NIKKY',
        'items' => $items,
        'banners' => Banner::all(),
    ]);
})->name('home');

Route::get('/products', function () {
    return view('products', [
        'items' => DB::table('items')->get(),
        'param' => ''
    ]);
});
Route::get('/products/{params}', function (string $params) {
    $items = DB::table('items')->where('category', $params)->get();
    if (!sizeof($items)) {
        return redirect('/');
    }
    return view('products', [
        'items' => $items,
        'param' => $params,
    ]);
});
Route::get('/products/show/{id}', [ItemController::class, 'detail']);

Route::controller(AuthController::class)->prefix('/auth')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/logout', 'logout');
});


// Bagian User
Route::middleware(['auth', 'authorization:customer'])->group(function () {
    Route::get('/profile', [UserController::class, 'userProfile']);
    Route::get('/setting', [UserController::class, 'userSetting']);
    Route::post('/update', [UserController::class, 'userUpdate']);

    Route::get('/orders', [OrderController::class, 'userShow']);
    Route::get('/orders/show/{id}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/carts/add', [CartController::class, 'addToCart']);
    Route::post('/carts/update', [CartController::class, 'updateCart']);
    Route::post('/carts/checkout', [CartController::class, 'checkout']);

    Route::get('/shipping/{id}', [ShippingController::class, 'createShippingForm'])->name('shipping.create');
    Route::post('/shipping/create/{id}', [ShippingController::class, 'createShipping']);
    Route::get('/shipping/show/{id}', [ShippingController::class, 'showShippingInfo'])->name('shipping.show');
    Route::post('/shipping/update/{id}', [ShippingController::class, 'updateShippingInfo'])->name('shipping.update');

    Route::get('/payment/process/{id}', [PaymentController::class, 'processPayment'])->name('payment.process');

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlists/add', [WishlistController::class, 'addToWishlist']);
    Route::post('/wishlists/update', [WishlistController::class, 'updateWishlist']);
    Route::delete('/wishlists/delete/{id}', [WishlistController::class, 'destroy'])->name('delete')->middleware('auth');
});


Route::middleware(['auth', 'authorization:admin'])->prefix('/admin')->group(function () {
    Route::get('/', function () {
        return redirect('/admin/products/page/1');
    });
    Route::controller(ItemController::class)->prefix('/products')->group(function () {
        Route::get('/page/{page}', 'index')->name('products');
        Route::get('/show/{id}', 'show');
        Route::get('/create', 'create');
        Route::get('/edit/{id}', 'edit');
        Route::post('/update', 'update');
        Route::post('/store', 'store');
        Route::get('/delete/{id}', 'delete');
    });
    Route::controller(UserController::class)->prefix('/users')->group(function () {
        Route::get('/', 'index');
        Route::get('/show/{id}', 'show');
        Route::get('/create', 'create');
        Route::get('/edit/{id}', 'edit');
        Route::post('/update', 'update');
        Route::post('/store', 'store');
        // Route::get('/delete/{id}', 'delete');
    });
    Route::controller(OrderController::class)->prefix('/orders')->group(function () {
        Route::get('/', 'index');
        Route::get('/changeStatus/{id}/{status}', 'changeStatus');
    });
    Route::get('/dashboard', function () {
        return view(
            'admin.dashboard.index',
            [
                'countPendingOrders' => OrderController::pendingOrders(),
            ]
        );
    });
});
