<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - My Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white">

    <!-- Header / Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 text-primary" href="{{ route('home') }}">My Store</a>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-outline-primary px-3">เข้าสู่ระบบ</a>
            </div>
        </div>
    </nav>

    <!-- Form Register -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm p-3">
                    <div class="card-body">
                        <h3 class="fw-bold text-dark mb-1 text-center">สมัครสมาชิก</h3>
                        <p class="text-muted text-center mb-4 small">กรอกข้อมูลเพื่อสร้างบัญชีสมาชิกใหม่</p>

                        <!-- แจ้งเตือนข้อผิดพลาด (Validation Error) -->
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 mb-4">
                                <ul class="mb-0 ps-3 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                       <form action="{{ route('register') }}" method="POST">
    @csrf
    
    <div class="mb-3">
        <label class="form-label text-secondary small fw-bold">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-secondary small fw-bold">อีเมล <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label text-secondary small fw-bold">เบอร์โทรศัพท์</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
    </div>

    <div class="mb-3">
        <label class="form-label text-secondary small fw-bold">รหัสผ่าน (อย่างน้อย 8 ตัวอักษร) <span class="text-danger">*</span></label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-4">
        <label class="form-label text-secondary small fw-bold">ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">สมัครสมาชิก</button>

    <div class="text-center">
        <span class="text-muted small">มีบัญชีผู้ใช้แล้ว?</span>
        <a href="{{ route('login') }}" class="text-primary text-decoration-none small fw-bold ms-1">เข้าสู่ระบบ</a>
    </div>
</form>
        </div>
    </div>

</body>
</html>