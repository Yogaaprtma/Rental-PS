<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Bookings - PS Rental</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        .status-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }
        .status-pending {
            background-color: #f6c23e;
            color: #fff;
        }
        .status-success {
            background-color: #1cc88a;
            color: #fff;
        }
        .status-cancelled {
            background-color: #e74a3b;
            color: #fff;
        }
        .booking-card {
            transition: all 0.3s;
        }
        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Rental PS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home.customer') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('booking.page') }}">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="mb-4">My Bookings</h1>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            @if($bookings->count() > 0)
                @foreach($bookings as $booking)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="booking-card card shadow h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="m-0">Booking #{{ $booking->id }}</h5>
                            <span class="badge status-badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Service:</strong> {{ $booking->service->name }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('l, F j, Y') }}</p>
                            <p class="mb-1"><strong>Price:</strong> Rp {{ number_format($booking->price, 0, ',', '.') }}</p>
                            <p class="mb-1"><strong>Booked On:</strong> {{ $booking->created_at->format('F j, Y g:i A') }}</p>
                            
                            @if($booking->status == 'pending')
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="fas fa-exclamation-circle"></i> Payment pending. Please complete your payment to confirm this booking.
                                </div>
                            @elseif($booking->status == 'success')
                                <div class="alert alert-success mt-3 mb-0">
                                    <i class="fas fa-check-circle"></i> Payment completed. Your booking is confirmed!
                                </div>
                            @elseif($booking->status == 'cancelled')
                                <div class="alert alert-danger mt-3 mb-0">
                                    <i class="fas fa-times-circle"></i> This booking has been cancelled.
                                </div>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">Transaction ID: {{ $booking->midtrans_order_id ?: 'N/A' }}</small>
                            
                            @if($booking->status == 'pending')
                                <a href="{{ route('booking.confirmation.page', $booking->id) }}" class="btn btn-primary btn-sm">Pay Now</a>
                            @elseif($booking->status == 'success')
                                <span class="badge bg-success">Paid</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> You don't have any bookings yet. <a href="{{ route('home.customer') }}" class="alert-link">Create a booking now!</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 PS Rental Service. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>