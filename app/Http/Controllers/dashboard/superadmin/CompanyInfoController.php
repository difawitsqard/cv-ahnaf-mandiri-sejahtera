<?php

namespace App\Http\Controllers\dashboard\superadmin;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\superadmin\CompanyInfoRequest;

class CompanyInfoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    private function store($data)
    {
        $CompanyInfo = new CompanyInfo();
        $CompanyInfo->fill($data);
        $CompanyInfo->save();

        session()->flash('form-name', 'company-info');
        return redirect()->back()->with('success', 'Informasi perusahaan berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    private function update($data)
    {
        $CompanyInfo = CompanyInfo::first();
        $CompanyInfo->update($data);

        session()->flash('form-name', 'company-info');
        return redirect()->back()->with('success', 'Informasi perusahaan berhasil diperbarui.');
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
