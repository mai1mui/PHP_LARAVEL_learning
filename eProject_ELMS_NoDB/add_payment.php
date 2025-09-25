<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Payment</title>
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
    <h2 class="text-2xl font-bold mb-6 text-center">Add New Payment</h2>

    <form id="add-payment-form" class="space-y-6">

      <!-- Payment ID (auto generated, readonly) -->
      <div>
        <label for="payment-id" class="block text-sm font-medium mb-1">PaymentID</label>
        <input type="text" id="payment-id" placeholder="Auto-generated"
          class="w-full px-4 py-2 bg-gray-600 text-gray-400 border border-gray-500 rounded-lg" readonly>
      </div>

      <!-- Learner -->
      <div>
        <label for="learner" class="block text-sm font-medium mb-1">Learner</label>
        <input type="text" id="learner" placeholder="Enter student name" required
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
      </div>

      <!-- Course -->
      <div>
        <label for="course" class="block text-sm font-medium mb-1">Course</label>
        <select id="course"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary" required>
          <option value="">-- Select course --</option>
          <option value="Demo 01">Demo 01</option>
          <option value="Demo 02">Demo 02</option>
          <option value="Demo 03">Demo 03</option>
        </select>
      </div>

      <!-- Amount -->
      <div>
        <label for="amount" class="block text-sm font-medium mb-1">Amount</label>
        <input type="number" id="amount" placeholder="Enter amount" required
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
      </div>

      <!-- Payment Date -->
      <div>
        <label for="payment-date" class="block text-sm font-medium mb-1">Payment Date</label>
        <input type="date" id="payment-date" required
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
      </div>

      <!-- Status -->
      <div>
        <label for="status" class="block text-sm font-medium mb-1">Status</label>
        <select id="status"
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary" required>
          <option value="">-- Select status --</option>
          <option value="paid">Paid</option>
          <option value="processing">Processing</option>
          <option value="not_confirmed">Not confirmed</option>
        </select>
      </div>

      <!-- Transaction Ref -->
      <div>
        <label for="transaction-ref" class="block text-sm font-medium mb-1">Transaction Ref</label>
        <input type="text" id="transaction-ref" placeholder="" readonly
          class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-primary">
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-4 pt-4">
        <button type="button" onclick="window.location.href='payment.php'"
          class="px-6 py-2 rounded-lg border border-gray-600 text-gray-300 font-semibold hover:bg-gray-700 transition">
          Cancel
        </button>
        <button type="submit"
          class="px-6 py-2 rounded-lg bg-primary text-textlight font-semibold hover:bg-primaryhover shadow-md transition">
          Save
        </button>
      </div>
    </form>
  </div>
</body>
</html>
