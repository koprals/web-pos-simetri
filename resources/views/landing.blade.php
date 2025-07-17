<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Core Padel</title>
  @vite('resources/css/app.css')
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html {
      scroll-behavior: smooth;
    }
    .hero-bg {
      background: radial-gradient(circle at top left, #064e3b, #065f46);
    }
  </style>
</head>
    <body class="font-sans bg-white text-gray-800">

    <!-- Header -->
    <header class="fixed top-0 left-0 w-full bg-green-700 shadow-md z-50">
        <div class="container mx-auto flex justify-between items-center px-6 py-4">
        <div class="flex items-center gap-2">
            <img src="https://placehold.co/40x40" class="rounded-full" alt="Logo">
            <span class="text-white text-xl font-bold">CORE PADEL</span>
        </div>
        <nav class="hidden md:flex space-x-6 text-white font-medium">
            <a href="#hero" class="hover:text-yellow-300">Home</a>
            <a href="#social" class="hover:text-yellow-300">Social</a>
            <a href="#contact" class="hover:text-yellow-300">Contact</a>
        </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="hero-bg text-white pt-32 pb-20">
        <div class="container mx-auto flex flex-col md:flex-row items-center px-6 gap-10">
        <div class="md:w-1/2 text-center md:text-left">
            <h1 class="text-5xl font-bold mb-4 leading-tight">Unleash Your Inner Athlete</h1>
            <p class="mb-6 text-lg text-gray-200">Experience the energy of padel sports with Core Padel – where passion meets performance!</p>
            <a href="#contact" class="bg-yellow-400 hover:bg-yellow-500 text-green-900 font-semibold px-6 py-3 rounded-full transition duration-300 inline-block">Join Us Today</a>
        </div>
        <div class="md:w-1/2 flex justify-center">
            <img src="https://placehold.co/600x400" class="rounded-xl shadow-2xl transform hover:scale-105 transition" alt="Padel Player">
        </div>
        </div>
    </section>

    <!-- Social Media Section -->
    <section id="social" class="bg-gray-50 py-16">
        <div class="container mx-auto text-center px-4">
        <h2 class="text-3xl font-bold text-green-800 mb-6">Follow Our Journey</h2>
        <p class="mb-6 text-gray-600 max-w-xl mx-auto">Stay connected with Core Padel. Join our community on social media and get daily updates, tips, and more.</p>
        <div class="flex justify-center gap-8 text-green-700 text-3xl">
            <a href="#" class="hover:text-green-900 transition">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 5 3.66 9.13 8.44 9.88v-6.99h-2.54V12h2.54V9.79c0-2.51 1.5-3.89 3.79-3.89 1.1 0 2.25.2 2.25.2v2.47h-1.27c-1.25 0-1.64.77-1.64 1.56V12h2.8l-.45 2.89h-2.35v6.99C18.34 21.13 22 17 22 12z"/></svg>
            </a>
            <a href="#" class="hover:text-green-900 transition">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.584.012 4.85.07 1.366.062 2.633.34 3.608 1.315s1.253 2.242 1.315 3.608c.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.34 2.633-1.315 3.608s-2.242 1.253-3.608 1.315c-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.34-3.608-1.315s-1.253-2.242-1.315-3.608C2.212 15.584 2.2 15.204 2.2 12s.012-3.584.07-4.85c.062-1.366.34-2.633 1.315-3.608S5.827 2.332 7.193 2.27c1.266-.058 1.646-.07 4.85-.07z"/></svg>
            </a>
        </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-white py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

                <!-- Left: Google Maps (400x400 fixed size) -->
                <div class="rounded-lg overflow-hidden shadow-lg flex justify-center">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15866.665707905076!2d106.82200295906999!3d-6.1753924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e9bcc8!2sNational%20Monument!5e0!3m2!1sen!2sid!4v1751958562545!5m2!1sen!2sid"
                        width="800" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="w-[800px] h-[400px]">
                    </iframe>
                </div>

                <!-- Right: Contact Info -->
                <div class="space-y-6">
                    <h2 class="text-4xl font-bold text-green-700">Get in touch</h2>
                    <p class="text-gray-600">
                        We’re always on the lookout to work with new clients. If you’re interested in working with us, please get in touch in one of the following ways.
                    </p>
                    <div class="space-y-5 text-gray-800">
                        <div class="flex items-start gap-4">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-lg">Address</h4>
                                <p>Jl. Padel No.123, Jakarta, Indonesia</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.586a1 1 0 01.707.293l1.414 1.414A1 1 0 0010.414 5H20a2 2 0 012 2v10a2 2 0 01-2 2h-7.586a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-.707.293H5a2 2 0 01-2-2V5z" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-lg">Phone</h4>
                                <p>+62 812-3456-7890</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m8 0l-4 4m4-4l-4-4" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-lg">E-Mail</h4>
                                <p>contact@corepadel.com</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-green-800 text-white text-center py-4">
        &copy; 2025 Core Padel. All rights reserved.
    </footer>

    </body>
</html>
