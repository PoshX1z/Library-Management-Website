@extends('layout')

@section('content')
<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">ข้อความติดต่อ (Contacts)</h3>
            <small class="text-muted">กล่องข้อความจากผู้ใช้งานและสมาชิก</small>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addContactModal" style="background-color: var(--primary-color); border:none;">
            <i class="fas fa-paper-plane me-2"></i> สร้างข้อความใหม่
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @if($contacts->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                        <p>ไม่มีข้อความใหม่</p>
                    </div>
                @endif

                @foreach($contacts as $contact)
                <div class="list-group-item list-group-item-action p-4 border-bottom {{ $contact->is_read ? 'bg-light' : 'bg-white border-start border-4 border-primary' }}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            @if(!$contact->is_read)
                                <span class="badge bg-primary me-2">New</span>
                            @endif
                            <h6 class="fw-bold mb-0 text-dark">{{ $contact->subject }}</h6>
                        </div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($contact->created_at)->diffForHumans() }}</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="d-block text-muted fw-bold mb-1">
                                <i class="fas fa-user-circle me-1"></i> {{ $contact->name }} 
                                <span class="fw-normal text-secondary">&lt;{{ $contact->email }}&gt;</span>
                            </small>
                            <p class="text-secondary mb-0" style="white-space: pre-wrap;">{{ $contact->message }}</p>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editContactModal{{ $contact->id }}">
                                <i class="fas fa-edit"></i> แก้ไข / อ่าน
                            </button>
                            
                            <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบข้อความ?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editContactModal{{ $contact->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold">จัดการข้อความ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('contacts.update', $contact->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label class="form-label">ผู้ส่ง</label>
                                        <input type="text" name="name" class="form-control" value="{{ $contact->name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">อีเมล</label>
                                        <input type="email" name="email" class="form-control" value="{{ $contact->email }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">หัวข้อ</label>
                                        <input type="text" name="subject" class="form-control" value="{{ $contact->subject }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">ข้อความ</label>
                                        <textarea name="message" class="form-control" rows="4">{{ $contact->message }}</textarea>
                                    </div>

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" name="is_read" value="1" {{ $contact->is_read ? 'checked' : '' }}>
                                        <label class="form-check-label">ทำเครื่องหมายว่า "อ่านแล้ว"</label>
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
    </div>
</div>

<div class="modal fade" id="addContactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">สร้างข้อความใหม่ (Test)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contacts.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">ชื่อผู้ติดต่อ</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">อีเมล</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">หัวข้อเรื่อง</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ข้อความ</label>
                        <textarea name="message" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="background-color: var(--primary-color); border:none;">ส่งข้อความ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection