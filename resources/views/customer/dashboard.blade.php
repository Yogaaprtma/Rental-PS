<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PS Rental Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fullcalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        .alert {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: rgba(25, 135, 84, 0.1);
            border-left: 4px solid #198754;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
        }
        
        .alert-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
        }
        
        .alert-success .alert-icon {
            background-color: rgba(25, 135, 84, 0.2);
            color: #198754;
        }
        
        .alert-danger .alert-icon {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
        
        @keyframes progress-disappear {
            0% { width: 100%; }
            100% { width: 0%; }
        }
        
        /* Add fade-in animation */
        .alert.fade.show {
            animation: fadeInDown 0.5s ease forwards;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .service-card {
            transition: all 0.3s;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .service-card.selected {
            border-color: #4e73df;
            background-color: #f8f9fc;
        }
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
        #calendar {
            height: 400px;
        }
        .fc-day-today {
            background-color: rgba(78, 115, 223, 0.1) !important;
        }
        .fc-day-disabled {
            background-color: #f8f9fa;
            opacity: 0.5;
        }
        .pricing-box {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .selected-date {
            font-weight: bold;
            color: #4e73df;
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
                        <a class="nav-link active" href="{{ route('home.customer') }}">Dashboard</a>
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

    <!-- Improved success notification -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-relative overflow-hidden mt-4 ms-4 me-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon me-3">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div class="alert-content">
                    <h6 class="alert-heading mb-1 fw-bold">Success!</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <!-- Animated progress bar that autocloses the alert -->
            <div class="progress position-absolute bottom-0 start-0 w-100" style="height: 4px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%; animation: progress-disappear 5s linear forwards;"></div>
            </div>
        </div>
    @endif

    <!-- Improved error notification -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-relative overflow-hidden shadow-sm mt-4 ms-4 me-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon me-3">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
                <div class="alert-content">
                    <h6 class="alert-heading mb-1 fw-bold">Error!</h6>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <!-- Animated progress bar that autocloses the alert -->
            <div class="progress position-absolute bottom-0 start-0 w-100" style="height: 4px;">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 100%; animation: progress-disappear 5s linear forwards;"></div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="mb-4">Welcome, {{ Auth::user()->nama }}!</h1>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Rent your favorite PlayStation system. Choose your preferred console and booking date below.
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Choose Rental Service</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($services as $service)
                            <div class="col-md-6 mb-4">
                                <div class="service-card card h-100" data-id="{{ $service->id }}" data-name="{{ $service->name }}" data-price="{{ $service->price }}">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $service->name }}</h5>
                                        <p class="card-text">Rp {{ number_format($service->price, 0, ',', '.') }} per session</p>
                                        <button class="btn btn-outline-primary select-service">Select</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Select Date</h6>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
                
                <div class="pricing-box shadow">
                    <h5 class="mb-3">Booking Summary</h5>
                    <div class="mb-3">
                        <p class="mb-1">Selected Service: <span id="selected-service">None</span></p>
                        <p class="mb-1">Date: <span id="selected-date">None</span></p>
                        <p class="mb-1">Base Price: Rp <span id="base-price">0</span></p>
                        <p class="mb-1">Weekend Surcharge: Rp <span id="weekend-charge">0</span></p>
                        <hr>
                        <p class="fw-bold mb-1">Total Price: Rp <span id="total-price">0</span></p>
                    </div>
                    <form id="booking-form" action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="service_id" id="service_id">
                        <input type="hidden" name="booking_date" id="booking_date">
                        <input type="hidden" name="price" id="price">
                        <button type="submit" class="btn btn-primary w-100" id="book-now-btn" disabled>Continue to Payment</button>
                    </form>
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
    <!-- Fullcalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const closeButton = alert.querySelector('.btn-close');
                    if(closeButton) {
                        closeButton.click();
                    }
                }, 5000);
            });

            let selectedServiceId = null;
            let selectedServiceName = null;
            let selectedServicePrice = 0;
            let selectedDate = null;
            let isWeekend = false;
            const weekendSurcharge = 50000;

            // Initialize calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                selectMirror: true,
                validRange: {
                    start: new Date()
                },
                select: function(info) {
                    // Update selected date
                    selectedDate = info.startStr;
                    
                    // Check if weekend (0 = Sunday, 6 = Saturday)
                    const dayOfWeek = new Date(selectedDate).getDay();
                    isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);
                    
                    // Display selected date
                    document.getElementById('selected-date').textContent = formatDate(selectedDate);
                    
                    // Update pricing
                    updatePricing();
                    
                    // Enable button if service also selected
                    toggleBookButton();
                }
            });
            calendar.render();

            // Service selection
            document.querySelectorAll('.service-card').forEach(card => {
                card.addEventListener('click', function() {
                    // Remove previous selection
                    document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
                    
                    // Add selection to current card
                    this.classList.add('selected');
                    
                    // Update selected service
                    selectedServiceId = this.dataset.id;
                    selectedServiceName = this.dataset.name;
                    selectedServicePrice = parseInt(this.dataset.price);
                    
                    // Update form
                    document.getElementById('service_id').value = selectedServiceId;
                    
                    // Update display
                    document.getElementById('selected-service').textContent = selectedServiceName;
                    
                    // Update pricing
                    updatePricing();
                    
                    // Enable button if date also selected
                    toggleBookButton();
                });
            });

            // Format date for display
            function formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            }
            
            // Calculate and update pricing
            function updatePricing() {
                let basePrice = selectedServicePrice || 0;
                let weekendCharge = isWeekend ? weekendSurcharge : 0;
                let totalPrice = basePrice + weekendCharge;
                
                document.getElementById('base-price').textContent = formatPrice(basePrice);
                document.getElementById('weekend-charge').textContent = formatPrice(weekendCharge);
                document.getElementById('total-price').textContent = formatPrice(totalPrice);
                
                // Update form
                document.getElementById('price').value = totalPrice;
                document.getElementById('booking_date').value = selectedDate;
            }
            
            // Format price for display
            function formatPrice(price) {
                return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
            
            // Toggle book button based on selections
            function toggleBookButton() {
                const bookButton = document.getElementById('book-now-btn');
                bookButton.disabled = !(selectedServiceId && selectedDate);
            }
        });
    </script>
</body>
</html>