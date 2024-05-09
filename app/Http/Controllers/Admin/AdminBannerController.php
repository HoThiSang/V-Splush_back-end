<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;

class AdminBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $banner;

    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
    }
    public function index()
    {
        $allBanner = $this->banner->getAllBanner();
        if ($allBanner !== null) {
            return response()->json([
                'status' => 'success',
                'message' => 'Banner retrieved successfully',
                'data' => $allBanner
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve banners',
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     // $validator = Validator::make($request->all(), [
    //     //     'title' => 'required|min:3',
    //     //     'content' => 'required',
    //     //     'image_url' => 'image|mimes:jpeg,png,jpg|max:5000'
    //     // ]);
    //     // if ($validator->fails()) {
    //     //     return back()->withErrors($validator)->withInput();
    //     // }

    //     $banner = Banner::find($id);
    //     $banner->title = $request->input('title');


    //     $banner->content = $request->input('content');
    //     $banner->sub_title = $request->input('sub_title');
    //     $banner->image_name = $request->input('image_name');
    //     if ($request->hasFile('image_url')) {

    //         $file = $request->file('image_url');
    //         $uploadedFileUrl = Cloudinary::upload($request->file('image_url')->getRealPath(), [
    //             'folder' => 'upload_image'
    //         ])->getSecurePath();
    //         $publicId = Cloudinary::getPublicId();
    //         $extension = $file->getClientOriginalName();
    //         $filename = time() . '_' . $extension;
    //         $banner->image_url = $uploadedFileUrl;
    //         $banner->image_name = $filename;
    //         $banner->publicId = $publicId;
    //     }
    //     $banner->update();
    //     // return redirect()->route('admin-banner')->with('success', 'Update successfully');
    // }
    public function update(Request $request, $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner not found',
            ], 404);
        }

        $banner->title = $request->input('title');
        $banner->content = $request->input('content');
        $banner->sub_title = $request->input('sub_title');
        $banner->image_name = $request->input('image_name');

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'upload_image'
            ])->getSecurePath();
            $publicId = Cloudinary::getPublicId();
            $filename = time() . '_' . $file->getClientOriginalName();
            $banner->image_url = $uploadedFileUrl;
            $banner->image_name = $filename;
            $banner->publicId = $publicId;
        }

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $uploadedFileUrl = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'upload_image'
            ])->getSecurePath();
            $publicId = Cloudinary::getPublicId();
            $filename = time() . '_' . $file->getClientOriginalName();
            $banner->image_url = $uploadedFileUrl;
            $banner->image_name = $filename;
            $banner->publicId = $publicId;
        } 

        $banner->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Banner updated successfully',
            'data' => $banner
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!empty($id)) {
            $banner = Banner::find($id);
            if ($banner) {
                $deleted = $banner->deleteBannerById($id);
                if ($deleted) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Deleted banner successfully',
                        'data' => $banner
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to delete banner',
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'banner not found',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid banner ID',
            ], 400);
        }
    }
}
