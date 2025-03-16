@extends('admin.layouts.main')

@section('title', 'Dashboard Admin')

@section('styles')
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
    </style>
@endsection

@section('content') 

    <!-- Improved success notification -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-relative overflow-hidden" role="alert">
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
        <div class="alert alert-danger alert-dismissible fade show position-relative overflow-hidden shadow-sm" role="alert">
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

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4">Dashboard Admin</h2>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Layanan</h6>
                        <h2 class="mb-0 mt-2">{{ \App\Models\Service::count() }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Pengguna</h6>
                        <h2 class="mb-0 mt-2">{{ \App\Models\User::count() }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Booking</h6>
                        <h2 class="mb-0 mt-2">{{ \App\Models\Booking::count() }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Pending</h6>
                        <h2 class="mb-0 mt-2">{{ \App\Models\Booking::where('status', 'pending')->count() }}</h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card button-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title">Kelola Layanan</h5>
                            <p class="card-text text-muted">Tambah, edit, atau hapus layanan rental PS.</p>
                        </div>
                        <div class="bg-primary rounded-circle p-3 text-white">
                            <i class="fas fa-gamepad fa-2x"></i>
                        </div>
                    </div>
                    <a href="{{ route('service.page') }}" class="btn btn-primary"><i class="fas fa-cog me-2"></i> Kelola Layanan</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card button-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title">Daftar Transaksi</h5>
                            <p class="card-text text-muted">Lihat semua transaksi booking yang telah dilakukan pengguna.</p>
                        </div>
                        <div class="bg-success rounded-circle p-3 text-white">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                    <a href="{{ route('booking.admin.page') }}" class="btn btn-success"><i class="fas fa-list me-2"></i> Lihat Transaksi</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booking Terbaru</h5>
                        <a href="{{ route('booking.admin.page') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pengguna</th>
                                    <th>Layanan</th>
                                    <th>Tanggal</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Booking::with(['user', 'service'])->latest()->take(5)->get() as $booking)
                                <tr>
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->user->nama }}</td>
                                    <td>{{ $booking->service->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($booking->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($booking->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @elseif($booking->status == 'success')
                                        <span class="badge bg-success">Success</span>
                                        @elseif($booking->status == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                        @else
                                        <span class="badge bg-secondary">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data booking</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Table -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Layanan Populer</h5>
                        <a href="{{ route('service.page') }}" class="btn btn-sm btn-primary">Kelola</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Layanan</th>
                                    <th>Harga</th>
                                    <th>Booking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Service::withCount('bookings')->orderBy('bookings_count', 'desc')->take(5)->get() as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                    <td>{{ $service->bookings_count }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data layanan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pengguna Aktif</h5>
                        <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Booking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\User::withCount('bookings')->orderBy('bookings_count', 'desc')->take(5)->get() as $user)
                                <tr>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->bookings_count }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data pengguna</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
        });
    </script>
@endsection