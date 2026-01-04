@extends('layout')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">ตารางเวลา & กิจกรรม</h3>
            <small class="text-muted">จัดการวันหยุดและกิจกรรมของห้องสมุด</small>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addEventModal" style="background-color: var(--primary-color); border:none;">
            <i class="fas fa-calendar-plus me-2"></i> เพิ่มกิจกรรม
        </button>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <h5 class="fw-bold text-secondary mb-3"><i class="fas fa-clock me-2"></i> เร็วๆ นี้</h5>
            
            @if($upcoming->isEmpty())
                <div class="alert alert-light border-0 shadow-sm text-center py-4">
                    ไม่มีกิจกรรมเร็วๆ นี้
                </div>
            @endif

            @foreach($upcoming as $event)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <div class="d-flex">
                        <div class="text-center me-4 p-3 rounded-3 shadow-sm bg-white border" style="min-width: 90px;">
                            <h5 class="fw-bold text-primary m-0">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</h5>
                            <small class="text-uppercase text-muted fw-bold">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</small>
                            <div class="badge bg-light text-dark mt-2 w-100 border">{{ \Carbon\Carbon::parse($event->event_date)->format('Y') }}</div>
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-2">
                                <h5 class="fw-bold text-dark m-0">{{ $event->title }}</h5>
                                
                                @php
                                    $badges = [
                                        'Holiday' => 'bg-danger',
                                        'Event' => 'bg-success',
                                        'Maintenance' => 'bg-warning text-dark',
                                        'Meeting' => 'bg-info text-dark'
                                    ];
                                    $badgeColor = $badges[$event->type] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeColor }} bg-opacity-75 rounded-pill px-3 py-2">{{ $event->type }}</span>
                            </div>
                            
                            <p class="text-muted mb-2">{{ $event->description }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-secondary">
                                    <i class="far fa-clock me-1"></i> 
                                    {{ $event->start_time ? substr($event->start_time, 0, 5) : 'All Day' }} 
                                    {{ $event->end_time ? '- ' . substr($event->end_time, 0, 5) : '' }}
                                </small>
                                
                                <form action="{{ route('schedules.destroy', $event->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบกิจกรรม?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm text-danger border-0 bg-transparent p-0">
                                        <i class="fas fa-trash-alt"></i> ลบ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="col-md-4">
            <h5 class="fw-bold text-secondary mb-3"><i class="fas fa-history me-2"></i> กิจกรรมที่ผ่านมา</h5>
            <div class="card border-0 shadow-sm">
                <div class="list-group list-group-flush">
                    @foreach($past as $event)
                    <div class="list-group-item p-3 border-light">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 text-muted">{{ $event->title }}</h6>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/y') }}</small>
                        </div>
                        <small class="text-muted opacity-50">{{ $event->type }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">เพิ่มกิจกรรมใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">หัวข้อกิจกรรม</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">วันที่</label>
                            <input type="date" name="event_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ประเภท</label>
                            <select name="type" class="form-select">
                                <option value="Event">Event (กิจกรรม)</option>
                                <option value="Holiday">Holiday (วันหยุด)</option>
                                <option value="Maintenance">Maintenance (ปรับปรุง)</option>
                                <option value="Meeting">Meeting (ประชุม)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">เวลาเริ่ม</label>
                            <input type="time" name="start_time" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">เวลาสิ้นสุด</label>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">รายละเอียด</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: var(--primary-color); border:none;">บันทึกกิจกรรม</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection