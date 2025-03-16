<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('customer.dashboard', compact('services'));
    }
}