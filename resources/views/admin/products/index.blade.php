<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้า - Retail Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">Admin System</a>
            <div class="d-flex align-items-center text-white">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm me-3">กลับหน้า Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-danger btn-sm" type="submit">ออกจากระบบ</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h3 class="fw-bold m-0">รายการสินค้าทั้งหมด</h3>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ เพิ่มสินค้าใหม่</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Card สำหรับกรองหมวดหมู่ -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label class="fw-bold small mb-0">กรองตามหมวดหมู่:</label>
                    </div>
                    <div class="col-auto">
                        <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 200px;">
                            <option value="">-- แสดงทุกหมวดหมู่ --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(request('category_id'))
                        <div class="col-auto">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">ล้างตัวกรอง</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- ตารางแสดงรายการสินค้า -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">รูปภาพ</th>
                                <th>รหัสสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>หมวดหมู่</th>
                                <th>ราคาขาย</th>
                                <th>คงเหลือ</th>
                                <th class="text-end pe-4">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $item)
                                <tr>
                                    <td class="ps-4">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" class="rounded border" width="50" height="50" style="object-fit: cover;">
                                        @else
                                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 10px;">ไม่มีรูป</div>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->category->name }}</span></td>
                                    <td class="fw-bold text-success">฿{{ number_format($item->price, 2) }}</td>
                                    <td>
                                        @if($item->stock <= 5)
                                            <span class="badge bg-danger">{{ $item->stock }} ชิ้น</span>
                                        @else
                                            <span class="badge bg-success">{{ $item->stock }} ชิ้น</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.products.edit', $item->id) }}" class="btn btn-outline-warning btn-sm text-dark">แก้ไข</a>
                                            <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST" onsubmit="return confirm('ยืนยันที่จะลบสินค้านี้?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">ลบ</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">ไม่พบข้อมูลสินค้าในหมวดหมู่นี้</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>