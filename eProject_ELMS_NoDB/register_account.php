<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        darkblue: '#111827',
                        cardblue: '#1f2937',
                        primary: '#3b82f6',
                        primaryhover: '#2563eb',
                        textlight: '#f9fafb',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-darkblue flex items-center justify-center min-h-screen p-4">

    <div class="bg-cardblue text-textlight rounded-xl shadow-2xl p-8 w-full max-w-sm relative">
        <h2 class="text-2xl font-bold mb-6 text-center">Sign up for an account</h2>
        <p class="text-center text-sm mb-6 text-gray-400">Create an account to start the experience</p>

        <form>
            <div class="mb-4">
                <label for="fullname" class="block text-sm font-medium text-gray-400 mb-1">Full name</label>
                <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="example@email.com" required class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-400 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Enter password" required class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200 pr-12">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-sm leading-5 font-medium text-gray-400 hover:text-white focus:outline-none transition-colors duration-200">
                        Show
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <label for="confirm-password" class="block text-sm font-medium text-gray-400 mb-1">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Re-enter password" required class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
            </div>

            <button type="submit" class="w-full py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-400 mt-6">
            Already have an account?
            <a href="login.php" class="text-primary hover:underline font-medium transition-colors duration-200">Login now</a>
        </p>
    </div>

    <script>
        // JavaScript để chuyển đổi mật khẩu
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('togglePassword');

        togglePasswordButton.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePasswordButton.textContent = type === 'password' ? 'Hiện' : 'Ẩn';
        });
    </script>

</body>
</html>
