<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lesson View - HTML Basics</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <!-- Header -->
  <header class="bg-blue-700 text-white py-4 shadow-md">
    <div class="container mx-auto px-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold">Lesson01: HTML Basics</h1>
      <a href="course_detail.html" class="bg-white text-blue-700 px-4 py-2 rounded-lg shadow hover:bg-gray-200 transition">
       Back to Course
      </a>
    </div>
  </header>

  <!-- Main -->
  <main class="container mx-auto px-6 py-8">
    <!-- Lesson Info -->
    <section class="bg-white rounded-lg shadow p-6 mb-8">
      <h2 class="text-xl font-semibold mb-3">🎯 Learning Objectives</h2>
      <ul class="list-disc ml-6 space-y-2">
        <li>Understand the basic structure of an HTML document.</li>
        <li>Learn about headings, paragraphs, and links.</li>
        <li>Know how to display images and lists.</li>
      </ul>
    </section>

    <!-- Lesson Content -->
    <section class="bg-white rounded-lg shadow p-6 mb-8">
      <h2 class="text-xl font-semibold mb-3">📘 Lesson Content</h2>
      <p class="mb-4">HTML (HyperText Markup Language) is the standard language for creating web pages. An HTML document is made up of elements wrapped in tags.</p>

      <pre class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto mb-4">
&lt;!DOCTYPE html&gt;
&lt;html&gt;
  &lt;head&gt;
    &lt;title&gt;My First Page&lt;/title&gt;
  &lt;/head&gt;
  &lt;body&gt;
    &lt;h1&gt;Hello World!&lt;/h1&gt;
    &lt;p&gt;This is my first HTML page.&lt;/p&gt;
    &lt;a href="https://www.example.com"&gt;Visit Example&lt;/a&gt;
  &lt;/body&gt;
&lt;/html&gt;
      </pre>
    </section>

    <!-- Video -->
    <section class="bg-white rounded-lg shadow p-6 mb-8">
      <h2 class="text-xl font-semibold mb-3">🎥 Demo Video</h2>
      <div class="aspect-w-16 aspect-h-9">
        <iframe class="w-full h-96 rounded-lg"
          src="https://www.youtube.com/embed/pQN-pnXPaVg"
          title="HTML Tutorial for Beginners"
          frameborder="0"
          allowfullscreen>
        </iframe>
      </div>
    </section>

    <!-- Resources -->
    <section class="bg-white rounded-lg shadow p-6 mb-8">
      <h2 class="text-xl font-semibold mb-3">📂 Additional Resources</h2>
      <ul class="list-disc ml-6 space-y-2">
        <li><a href="https://developer.mozilla.org/en-US/docs/Web/HTML" class="text-blue-600 hover:underline">MDN Web Docs - HTML</a></li>
        <li><a href="https://www.w3schools.com/html/" class="text-blue-600 hover:underline">W3Schools HTML Tutorial</a></li>
      </ul>
    </section>

    <!-- Quiz -->
    <section class="bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-semibold mb-4">📝 Quick Quiz</h2>

      <form id="quizForm" class="space-y-6">
        <!-- Q1 -->
        <div>
          <p class="font-medium mb-2">1. What does HTML stand for?</p>
          <label class="block"><input type="radio" name="q1" value="a"> HyperText Markup Language</label>
          <label class="block"><input type="radio" name="q1" value="b"> Hyperlinks and Text Markup Language</label>
          <label class="block"><input type="radio" name="q1" value="c"> Home Tool Markup Language</label>
        </div>

        <!-- Q2 -->
        <div>
          <p class="font-medium mb-2">2. Which tag is used to create a hyperlink?</p>
          <label class="block"><input type="radio" name="q2" value="a"> &lt;link&gt;</label>
          <label class="block"><input type="radio" name="q2" value="b"> &lt;a&gt;</label>
          <label class="block"><input type="radio" name="q2" value="c"> &lt;href&gt;</label>
        </div>

        <!-- Q3 -->
        <div>
          <p class="font-medium mb-2">3. Which HTML element is used for the largest heading?</p>
          <label class="block"><input type="radio" name="q3" value="a"> &lt;heading&gt;</label>
          <label class="block"><input type="radio" name="q3" value="b"> &lt;h6&gt;</label>
          <label class="block"><input type="radio" name="q3" value="c"> &lt;h1&gt;</label>
        </div>

        <button type="button" onclick="checkQuiz()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
          Submit Quiz
        </button>
      </form>

      <div id="quizResult" class="mt-6 text-lg font-bold"></div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-800 text-gray-300 text-center py-4 mt-8">
    © 2025 E-Learning LMS. All rights reserved.
  </footer>

  <script>
    function checkQuiz(){
      const answers = { q1: "a", q2: "b", q3: "c" };
      let score = 0, total = Object.keys(answers).length;

      for(const [q, correct] of Object.entries(answers)){
        const selected = document.querySelector(`input[name="${q}"]:checked`);
        if(selected && selected.value === correct){
          score++;
        }
      }

      const resultEl = document.getElementById("quizResult");
      resultEl.textContent = `You scored ${score} / ${total}`;
      resultEl.className = score === total ? "mt-6 text-green-600 font-bold" : "mt-6 text-red-600 font-bold";
    }
  </script>

</body>
</html>
