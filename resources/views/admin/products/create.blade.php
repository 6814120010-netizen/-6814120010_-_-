<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้าใหม่ - Retail Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold m-0">เพิ่มสินค้าใหม่</h4>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">ย้อนกลับ</a>
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

                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">หมวดหมู่ <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">-- เลือกหมวดหมู่ --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">รหัสสินค้า / บาร์โค้ด <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="เช่น 8850001234567" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">ชื่อสินค้า <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="เช่น น้ำยาซักผ้าสูตรเข้มข้น" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">ราคาขาย (บาท) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" placeholder="0.00" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">จำนวนสต็อกเริ่มต้น <span class="text-danger">*</span></label>
                                    <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small">รูปภาพสินค้า <span class="text-muted">(ไม่จำเป็น)</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">บันทึกสินค้า</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>