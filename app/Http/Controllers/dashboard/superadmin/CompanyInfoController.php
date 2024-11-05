<?php

namespace App\Http\Controllers\dashboard\superadmin;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\superadmin\CompanyInfoRequest;

class CompanyInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $CompanyInfo = CompanyInfo::first();
        return view('dashboard.superadmin.company-info.index', compact('CompanyInfo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    private function store($data)
    {
        $CompanyInfo = new CompanyInfo();
        $CompanyInfo->fill($data);
        $CompanyInfo->save();
        return redirect()->back()->with('success', 'Info perusahaan berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    private function update($data)
    {
        $CompanyInfo = CompanyInfo::first();
        $CompanyInfo->update($data);
        return redirect()->back()->with('success', 'Info perusahaan berhasil diperbarui.');
    }

    public function CreateOrUpdate(CompanyInfoRequest $request)
    {
        $CompanyInfo = CompanyInfo::first();
        if (!$CompanyInfo) {
            return $this->store($request->validated());
        } else {
            return $this->update($request->validated());
        }
    }
}
