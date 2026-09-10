<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập - AutoCare Long Biên</title>
</head>

<body>

    <h1>Đăng nhập</h1>

    <!-- Thông báo sau khi đăng ký thành công -->
    @if (session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif


    <!-- Hiển thị lỗi -->
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


    <!-- Form đăng nhập -->
    <form method="POST"
          action="{{ route('login.submit') }}">

        @csrf


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
                autofocus
            >

        </div>


        <!-- Mật khẩu -->
        <div style="margin-bottom: 15px;">

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


        <!-- Ghi nhớ đăng nhập -->
        <div style="margin-bottom: 15px;">

            <input
                type="checkbox"
                id="remember"
                name="remember"
            >

            <label for="remember">
                Ghi nhớ đăng nhập
            </label>

        </div>


        <button type="submit">
            Đăng nhập
        </button>

    </form>


    <p style="margin-top: 20px;">
        Chưa có tài khoản?

        <a href="{{ route('register') }}">
            Đăng ký
        </a>
    </p>


    <p>
        <a href="{{ route('home') }}">
            ← Quay lại trang chủ
        </a>
    </p>

</body>

</html>