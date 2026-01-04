-- 1. Setup Database
DROP DATABASE IF EXISTS libraryManagement;
CREATE DATABASE libraryManagement CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE libraryManagement;

-- ==========================================
-- 2. Create Tables
-- ==========================================

-- Table: Members (Students)
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    status ENUM('Active', 'Banned', 'Expired') DEFAULT 'Active',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Table: Authors
CREATE TABLE authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    bio TEXT
);

-- Table: Books
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(20),
    title VARCHAR(255) NOT NULL,
    author_id INT,
    category_id INT,
    description TEXT,
    price DECIMAL(10, 2),
    stock_quantity INT DEFAULT 1,
    image VARCHAR(255) DEFAULT 'book1.png',
    location VARCHAR(50),
    status ENUM('Available', 'Borrowed', 'Maintenance', 'Lost', 'Sold Out') DEFAULT 'Available';
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Purchases History Table
CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    buyer_name VARCHAR(100) DEFAULT 'Guest',
    price DECIMAL(10, 2) NOT NULL,
    purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- Table: Borrows (Transactions)
CREATE TABLE borrows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    staff_id INT,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,
    fine_amount DECIMAL(10, 2) DEFAULT 0.00,
    status ENUM('Borrowed', 'Returned', 'Overdue', 'Lost') DEFAULT 'Borrowed',
    note TEXT,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (member_id) REFERENCES members(id),
    FOREIGN KEY (staff_id) REFERENCES staffs(id)
);

-- Table: Schedules
CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    start_time TIME,
    end_time TIME,
    type ENUM('Holiday', 'Event', 'Maintenance', 'Meeting') DEFAULT 'Event',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Notes
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES staffs(id)
);

-- Table: Staffs (Admins)
CREATE TABLE staffs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Super Admin', 'Librarian') DEFAULT 'Librarian',
    remember_token VARCHAR(100) NULL;
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Fix for "Table sessions doesn't exist"
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id INT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL
);
CREATE TABLE cache (
    `key` VARCHAR(255) PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL
);


-- ==========================================
-- 3. Insert Mock Data
-- ==========================================

-- Mock Staffs
INSERT INTO staffs (name, email, password, role, phone) VALUES
('Admin Somchai', 'admin@library.com', 'password123', 'Super Admin', '081-111-1111'),
('Librarian Malee', 'malee@library.com', 'password123', 'Librarian', '081-222-2222'),
('Librarian Somsak', 'somsak@library.com', 'password123', 'Librarian', '081-333-3333'),
('Staff Wichai', 'wichai@library.com', 'password123', 'Librarian', '081-444-4444'),
('Staff Nida', 'nida@library.com', 'password123', 'Librarian', '081-555-5555'),
('Staff Prawit', 'prawit@library.com', 'password123', 'Librarian', '081-666-6666'),
('Staff Araya', 'araya@library.com', 'password123', 'Librarian', '081-777-7777'),
('Staff Thana', 'thana@library.com', 'password123', 'Librarian', '081-888-8888'),
('Staff Siriporn', 'siriporn@library.com', 'password123', 'Librarian', '081-999-9999'),
('Staff Kanda', 'kanda@library.com', 'password123', 'Librarian', '081-000-0000');

-- Mock Members
INSERT INTO members (member_code, name, email, phone, status) VALUES
('STD001', 'John Doe', 'john@uni.ac.th', '090-111-1111', 'Active'),
('STD002', 'Jane Smith', 'jane@uni.ac.th', '090-222-2222', 'Active'),
('STD003', 'Suda Jaidee', 'suda@uni.ac.th', '090-333-3333', 'Active'),
('STD004', 'Mana Meemark', 'mana@uni.ac.th', '090-444-4444', 'Banned'),
('STD005', 'Piti Rukrian', 'piti@uni.ac.th', '090-555-5555', 'Active'),
('STD006', 'Manee Chujai', 'manee@uni.ac.th', '090-666-6666', 'Active'),
('STD007', 'Chujai Rakdee', 'chujai@uni.ac.th', '090-777-7777', 'Expired'),
('STD008', 'Weera Brave', 'weera@uni.ac.th', '090-888-8888', 'Active'),
('STD009', 'Somsri Happy', 'somsri@uni.ac.th', '090-999-9999', 'Active'),
('STD010', 'Taksin Shin', 'taksin@uni.ac.th', '090-000-0000', 'Active');

-- Mock Categories
INSERT INTO categories (name, description) VALUES
('Technology', 'Computer science, programming, AI'),
('History', 'World history, Thai history'),
('Business', 'Economics, marketing, management'),
('Fiction', 'Novels, short stories'),
('Science', 'Physics, chemistry, biology'),
('Self-Help', 'Personal development'),
('Comics', 'Manga, graphic novels'),
('Languages', 'English, Japanese, Chinese'),
('Travel', 'Guides, maps, experiences'),
('Cookbook', 'Recipes and culinary arts');

-- Mock Authors
INSERT INTO authors (name) VALUES
('J.K. Rowling'), ('George Orwell'), ('Simon Sinek'), ('Robert Kiyosaki'),
('James Clear'), ('Thich Nhat Hanh'), ('Murakami'), ('Agatha Christie'),
('Walter Isaacson'), ('Stephen King');

