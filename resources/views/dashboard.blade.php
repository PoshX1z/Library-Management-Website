@extends('layout')

@section('content')
<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">หน้าสรุปผล</h3>
            <small class="text-muted">ภาพรวมของระบบห้องสมุดวันนี้</small>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" style="background-color: var(--primary-color); border:none;">
            <i class="fas fa-plus me-2"></i> ทำรายการยืมใหม่
        </button>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">หนังสือทั้งหมด</h6>
                        <h3 class="fw-bold m-0">{{ number_format($stats['total_books']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">สมาชิกทั้งหมด</h6>
                        <h3 class="fw-bold m-0">{{ number_format($stats['total_members']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                        <i class="fas fa-hand-holding-open fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">กำลังถูกยืม</h6>
                        <h3 class="fw-bold m-0">{{ number_format($stats['borrowed']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-3 p-3 me-3">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">เกินกำหนดคืน</h6>
                        <h3 class="fw-bold m-0">{{ number_format($stats['overdue']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold m-0 text-secondary"><i class="fas fa-history me-2"></i> รายการล่าสุด</h5>
            <a href="#" class="text-decoration-none small text-primary">ดูทั้งหมด &rarr;</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3">ผู้ยืม</th>
                            <th>หนังสือ</th>
                            <th>วันที่ยืม</th>
                            <th>สถานะ</th>
                            <th class="text-end pe-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_transactions as $t)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-secondary bg-opacity-10 text-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:35px; height:35px;">
                                        <i class="fas fa-user small"></i>
                                    </div>
                                    <span class="fw-bold text-dark">
                                        {{ $t->member ? $t->member->name : 'Unknown' }}
                                    </span>
                                </div>
                            </td>
                            <td>{{ $t->book ? $t->book->title : 'Unknown Book' }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->borrow_date)->format('d/m/Y') }}</td>
                            <td>
                                @if($t->status == 'Borrowed')
                                    <span class="badge bg-warning text-dark bg-opacity-25 px-3 py-2 rounded-pill">กำลังยืม</span>
                                @elseif($t->status == 'Returned')
                                    <span class="badge bg-success bg-opacity-25 text-success px-3 py-2 rounded-pill">คืนแล้ว</span>
                                @elseif($t->status == 'Overdue')
                                    <span class="badge bg-danger bg-opacity-25 text-danger px-3 py-2 rounded-pill">เกินกำหนด</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-25 text-secondary px-3 py-2 rounded-pill">{{ $t->status }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-ellipsis-v"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection