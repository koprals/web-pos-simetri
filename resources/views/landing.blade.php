<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $logo['logo_text'] ?? 'Core Padel' }}</title>
  <link rel="icon" type="image/png" href="{{ asset('storage/' . ($logo['logo_image'] ?? 'uploads/logo/default.png')) }}">
  <style>
    /* Base Styles */
    html {
      scroll-behavior: smooth;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
      background-color: white;
      color: #1f2937;
    }

    body {
      margin: 0;
      padding: 0;
    }

    /* Header Styles */
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: #047857;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      z-index: 50;
    }

    .header-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 1rem 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .logo-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }

    .logo-text {
      color: white;
      font-size: 1.25rem;
      font-weight: 700;
    }

    nav {
      display: none;
    }

    nav a {
      color: white;
      font-weight: 500;
      text-decoration: none;
      margin: 0 1.5rem;
    }

    nav a:hover {
      color: #facc15;
    }

    /* Hero Section */
    #hero {
      background: radial-gradient(circle at top left, #064e3b, #065f46);
      color: white;
      padding-top: 8rem;
      padding-bottom: 5rem;
    }

    .hero-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 2.5rem;
    }

    .hero-content {
      text-align: center;
    }

    .hero-title {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 1rem;
      line-height: 1.25;
    }

    .hero-description {
      font-size: 1.125rem;
      color: #e5e7eb;
      margin-bottom: 1.5rem;
    }

    .hero-button {
      background-color: #facc15;
      color: #064e3b;
      font-weight: 600;
      padding: 0.75rem 1.5rem;
      border-radius: 9999px;
      display: inline-block;
      text-decoration: none;
    }

    .hero-button:hover {
      background-color: #eab308;
    }

    .hero-image {
      display: flex;
      justify-content: center;
    }

    .hero-image img {
      width: 100%;
      max-width: 600px;
      border-radius: 0.75rem;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      transition: transform 0.3s ease;
    }

    .hero-image img:hover {
      transform: scale(1.05);
    }

    /* Social Media Section */
    #social {
      background-color: #f9fafb;
      padding: 4rem 0;
    }

    .social-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1rem;
      text-align: center;
    }

    .social-title {
      font-size: 1.875rem;
      font-weight: 700;
      color: #064e3b;
      margin-bottom: 1.5rem;
    }

    .social-description {
      color: #4b5563;
      margin-bottom: 1.5rem;
      max-width: 36rem;
      margin-left: auto;
      margin-right: auto;
    }

    .social-icons {
      display: flex;
      justify-content: center;
      gap: 2rem;
      color: #047857;
      font-size: 1.875rem;
    }

    .social-icons a {
      color: inherit;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2rem;
      height: 2rem;
    }

    .social-icons a:hover {
      color: #064e3b;
    }

    /* Contact Section */
    #contact {
      background-color: white;
      padding: 4rem 0;
    }

    .contact-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1.5rem;
    }

    .contact-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 2rem;
    }

    .contact-map {
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: center;
      width: 100%;
      height: 100%
    }

    .contact-map iframe {
      width: 100%;
      height: 573px;
      border: none;
    }

    .contact-info {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .contact-title {
      font-size: 2.25rem;
      font-weight: 700;
      color: #047857;
    }

    .contact-text {
      color: #4b5563;
    }

    .contact-details {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
      color: #1f2937;
    }

    .contact-item {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }

    .contact-icon {
      width: 1.75rem;
      height: 1.75rem;
      color: #059669;
      flex-shrink: 0;
    }

    .contact-item-title {
      font-weight: 600;
      font-size: 1.125rem;
    }

    /* Footer */
    footer {
      background-color: #065f46;
      color: white;
      text-align: center;
      padding: 1rem 0;
    }

    /* Responsive Styles */
    @media (min-width: 768px) {
      nav {
        display: flex;
      }

      .hero-container {
        flex-direction: row;
        align-items: center;
      }

      .hero-content {
        text-align: left;
        flex: 1;
      }

      .hero-image {
        flex: 1;
      }

      .contact-grid {
        grid-template-columns: 1fr 1fr;
      }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="header-container">
      <div class="logo-container">
        <img src="{{ asset('storage/' . ($logo['logo_image'] ?? 'uploads/logo/default.png')) }}" class="logo-img" alt="Logo">
        <span class="logo-text">{{ $logo['logo_text'] ?? 'Core Padel' }}</span>
      </div>
      <nav>
        <a href="#hero">Home</a>
        <a href="#social">Social</a>
        <a href="#contact">Contact</a>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section id="hero">
    <div class="hero-container">
      <div class="hero-content">
        <h1 class="hero-title">{{ $hero['title'] ?? 'Unleash Your Inner Athlete' }}</h1>
        <p class="hero-description">{{ $hero['subtitle'] ?? 'Experience the energy of padel sports with Core Padel – where passion meets performance!' }}</p>
        <a href="#contact" class="hero-button">{{ $hero['button_text'] ?? 'Join Us Today' }}</a>
      </div>
      <div class="hero-image">
        <img src="{{ asset('storage/' . ($hero['background_image'] ?? 'uploads/hero/default-hero.jpg')) }}" alt="Hero Image">
      </div>
    </div>
  </section>

  <!-- Social Media Section -->
  <section id="social">
    <div class="social-container">
      <h2 class="social-title">{{ $social['title'] ?? 'Follow Our Journey' }}</h2>
      <p class="social-description">{{ $social['description'] ?? 'Stay connected with Core Padel. Join our community on social media and get daily updates, tips, and more.' }}</p>
      <div class="social-icons">
        @foreach (['facebook', 'instagram', 'tiktok'] as $platform)
          @if(!empty($social[$platform]))
            <a href="{{ $social[$platform] }}" target="_blank">
              <i class="fab fa-{{ $platform }}"></i>
            </a>
          @endif
        @endforeach
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact">
    <div class="contact-container">
      <div class="contact-grid">
        <div class="contact-map">
          <iframe
            src="{{ $contact['map_embed'] ?? 'https://maps.google.com' }}"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
        <div class="contact-info">
          <h2 class="contact-title">{{ $contact['title'] ?? 'Get in touch' }}</h2>
          <p class="contact-text">{{ $contact['description'] ?? 'We\'re always on the lookout to work with new clients. If you\'re interested in working with us, please get in touch in one of the following ways.' }}</p>
          <div class="contact-details">
            <div class="contact-item">
                <div>
                    <h4 class="contact-item-title">Address</h4>
                    <p>{{ $contact['address'] ?? 'Jl. Padel No.123, Jakarta, Indonesia' }}</p>
                </div>
            </div>
            <div class="contact-item">
                <div>
                    <h4 class="contact-item-title">Phone</h4>
                    <p>{{ $contact['phone'] ?? '+62 812-3456-7890' }}</p>
                </div>
            </div>
            <div class="contact-item">
                <div>
                    <h4 class="contact-item-title">E-Mail</h4>
                    <p>{{ $contact['email'] ?? 'contact@corepadel.com' }}</p>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    &copy; {{ date('Y') }} 'Simetri'. All rights reserved.
  </footer>

</body>
</html>
