<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Payment</title>
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
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-darkblue flex items-center justify-center min-h-screen p-4">
  <div class="bg-cardblue text-textlight rounded-xl shadow-2xl p-8 w-full max-w-2xl relative">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Payment</h2>

    <form id="edit-payment-form" class="space-y-6">

      <!-- Payment ID -->
      <div>
        <label for="payment-id" class="block text-sm font-medium mb-1">PaymentID</label>
        <input type="text" id="payment-id" value="#123456"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary"
          readonly>
      </div>

      <!-- Learner -->
      <div>
        <label for="learner" class="block text-sm font-medium mb-1">Learner</label>
        <input type="text" id="learner" value="Nguyễn Văn A"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary"
          readonly>
      </div>

      <!-- Course -->
      <div>
        <label for="course" class="block text-sm font-medium mb-1">Course</label>
        <input type="text" id="course" value="Demo 01"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary"
          readonly>
      </div>

      <!-- Amount -->
      <div>
        <label for="amount" class="block text-sm font-medium mb-1">Amount</label>
        <input type="text" id="amount" value="500,000đ"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary"
          required>
      </div>

      <!-- Payment date -->
      <div>
        <label for="payment-date" class="block text-sm font-medium mb-1">Payment Date</label>
        <input type="date" id="payment-date" value="2023-10-10"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary"
          required>
      </div>

      <!-- Status -->
      <div>
        <label for="status" class="block text-sm font-medium mb-1">Status</label>
        <select id="status"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary" required>
          <option value="paid" selected>Paid</option>
          <option value="processing">Processing</option>
          <option value="not_confirmed">Not confirmed</option>
        </select>
      </div>

      <!-- Transaction Ref -->
      <div>
        <label for="transaction-ref" class="block text-sm font-medium mb-1">Transaction Ref</label>
        <input type="text" id="transaction-ref" value="10102023ACB"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary"
          required>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-4">
        <button type="button" onclick="window.location.href='payment.php'"
          class="px-6 py-2 border border-gray-600 text-gray-400 rounded-lg font-semibold hover:bg-gray-700">
          Cancel
        </button>
        <button type="submit"
          class="px-6 py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover shadow-lg">
          Save
        </button>
      </div>
    </form>
  </div>
</body>
</html>
