<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์และแต้มสะสม - Retail Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark px-4">
    <div style="width: 100%; display: flex; align-items: center;">

        <!-- ชื่อหน้า -->
        <h1 class="navbar-brand mb-0 fs-3">
            👤 โปรไฟล์สมาชิก
        </h1>

        <!-- ปุ่มด้านขวา -->
        <div style="margin-left: auto; display: flex; align-items: center; gap: 10px;">

            @if(auth()->user()->role === 'customer')
                <a href="{{ route('home') }}"
                   class="btn btn-light">
                    🛒 ไปหน้า Dashboard
                </a>

            @elseif(in_array(auth()->user()->role, ['employee', 'manager']))
                <a href="{{ route('pos.index') }}"
                   class="btn btn-light">
                    🛒 ไปหน้า POS
                </a>

            @elseif(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="btn btn-light">
                    📊 ไปหน้า Dashboard
                </a>
            @endif

            <form action="{{ route('logout') }}"
                  method="POST"
                  style="margin: 0;">
                @csrf

                <button type="submit" class="btn btn-danger">
                    🚪 ออกจากระบบ
                </button>
            </form>

        </div>

    </div>
</nav>
        
    <!-- แสดงปุ่ม Dashboard เฉพาะ Admin หรือ หัวหน้า เท่านั้น -->
    @if(Auth::check() && in_array(Auth::user()->role ?? '', ['admin', 'manager', 'head', 'หัวหน้า']))
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">กลับ Dashboard</a>
    @endif
</div>
        </div>
    </nav>

    <div class="container my-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="row g-4">
            <!-- ฝั่งซ้าย: ข้อมูลโปรไฟล์ & แต้มสะสม -->
            <div class="col-md-4">
                <!-- การ์ดแต้มสะสม -->
                <div class="card border-0 shadow-sm bg-warning text-dark mb-3 p-3 text-center">
                    <div class="fs-6 fw-bold text-uppercase">⭐ แต้มสะสมของคุณ</div>
                    <div class="display-4 fw-bold my-2">{{ number_format($user->points) }}</div>
                    <div class="small text-muted">แต้มสะสมจากการซื้อสินค้า (100 บาท = 1 แต้ม)</div>
                </div>

                <!-- การ์ดข้อมูลส่วนตัว -->
                <div class="card border-0 shadow-sm p-3">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">ข้อมูลส่วนตัว</h5>
                    <div class="mb-2">
                        <label class="text-muted small">ชื่อ-นามสกุล:</label>
                        <div class="fw-bold fs-6">{{ $user->name }}</div>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small">อีเมล:</label>
                        <div class="fw-bold">{{ $user->email }}</div>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small">เบอร์โทรศัพท์:</label>
                        <div class="fw-bold">{{ $user->phone ?? 'ยังไม่ได้ระบุ' }}</div>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small">วันที่เป็นสมาชิก:</label>
                        <div class="fw-bold">{{ $user->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- ฝั่งขวา: ประวัติการสั่งซื้อของฉัน -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold m-0">🛍️ ประวัติการสั่งซื้อของฉัน</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>เลขที่ใบเสร็จ</th>
                                        <th>วันที่ซื้อ</th>
                                        <th class="text-end">ยอดรวม</th>
                                        <th class="text-center">รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($orders) > 0): ?>
                                        <?php foreach ($orders as$order): ?>
                                            <tr>
                                                <td class="fw-bold text-primary">{{ $order->order_no }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-end fw-bold">฿{{ number_format($order->total_amount, 2) }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="showOrderDetail({{ $order->id }})">
                                                        🔍 ดูสินค้าที่ซื้อ
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">คุณยังไม่มีประวัติการสั่งซื้อ</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if ($orders->hasPages()): ?>
                        <div class="card-footer bg-white pt-3">
                            {{ $orders->links() }}
                        </div>
                    <?php endif; ?>
<!-- แก้ไขข้อมูลส่วนตัว -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h5 class="card-title fw-bold">✏️ แก้ไขข้อมูลส่วนตัว</h5>
        <form action="{{ route('profile.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">ชื่อ-นามสกุล</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
        </form>
    </div>
</div>

<!-- เปลี่ยนรหัสผ่าน -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h5 class="card-title fw-bold">🔐 เปลี่ยนรหัสผ่าน</h5>
        <form action="{{ route('profile.password', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">รหัสผ่านปัจจุบัน</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสผ่านใหม่ (อย่างน้อย 8 ตัวอักษร)</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning">เปลี่ยนรหัสผ่าน</button>
        </form>
    </div>
</div>

<!-- โซนอันตราย: ลบบัญชีตัวเอง -->
<div class="card border-danger text-danger mt-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title fw-bold">⚠️ ลบบัญชีผู้ใช้</h5>
        <p class="card-text text-muted small mb-3">
            หากคุณทำการลบบัญชี ข้อมูลส่วนตัวและประวัติการซื้อของคุณจะถูกลบถาวรและไม่สามารถกู้คืนได้
        </p>
        
        <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('⚠️ ยืนยันที่จะลบบัญชีของคุณหรือไม่? การกระทำนี้ไม่สามารถยกเลิกได้');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">🗑️ ลบบัญชีของฉัน</button>
        </form>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal แสดงรายละเอียดใบเสร็จ -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">รายการสินค้า ใบเสร็จ: <span id="modal-order-no" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>สินค้า</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-end">ราคารวม</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items-body"></tbody>
                    </table>
                    <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                        <span class="fw-bold">ยอดเงินสุทธิ:</span>
                        <strong id="modal-total" class="fs-5 text-primary"></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showOrderDetail(orderId) {
            fetch(`/reports/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-order-no').innerText = data.order_no;
                    document.getElementById('modal-total').innerText = '฿' + parseFloat(data.total_amount).toFixed(2);

                    let itemsHtml = '';
                    data.items.forEach(item => {
                        let productName = item.product ? item.product.name : 'สินค้าถูกลบ';
                        itemsHtml += `
                            <tr>
                                <td>${productName}</td>
                                <td class="text-center">${item.quantity}</td>
                                <td class="text-end">฿${parseFloat(item.subtotal).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                    document.getElementById('modal-items-body').innerHTML = itemsHtml;

                    var modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
                    modal.show();
                });
        }
    </script>
</body>
</html>