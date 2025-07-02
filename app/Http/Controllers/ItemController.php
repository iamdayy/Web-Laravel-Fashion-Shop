<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', '1');

        $items = Item::with(['rating', 'sold'])->paginate(10, ['*'], 'page', (int)$page);
        // return $items[0]->id;
        // example using API (add BASE_ENV=localhost:8001)
        // $endpoint = env('BASE_ENV') . '/api/admin/products?page=' . $page;
        // $client = new Client();

        // $response = $client->request('GET', $endpoint);
        // $items = json_decode($response->getBody(), true);


        return view('admin.products.index', [
            'items' => $items,
            'countPendingOrders' => OrderController::pendingOrders(),
        ]);
    }

    public function show(string $id)
    {
        $items = Item::with(['rating', 'sold'])
            ->where('id', $id)
            ->get();
        return view(
            'admin.products.show',
            [
                'id' => $id,
                'items' => $items,
                'countPendingOrders' => OrderController::pendingOrders(),
            ]
        );
    }
    public function detail(string $id)
    {
        $item = Item::with(['reviews', 'sold', 'rating'])->where('id', $id)->first();
        $relatedItems = Item::where('category', $item->category)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();
        return view(
            'details',
            [
                'id' => $id,
                'item' => $item,
                'relatedItems' => $relatedItems,
            ]
        );
    }

    public function create()
    {
        return view(
            'admin.products.create',
            [
                'countPendingOrders' => OrderController::pendingOrders(),
            ]
        );
    }

    public function store(Request $request)
    {
        $date = Carbon::now()->format('Ymd_His');
        $image = $request->photo;
        $extension = $image->getClientOriginalExtension();
        $newName = "IMG_$date.$extension";
        $image->storePubliclyAs('images/items', $newName, 'public');

        // $image->storePubliclyAs('images/products', $image->getClientOriginalName(), 'public');
        Item::create([
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price,
            'photo' => "/storage/images/items/{$newName}",
            'category' => $request->category,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return redirect('/admin/products');
    }

    // delete products
    public function delete(string $id)
    {
        $item = Item::where('id', $id)->first();
        if (!$item) {
            return redirect('/admin/products')->with('error', 'Item not found');
        }
        // If you want to delete the photo from storage, uncomment the following lines
        if ($item->photo) {
            $filename = substr($item->photo, 1); // Remove the leading slash
            if (Storage::exists('public/' . $filename)) {
                Storage::delete('public/' . $filename);
            }
        }
        // $filename =  substr($item->first()->photo, 1);
        // echo base_path($filename);
        // exit;
        // delete photo
        // Storage::delete(base_path('app/public' . $filename));
        $item->delete();
        return redirect('/admin/products');
    }

    // edit products
    public function edit(string $id)
    {
        $item = DB::table('items')->where('id', $id)->first();

        return view('admin.products.update', [
            'item' => $item,
            'countPendingOrders' => OrderController::pendingOrders(),
        ]);
    }

    // update products
    public function update(Request $request)
    {
        $oldImage = $request->photo;
        $newImage = $request->newPhoto;
        $imgUrl = $oldImage;
        if ($newImage != NULL) {

            $date = Carbon::now()->format('Ymd_His');

            $extension = $newImage->getClientOriginalExtension();
            $newName = "IMG_$date.$extension";
            $newImage->storePubliclyAs('images/products', $newName, 'public');
            $imgUrl = "/storage/images/products/{$newName}";
        }
        Item::where('id', $request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price,
            'photo' => $imgUrl,
            'category' => $request->category,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return redirect('/admin/products');
    }
}
