# AutoCare Long Biên

Hệ thống quản lý bảo dưỡng và chăm sóc ô tô tích hợp trợ lý AI hỗ trợ tra cứu thông tin bảo dưỡng, dịch vụ, lịch hẹn, phương tiện, hóa đơn và dữ liệu khách hàng.

Dự án được xây dựng bằng Laravel, MySQL và tích hợp Gemini API cho chức năng chatbot RAG.

---

## 1. Giới thiệu

AutoCare Long Biên là hệ thống hỗ trợ quản lý hoạt động bảo dưỡng ô tô với các nhóm người dùng:

- CUSTOMER
- STAFF
- TECHNICIAN
- ADMIN

Hệ thống hỗ trợ các nghiệp vụ chính như:

- Quản lý tài khoản người dùng.
- Quản lý khách hàng.
- Quản lý phương tiện.
- Quản lý dịch vụ bảo dưỡng.
- Đặt lịch bảo dưỡng trực tuyến.
- Quản lý lịch hẹn.
- Quản lý lịch sử bảo dưỡng.
- Quản lý hóa đơn.
- Quản lý kỹ thuật viên.
- Quản lý dữ liệu tri thức.
- Chatbot AutoCare AI.
- Tra cứu dữ liệu khách hàng đã xác thực.
- Retrieval-Augmented Generation (RAG).
- Hybrid Retrieval kết hợp semantic search và lexical search.
- Chế độ fallback khi Gemini không khả dụng.

---

## 2. Công nghệ sử dụng

### Backend

- PHP
- Laravel
- Laravel Eloquent ORM

### Database

- MySQL

### Frontend

- Blade Template
- HTML
- CSS
- JavaScript
- Bootstrap

### AI / RAG

- Google Gemini API
- Gemini Embedding
- Hybrid Retrieval
- Semantic Search
- Lexical Search
- Retrieval-Augmented Generation (RAG)

### Authentication

- Laravel Authentication
- Google OAuth

---

## 3. Kiến trúc chatbot AutoCare AI

Chatbot sử dụng nhiều lớp xử lý để đảm bảo câu trả lời dựa trên dữ liệu của hệ thống.

Luồng tổng quát:

```text
User Question
      |
      v
Intent / Context Resolution
      |
      +----------------------------+
      |                            |
      v                            v
Customer Data                Knowledge Query
      |                            |
      v                            v
Authorized Backend Data      Knowledge Retrieval
                                   |
                          +--------+--------+
                          |                 |
                          v                 v
                    Semantic Search   Lexical Search
                          |                 |
                          +--------+--------+
                                   |
                                   v
                            Hybrid Ranking
                                   |
                                   v
                            Relevant Documents
                                   |
                                   v
                              Gemini LLM
                                   |
                                   v
                              Final Answer
```

Nếu Gemini không khả dụng, hệ thống có thể chuyển sang lexical retrieval và fallback answer để vẫn cung cấp thông tin dựa trên dữ liệu có sẵn.

---

## 4. AI Runtime Modes

AutoCare hỗ trợ ba chế độ chạy AI thông qua biến:

```env
AI_MODE=
```

### `live`

```env
AI_MODE=live
```

Chế độ vận hành đầy đủ.

Hệ thống có thể sử dụng:

- Gemini Embedding.
- Hybrid Retrieval.
- Gemini Generation.
- Fallback khi API gặp lỗi.

### `test`

```env
AI_MODE=test
```

Chế độ dùng cho kiểm thử.

Trong chế độ này:

- Không gọi Gemini Generation API thật.
- Không gọi Gemini Embedding API thật.
- Retrieval sử dụng lexical search.
- Chatbot sử dụng fallback answer.
- Có log để xác nhận quá trình xử lý.

Mục đích là cho phép kiểm thử hệ thống mà không tiêu tốn Gemini API quota.

### `retrieval_only`

```env
AI_MODE=retrieval_only
```

Chế độ chỉ sử dụng retrieval để trả lời.

Gemini Generation sẽ không được sử dụng.

Chế độ này được dùng như phương án fallback khi cần vận hành chatbot mà không phụ thuộc vào bước sinh câu trả lời của LLM.

---

## 5. Yêu cầu môi trường

Máy chạy dự án cần có:

