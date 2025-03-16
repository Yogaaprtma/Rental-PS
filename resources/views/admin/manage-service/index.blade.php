@extends('admin.layouts.main')

@section('title', 'Manajemen Layanan')

@section('styles')
    <style>
        .breadcrumb {
            border-left: 4px solid #4e73df;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            color: #6c757d;
        }
        .breadcrumb-item.active {
            color: #5a5c69;
            font-weight: 500;
        }
        .search-box {
            position: relative;
            margin-bottom: 1rem;
        }
        .search-box input {
            padding-left: 2.5rem;
            border-radius: 2rem;
        }
        .search-box i {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .table-action {
            display: flex;
            gap: 0.5rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        .empty-state i {
            font-size: 3rem;
            color: #d1d3e2;
            margin-bottom: 1rem;
        }
        .price-column {
            font-weight: 500;
            color: #5a5c69;
        }

        .notification-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 999;
            min-width: 300px;
            max-width: 400px;
        }
        
        .alert {
            border-left: 4px solid;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 15px;
            padding: 0.75rem 1rem;
        }
        
        .alert-icon-content {
            display: flex;
            align-items: center;
        }
        
        .alert-success {
            border-left-color: #1cc88a;
            background-color: rgba(28, 200, 138, 0.1);
        }
        
        .alert-danger {
            border-left-color: #e74a3b;
            background-color: rgba(231, 74, 59, 0.1);
        }
        
        /* Animations */
        .animated {
            animation-duration: 0.5s;
            animation-fill-mode: both;
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translate3d(50px, 0, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        .fadeInRight {
            animation-name: fadeInRight;
        }
        
        @keyframes fadeOutRight {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
                transform: translate3d(50px, 0, 0);
            }
        }
        
        .fadeOutRight {
            animation-name: fadeOutRight;
        }
        
        @media (max-width: 768px) {
            .table-action {
                flex-direction: column;
                gap: 0.25rem;
            }
            .d-inline-form {
                margin-top: 0.25rem;
            }
            .notification-container {
                right: 10px;
                left: 10px;
                min-width: unset;
                max-width: unset;
            }
        }
    </style>
@endsection

@section('content')

    <div class="notification-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show animated fadeInRight" role="alert">
                <div class="alert-icon-content">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show animated fadeInRight" role="alert">
                <div class="alert-icon-content">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light py-2 px-3 rounded shadow-sm">
            <li class="breadcrumb-item">
                <a href="{{ route('home.admin') }}" class="text-primary">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-cogs me-1"></i>Manajemen Layanan
            </li>
        </ol>
    </nav>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Layanan Rental</h1>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">Daftar Layanan</h5>
            <a href="{{ route('service.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Tambah Layanan
            </a>
        </div>
        <div class="card-body">
            <!-- Search Box -->
            <div class="row mb-3">
                <div class="col-md-6 col-lg-4">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari layanan...">
                    </div>
                </div>
            </div>
            
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover" id="serviceTable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama Layanan</th>
                            <th width="25%">Harga</th>
                            <th width="30%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $service->name }}</td>
                                <td class="price-column">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                <td>
                                    <div class="table-action">
                                        <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i><span class="d-none d-md-inline ms-1">Edit</span>
                                        </a>
                                        <form action="{{ route('service.destroy', $service->id) }}" method="POST" class="d-inline d-inline-form delete-service-form" data-service-name="{{ $service->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i><span class="d-none d-md-inline ms-1">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="fas fa-database"></i>
                                        <h5>Belum ada data layanan</h5>
                                        <p>Silakan tambahkan layanan baru dengan mengklik tombol "Tambah Layanan" di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination if needed -->
            @if(isset($services) && method_exists($services, 'links') && $services->hasPages())
            <div class="pagination-container">
                {{ $services->links() }}
            </div>
            @endif
        </div>
        <div class="card-footer text-muted text-center">
            <small>Total Layanan: {{ $services->count() }}</small>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Simple search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#serviceTable tbody tr');
            
            searchInput.addEventListener('keyup', function() {
                const searchTerm = searchInput.value.toLowerCase();
                
                tableRows.forEach(row => {
                    if (row.querySelector('td:nth-child(2)')) {
                        const serviceName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        if (serviceName.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });

            // Delete confirmation with SweetAlert2
            const deleteForms = document.querySelectorAll('.delete-service-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    
                    const serviceName = this.getAttribute('data-service-name');
                    
                    Swal.fire({
                        title: 'Konfirmasi Penghapusan',
                        text: `Apakah Anda yakin ingin menghapus layanan "${serviceName}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e74a3b',
                        cancelButtonColor: '#858796',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Auto-dismiss notifications after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('fadeInRight');
                    alert.classList.add('fadeOutRight');
                    
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }, 5000);
                
                // Manual dismiss with animation
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        alert.classList.remove('fadeInRight');
                        alert.classList.add('fadeOutRight');
                        
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 500);
                    });
                }
            });
        });
    </script>
@endsection