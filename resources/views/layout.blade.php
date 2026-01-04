<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Library Admin System</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 512 512%22><path fill=%22%230d6efd%22 d=%22M464 64h-32v384H128a64 64 0 0 1-64-64V64H48a16 16 0 0 0-16 16v384a48 48 0 0 0 48 48h384a48 48 0 0 0 48-48V80a16 16 0 0 0-16-16zm-96 320H128a32 32 0 0 0 0 64h240z%22/></svg>">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #4f46e5;
            --secondary-color: #64748b;
            --bg-color: #f1f5f9;
            --sidebar-bg: #1e293b;
            --text-color: #334155;
        }

        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-link {
            color: #cbd5e1;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 400;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.05);
            border-left-color: var(--primary-color);
        }

        .nav-link i {
            width: 25px;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: all 0.3s;
        }

        .topbar {
            background: white;
            height: 70px;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .content-wrapper {
            padding: 2rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <nav class="sidebar" id="sidebar">
        <a href="#" class="sidebar-brand">
            <i class="fas fa-book-reader me-2"></i> LMS Admin
        </a>
        <div class="d-flex flex-column mt-3">
            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> หน้าสรุปผล
            </a>
            <a href="{{ url('/books') }}" class="nav-link {{ request()->is('books*') ? 'active' : '' }}">
                <i class="fas fa-book"></i> หนังสือทั้งหมด
            </a>
            <a href="{{ url('/purchases') }}" class="nav-link {{ request()->is('purchases*') ? 'active' : '' }}">
                <i class="fa-regular fa-money-bill-1"></i> ซื้อหนังสือ
            </a>
            <a href="{{ url('/transactions') }}" class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i> แก้ไข (ยืม-คืน)
            </a>
            <a href="{{ url('/schedule') }}" class="nav-link {{ request()->is('schedule*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> ตารางเวลา
            </a>
            <a href="{{ url('/notes') }}" class="nav-link {{ request()->is('notes*') ? 'active' : '' }}">
                <i class="fas fa-sticky-note"></i> จดบันทึก
            </a>
            <a href="{{ url('/staffs') }}" class="nav-link {{ request()->is('staffs*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> จัดการบุคลากร
            </a>
            <a href="{{ url('/contacts') }}" class="nav-link {{ request()->is('contacts*') ? 'active' : '' }}">
                <i class="fas fa-headset"></i> ติดต่อเจ้าหน้าที่
            </a>
        </div>
    </nav>

    <div class="main-content">
        <div class="topbar">
            <button class="btn border-0 d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars fa-lg"></i>
            </button>
            
            <h5 class="m-0 text-secondary fw-bold d-none d-lg-block">ระบบจัดการห้องสมุด</h5>

            <div class="user-profile">
                <div class="text-end me-2 d-none d-sm-block">
                    <div class="fw-bold text-dark">Admin User</div>
                    <small class="text-muted">ผู้ดูแลระบบ</small>
                </div>
                <div class="user-avatar">A</div>
            </div>
        </div>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>