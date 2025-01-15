<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\dashboard\OutletManagementRequest;

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

        return view('dashboard.outlet-management.index', compact('Outlets', 'totalOutlets'));
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
            $outlet = Outlet::where('slug', $id)->orWhere('id', $id)->firstOrFail();

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

    public function edit(Outlet $outlet)
    {
        return view('dashboard.outlet-management.edit', compact('outlet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OutletManagementRequest $request, Outlet $outlet)
    {
        $validatedData = $request->validated();

        if (auth()->user()->role != 'superadmin') {
            if ($outlet->id != auth()->user()->outlet_id) {
                return redirect()
                    ->back()
                    ->withErrors(['error' => 'Anda tidak memiliki akses untuk mengubah outlet ini.']);
            }
        }

        if (isset($validatedData['delete_image']) && $validatedData['delete_image'][0] == $outlet->id) {
            if ($outlet->image_path) $this->imageUploadService->deleteImage($outlet->image_path);
            $validatedData['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($outlet->image_path) {
                $this->imageUploadService->deleteImage($outlet->image_path);
            }
            $validatedData['image_path'] = $this->imageUploadService->uploadImage($validatedData['image'], 'outlets');
        }

        $outlet->update($validatedData);

        return redirect()
            ->back()
            ->with('success', 'Outlet ' . $outlet->name . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outlet $outlet)
    {
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
