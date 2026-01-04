@extends('layout')

@section('content')
<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">จัดการบุคลากร (Staffs)</h3>
            <small class="text-muted">ดูแลสิทธิ์การใช้งานและข้อมูลเจ้าหน้าที่</small>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addStaffModal" style="background-color: var(--primary-color); border:none;">
            <i class="fas fa-user-plus me-2"></i> เพิ่มเจ้าหน้าที่
        </button>
    </div>

    <div class="row g-4">
        @foreach($staffs as $staff)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 m-0">
                    @if($staff->role == 'Super Admin')
                        <div class="bg-primary text-white px-3 py-1 rounded-bottom-start small shadow-sm">
                            <i class="fas fa-crown me-1"></i> Super Admin
                        </div>
                    @else
                        <div class="bg-secondary bg-opacity-10 text-secondary px-3 py-1 rounded-bottom-start small">
                            <i class="fas fa-user-shield me-1"></i> Librarian
                        </div>
                    @endif
                </div>

                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px; font-size: 1.5rem; color: var(--primary-color);">
                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                    </div>
                    
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $staff->name }}</h6>
                        <small class="text-muted d-block text-truncate">{{ $staff->email }}</small>
                        <small class="text-muted"><i class="fas fa-phone-alt me-1" style="font-size: 0.7rem;"></i> {{ $staff->phone }}</small>
                    </div>

                    <div class="dropdown ms-2">
                        <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $staff->id }}">
                                    <i class="fas fa-edit me-2 text-warning"></i> แก้ไขข้อมูล
                                </button>
                            </li>
                            <li>
                                <form action="{{ route('staffs.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบบัญชีนี้?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger">
                                        <i class="fas fa-trash me-2"></i> ลบบัญชี
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card-footer bg-light border-0 py-2">
                    <small class="text-muted" style="font-size: 0.75rem;">
                        <i class="far fa-clock me-1"></i> เข้าร่วมเมื่อ: {{ \Carbon\Carbon::parse($staff->created_at)->format('d/m/Y') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editStaffModal{{ $staff->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">แก้ไขข้อมูล: {{ $staff->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('staffs.update', $staff->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="form-label">ชื่อ-นามสกุล</label>
                                <input type="text" name="name" class="form-control" value="{{ $staff->name }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">อีเมล (Login)</label>
                                <input type="email" name="email" class="form-control" value="{{ $staff->email }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $staff->phone }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ตำแหน่ง</label>
                                    <select name="role" class="form-select">
                                        <option value="Librarian" {{ $staff->role == 'Librarian' ? 'selected' : '' }}>Librarian</option>
                                        <option value="Super Admin" {{ $staff->role == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-danger">เปลี่ยนรหัสผ่าน (เว้นว่างถ้าไม่เปลี่ยน)</label>
                                <input type="password" name="password" class="form-control" placeholder="ตั้งรหัสผ่านใหม่...">
                            </div>

                            <button type="submit" class="btn btn-warning w-100">บันทึกการแก้ไข</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">เพิ่มเจ้าหน้าที่ใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('staffs.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">ชื่อ-นามสกุล</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">อีเมล (Login)</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ตำแหน่ง</label>
                            <select name="role" class="form-select">
                                <option value="Librarian">Librarian (บรรณารักษ์)</option>
                                <option value="Super Admin">Super Admin (ผู้ดูแล)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">รหัสผ่านเริ่มต้น</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" style="background-color: var(--primary-color); border:none;">บันทึกข้อมูล</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection