@extends('layout')

@section('content')
<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">จดบันทึก (Notes)</h3>
            <small class="text-muted">บันทึกงานและแจ้งเตือนสำหรับเจ้าหน้าที่</small>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal" style="background-color: var(--primary-color); border:none;">
            <i class="fas fa-sticky-note me-2"></i> สร้างโน้ตใหม่
        </button>
    </div>

    <h5 class="fw-bold text-secondary mb-3"><i class="fas fa-thumbtack me-2"></i> รายการที่ต้องทำ</h5>
    <div class="row g-3 mb-5">
        @if($active_notes->isEmpty())
            <div class="col-12">
                <div class="alert alert-light border-0 shadow-sm text-center py-4 text-muted">
                    <i class="fas fa-check-circle fa-2x mb-2 opacity-50"></i>
                    <p>ไม่มีงานค้าง เยี่ยมมาก!</p>
                </div>
            </div>
        @endif

        @foreach($active_notes as $note)
        <div class="col-md-4 col-lg-3">
            @php
                $borderClass = match($note->priority) {
                    'High' => 'border-danger',
                    'Medium' => 'border-warning',
                    'Low' => 'border-success',
                    default => 'border-secondary'
                };
                $badgeClass = match($note->priority) {
                    'High' => 'bg-danger',
                    'Medium' => 'bg-warning text-dark',
                    'Low' => 'bg-success',
                    default => 'bg-secondary'
                };
            @endphp
            
            <div class="card h-100 shadow-sm border-start border-4 {{ $borderClass }} position-relative hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge {{ $badgeClass }} bg-opacity-75 rounded-pill" style="font-size: 0.7rem;">{{ $note->priority }}</span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle p-1" style="width: 24px; height: 24px;" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v small text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editNoteModal{{ $note->id }}">
                                        <i class="fas fa-edit me-2 text-warning"></i> แก้ไข
                                    </button>
                                </li>
                                <li>
                                    <form action="{{ route('notes.update', $note->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="toggle_status" value="1">
                                        <button class="dropdown-item text-success">
                                            <i class="fas fa-check me-2"></i> ทำเสร็จแล้ว
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger">
                                            <i class="fas fa-trash me-2"></i> ลบ
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-dark">{{ $note->title }}</h6>
                    <p class="small text-muted mb-3" style="white-space: pre-line;">{{ $note->content }}</p>
                    
                    <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-2">
                        <small class="text-secondary" style="font-size: 0.75rem;">
                            <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editNoteModal{{ $note->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">แก้ไขบันทึก</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('notes.update', $note->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">หัวข้อ</label>
                                <input type="text" name="title" class="form-control" value="{{ $note->title }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ความสำคัญ</label>
                                <select name="priority" class="form-select">
                                    <option value="High" {{ $note->priority == 'High' ? 'selected' : '' }}>High (ด่วนมาก)</option>
                                    <option value="Medium" {{ $note->priority == 'Medium' ? 'selected' : '' }}>Medium (ปานกลาง)</option>
                                    <option value="Low" {{ $note->priority == 'Low' ? 'selected' : '' }}>Low (ทั่วไป)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">รายละเอียด</label>
                                <textarea name="content" class="form-control" rows="4" required>{{ $note->content }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-warning w-100">บันทึกการแก้ไข</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <h5 class="fw-bold text-secondary mb-3"><i class="fas fa-check-double me-2"></i> ประวัติงานที่เสร็จแล้ว</h5>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($completed_notes as $note)
                <div class="list-group-item d-flex justify-content-between align-items-center opacity-50 bg-light">
                    <div>
                        <span class="text-decoration-line-through fw-bold text-muted">{{ $note->title }}</span>
                        <small class="d-block text-muted">{{ Str::limit($note->content, 50) }}</small>
                    </div>
                    <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('ลบถาวร?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm text-danger p-0 border-0"><i class="fas fa-times"></i></button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">สร้างโน้ตใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('notes.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">หัวข้อ</label>
                        <input type="text" name="title" class="form-control" placeholder="เช่น สั่งซื้อหนังสือเพิ่ม..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ความสำคัญ</label>
                        <select name="priority" class="form-select">
                            <option value="Medium">Medium (ปานกลาง)</option>
                            <option value="High">High (ด่วนมาก)</option>
                            <option value="Low">Low (ทั่วไป)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รายละเอียด</label>
                        <textarea name="content" class="form-control" rows="4" placeholder="รายละเอียด..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="background-color: var(--primary-color); border:none;">บันทึก</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection