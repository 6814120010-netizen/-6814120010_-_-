<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการสินค้าทั้งหมด - My Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">🛍️ My Store</a>

        <div class="d-flex align-items-center gap-2">
            @auth
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-light btn-sm">
                        👤 โปรไฟล์
                    </a>
                @else
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-light btn-sm">
                        👤 โปรไฟล์
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
                        Dashboard
                    </a>
                @elseif(auth()->user()->isEmployee() || auth()->user()->isManager())
                    <a href="{{ route('pos.index') }}" class="btn btn-success btn-sm">
                        🛒 ไปหน้า POS
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        ออกจากระบบ
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                    เข้าสู่ระบบ
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                    สมัครสมาชิก
                </a>
            @endauth
        </div>
    </div>
</nav>

<div class="container my-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="text-center mb-4">
        <h2 class="fw-bold">🛒 รายการสินค้าทั้งหมด</h2>
        <p class="text-muted">เลือกชมรายการสินค้าและเช็กจำนวนคงเหลือของร้านเราได้เลย</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse ($products as $product)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    @if(!empty($product->image) && file_exists(public_path('storage/' . $product->image)))
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="card-img-top"
                             alt="{{ $product->name }}"
                             style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                             style="height: 180px;">
                            <span>ไม่มีรูปภาพ</span>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column justify-content-between p-3">
                        <div>
                            <h6 class="card-title fw-bold text-dark text-truncate mb-1">
                                {{ $product->name }}
                            </h6>
                            <p class="card-text text-muted small mb-0">
                                คงเหลือ: <strong>{{ $product->stock ?? $product->quantity ?? 0 }}</strong> ชิ้น
                            </p>
                        </div>

                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-primary">
                                ฿{{ number_format($product->price, 2) }}
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                มีสินค้า
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="alert alert-warning d-inline-block px-4">
                    ยังไม่มีรายการสินค้าในระบบขณะนี้
                </div>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>
