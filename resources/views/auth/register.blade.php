<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Đăng ký - AutoCare Long Biên</title>
</head>

<body>

    <h1>Đăng ký tài khoản</h1>

    <!-- Thông báo đăng ký thành công -->
    @if (session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif


    <!-- Hiển thị lỗi validate -->
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">

            <strong>
                Vui lòng kiểm tra lại thông tin:
            </strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>

        </div>
    @endif


    <!-- Form đăng ký -->
    <form method="POST"
          action="{{ route('register.submit') }}">

        @csrf


        <!-- Họ tên -->
        <div style="margin-bottom: 10px;">

            <label for="name">
                Họ và tên
            </label>

            <br>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >

        </div>


        <!-- Email -->
        <div style="margin-bottom: 10px;">

            <label for="email">
                Email
            </label>

            <br>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >

        </div>


        <!-- Mật khẩu -->
        <div style="margin-bottom: 10px;">

            <label for="password">
                Mật khẩu
            </label>

            <br>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

        </div>


        <!-- Nhập lại mật khẩu -->
        <div style="margin-bottom: 15px;">

            <label for="password_confirmation">
                Nhập lại mật khẩu
            </label>

            <br>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
            >

        </div>


        <button type="submit">
            Đăng ký
        </button>

    </form>


    <p style="margin-top: 20px;">
        Đã có tài khoản?

        <a href="#">
            Đăng nhập
        </a>
    </p>


    <p>
        <a href="{{ route('home') }}">
            ← Quay lại trang chủ
        </a>
    </p>

</body>

</html>