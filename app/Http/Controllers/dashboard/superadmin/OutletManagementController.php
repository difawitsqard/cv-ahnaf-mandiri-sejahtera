<?php

namespace App\Http\Controllers\dashboard\superadmin;

use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\dashboard\superadmin\OutletManagementRequest;

class OutletManagementController extends Controller
{
    protected $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = is_numeric($request->perPage) ? $request->perPage : 9;

        if (!empty($request->search)) {
            $Outlets = Outlet::filter()->orderBy('id', 'desc')->paginate($perPage);
            $Outlets->appends(['search' => $request->search]);
        } else {
            $Outlets = Outlet::orderBy('id', 'desc')->paginate($perPage);
        }
        $Outlets->appends(['perPage' => $perPage]);

        $totalOutlets = Outlet::count();

        return view('dashboard.superadmin.outlet-management.index', compact('Outlets', 'totalOutlets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OutletManagementRequest $request)
    {
        $validatedData = $request->validated();

        $slug = Outlet::generateUniqueSlug($validatedData['name']);

        if ($request->hasFile('image')) {
            $validatedData['image_path'] = $this->imageUploadService->uploadImage($request->file('image'), $slug);
        }

        $outlet = Outlet::create($validatedData);

        return redirect()
            ->route('outlet.index')
            ->with('success', 'Outlet ' . $outlet->name . ' berhasil ditambahkan.');
    }

    public function fetch(string $id)
    {
        try {
            $outlet = Outlet::findOrFail($id);

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $outlet,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'status' => false,
                    'code' => 404,
                    'message' => 'Outlet not found',
                ],
                404,
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OutletManagementRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $outlet = Outlet::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($outlet->image_path) {
                $this->imageUploadService->deleteImage($outlet->image_path);
            }
            $validatedData['image_path'] = $this->imageUploadService->uploadImage($validatedData['image'], 'outlets');
        }

        $outlet->update($validatedData);

        return redirect()
            ->route('outlet.index')
            ->with('success', 'Outlet ' . $outlet->name . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Retrieve the specific service instance
        $outlet = Outlet::where('slug', $id)->firstOrFail();

        $directoryPath = public_path('uploads/' . $outlet->slug);
        if (File::exists($directoryPath)) {
            File::deleteDirectory($directoryPath);
        }

        $outlet->delete();

        return redirect()
            ->back()
            ->with('success', 'Outlet ' . $outlet->name . ' berhasil dihapus.');
    }
}
