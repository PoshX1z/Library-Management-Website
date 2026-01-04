@extends('layout')

@section('content')
<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Dashboard</h3>
            <small class="text-muted">ภาพรวมระบบและสถิติประจำวัน</small>
        </div>
        <div class="text-end">
            <span class="badge bg-light text-dark border px-3 py-2 shadow-sm">
                <i class="far fa-calendar-alt me-2"></i> {{ \Carbon\Carbon::now()->format('D, d M Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="{{ route('books.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 bg-primary text-white hover-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fas fa-book fa-lg"></i>
                            </div>
                            <span class="small bg-white bg-opacity-25 px-2 rounded">Books</span>
                        </div>
                        <h2 class="fw-bold mb-0">{{ number_format($total_books) }}</h2>
                        <small class="text-white-50">หนังสือทั้งหมด</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('transactions.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 bg-warning text-dark hover-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-dark bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fas fa-hand-holding-open fa-lg"></i>
                            </div>
                            <span class="small bg-dark bg-opacity-10 px-2 rounded">Borrows</span>
                        </div>
                        <h2 class="fw-bold mb-0">{{ number_format($active_borrows) }}</h2>
                        <small class="text-dark opacity-75">กำลังถูกยืม</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('purchases.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 bg-success text-white hover-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fas fa-cash-register fa-lg"></i>
                            </div>
                            <span class="small bg-white bg-opacity-25 px-2 rounded">Sales</span>
                        </div>
                        <h2 class="fw-bold mb-0">{{ number_format($today_sales) }} ฿</h2>
                        <small class="text-white-50">ยอดขายวันนี้</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('contacts.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 bg-info text-white hover-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fas fa-envelope fa-lg"></i>
                            </div>
                            @if($unread_messages > 0)
                                <span class="badge bg-danger ms-auto">New</span>
                            @endif
                        </div>
                        <h2 class="fw-bold mb-0">{{ number_format($unread_messages) }}</h2>
                        <small class="text-white-50">ข้อความใหม่</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0"><i class="fas fa-history me-2 text-secondary"></i> การยืม-คืน ล่าสุด</h6>
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-light text-primary">ดูทั้งหมด</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary small">
                            <tr>
                                <th class="ps-4">สมาชิก</th>
                                <th>หนังสือ</th>
                                <th>วันที่ยืม</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_transactions as $t)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $t->member->name ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $t->member->member_code ?? '-' }}</small>
                                </td>
                                <td>{{ $t->book->title }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->borrow_date)->format('d M Y') }}</td>
                                <td>
                                    @if($t->status == 'Borrowed')
                                        <span class="badge bg-warning text-dark bg-opacity-25 rounded-pill">ยืม</span>
                                    @elseif($t->status == 'Returned')
                                        <span class="badge bg-success bg-opacity-25 text-success rounded-pill">คืนแล้ว</span>
                                    @elseif($t->status == 'Overdue')
                                        <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill">เกินกำหนด</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">ยังไม่มีรายการยืม-คืน</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold m-0"><i class="fas fa-rocket me-2 text-secondary"></i> เมนูลัด (Quick Actions)</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('books.index') }}" class="btn btn-light w-100 py-3 border text-secondary hover-lift">
                                <i class="fas fa-plus-circle fa-2x mb-2 text-primary d-block"></i>
                                เพิ่มหนังสือ
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('transactions.index') }}" class="btn btn-light w-100 py-3 border text-secondary hover-lift">
                                <i class="fas fa-book-reader fa-2x mb-2 text-warning d-block"></i>
                                ยืมหนังสือ
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('purchases.index') }}" class="btn btn-light w-100 py-3 border text-secondary hover-lift">
                                <i class="fas fa-shopping-cart fa-2x mb-2 text-success d-block"></i>
                                ขายหนังสือ (POS)
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('schedules.index') }}" class="btn btn-light w-100 py-3 border text-secondary hover-lift">
                                <i class="fas fa-calendar-alt fa-2x mb-2 text-info d-block"></i>
                                ตารางกิจกรรม
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('staffs.index') }}" class="btn btn-light w-100 py-3 border text-secondary hover-lift">
                                <i class="fas fa-users-cog fa-2x mb-2 text-dark d-block"></i>
                                จัดการบุคลากร
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('notes.index') }}" class="btn btn-light w-100 py-3 border text-secondary hover-lift">
                                <i class="fas fa-sticky-note fa-2x mb-2 text-danger d-block"></i>
                                จดบันทึก
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm text-center bg-primary text-white p-4">
                <i class="fas fa-shield-alt fa-3x mb-3 opacity-50"></i>
                <h5 class="fw-bold">System Secure</h5>
                <p class="small text-white-50 mb-0">
                    เข้าสู่ระบบล่าสุด: 
                    {{ Auth::user()->updated_at ? \Carbon\Carbon::parse(Auth::user()->updated_at)->diffForHumans() : 'เมื่อสักครู่' }}
                </p>
                <p class="small text-white-50">Role: {{ Auth::user()->role }}</p>
            </div>

        </div>
    </div>
</div>

<style>
    .hover-lift { transition: transform 0.2s; }
    .hover-lift:hover { transform: translateY(-3px); }
</style>
@endsection