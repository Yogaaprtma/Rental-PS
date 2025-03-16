<?php

namespace App\Http\Controllers;

use Log;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function booking()
    {
        $service = Service::all();
        $bookings = Auth::user()->bookings()->with('service')->get();
        return view('customer.booking.index', compact('service', 'bookings'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date',
        ]);

        $service = Service::find($request->service_id);
        $price = $service->price;

        // Tambahan biaya weekend
        $dayOfWeek = date('N', strtotime($request->booking_date));
        if ($dayOfWeek == 6 || $dayOfWeek == 7) {
            $price += 50000;
        }

        // Simpan data booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'booking_date' => $request->booking_date,
            'price' => $price,
            'status' => 'pending'
        ]);

        // Gunakan order_id unik untuk Midtrans
        $order_id = 'BOOK-' . time() . '-' . $booking->id;

        // Integrasi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $booking->id,
                'gross_amount' => $booking->price,
            ],
            'customer_details' => [
                'email' => Auth::user()->email,
                'first_name' => Auth::user()->nama,
                'phone' => Auth::user()->no_telp,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        // Simpan order_id Midtrans di database
        $booking->midtrans_order_id = $booking->id;
        $booking->save();

        return redirect()->route('booking.confirmation.page', $booking->id)->with('snapToken', $snapToken);
    }

    public function confirmation($id)
    {
        $booking = Booking::with(['user', 'service'])->findOrFail($id);
        
        if ($booking->user_id != Auth::id()) {
            return redirect()->route('booking.page')->with('error', 'Anda tidak memiliki akses ke booking ini');
        }
        
        // Generate Snap Token jika belum ada
        if (!session('snapToken')) {
            $params = [
                'transaction_details' => [
                    'order_id' => $booking->id,
                    'gross_amount' => $booking->price,
                ],
                'customer_details' => [
                    'email' => $booking->user->email,
                    'first_name' => $booking->user->nama,
                    'phone' => $booking->user->no_telp,
                ]
            ];
            
            $snapToken = Snap::getSnapToken($params);
        } else {
            $snapToken = session('snapToken');
        }
        
        return view('customer.booking.confirmation', compact('booking', 'snapToken'));
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $booking->status = 'success';
        $booking->save();
        
        return response()->json(['success' => true]);
    }

    public function midtransCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
    
        if ($hashed == $request->signature_key) {
            // Cari booking berdasarkan midtrans_order_id
            $booking = Booking::where('midtrans_order_id', $request->order_id)->first();
    
            if (!$booking) {
                return response()->json(['message' => 'Booking not found'], 404);
            }
    
            // Update status berdasarkan transaksi Midtrans
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $booking->status = 'success';
            } else if ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                $booking->status = 'cancelled';
            } else if ($request->transaction_status == 'pending') {
                $booking->status = 'pending';
            }
    
            $booking->save();
    
            return response()->json(['message' => 'Success']);
        }
    
        return response()->json(['message' => 'Invalid signature'], 403);
    }
}