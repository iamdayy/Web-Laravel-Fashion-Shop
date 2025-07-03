<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Models\Banner;
use App\Models\Item;
use App\Models\Offer;
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
    $items = Item::with('rating')
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();
    $categories = Item::select('category')
        ->distinct()
        ->get()
        ->map(function ($item) {
            $category = $item->category;
            $photo = Item::where('category', $category)->value('photo');
            $products_count = Item::where('category', $category)->count();
            return [
                'category' => $category,
                'photo' => $photo,
                'products_count' => $products_count,
            ];
        });
    $categories = $categories->map(function ($category) {
        return (object) $category;
    });
    $offers = Offer::active()
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    return view('home', [
        'route' => Request::route()->getName(),
        'title' => 'NIKKY',
        'items' => $items,
        'banners' => Banner::all(),
        'categories' => $categories,
        'offers' => $offers,
    ]);
})->name('home');

Route::get('/products', function () {
    // Mengambil semua item dari database
    // dan mengembalikannya ke view 'products'
    // Jika tidak ada item, redirect ke halaman utama
    $items = Item::with('rating')->get();
    if ($items->isEmpty()) {
        return redirect('/');
    }
    return view('products', [
        'items' => $items,
        'param' => ''
    ]);
});
Route::get('/products/{params}', function (string $params) {
    $items = Item::with('rating')
        ->where(function ($query) use ($params) {
            $query->where('name', 'like', '%' . $params . '%')
                ->orWhere('description', 'like', '%' . $params . '%')
                ->orWhere('category', 'like', '%' . $params . '%');
        })
        ->get();
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
    Route::get('payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlists/add', [WishlistController::class, 'addToWishlist']);
    Route::post('/wishlists/update', [WishlistController::class, 'updateWishlist']);
    Route::delete('/wishlists/delete/{id}', [WishlistController::class, 'destroy'])->name('delete')->middleware('auth');

    Route::get('/rajaongkir/destination', [ShippingController::class, 'getDestination'])->name('rajaongkir.destination');
    Route::get('/rajaongkir/cost', [ShippingController::class, 'calculateShippingCost'])->name('rajaongkir.cost');
});


Route::middleware(['auth', 'authorization:admin'])->prefix('/admin')->group(function () {
    Route::get('/', function () {
        return redirect('/admin/dashboard');
    });
    Route::controller(BannerController::class)->prefix('/banners')->group(function () {
        Route::get('/', 'index')->name('admin.banners.index');
        Route::get('/create', 'create')->name('admin.banners.create');
        Route::post('/store', 'store')->name('admin.banners.store');
        Route::get('/edit/{id}', 'edit')->name('admin.banners.edit');
        Route::post('/update/{id}', 'update')->name('admin.banners.update');
        Route::get('/delete/{id}', 'destroy')->name('admin.banners.delete');
    });
    Route::controller(OfferController::class)->prefix('/offers')->group(function () {
        Route::get('/', 'index')->name('admin.offers.index');
        Route::get('/create', 'create')->name('admin.offers.create');
        Route::post('/store', 'store')->name('admin.offers.store');
        Route::get('/edit/{id}', 'edit')->name('admin.offers.edit');
        Route::post('/update/{id}', 'update')->name('admin.offers.update');
        Route::get('/delete/{id}', 'destroy')->name('admin.offers.delete');
    });
    Route::controller(ItemController::class)->prefix('/products')->group(function () {
        Route::get('/', 'index')->name('products');
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
        Route::get('/', 'index')->name('admin.orders.index');
        Route::get('/{id}', 'adminShow')->name('admin.orders.show');
        Route::get('/changeStatus/{id}/{status}', 'changeStatus')->name('admin.orders.changeStatus');
    });
    Route::get('/dashboard', function () {
        $mostSoldItems = Item::with(['sold', 'rating'])
            ->whereHas('sold', function ($query) {
                $query->where('total_sold', '>', 0);
                $query->orderBy('total_sold', 'desc');
            })
            ->take(5)
            ->get();
        return view(
            'admin.dashboard.index',
            [
                'items' => $mostSoldItems,
                'countPendingOrders' => OrderController::pendingOrders(),
            ]
        );
    });
});


Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])->name('payment.notification');