- PHP
- Composer
- MySQL
- Node.js / npm
- Git

Có thể sử dụng XAMPP để chạy PHP và MySQL trên Windows.

---

## 6. Cài đặt dự án

Clone repository:

```bash
git clone https://github.com/nguyennquann06/autocare-longbien.git
```

Di chuyển vào thư mục dự án:

```bash
cd autocare-longbien
```

Cài dependency PHP:

```bash
composer install
```

Cài dependency frontend:

```bash
npm install
```

Tạo file `.env`.

### Windows

```bash
copy .env.example .env
```

### Git Bash / Linux / macOS

```bash
cp .env.example .env
```

Tạo application key:

```bash
php artisan key:generate
```

---

## 7. Cấu hình Database

Tạo database MySQL:

```text
autocare_longbien
```

Sau đó cấu hình trong `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=autocare_longbien
DB_USERNAME=root
DB_PASSWORD=
```

Chạy migration:

```bash
php artisan migrate
```

Nếu project có dữ liệu seed:

```bash
php artisan db:seed
```

Hoặc:

```bash
php artisan migrate --seed
```

---

## 8. Cấu hình Gemini

Trong `.env`:

```env
AI_PROVIDER=gemini
AI_MODE=live

GEMINI_API_KEY=
GEMINI_MODEL=
GEMINI_EMBEDDING_MODEL=

GEMINI_TIMEOUT=15
GEMINI_CONNECT_TIMEOUT=5
GEMINI_RETRY_TIMES=2

GEMINI_DAILY_REQUEST_LIMIT=100
```

Không commit API key thật lên GitHub.

File `.env.example` chỉ chứa tên biến và cấu hình mẫu.

---

## 9. Giới hạn request Gemini

AutoCare có bộ đếm nội bộ để hạn chế số lượt gọi Gemini mỗi ngày.

Ví dụ:

```env
GEMINI_DAILY_REQUEST_LIMIT=100
```

Khi đã đạt giới hạn:

- Hệ thống không tiếp tục gọi Gemini Generation API.
- Chatbot tự chuyển sang fallback.
- Người dùng vẫn có thể nhận được câu trả lời từ dữ liệu retrieval nếu dữ liệu phù hợp.
- Sự kiện được ghi vào log.

---

## 10. Cấu hình Google OAuth

Nếu sử dụng đăng nhập Google, cấu hình:

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

Thông tin Client ID và Client Secret không được commit vào repository.

---

## 11. Chạy dự án

Khởi động Laravel:

```bash
php artisan serve
```

Mặc định truy cập:

```text
http://127.0.0.1:8000
```

Nếu frontend cần build:

```bash
npm run dev
```

---

## 12. Xóa cache sau khi thay đổi cấu hình

Khi thay đổi `.env` hoặc file config, chạy:

```bash
php artisan optimize:clear
```

Có thể kiểm tra cấu hình AI bằng:

```bash
php artisan tinker
```

Ví dụ:

```php
[
    config('ai.mode'),
    config('ai.providers.gemini.timeout'),
    config('ai.providers.gemini.connect_timeout'),
    config('ai.providers.gemini.retry_times'),
];
```

---

## 13. Cấu hình timeout Gemini

Cấu hình mặc định:

```env
GEMINI_TIMEOUT=15
GEMINI_CONNECT_TIMEOUT=5
GEMINI_RETRY_TIMES=2
```

Mục đích là hạn chế trường hợp request Gemini kéo dài quá lâu và vượt quá giới hạn thực thi của PHP.

Nếu API không phản hồi đúng thời gian, hệ thống có thể retry và sau đó chuyển sang fallback.

---

## 14. Knowledge Retrieval

AutoCare AI sử dụng Hybrid Retrieval.

Các tín hiệu retrieval gồm:

- Semantic similarity.
- Lexical score.
- Exact token matching.
- Keyword coverage.
- Brand detection.
- Brand conflict filtering.
- Relevance gate.

Trong điều kiện bình thường:

```text
Query
  ↓
Gemini Query Embedding
  ↓
Semantic Similarity
  +
Lexical Analysis
  ↓
Hybrid Ranking
  ↓
Relevant Documents
```

Trong `AI_MODE=test`:

```text
Query
  ↓
Lexical Analysis
  ↓
Lexical Ranking
  ↓
Relevant Documents
```

