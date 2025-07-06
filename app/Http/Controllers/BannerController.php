<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch banners from the database
        $items = \App\Models\Banner::paginate(10);
        $countPendingOrders = \App\Models\Order::where('status', 'pending')->count();

        // Return the view with banners data
        return view('admin.banners.index', compact('items', 'countPendingOrders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countPendingOrders = \App\Models\Order::where('status', 'pending')->count();
        // Return the view for creating a new banner
        return view('admin.banners.create', compact('countPendingOrders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            'redirectUrl' => 'required|url',
        ]);
        $filename = '';
        // Handle the image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $filename = '/storage/images/' . $imagePath; // Store the path to the image
        } else {
            return redirect()->back()->withErrors(['image' => 'Image is required.']);
        }

        // Create a new banner instance
        $banner = new \App\Models\Banner();
        $banner->imageUrl = $filename;
        $banner->redirectUrl = $request->redirectUrl;
        $banner->save();

        // Redirect to the banners index with success message
        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Find the banner by ID
        $item = \App\Models\Banner::findOrFail($id);
        $countPendingOrders = \App\Models\Order::where('status', 'pending')->count();

        // Return the view for editing the banner
        return view('admin.banners.edit', compact('item', 'countPendingOrders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            'redirectUrl' => 'required|url',
        ]);

        // Find the banner by ID
        $banner = \App\Models\Banner::findOrFail($id);
        // Check if the banner has an image and delete it from storage
        if ($banner->imageUrl) {
            $imagePath = public_path($banner->imageUrl);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the old image file
            }
        }

        // Handle the image upload if a new image is provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $banner->imageUrl = '/storage/images/' . $imagePath; // Update the image path
        }

        // Update the redirect URL
        $banner->redirectUrl = $request->redirectUrl;
        $banner->save();

        // Redirect to the banners index with success message
        return redirect('/admin/banners');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the banner by ID
        $banner = \App\Models\Banner::findOrFail($id);
        // Check if the banner has an image and delete it from storage
        if ($banner->imageUrl) {
            $imagePath = public_path($banner->imageUrl);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }
        }
        // Delete the banner
        $banner->delete();

        // Redirect to the banners index with success message
        return redirect('/admin/banners');
    }
}
