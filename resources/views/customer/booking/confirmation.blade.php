<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Confirmation - PS Rental</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        .payment-details {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .countdown {
            font-size: 1.5rem;
            font-weight: bold;
            color: #e74a3b;
        }
        .payment-method-card {
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .payment-method-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .payment-method-card.selected {
            border-color: #4e73df;
            background-color: #f8f9fc;
        }
        .payment-logo {
            height: 40px;
            object-fit: contain;
        }
    </style>
    <!-- Midtrans Client Key -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
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
                        <a class="nav-link" href="{{ route('booking.page') }}">My Bookings</a>
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
                <h1 class="mb-4">Payment Confirmation</h1>
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
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="payment-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                                    <p class="mb-1"><strong>Service:</strong> {{ $booking->service->name }}</p>
                                    <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('l, F j, Y') }}</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p class="mb-1"><strong>Customer:</strong> {{ $booking->user->nama }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $booking->user->email }}</p>
                                    <p class="mb-1"><strong>Phone:</strong> {{ $booking->user->no_telp }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Base Price:</strong></p>
                                    @if(\Carbon\Carbon::parse($booking->booking_date)->isWeekend())
                                        <p class="mb-1"><strong>Weekend Surcharge:</strong></p>
                                    @endif
                                    <p class="mb-1 fw-bold"><strong>Total Price:</strong></p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-1">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</p>
                                    @if(\Carbon\Carbon::parse($booking->booking_date)->isWeekend())
                                        <p class="mb-1">Rp 50.000</p>
                                    @endif
                                    <p class="mb-1 fw-bold">Rp {{ number_format($booking->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Payment Deadline</h5>
                                    <p class="mb-0">Please complete your payment within <span class="countdown" id="countdown">24:00:00</span> to confirm your booking.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment Method</h6>
                    </div>
                    <div class="card-body">
                        <button id="pay-button" class="btn btn-primary btn-lg btn-block w-100 mb-3">
                            Pay Now
                        </button>
                        <div class="text-center mb-3">
                            <small class="text-muted">Powered by</small>
                            <img src="https://midtrans.com/assets/images/logo-midtrans-color.png" alt="Midtrans" class="d-block mx-auto mt-2" style="height: 30px;">
                        </div>
                        <div class="text-center">
                            <small class="text-muted">All transactions are secure and encrypted.</small>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Need Help?</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">If you have any questions or issues with your payment, please contact our customer support:</p>
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i> support@psrental.com</p>
                        <p class="mb-1"><i class="fas fa-phone me-2"></i> +6281234567890</p>
                    </div>
                </div>
            </div>
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
    
    <script>
        // Countdown timer function
        function startCountdown() {
            const deadline = new Date();
            deadline.setHours(deadline.getHours() + 24); // 24 hours from now
            
            const countdownElement = document.getElementById('countdown');
            
            function updateCountdown() {
                const now = new Date();
                const timeLeft = deadline - now;
                
                if (timeLeft <= 0) {
                    countdownElement.textContent = "00:00:00";
                    return;
                }
                
                const hours = Math.floor(timeLeft / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                
                countdownElement.textContent = 
                    (hours < 10 ? "0" + hours : hours) + ":" +
                    (minutes < 10 ? "0" + minutes : minutes) + ":" +
                    (seconds < 10 ? "0" + seconds : seconds);
            }
            
            // Initial call
            updateCountdown();
            
            // Update every second
            setInterval(updateCountdown, 1000);
        }
        
        // Start countdown on page load
        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();

            // Make Bootstrap modal available globally
            var bootstrap = window.bootstrap;
            
            // Setup Midtrans click handler
            document.getElementById('pay-button').addEventListener('click', function() {
                // Call Midtrans SNAP
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        // Perbarui status secara manual
                        fetch('{{ route("payment.update.status", $booking->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Create and show success modal
                                const successModal = document.createElement('div');
                                successModal.className = 'modal fade';
                                successModal.id = 'paymentSuccessModal';
                                successModal.setAttribute('tabindex', '-1');
                                successModal.setAttribute('aria-hidden', 'true');
                                
                                successModal.innerHTML = `
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-check-circle me-2"></i>Payment Successful
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center py-4">
                                                <div class="mb-4">
                                                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                                                </div>
                                                <h4 class="mb-3">Thank You!</h4>
                                                <p class="mb-4">Your payment was completed successfully. Your booking is now confirmed.</p>
                                                <div class="d-grid">
                                                    <button type="button" class="btn btn-primary" id="viewBookingsBtn">
                                                        View My Bookings
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                                document.body.appendChild(successModal);
                                
                                // Initialize and show the modal
                                const bsModal = new bootstrap.Modal(successModal);
                                bsModal.show();
                                
                                // Set up redirect when "View My Bookings" is clicked
                                document.getElementById('viewBookingsBtn').addEventListener('click', function() {
                                    window.location.href = '{{ route("booking.page") }}';
                                });
                                
                                // Auto redirect after 3 seconds
                                setTimeout(function() {
                                    window.location.href = '{{ route("booking.page") }}';
                                }, 3000);
                            }
                        });
                    },
                    onPending: function(result) {
                        // Tetap di halaman yang sama dengan pesan pending
                        alert('Pembayaran menunggu. Silakan selesaikan pembayaran Anda.');
                    },
                    onError: function(result) {
                        // Tetap di halaman yang sama dengan pesan error
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        // Tetap di halaman yang sama dengan pesan penutupan
                        alert('Jendela pembayaran ditutup. Booking Anda masih dalam status menunggu.');
                    }
                });
            });
        });
    </script>
</body>
</html>