Nhờ đó TEST mode không cần gọi API bên ngoài.

---

## 15. Phân quyền dữ liệu chatbot

Dữ liệu cá nhân của khách hàng được lấy từ backend AutoCare sau khi đã xác thực người dùng.

Chatbot không được dựa vào prompt để tự quyết định quyền truy cập dữ liệu.

Dữ liệu như:

- phương tiện;
- biển số;
- ODO;
- lịch hẹn;
- lịch sử bảo dưỡng;
- hóa đơn;

phải được lấy thông qua lớp backend có kiểm tra người dùng hiện tại.

Điều này giúp hạn chế nguy cơ truy cập chéo dữ liệu giữa các tài khoản.

---

## 16. Fallback

AutoCare AI có cơ chế fallback trong các trường hợp:

- Gemini không được cấu hình.
- Gemini API timeout.
- Gemini API gặp lỗi.
- Gemini đạt giới hạn request nội bộ.
- `AI_MODE=test`.
- `AI_MODE=retrieval_only`.

Fallback chỉ sử dụng dữ liệu mà hệ thống retrieval hoặc backend đã xác định.

---

## 17. Log

Log Laravel nằm tại:

```text
storage/logs/laravel.log
```

Ví dụ kiểm tra hoạt động AI:

```bash
grep -nE "Gemini|Chatbot switched|AI_MODE|lexical-only" storage/logs/laravel.log
```

Trong TEST mode có thể xuất hiện:

```text
Knowledge retrieval is using lexical-only mode because AI_MODE=test.
```

và:

```text
Gemini generation skipped because AI runtime mode is not live.
```

Điều này xác nhận hệ thống không sử dụng Gemini Generation trong TEST mode.

---

## 18. Kiểm tra cú pháp PHP

Sau khi chỉnh sửa các service:

```bash
php -l app/Services/ChatService.php
php -l app/Services/GeminiProvider.php
php -l app/Services/GeminiEmbeddingService.php
php -l app/Services/KnowledgeRetrievalService.php
```

Nếu hợp lệ sẽ nhận được:

```text
No syntax errors detected
```

---

## 19. Kiểm thử chatbot

Các nhóm kiểm thử chính gồm:

### Knowledge Questions

Kiểm tra chatbot trả lời đúng thông tin từ Knowledge Base.

### Customer Data Questions

Kiểm tra chatbot trả lời đúng dữ liệu của tài khoản đang đăng nhập.

### Out-of-scope Questions

Kiểm tra chatbot từ chối hoặc không tự tạo thông tin ngoài phạm vi dữ liệu AutoCare.

### Authorization Tests

Kiểm tra tài khoản này không thể truy xuất dữ liệu riêng tư của tài khoản khác.

---

## 20. Mục tiêu đánh giá chatbot

Các tiêu chí chính:

```text
Tỷ lệ trả lời đúng:           >= 80%
Tỷ lệ từ chối đúng:           >= 90%
Cross-account data leakage:   0
```

Bộ kiểm thử chính thức gồm các câu hỏi thuộc các nhóm:

- Knowledge.
- Authenticated customer data.
- Out-of-scope / refusal.

---

## 21. Cấu trúc thư mục quan trọng

```text
app/
├── Models/
├── Http/
└── Services/
    ├── AiProviderManager.php
    ├── ChatService.php
    ├── ConversationContextService.php
    ├── CustomerContextService.php
    ├── GeminiEmbeddingService.php
    ├── GeminiProvider.php
    └── KnowledgeRetrievalService.php

config/
├── ai.php
└── knowledge_sources.php

database/
├── migrations/
└── seeders/

resources/
└── views/

routes/
└── web.php

storage/
└── logs/
```

---

## 22. Bảo mật

Không commit các thông tin sau lên GitHub:

```text
.env
GEMINI_API_KEY
GOOGLE_CLIENT_SECRET
Database password
API credentials
```

Chỉ sử dụng `.env.example` để mô tả cấu hình cần thiết.

---

## 23. Repository

GitHub:

```text
https://github.com/nguyennquann06/autocare-longbien
```

Branch chính:

```text
main
```

---

## 24. Tác giả

**Nguyễn Đức Quân**

Đồ án tốt nghiệp – Hệ thống AutoCare Long Biên.