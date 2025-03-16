@extends('admin.layouts.main')

@section('title', 'List Booking')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .card {
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 8px 12px;
        border-radius: 50px;
    }
    
    .badge-success {
        background-color: #d4edda;
        color: #155724;
    }
    
    .badge-warning {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .badge-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .dashboard-header {
        position: relative;
        margin-bottom: 30px;
        padding-bottom: 15px;
    }
    
    .dashboard-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(to right, #6a11cb, #2575fc);
        border-radius: 3px;
    }
    
    .dashboard-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        flex: 1;
        min-width: 200px;
        padding: 20px;
        border-radius: 12px;
        color: white;
    }
    
    .stat-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: bold;
    }
    
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .text-gradient {
        background: linear-gradient(to right, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .breadcrumb {
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "\f105";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: #6c757d;
    }
    
    .breadcrumb-item a {
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .breadcrumb-item a:hover {
        color: #0056b3;
    }
    
    .breadcrumb-item.active {
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4 px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light py-2 px-3 rounded shadow-sm">
            <li class="breadcrumb-item">
                <a href="{{ route('home.admin') }}" class="text-primary">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-shopping-cart me-1"></i>List Booking
            </li>
        </ol>
    </nav>

    <h2 class="dashboard-header text-center mb-5">
        <span class="text-gradient">Daftar Booking</span>
    </h2>
    
    <!-- Dashboard Stats -->
    <div class="dashboard-stats">
        <div class="stat-card bg-primary">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-number">{{ $bookings->where('status', 'success')->count() }}</div>
            <div class="stat-label">Booking Sukses</div>
        </div>
        
        <div class="stat-card bg-warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $bookings->where('status', 'pending')->count() }}</div>
            <div class="stat-label">Menunggu Pembayaran</div>
        </div>
        
        <div class="stat-card bg-danger">
            <div class="stat-icon">
                <i class="fas fa-ban"></i>
            </div>
            <div class="stat-number">{{ $bookings->where('status', 'cancelled')->count() }}</div>
            <div class="stat-label">Dibatalkan</div>
        </div>
        
        <div class="stat-card bg-success">
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-number">Rp{{ number_format($bookings->where('status', 'success')->sum('price'), 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
    
    <div class="card shadow-lg p-3 mb-5 bg-white rounded animated fadeIn">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i> Detail Booking</h5>
            <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Cetak
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="bookingTable" class="table table-striped table-hover dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>Layanan</th>
                            <th>Tanggal Booking</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Order ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $index => $booking)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>{{ $booking->user->nama }}</div>
                                </div>
                            </td>
                            <td>{{ $booking->user->email }}</td>
                            <td>{{ $booking->user->no_telp }}</td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                    {{ $booking->user->alamat }}
                                </span>
                            </td>
                            <td>{{ $booking->service->name }}</td>
                            <td>
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                            </td>
                            <td>
                                <span class="fw-bold">Rp{{ number_format($booking->price, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if ($booking->status == 'success')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle me-1"></i> Berhasil
                                    </span>
                                @elseif ($booking->status == 'pending')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock me-1"></i> Menunggu
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle me-1"></i> Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($booking->midtrans_order_id)
                                    <code>{{ $booking->midtrans_order_id }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bookingTable').DataTable({
            responsive: true,
            language: {
                search: "<i class='fas fa-search'></i>",
                searchPlaceholder: "Cari data booking...",
                paginate: {
                    next: '<i class="fas fa-chevron-right"></i>',
                    previous: '<i class="fas fa-chevron-left"></i>'
                },
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ada data yang cocok",
            },
            dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex align-items-center"f>>rtip'
        });
    });
</script>
@endsection