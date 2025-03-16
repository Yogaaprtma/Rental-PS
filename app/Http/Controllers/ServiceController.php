<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function createService()
    {
        return view('admin.manage-service.create');
    }

    public function storeService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('service.create')
                ->withErrors($validator)
                ->withInput();
        }

        Service::create([
            'name' => $request->name,
            'price' => $request->price
        ]);

        return redirect()->route('service.page')->with('success', 'Service berhasil ditambahkan.');
    }

    public function editService($id)
    {
        $service = Service::find($id);
        return view('admin.manage-service.edit', compact('service'));
    }

    public function updateService(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('service.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $service = Service::findOrFail($id);

        $service->update([
            'name' => $request->name,
            'price' => $request->price
        ]);

        return redirect()->route('service.page')->with('success', 'Service berhasil diperbarui.');
    }

    public function destroyService($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('service.page')->with('success', 'Service berhasil dihapus.');
    }
}