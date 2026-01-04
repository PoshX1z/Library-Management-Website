@extends('layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">ซื้อหนังสือ (POS)</h3>
            <small class="text-muted">ระบบจำหน่ายหนังสือและประวัติการขาย</small>
        </div>
        <div class="p-2 bg-white rounded shadow-sm border">
            <span class="text-muted small me-2">ยอดขายวันนี้:</span>
            <span class="fw-bold text-success">{{ number_format($history->where('purchased_at', '>=', \Carbon\Carbon::today())->sum('price')) }} ฿</span>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('purchases.index') }}" method="GET" class="row g-2">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control border-0 bg-light" placeholder="ค้นหาชื่อสินค้า..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="category_id" class="form-select border-0 bg-light" onchange="this.form.submit()">
                                <option value="">ทุกหมวดหมู่</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-3">
                @foreach($books as $book)
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden product-card">
                        <div class="position-relative">
                            <img src="{{ asset('images/books/' . $book->image) }}" class="w-100 object-fit-cover" style="height: 180px;" onerror="this.src='https://via.placeholder.com/150x180?text=No+Image'">
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                @if($book->stock_quantity > 0)
                                    <span class="badge bg-white text-dark shadow-sm">เหลือ {{ $book->stock_quantity }} เล่ม</span>
                                @else
                                    <span class="badge bg-danger shadow-sm">SOLD OUT</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-truncate mb-1">{{ $book->title }}</h6>
                            <small class="text-muted">{{ $book->category->name ?? 'Uncategorized' }}</small>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h5 class="fw-bold text-primary m-0">{{ number_format($book->price) }} ฿</h5>
                                
                                @if($book->stock_quantity > 0)
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" 
                                            onclick="openPaymentModal({{ $book->id }}, '{{ $book->title }}', {{ $book->price }})">
                                        <i class="fas fa-shopping-cart me-1"></i> ซื้อเลย
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-light text-muted rounded-pill px-3" disabled>สินค้าหมด</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold m-0"><i class="fas fa-history me-2 text-secondary"></i> ประวัติการซื้อล่าสุด</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($history as $h)
                        <div class="list-group-item border-light py-3">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 text-truncate" style="max-width: 180px;">{{ $h->book->title }}</h6>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill">+{{ number_format($h->price) }} ฿</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted"><i class="fas fa-user me-1"></i> {{ $h->buyer_name }}</small>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($h->purchased_at)->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($history->isEmpty())
                        <div class="text-center py-5 text-muted opacity-50">
                            <i class="fas fa-receipt fa-3x mb-2"></i>
                            <p>ยังไม่มีรายการขาย</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow text-center">
            <div class="modal-body p-4">
                <h5 class="fw-bold mb-1">สแกนเพื่อชำระเงิน</h5>
                <p class="text-muted small mb-4" id="modalBookName">Laravel Book</p>
                
                <div class="position-relative d-inline-block mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=FakePayment" class="img-fluid rounded border p-2">
                    <div id="paymentLoading" class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center d-none">
                        <div class="spinner-border text-success" role="status"></div>
                    </div>
                </div>

                <h3 class="fw-bold text-primary mb-3" id="modalPrice">450 ฿</h3>
                
                <div class="progress mb-2" style="height: 5px;">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" id="paymentProgress" style="width: 0%"></div>
                </div>
                <small class="text-muted d-block" id="statusText">กรุณาสแกน QR Code...</small>
            </div>
        </div>
    </div>
</div>

<script>
    let currentBookId = null;

    function openPaymentModal(id, title, price) {

        currentBookId = id;
        document.getElementById('modalBookName').innerText = title;
        document.getElementById('modalPrice').innerText = new Intl.NumberFormat().format(price) + ' ฿';
        
        document.getElementById('paymentProgress').style.width = '0%';
        document.getElementById('statusText').innerText = 'กำลังรอการสแกน...';
        document.getElementById('statusText').classList.remove('text-success');
        document.getElementById('paymentLoading').classList.add('d-none');
        
        var myModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        myModal.show();

        let progress = 0;
        const interval = setInterval(() => {
            progress += 2; 
            document.getElementById('paymentProgress').style.width = progress + '%';

            if (progress >= 100) {
                clearInterval(interval);
                completePurchase();
            }
        }, 100);
    }

    function completePurchase() {
        document.getElementById('statusText').innerText = 'ชำระเงินสำเร็จ!';
        document.getElementById('statusText').classList.add('text-success', 'fw-bold');
        document.getElementById('paymentLoading').classList.remove('d-none'); 

        fetch('{{ route("purchases.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                book_id: currentBookId,
                buyer_name: 'Walk-in Customer'
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('Error: ' + data.error);
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection