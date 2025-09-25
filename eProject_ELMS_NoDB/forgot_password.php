<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
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
</head>
<body class="bg-darkblue text-white min-h-screen flex items-center justify-center p-6">
  <div class="bg-gray-800 rounded-xl shadow-xl p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6">Forgot Password</h2>
    <p class="text-gray-400 text-sm text-center mb-6">
      Enter your email address and we will send you a link to reset your password.
    </p>

    <form action="forgot_password_process.php" method="POST" class="space-y-6">
      <!-- Email input -->
      <div>
        <label class="block text-sm text-gray-400 mb-1">Email</label>
        <input type="email" name="email" required placeholder="you@example.com"
               class="w-full px-4 py-2 bg-gray-700 text-gray-200 rounded-lg border border-gray-600 
                      focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
      </div>

      <!-- Buttons -->
      <div class="flex justify-center space-x-4 pt-4">
        <button type="submit" 
                class="px-6 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg flex items-center space-x-2">
          <i class="fas fa-paper-plane"></i>
          <span>Send Reset Link</span>
        </button>
        <a href="login.php" 
           class="px-6 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-500 transition-colors duration-200 shadow-lg flex items-center space-x-2">
          <span>Back to Login</span>
        </a>
      </div>
    </form>
  </div>
</body>
</html>
