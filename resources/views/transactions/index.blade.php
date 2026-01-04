@extends('layout')

@section('content')
<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">แก้ไข (ยืม-คืน)</h3>
            <small class="text-muted">จัดการธุรกรรมการยืมและคืนหนังสือ</small>
        </div>
        
        <div class="d-flex gap-2">
            <div class="btn-group shadow-sm">
                <a href="{{ route('transactions.index') }}" class="btn {{ !request('filter') ? 'btn-primary' : 'btn-light' }}" style="{{ !request('filter') ? 'background-color: var(--primary-color); border:none;' : '' }}">
                    กำลังยืม / ค้างส่ง
                </a>
                <a href="{{ route('transactions.index', ['filter' => 'all']) }}" class="btn {{ request('filter') == 'all' ? 'btn-primary' : 'btn-light' }}" style="{{ request('filter') == 'all' ? 'background-color: var(--primary-color); border:none;' : '' }}">
                    ประวัติทั้งหมด
                </a>
            </div>

            <button class="btn btn-success shadow-sm text-white" data-bs-toggle="modal" data-bs-target="#newBorrowModal">
                <i class="fas fa-plus-circle me-2"></i> ยืมหนังสือใหม่
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3">รหัส / สมาชิก</th>
                            <th>หนังสือที่ยืม</th>
                            <th>กำหนดส่ง (Due Date)</th>
                            <th>ค่าปรับ</th>
                            <th>สถานะ</th>
                            <th class="text-end pe-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $t)
                        <tr class="{{ $t->status == 'Overdue' ? 'bg-danger bg-opacity-10' : '' }}">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $t->member->member_code }}</div>
                                <small class="text-muted">{{ $t->member->name }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/books/' . $t->book->image) }}" class="rounded me-2 shadow-sm" style="width: 35px; height: 50px; object-fit: cover;">
                                    <span>{{ $t->book->title }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold {{ \Carbon\Carbon::parse($t->due_date)->isPast() && $t->status != 'Returned' ? 'text-danger' : 'text-dark' }}">
                                    {{ \Carbon\Carbon::parse($t->due_date)->format('d M Y') }}
                                </div>
                                @if(\Carbon\Carbon::parse($t->due_date)->isPast() && $t->status != 'Returned')
                                    @php
                                        $diff = \Carbon\Carbon::parse($t->due_date)->diff(\Carbon\Carbon::now());
                                    @endphp
                                    <small class="text-danger fw-bold" style="font-size: 0.75rem;">
                                        เกินกำหนด {{ $diff->days }} วัน {{ $diff->h }} ชม. {{ $diff->i }} นาที
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($t->fine_amount > 0)
                                    <span class="text-danger fw-bold">{{ number_format($t->fine_amount) }} ฿</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($t->status == 'Borrowed')
                                    <span class="badge bg-warning text-dark bg-opacity-25 px-3 py-2 rounded-pill">กำลังยืม</span>
                                @elseif($t->status == 'Returned')
                                    <span class="badge bg-success bg-opacity-25 text-success px-3 py-2 rounded-pill">คืนแล้ว</span>
                                @elseif($t->status == 'Overdue')
                                    <span class="badge bg-danger bg-opacity-25 text-danger px-3 py-2 rounded-pill">เกินกำหนด</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $t->status }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                        @if($t->status == 'Borrowed' || $t->status == 'Overdue')
                                            <li>
                                                <form action="{{ route('transactions.update', $t->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="action" value="return">
                                                    <button type="submit" class="dropdown-item text-success fw-bold" onclick="return confirm('ยืนยันการรับคืนหนังสือ?')">
                                                        <i class="fas fa-check-circle me-2"></i> รับคืนหนังสือ
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTransactionModal{{ $t->id }}">
                                                    <i class="fas fa-edit me-2 text-warning"></i> แก้ไขข้อมูล
                                                </button>
                                            </li>
                                        @else
                                            <li><span class="dropdown-item text-muted disabled">ไม่มีการดำเนินการ</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editTransactionModal{{ $t->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title fw-bold">แก้ไขข้อมูลการยืม</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('transactions.update', $t->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="edit">

                                            <div class="mb-3">
                                                <label class="form-label text-secondary">ผู้ยืม - หนังสือ</label>
                                                <input type="text" class="form-control bg-light" value="{{ $t->member->name }} - {{ $t->book->title }}" disabled>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">กำหนดส่งคืน (Due Date)</label>
                                                <input type="date" name="due_date" class="form-control" value="{{ $t->due_date }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">ค่าปรับ (ปรับปรุงยอด)</label>
                                                <input type="number" name="fine_amount" class="form-control" value="{{ $t->fine_amount }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">หมายเหตุ</label>
                                                <textarea name="note" class="form-control" rows="2">{{ $t->note }}</textarea>
                                            </div>

                                            <button type="submit" class="btn btn-warning w-100 text-dark">บันทึกการแก้ไข</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
                
                @if($transactions->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                        <p>ไม่มีรายการที่ต้องจัดการในขณะนี้</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newBorrowModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-book-reader me-2"></i> ทำรายการยืมหนังสือ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-bold">เลือกสมาชิกผู้ยืม</label>
                        <select name="member_id" class="form-select form-select-lg" required>
                            <option value="" disabled selected>-- ค้นหาชื่อสมาชิก --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">
                                    {{ $member->member_code }} - {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary fw-bold">เลือกหนังสือ (เฉพาะสถานะว่าง)</label>
                        <select name="book_id" class="form-select form-select-lg" required>
                            <option value="" disabled selected>-- ค้นหาชื่อหนังสือ --</option>
                            @foreach($available_books as $book)
                                <option value="{{ $book->id }}">
                                    {{ $book->title }} (ISBN: {{ $book->isbn }})
                                </option>
                            @endforeach
                        </select>
                        @if($available_books->isEmpty())
                            <small class="text-danger d-block mt-1">* ไม่มีหนังสือว่างให้ยืมในขณะนี้</small>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary fw-bold">กำหนดส่งคืน (Due Date)</label>
                        <input type="date" name="due_date" class="form-control form-control-lg" 
                               value="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}" required>
                        <small class="text-muted">* ค่าเริ่มต้นคือ 7 วันจากวันนี้</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" style="background-color: var(--primary-color); border:none;">
                            ยืนยันการยืม
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection