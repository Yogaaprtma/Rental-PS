@extends('admin.layouts.main')

@section('title', 'Tambah Layanan')

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
        .form-label {
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        .btn-submit {
            background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(63, 55, 201, 0.3);
        }
        .btn-back {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 500;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
            color: white;
        }
        .help-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light py-2 px-3 rounded shadow-sm">
            <li class="breadcrumb-item">
                <a href="{{ route('home.admin') }}" class="text-primary">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('service.page') }}" class="text-primary">
                    <i class="fas fa-cogs me-1"></i>Manajemen Layanan
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-plus-circle me-1"></i>Tambah Layanan
            </li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Layanan Baru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Form -->
                    <form action="{{ route('service.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text mt-2">
                                <i class="fas fa-info-circle me-1"></i>Masukkan nama layanan yang jelas dan deskriptif
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="price" class="form-label">Harga (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="help-text mt-2">
                                <i class="fas fa-info-circle me-1"></i>Masukkan harga layanan tanpa karakter khusus
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('service.page') }}" class="btn btn-light btn-back">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-submit">
                                <i class="fas fa-save me-2"></i>Simpan Layanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Format currency function
        function formatCurrency(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');
            
            // Add event listener for input formatting
            if (priceInput) {
                priceInput.addEventListener('input', function() {
                    formatCurrency(this);
                });
            }
        });
    </script>
@endsection