<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessRequest;
use App\Models\Businesse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    //
    public function registration(){
        $id = Auth::user()->id;

        $business = Businesse::where('user_id', $id)->first() ?? null;

        return view('customer.business.index', compact('business'));
    }

    public function registrationSubmit(BusinessRequest $request){

        $validated = $request->validated();
        $id = Auth::user()->id;
        if ($request->hasFile('fileInput')) {
            $file = $request->file('fileInput');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/uploads');
            $file->move($destinationPath, $fileName);

            $fileUrl = 'storage/uploads/' . $fileName;

            $validated['file_path'] = $fileUrl;
        }
        $validated['user_id'] = $id;

        $business = Businesse::where('user_id', $id)->first();
        if ($business) {
            $business->update($validated);
        } else {
            Businesse::create($validated);
        }

        toastr()->success('Cập nhật thành công.');
        return redirect()->back();
    }
}
