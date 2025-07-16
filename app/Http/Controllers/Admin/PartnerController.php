<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $partners = Partner::select([
                'id', 'full_name', 'company_phone', 'industry','phone',
                'position', 'email', 'tax_code', 'source', 'note'
            ]);

            return DataTables::of($partners)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('partners.edit', $row->id);
                    $deleteUrl = route('partners.destroy', $row->id);

                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-warning me-1" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="' . $deleteUrl . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Bạn có chắc muốn xoá?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" title="Xoá">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.partner.index');
    }

    public function create()
    {
        return view('backend.partner.save');
    }

    public function store(PartnerRequest $request)
    {
        Partner::create($request->validated());
        return redirect()->route('partners.index')->with('success', 'Partner created successfully.');
    }


    public function edit(Partner $partner)
    {
        return view('backend.partner.save', compact('partner'));
    }

    public function update(PartnerRequest $request, Partner $partner)
    {
        $partner->update($request->validated());
        return redirect()->route('partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('partners.index')->with('success', 'Partner deleted successfully.');
    }
}
