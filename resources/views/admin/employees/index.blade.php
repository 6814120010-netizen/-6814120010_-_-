<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการพนักงาน & หัวหน้าร้าน - Retail Store</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">รายชื่อพนักงาน & หัวหน้าร้าน</h3>
            <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">+ เพิ่มบัญชีใหม่</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ชื่อ-นามสกุล</th>
                                <th>อีเมล</th>
                                <th>เบอร์โทรศัพท์</th>
                                <th>ตำแหน่ง (Role)</th>
                                <th class="text-end pe-4">จัดการ (Admin Only)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $emp)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $emp->name }}</td>
                                    <td>{{ $emp->email }}</td>
                                    <td>{{ $emp->phone ?? '-' }}</td>
                                    <td>
                                        @if($emp->isManager())
                                            <span class="badge bg-warning text-dark">หัวหน้าร้าน (Manager)</span>
                                        @else
                                            <span class="badge bg-info text-dark">พนักงานทั่วไป</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
    <div class="d-flex justify-content-end gap-2">
        @if(auth()->user()->role === 'admin')
            <!-- ปุ่มสลับยศ ( Admin Only ) -->
            <form action="{{ route('admin.employees.changeRole', $emp->id) }}" method="POST">
                @csrf
                @method('PATCH')
                @if($emp->isManager())
                    <input type="hidden" name="role" value="employee">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">ลดเป็นพนักงาน</button>
                @else
                    <input type="hidden" name="role" value="manager">
                    <button type="submit" class="btn btn-outline-warning btn-sm text-dark">เลื่อนเป็นหัวหน้าร้าน</button>
                @endif
            </form>

            <!-- ปุ่มลบบัญชี ( Admin Only ) -->
            <form action="{{ route('admin.employees.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('ยืนยันที่จะลบบัญชีนี้หรือไม่?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">ลบบัญชี</button>
            </form>
        @else
            <!-- ถ้าเป็นหัวหน้า/บทบาทอื่น ให้แสดงข้อความนี้แทน -->
            <span class="text-muted small">ไม่มีสิทธิ์จัดการ</span>
        @endif
    </div>
</td>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">ยังไม่มีข้อมูลในระบบ</td>
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