<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิกให้ลูกค้า - Retail Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold m-0">สมัครสมาชิกใหม่ (หน้าร้าน)</h4>
                            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">ย้อนกลับ</a>
                        </div>

                        <p class="text-muted small mb-4">ลงทะเบียนด่วนสำหรับลูกค้า เพื่อใช้สะสมแต้มหน้าร้าน</p>

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 mb-4">
                                <ul class="mb-0 ps-3 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('pos.customers.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small">ชื่อ-นามสกุล ลูกค้า <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="เช่น สมชาย ใจดี" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="เช่น 0812345678" required>
                                <div class="form-text small">* ใช้สำหรับค้นหาสมาชิกและใช้เป็นรหัสผ่านเข้าสู่ระบบเริ่มต้น</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small">อีเมล <span class="text-muted">(ไม่จำเป็นต้องกรอก)</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="customer@email.com">
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold py-2">บันทึกสมัครสมาชิก</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>