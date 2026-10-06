<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนพนักงาน/หัวหน้าร้าน - Retail Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold m-0">เพิ่มบัญชีผู้ใช้งานใหม่</h4>
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary btn-sm">ย้อนกลับ</a>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 mb-4">
                                <ul class="mb-0 ps-3 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.employees.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">ตำแหน่ง <span class="text-danger">*</span></label>
                                <select name="role" class="form-select" required>
                                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>พนักงานทั่วไป (Employee)</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>หัวหน้าร้าน (Manager)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">อีเมลเข้าใช้งาน <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small">กำหนดรหัสผ่านเริ่มต้น <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">บันทึกข้อมูล</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>