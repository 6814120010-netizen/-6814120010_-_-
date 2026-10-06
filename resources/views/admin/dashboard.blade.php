<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Retail Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">Admin System</a>
            <div class="d-flex align-items-center text-white">
                <span class="me-3">ผู้ดูแลระบบ: <strong>{{ Auth::user()->name }}</strong></span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-danger btn-sm" type="submit">ออกจากระบบ</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h3 class="fw-bold mb-4">ภาพรวมระบบ (Dashboard)</h3>

        <!-- Card สรุปข้อมูล -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white border-0 shadow-sm p-3">
                    <p class="small mb-1">จำนวนลูกค้าทั้งหมด</p>
                    <h2 class="fw-bold mb-1">{{ \App\Models\User::where('role', 'customer')->count() }}</h2>
                    <p class="small text-white-50 m-0">บัญชีสมาชิกในระบบ</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white border-0 shadow-sm p-3">
                    <p class="small mb-1">จำนวนพนักงาน</p>
                    <h2 class="fw-bold mb-1">{{ \App\Models\User::whereIn('role', ['employee', 'manager'])->count() }}</h2>
                    <p class="small text-white-50 m-0">ผู้ใช้งานสิทธิ์ Employee / Manager</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark border-0 shadow-sm p-3">
                    <p class="small mb-1">จำนวนสินค้าในระบบ</p>
                    <h2 class="fw-bold mb-1">{{ \App\Models\Product::count() }}</h2>
                    <p class="small text-dark-50 m-0">รายการสินค้าพร้อมขาย</p>
                </div>
            </div>
        </div>

        <!-- เมนูดำเนินการด่วน -->
        <div class="card border-0 shadow-sm p-4">
            <h5 class="fw-bold mb-3">เมนูดำเนินการด่วน</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-primary">จัดการ / เพิ่มพนักงานใหม่</a>
                <!-- ปุ่มจัดการสินค้า -->
                <a href="{{ route('admin.products.index') }}" class="btn btn-dark">จัดการสินค้า</a>
                <a href="{{ route('pos.index') }}" class="btn btn-success">🛒 เปิดหน้าจอขายสินค้า (POS)</a>
                <a href="{{ route('reports.index') }}" class="btn btn-info text-white">📊 ดูรายงานประวัติการขาย</a>
                <a href="{{ route('profile.index') }}" class="btn btn-warning text-dark me-2">
                    👤 โปรไฟล์และแต้มสะสม
                </a>
            </div>
        </div>
    </div>
</body>
</html>