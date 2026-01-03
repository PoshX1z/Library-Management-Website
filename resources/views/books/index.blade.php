@extends('layout')

@section('content')
<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">หนังสือทั้งหมด</h3>
            <small class="text-muted">จัดการหนังสือในระบบ</small>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookModal" style="background-color: var(--primary-color); border:none;">
            <i class="fas fa-plus me-2"></i> เพิ่มหนังสือใหม่
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('books.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="ค้นหาชื่อหนังสือ..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">ทุกหมวดหมู่</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @foreach($books as $book)
        <div class="col-xl-2 col-lg-3 col-md-4 col-6">
            <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden group-hover-effect">
                
                <div class="position-absolute top-0 end-0 m-2 z-1">
                    @if($book->status == 'Available')
                        <span class="badge bg-success shadow-sm">ว่าง</span>
                    @elseif($book->status == 'Borrowed')
                        <span class="badge bg-warning text-dark shadow-sm">ถูกยืม</span>
                    @elseif($book->status == 'Maintenance')
                        <span class="badge bg-danger shadow-sm">ซ่อมบำรุง</span>
                    @else
                        <span class="badge bg-secondary shadow-sm">{{ $book->status }}</span>
                    @endif
                </div>

                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 240px; overflow:hidden;">
                    <img src="{{ asset('images/books/' . $book->image) }}" 
                         alt="{{ $book->title }}" 
                         class="w-100 h-100 object-fit-cover"
                         onerror="this.src='https://via.placeholder.com/150x220?text=No+Image'">
                </div>

                <div class="card-body p-3 d-flex flex-column">
                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $book->title }}">{{ $book->title }}</h6>
                    <small class="text-muted mb-2 text-truncate">{{ $book->author->name ?? 'Unknown' }}</small>
                    
                    <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">{{ number_format($book->price) }} ฿</span>
                        
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal{{ $book->id }}">
                                        <i class="fas fa-edit me-2 text-warning"></i> แก้ไข
                                    </button>
                                </li>
                                <li>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบหนังสือเล่มนี้?');">
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
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal{{ $book->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">แก้ไขข้อมูล: {{ $book->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">ชื่อหนังสือ</label>
                                        <input type="text" name="title" class="form-control" value="{{ $book->title }}" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">ผู้แต่ง</label>
                                            <select name="author_id" class="form-select" required>
                                                @foreach($authors as $author)
                                                    <option value="{{ $author->id }}" {{ $book->author_id == $author->id ? 'selected' : '' }}>
                                                        {{ $author->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">หมวดหมู่</label>
                                            <select name="category_id" class="form-select" required>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $book->category_id == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">ISBN</label>
                                            <input type="text" name="isbn" class="form-control" value="{{ $book->isbn }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">ราคา</label>
                                            <input type="number" name="price" class="form-control" value="{{ $book->price }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">สถานะ</label>
                                            <select name="status" class="form-select">
                                                <option value="Available" {{ $book->status == 'Available' ? 'selected' : '' }}>Available (ว่าง)</option>
                                                <option value="Borrowed" {{ $book->status == 'Borrowed' ? 'selected' : '' }}>Borrowed (ถูกยืม)</option>
                                                <option value="Maintenance" {{ $book->status == 'Maintenance' ? 'selected' : '' }}>Maintenance (ซ่อม)</option>
                                                <option value="Lost" {{ $book->status == 'Lost' ? 'selected' : '' }}>Lost (หาย)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">จำนวน & ตำแหน่ง</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Qty</span>
                                            <input type="number" name="stock_quantity" class="form-control" value="{{ $book->stock_quantity }}">
                                            <span class="input-group-text">Loc</span>
                                            <input type="text" name="location" class="form-control" value="{{ $book->location }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded text-center border h-100">
                                        <label class="form-label small text-muted">รูปปกปัจจุบัน</label>
                                        <div class="mb-2">
                                            <img src="{{ asset('images/books/' . $book->image) }}" class="img-thumbnail" style="height: 150px;">
                                        </div>
                                        <label class="form-label small text-muted mt-2">เปลี่ยนรูปใหม่</label>
                                        <input type="file" name="image" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">รายละเอียด</label>
                                    <textarea name="description" class="form-control" rows="3">{{ $book->description }}</textarea>
                                </div>
                            </div>
                            
                            <div class="text-end mt-4">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-warning px-4 text-dark">บันทึกการแก้ไข</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($books->isEmpty())
    <div class="text-center py-5">
        <div class="text-muted opacity-25 mb-3">
            <i class="fas fa-book fa-4x"></i>
        </div>
        <p class="text-muted">ไม่พบข้อมูลหนังสือ</p>
    </div>
    @endif

</div>

<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">เพิ่มหนังสือใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">ชื่อหนังสือ <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ผู้แต่ง <span class="text-danger">*</span></label>
                                    <select name="author_id" class="form-select" required>
                                        <option value="" disabled selected>เลือกผู้แต่ง...</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}">{{ $author->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">หมวดหมู่ <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="" disabled selected>เลือกหมวดหมู่...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">ISBN</label>
                                    <input type="text" name="isbn" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">ราคา</label>
                                    <input type="number" name="price" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">จำนวน</label>
                                    <input type="number" name="stock_quantity" class="form-control" value="1">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ตำแหน่งเก็บ (Location)</label>
                                <input type="text" name="location" class="form-control" placeholder="เช่น ชั้น 2 แถว A">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center border h-100 d-flex flex-column justify-content-center">
                                <i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-3"></i>
                                <label class="form-label small text-muted">อัพโหลดรูปปกหนังสือ</label>
                                <input type="file" name="image" class="form-control form-control-sm">
                                <small class="text-muted mt-2 d-block" style="font-size: 10px;">* หากไม่เลือกจะใช้รูป Default</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">รายละเอียด / เรื่องย่อ</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary px-4" style="background-color: var(--primary-color); border:none;">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection