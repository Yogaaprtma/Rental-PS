<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function listBooking()
    {
        $bookings = Booking::with(['user', 'service'])->get();
        return view('admin.list-booking', compact('bookings'));
    }

    public function service()
    {
        $services = Service::all();
        return view('admin.manage-service.index', compact('services'));
    }
}