-- Mock Books
INSERT INTO books (title, author_id, category_id, isbn, price, stock_quantity, location, status, image) VALUES
('Laravel for Beginners', 1, 1, '978-1', 450.00, 5, 'A1', 'Available', 'book1.png'),
('History of Ayutthaya', 2, 2, '978-2', 320.00, 3, 'B2', 'Available', 'book2.png'),
('Start With Why', 3, 3, '978-3', 550.00, 4, 'C1', 'Borrowed', 'book3.png'),
('Rich Dad Poor Dad', 4, 3, '978-4', 400.00, 10, 'C1', 'Available', 'book4.png'),
('Atomic Habits', 5, 6, '978-5', 480.00, 2, 'D5', 'Available', 'book5.png'),
('The Miracle of Mindfulness', 6, 6, '978-6', 250.00, 1, 'D5', 'Maintenance', 'book6.png'),
('Norwegian Wood', 7, 4, '978-7', 380.00, 3, 'E3', 'Available', 'book7.png'),
('Murder on the Orient Express', 8, 4, '978-8', 300.00, 5, 'E3', 'Available', 'book8.png'),
('Steve Jobs', 9, 3, '978-9', 650.00, 2, 'F1', 'Lost', 'book9.png'),
('IT', 10, 4, '978-10', 500.00, 1, 'E4', 'Available', 'book10.png');

-- Mock Borrows
INSERT INTO borrows (book_id, member_id, staff_id, borrow_date, due_date, return_date, status, fine_amount) VALUES
(1, 1, 1, '2026-01-01', '2026-01-08', NULL, 'Borrowed', 0),
(2, 2, 1, '2025-12-25', '2026-01-01', '2026-01-01', 'Returned', 0),
(3, 3, 2, '2025-12-20', '2025-12-27', NULL, 'Overdue', 50.00),
(4, 4, 2, '2025-12-01', '2025-12-08', '2025-12-09', 'Returned', 10.00),
(5, 5, 1, '2026-01-02', '2026-01-09', NULL, 'Borrowed', 0),
(6, 6, 1, '2026-01-03', '2026-01-10', NULL, 'Borrowed', 0),
(7, 7, 3, '2025-11-15', '2025-11-22', NULL, 'Lost', 380.00),
(8, 8, 3, '2026-01-01', '2026-01-08', NULL, 'Borrowed', 0),
(9, 9, 2, '2025-12-28', '2026-01-04', '2026-01-03', 'Returned', 0),
(10, 10, 1, '2026-01-02', '2026-01-09', NULL, 'Borrowed', 0);

-- Mock Schedules
INSERT INTO schedules (title, description, event_date, start_time, end_time, type) VALUES
('New Year Holiday', 'Library Closed', '2026-01-01', '08:00:00', '17:00:00', 'Holiday'),
('Book Fair', 'Discount books sale', '2026-01-15', '09:00:00', '16:00:00', 'Event'),
('Server Maintenance', 'System update', '2026-01-20', '22:00:00', '23:59:00', 'Maintenance'),
('Staff Meeting', 'Monthly perf review', '2026-02-01', '08:00:00', '10:00:00', 'Meeting'),
('Songkran Holiday', 'Library Closed', '2026-04-13', '08:00:00', '17:00:00', 'Holiday'),
('Coding Workshop', 'Learn Laravel', '2026-03-10', '13:00:00', '16:00:00', 'Event'),
('Reading Club', 'Weekly discussion', '2026-01-10', '14:00:00', '15:00:00', 'Event'),
('Fire Drill', 'Safety practice', '2026-02-15', '10:00:00', '11:00:00', 'Maintenance'),
('Inventory Check', 'Annual stock count', '2026-06-01', '08:00:00', '18:00:00', 'Maintenance'),
('Guest Speaker', 'Famous author visit', '2026-05-20', '13:00:00', '15:00:00', 'Event');

-- Mock Notes
INSERT INTO notes (staff_id, title, content, priority, is_completed) VALUES
(1, 'Order new CSS books', 'Students requested Tailwind books', 'High', FALSE),
(1, 'Fix Air Con', 'Room B air con is leaking', 'High', FALSE),
(2, 'Update Member List', 'Remove graduated students', 'Medium', TRUE),
(2, 'Call Electrician', 'Lights in corridor flickering', 'Medium', FALSE),
(3, 'Prepare Monthly Report', 'Due by Friday', 'High', FALSE),
(1, 'Organize bookshelves', 'Category A is messy', 'Low', FALSE),
(2, 'Backup Database', 'Weekly backup', 'High', TRUE),
(3, 'Buy Coffee', 'Staff room ran out of coffee', 'Low', TRUE),
(1, 'Check Fire Extinguisher', 'Check expiration date', 'Medium', FALSE),
(2, 'Decorate for New Year', 'Put up lights', 'Low', TRUE);

INSERT INTO contacts (name, email, subject, message, is_read) VALUES
('Student A', 'studentA@uni.ac.th', 'Request Harry Potter', 'Please buy more Harry Potter books.', 0),
('Teacher B', 'teacherB@uni.ac.th', 'Projector Issue', 'The projector in Room 2 is broken.', 0),
('Guest', 'guest@gmail.com', 'Opening Hours', 'Are you open on Sundays?', 1);


-- For login as admin
UPDATE staffs 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'admin@library.com';