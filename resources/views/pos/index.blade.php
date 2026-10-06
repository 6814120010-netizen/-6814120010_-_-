<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบขายสินค้า (POS) - Retail Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card { cursor: pointer; transition: transform 0.1s; }
        .product-card:hover { transform: scale(1.02); }
        .cart-table-container { max-height: 380px; overflow-y: auto; }
    </style>
</head>
<body class="bg-light">
    
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="#">🛒 ระบบขายสินค้า (POS)</a>
        <div class="d-flex align-items-center text-white">

    <a href="{{ route('pos.customers.create') }}"
       class="btn btn-outline-light btn-sm me-2">
        👤 สมัครสมาชิกให้ลูกค้า
    </a>

    <a href="{{ route('profile.index') }}"
       class="btn btn-outline-light btn-sm me-2">
        👤 โปรไฟล์
    </a>

            <!-- แสดงปุ่ม Dashboard เฉพาะ Admin หรือ หัวหน้า/Manager เท่านั้น -->
            @if(Auth::check() && in_array(Auth::user()->role ?? '', ['admin', 'manager', 'head', 'หัวหน้า']))
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm me-3">กลับหน้า Dashboard</a>
            @endif

            <span class="me-3 small">พนักงาน: <strong>{{ Auth::user()->name ?? 'Cashier' }}</strong></span>
            
            <!-- ฟอร์มปุ่มออกจากระบบ (Logout) -->
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">🚪 ออกจากระบบ</button>
            </form>
        </div>
    </div>
