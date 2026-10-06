<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานประวัติการขาย - Retail Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="#">📊 รายงานประวัติการขาย</a>
            <div class="d-flex align-items-center">
                <a href="{{ route('pos.index') }}" class="btn btn-success btn-sm me-2">🛒 ไปหน้าขาย (POS)</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">กลับ Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <!-- การ์ดสรุปยอดขาย -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-primary text-white p-3">
                    <div class="small text-white-50">ยอดขายรวม (ตามช่วงเวลาที่เลือก)</div>
                    <div class="fs-2 fw-bold">฿{{ number_format($totalSales, 2) }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-success text-white p-3">
                    <div class="small text-white-50">จำนวนรายการขายทั้งหมด</div>
                    <div class="fs-2 fw-bold">{{ number_format($totalOrders) }} ออเดอร์</div>
                </div>
            </div>
        </div>

        <!-- ฟอร์มค้นหา & ตัวกรองวันที่ -->
        <div class="card border-0 shadow-sm p-3 mb-4">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">ตั้งแต่วันที่:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">ถึงวันที่:</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">ค้นหาเลขที่ใบเสร็จ:</label>
                    <input type="text" name="search" class="form-control" placeholder="เช่น POS-2026..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 me-1">ค้นหา</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">รีเซ็ต</a>
                </div>
            </form>
        </div>

        <!-- ตารางประวัติการซื้อขาย -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>เลขที่ใบเสร็จ</th>
                                <th>วัน-เวลา ที่ขาย</th>
                                <th>พนักงานขาย</th>
                                <th>ชำระโดย</th>
                                <th class="text-end">ยอดรวม</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($orders) > 0): ?>
                                <?php foreach ($orders as$order): ?>
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $order->order_no }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->user->name ?? 'System' }}</td>
                                        <td><span class="badge bg-info text-dark">{{ strtoupper($order->payment_method) }}</span></td>
                                        <td class="text-end fw-bold">฿{{ number_format($order->total_amount, 2) }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary" onclick="showOrderDetail({{ $order->id }})">
                                                🔍 ดูรายละเอียด
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">ไม่พบประวัติการขาย</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($orders->hasPages())
                <div class="card-footer bg-white pt-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal แสดงรายละเอียดใบเสร็จ -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">รายละเอียดใบเสร็จ: <span id="modal-order-no" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3 small">
                        <div class="col-6">วัน-เวลา: <strong id="modal-date"></strong></div>
                        <div class="col-6 text-end">พนักงานขาย: <strong id="modal-user"></strong></div>
                    </div>

                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>สินค้า</th>
                                <th class="text-center">ราคา/ชิ้น</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-end">ราคารวม</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items-body"></tbody>
                    </table>

                    <div class="border-top pt-2">
                        <div class="d-flex justify-content-between">
                            <span>ยอดรวมทั้งหมด:</span>
                            <strong id="modal-total" class="fs-5 text-primary"></strong>
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span>รับเงินมา:</span>
                            <span id="modal-paid"></span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span>เงินทอน:</span>
                            <span id="modal-change"></span>
                        </div>
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
                    document.getElementById('modal-date').innerText = new Date(data.created_at).toLocaleString('th-TH');
                    document.getElementById('modal-user').innerText = data.user ? data.user.name : 'System';
                    document.getElementById('modal-total').innerText = '฿' + parseFloat(data.total_amount).toFixed(2);
                    document.getElementById('modal-paid').innerText = '฿' + parseFloat(data.paid_amount).toFixed(2);
                    document.getElementById('modal-change').innerText = '฿' + parseFloat(data.change_amount).toFixed(2);

                    let itemsHtml = '';
                    data.items.forEach(item => {
                        let productName = item.product ? item.product.name : 'สินค้าถูกลบ';
                        itemsHtml += `
                            <tr>
                                <td>${productName}</td>
                                <td class="text-center">฿${parseFloat(item.price).toFixed(2)}</td>
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