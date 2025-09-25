<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback & Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Thêm icon ngôi sao -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
        .rating-star-group .star {
            cursor: pointer;
            transition: color 0.2s ease-in-out;
            color: #4b5563; /* Màu xám của ngôi sao chưa chọn */
        }
        .rating-star-group .star.selected {
            color: #f59e0b; /* Màu vàng của ngôi sao đã chọn */
        }
    </style>
</head>
<body class="bg-darkblue flex items-center justify-center min-h-screen p-4">

    <div class="bg-cardblue text-textlight rounded-xl shadow-2xl p-8 w-full max-w-2xl relative">
        <h2 class="text-2xl font-bold mb-6 text-center">Feedback & Reports</h2>

        <!-- Feedback Form Section -->
        <div class="mb-8">
            <form>
                <div class="mb-4">
                    <label for="course" class="block text-sm font-medium text-gray-400 mb-1">Course</label>
                    <select id="course" name="course" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">-- Select course --</option>
                        <option value="course1">Demo 01</option>
                        <option value="course2">Demo 02</option>
                        <option value="course3">Demo 03</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-1">Rating</label>
                    <div id="rating-star-group" class="flex justify-center space-x-2 rating-star-group">
                        <i class="fa-solid fa-star star" data-value="1"></i>
                        <i class="fa-solid fa-star star" data-value="2"></i>
                        <i class="fa-solid fa-star star" data-value="3"></i>
                        <i class="fa-solid fa-star star" data-value="4"></i>
                        <i class="fa-solid fa-star star" data-value="5"></i>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="0">
                </div>

                <div class="mb-6">
                    <label for="feedback" class="block text-sm font-medium text-gray-400 mb-1">Feedback</label>
                    <textarea id="feedback" name="feedback" rows="4" placeholder="Write your feedback here..." class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200"></textarea>
                </div>

                <button type="submit" class="w-full py-2 bg-primary text-textlight rounded-lg font-semibold hover:bg-primaryhover transition-colors duration-200 shadow-lg">
                    Send feedback
                </button>
            </form>
        </div>
        

    </div>

    <script>
        const stars = document.querySelectorAll('.rating-star-group .star');
        const ratingInput = document.getElementById('rating');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = parseInt(star.getAttribute('data-value'));
                ratingInput.value = value;
                updateStars(value);
            });

            star.addEventListener('mouseover', () => {
                const value = parseInt(star.getAttribute('data-value'));
                updateStarsOnHover(value);
            });

            star.addEventListener('mouseout', () => {
                const selectedValue = parseInt(ratingInput.value);
                updateStars(selectedValue);
            });
        });

        function updateStars(selectedValue) {
            stars.forEach(star => {
                if (parseInt(star.getAttribute('data-value')) <= selectedValue) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        }

        function updateStarsOnHover(hoveredValue) {
            stars.forEach(star => {
                if (parseInt(star.getAttribute('data-value')) <= hoveredValue) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        }
    </script>
</body>
</html>