</nav>

    <div class="container-fluid px-4 my-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show fw-bold fs-5 text-center" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show fw-bold text-center" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3">
            <!-- ฝั่งซ้าย: รายการสินค้า -->
            <div class="col-md-7 col-lg-8">
                <div class="card border-0 shadow-sm p-3 mb-3">
                    <form method="GET" action="{{ route('pos.index') }}" class="row g-2">
                        <div class="col-md-4">
                            <select name="category_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- ทุกหมวดหมู่ --</option>
                                <?php foreach ($categories as$cat): ?>
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อสินค้า หรือสแกนบาร์โค้ด..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">ค้นหา</button>
                        </div>
                    </form>
                </div>

                <!-- Grid แสดงสินค้า -->
                <div class="row g-3">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as$p): ?>
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                <div class="card border-0 shadow-sm h-100 product-card p-2 text-center" onclick="addToCart({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $p->price }}, {{$p->stock }})">
                                    @if ($p->image)
                                        <img src="{{ asset('storage/' . $p->image) }}" class="card-img-top rounded mb-2" style="height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center mb-2" style="height: 100px; font-size: 12px;">ไม่มีรูป</div>
                                    @endif
                                    <div class="fw-bold small text-truncate">{{ $p->name }}</div>
                                    <div class="text-muted small">คงเหลือ: {{ $p->stock }}</div>
                                    <div class="text-success fw-bold fs-6 mt-1">฿{{ number_format($p->price, 2) }}</div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5 text-muted">ไม่พบสินค้า</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ฝั่งขวา: ตะกร้าสินค้าและคิดเงิน -->
            <div class="col-md-5 col-lg-4">
                <div class="card border-0 shadow-sm p-3">
                    <h5 class="fw-bold mb-3">รายการสั่งซื้อ</h5>

                    <div class="cart-table-container mb-3">
                        <table class="table align-middle table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>สินค้า</th>
                                    <th style="width: 80px;">จำนวน</th>
                                    <th class="text-end">รวม</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cart-table-body">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">ยังไม่มีสินค้าในตะกร้า</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2 fs-5">
                            <span class="fw-bold">ยอดรวมทั้งสิ้น:</span>
                            <span class="fw-bold text-primary" id="total-amount">฿0.00</span>
                        </div>

                        <form action="{{ route('pos.checkout') }}" method="POST" id="checkout-form">
                            @csrf
                            <input type="hidden" name="cart" id="cart-json">

                            <div class="mb-3 border rounded p-3 bg-light">
                                <label class="form-label small fw-bold">👤 เบอร์สมาชิก</label>
                                <div class="input-group">
                                    <input type="text" id="member-phone" name="member_phone" class="form-control" placeholder="กรอกเบอร์โทรศัพท์สมาชิก">
                                    <button type="button" class="btn btn-outline-primary" onclick="findMember()">ค้นหาสมาชิก</button>
                                </div>
                                <div id="member-result" class="small mt-2 text-muted">ไม่ระบุสมาชิกได้</div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label small fw-bold">รับเงิน (บาท):</label>
                                <input type="number" step="0.01" name="paid_amount" id="paid-amount" class="form-control form-control-lg text-end fw-bold" placeholder="0.00" oninput="calculateChange()" required>
                            </div>

                            <div class="d-flex justify-content-between mb-3 fs-6">
                                <span>เงินทอน:</span>
                                <span class="fw-bold text-danger" id="change-amount">฿0.00</span>
                            </div>

                            <input type="hidden" name="payment_method" value="cash">
                            <button type="submit" id="btn-submit" class="btn btn-success w-100 py-3 fw-bold fs-5" disabled>ชำระเงิน</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script จัดการ ตะกร้าสินค้า -->
    <script>
        let cart = [];

        function addToCart(id, name, price, stock) {
            let item = cart.find(i => i.id === id);
            if (item) {
                if (item.qty + 1 > stock) {
                    alert('สินค้าในสต็อกมีไม่พอ');
                    return;
                }
                item.qty += 1;
            } else {
                cart.push({ id: id, name: name, price: price, qty: 1, stock: stock });
            }
            renderCart();
        }

        function updateQty(id, qty) {
            let item = cart.find(i => i.id === id);
            if (item) {
                let newQty = parseInt(qty);
                if (newQty > item.stock) {
                    alert('สินค้าในสต็อกมีไม่พอ');
                    item.qty = item.stock;
                } else if (newQty <= 0) {
                    removeFromCart(id);
                    return;
                } else {
                    item.qty = newQty;
                }
            }
            renderCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(i => i.id !== id);
            renderCart();
        }

        function renderCart() {
            let tbody = document.getElementById('cart-table-body');
            let total = 0;

            if (cart.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">ยังไม่มีสินค้าในตะกร้า</td></tr>';
                document.getElementById('total-amount').innerText = '฿0.00';
                document.getElementById('cart-json').value = '';
                calculateChange();
                return;
            }

            let html = '';
            cart.forEach(item => {
                let subtotal = item.price * item.qty;
                total += subtotal;
                html += `
                    <tr>
                        <td class="small fw-bold">${item.name}</td>
                        <td>
                            <input type="number" value="${item.qty}" min="1" max="${item.stock}" class="form-control form-control-sm text-center px-1" onchange="updateQty(${item.id}, this.value)">
                        </td>
                        <td class="text-end small fw-bold">฿${subtotal.toFixed(2)}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeFromCart(${item.id})">✕</button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            document.getElementById('total-amount').innerText = '฿' + total.toFixed(2);
            document.getElementById('cart-json').value = JSON.stringify(cart);
            calculateChange();
        }


        let selectedMember = null;

        function findMember() {
            const phone = document.getElementById('member-phone').value.trim();
            const result = document.getElementById('member-result');

            if (!phone) {
                selectedMember = null;
                result.className = 'small mt-2 text-muted';
                result.innerText = 'ไม่ระบุสมาชิกได้';
                return;
            }

            fetch(`{{ route('pos.member') }}?phone=${encodeURIComponent(phone)}`)
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'ไม่พบสมาชิก');
                    return data;
                })
                .then(data => {
                    selectedMember = data.member;
                    result.className = 'small mt-2 text-success fw-bold';
                    result.innerText = `พบสมาชิก: ${data.member.name} | แต้มปัจจุบัน: ${data.member.points}`;
                })
                .catch(error => {
                    selectedMember = null;
                    result.className = 'small mt-2 text-danger fw-bold';
                    result.innerText = error.message;
                });
        }

        function calculateChange() {
            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let paid = parseFloat(document.getElementById('paid-amount').value) || 0;
            let change = paid - total;

            let changeEl = document.getElementById('change-amount');
            let submitBtn = document.getElementById('btn-submit');

            if (cart.length > 0 && paid >= total && total > 0) {
                changeEl.innerText = '฿' + change.toFixed(2);
                changeEl.className = 'fw-bold text-success';
                submitBtn.disabled = false;
            } else {
                changeEl.innerText = '฿' + (change < 0 ? '0.00' : change.toFixed(2));
                changeEl.className = 'fw-bold text-danger';
                submitBtn.disabled = true;
            }
        }
    </script>
</body>
</html>