<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SDO Koronadal City Library</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('img/logo.jpg') }}" type="image/x-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS (Animate on Scroll) -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- GLightbox -->
    <link href="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/css/glightbox.min.css" rel="stylesheet">

    <!-- Swiper -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11.0.5/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    {{-- <link href="assets/css/main.css" rel="stylesheet"> --}}

    @vite(['resources/assets/css/main.css'])

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="index.html" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{asset("img/logo.png")}}" alt="" style="width: 45px; height: 45px">
                <h1 class="sitename">SDO Koronadal City Library</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#available">Available Books</a></li>
                    <li><a href="#top">Top Books</a></li>
                    
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            @if (Route::has('login'))

                @if (session()->has('school_id_expires_at') && now()->lessThan(session('school_id_expires_at')))
                    <a href="{{ url('/') }}" class="cta-btn" href="index.html#about">Get Started</a>
                @else
                    @php
                        session()->forget('school_id');
                        session()->forget('school_id_expires_at');
                    @endphp
                @endif
                @auth
                    <a href="{{ url('/dashboard') }}" class="cta-btn" href="index.html#about">Dashboard</a>
                @else
                    <a href="{{ url('/login') }}" class="cta-btn" href="index.html#about">Log in</a>
                @endauth
            @endif

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">

            <img src="{{ asset('img/hero-bg.jpg') }}" alt="" data-aos="fade-in">

            <div class="container d-flex flex-column align-items-center">
                <h2 data-aos="fade-up" data-aos-delay="100">New adventure awaits!</h2>
                <p data-aos="fade-up" data-aos-delay="200">Check out our latest Supplementary Learning Resources (SLR)
                </p>
                <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="#available" class="btn-get-started">Borrow Book</a>
                </div>
            </div>

        </section><!-- /Hero Section -->

        
    <section id="about" class="about section">

            <div class="container">

                <div class="row gy-4">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <h3>SDO Koronadal City Books Inventory and Tracking System </h3>
                        <img src="{{ asset('img/koronadal-division.jpg') }}" class="img-fluid rounded-4 mb-4" alt="Library Image">
                        <p>
                            The Books Inventory and Tracking System  is a centralized, web-based platform designed for managing the book inventory at the division level and tracking the distribution of books to schools. It streamlines the process of cataloging, monitoring, and reporting on all library resources across the division.
                        </p>
                        <p>
                            The system enables each school to create borrowing requests, which are subject to approval by the division office. It also manages the return of books and maintains a detailed record of all transactions, ensuring accountability and efficient resource allocation.
                        </p>
                    </div>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
                        <div class="content ps-0 ps-lg-5">
                            <p>
                                Tailored for the needs of schools and the division library, this system reduces manual paperwork, improves coordination, and provides real-time visibility into book availability and usage.
                            </p>

                            <p class="fst-italic">
                                Key features include:
                            </p>
                            <ul>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">Centralized Book Inventory for division-wide management and distribution.</span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">Borrowing Request Creation and Approval workflow for each school.</span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">Book Return Management with status tracking and notifications.</span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">Detailed Reporting on inventory, borrowing history, and school allocations.</span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">Role-Based Access Control for secure and organized operations.</span></li>
                            </ul>

                            <div class="mt-4">
                                <img src="{{ asset('img/korondal-division-2.jpg') }}" class="img-fluid rounded-4 mb-4" alt="Division Library">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section><!-- /About Section -->

        <!-- About Section -->
        <section id="stats" class="stats section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-4 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100" style="outline: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                            <i class="bi bi-book color-blue flex-shrink-0"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="{{ $total_books }}"
                                    data-purecounter-duration="1" class="purecounter"></span>
                                <p>Books</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-4 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100" style="outline: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                            <i class="bi bi-journals color-orange flex-shrink-0"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="{{ $total_copies }}"
                                    data-purecounter-duration="1" class="purecounter"></span>
                                <p>Copies</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-4 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100" style="outline: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                            <i class="bi bi-building color-green flex-shrink-0"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="{{ $total_schools }}"
                                    data-purecounter-duration="1" class="purecounter"></span>
                                <p>Registered Schools</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->



                </div>

            </div>

        </section><!-- /Stats Section -->

        
        <!-- Services Section -->
        <section id="available" class="services section ">

            <!-- Section Title -->
            <div class="container section-title pb-3" data-aos="fade-up">
                <h2>Pick-a-Book </h2>
                <p>AVAILABLE STORYBOOKS AND FICTION BOOKS<br></p>
            </div><!-- End Section Title -->

            <div class="mb-1 ps-3 container-lg d-flex justify-content-start">
                <input type="text" class="input w-100 me-2 ps-3" placeholder="Search by book title..."
                    id="searchInput" style="max-width: 420px; font-size: 12px;">

                <select class="form-select w-100 me-1 ps-3 input" id="orderFilter" style="max-width: 180px; font-size: 12px;">
                    <option value="">Sort by</option>
                    <option value="title_asc">Title (A-Z)</option>
                    <option value="title_desc">Title (Z-A)</option>
                    <option value="author_asc">Author (A-Z)</option>
                    <option value="author_desc">Author (Z-A)</option>
                    <option value="year_desc">Newest First</option>
                    <option value="year_asc">Oldest First</option>
                </select>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="gy-1">
                    <div class=" gy-2">
                        <section class="row row-cols-2 row-cols-sm-2 row-cols-md-4 row-cols-lg-5 pt-4" id="available-books-container">
                            <div class="col-lg-3">
                                <article class="card card--1" style="width: auto;">
                                    <div class="card__info-hover">
                                        <svg class="card__like" viewBox="0 0 24 24">
                                            <path fill="#000000"
                                                d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                                        </svg>
                                        <div class="card__clock-info">
                                            <svg class="card__clock" viewBox="0 0 24 24">
                                                <path
                                                    d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
                                            </svg><span class="card__time">15 min</span>
                                        </div>

                                    </div>
                                    <div class="card__img"></div>
                                    <a href="#" class="card_link">
                                        <div class="card__img--hover"></div>
                                    </a>
                                    <div class="card__info">
                                        <span class="card__category"> Recipe</span>
                                        <h3 class="card__title">Crisp Spanish tortilla Matzo brei</h3>
                                        <span class="card__by">by <a href="#" class="card__author"
                                                title="author">Celeste
                                                Mills</a></span>
                                    </div>
                                </article>
                            </div>

                            <div class="col-lg-3">
                                <article class="card card--1" style="width: auto;">
                                    <div class="card__info-hover">
                                        <svg class="card__like" viewBox="0 0 24 24">
                                            <path fill="#000000"
                                                d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                                        </svg>
                                        <div class="card__clock-info">
                                            <svg class="card__clock" viewBox="0 0 24 24">
                                                <path
                                                    d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
                                            </svg><span class="card__time">15 min</span>
                                        </div>

                                    </div>
                                    <div class="card__img"></div>
                                    <a href="#" class="card_link">
                                        <div class="card__img--hover"></div>
                                    </a>
                                    <div class="card__info">
                                        <span class="card__category"> Recipe</span>
                                        <h3 class="card__title">Crisp Spanish tortilla Matzo brei</h3>
                                        <span class="card__by">by <a href="#" class="card__author"
                                                title="author">Celeste
                                                Mills</a></span>
                                    </div>
                                </article>
                            </div>

                            <div class="col-lg-3 mb-4">
                                <article class="card card--1" style="width: auto;">
                                    <div class="card__info-hover">
                                        <svg class="card__like" viewBox="0 0 24 24">
                                            <path fill="#000000"
                                                d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                                        </svg>
                                        <div class="card__clock-info">
                                            <svg class="card__clock" viewBox="0 0 24 24">
                                                <path
                                                    d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
                                            </svg><span class="card__time">15 min</span>
                                        </div>

                                    </div>
                                    <div class="card__img"></div>
                                    <a href="#" class="card_link">
                                        <div class="card__img--hover"></div>
                                    </a>
                                    <div class="card__info">
                                        <span class="card__category"> Recipe</span>
                                        <h3 class="card__title">Crisp Spanish tortilla Matzo brei</h3>
                                        <span class="card__by">by <a href="#" class="card__author"
                                                title="author">Celeste
                                                Mills</a></span>
                                    </div>
                                </article>
                            </div>

                            <div class="col-lg-3">
                                <article class="card card--1" style="width: auto;">
                                    <div class="card__info-hover">

                                        <div class="card__clock-info">
                                            <svg class="card__clock" viewBox="0 0 24 24">
                                                <path
                                                    d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
                                            </svg><span class="card__time">15 min</span>
                                        </div>

                                    </div>
                                    <div class="card__img"></div>
                                    <a href="#" class="card_link">
                                        <div class="card__img--hover"></div>
                                    </a>
                                    <div class="card__info">
                                        <span class="card__category"> Recipe</span>
                                        <h3 class="card__title">Crisp Spanish tortilla Matzo brei</h3>
                                        <span class="card__by">by <a href="#" class="card__author"
                                                title="author">Celeste
                                                Mills</a></span>
                                    </div>
                                </article>
                            </div>

                            <div class="col-lg-3">
                                <article class="card card--1" style="width: auto;">
                                    <div class="card__info-hover">

                                        <div class="card__clock-info">
                                            <svg class="card__clock" viewBox="0 0 24 24">
                                                <path
                                                    d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
                                            </svg><span class="card__time">15 min</span>
                                        </div>

                                    </div>
                                    <div class="card__img"></div>
                                    <a href="#" class="card_link">
                                        <div class="card__img--hover"></div>
                                    </a>
                                    <div class="card__info">
                                        <span class="card__category"> Recipe</span>
                                        <h3 class="card__title">Crisp Spanish tortilla Matzo brei</h3>
                                        <span class="card__by">by <a href="#" class="card__author"
                                                title="author">Celeste
                                                Mills</a></span>
                                    </div>
                                </article>
                            </div>

                        </section>


                    </div>
                    <div id="paginationContainer" class="d-flex justify-content-center">

                    </div>

                </div>
        </section>

        <section id="top" class="about section light-background">
            <div class="container-lg section-title pb-1" data-aos="fade-up">
                <h2>Books</h2>
                <p>Top Borrowed Books<br></p>
            </div><!-- End Section Title -->

            <div class="container-lg">

                <div class="row gy-4">

                    <section class="card-row-scroll">

                        @foreach ($top_books as $top_book)
                            <div class="w-25">
                                <article class="card card--1" style="min-height: 475px; width: 100%;">
                                    <div class="card__info-hover">
                                        <div class="card__clock-info">
                                            <svg class="card__clock" viewBox="0 0 24 24">
                                                <path
                                                    d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
                                            </svg>
                                            <span class="card__time">
                                                {{ \Carbon\Carbon::parse($top_book->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    @php
                                        $bookPhotoPath = $top_book->book_photo_path
                                            ? asset('storage/' . $top_book->book_photo_path)
                                            : asset('img/no-book-image.jpg');
                                        $publishedYear = \Carbon\Carbon::parse($top_book->published_date)->year ?? '';
                                        $availableCopies = $top_book->total_quantity ?? 0;
                                    @endphp
                                    <div class="card__img" style="background-image: url('{{ $bookPhotoPath }}');"></div>
                                    <a href="#" class="card_link">
                                        <div class="card__img--hover" style="background-image: url('{{ $bookPhotoPath }}');"></div>
                                    </a>
                                    <div class="card__info pb-2 pt-2">
                                        <h6 class="card__title fw-bold">{{ $top_book->title }}</h6>
                                        <div class="card__by">by <a href="#" class="card__author" title="author">{{ $top_book->author }}</a></div>
                                        <span class="card__category mt-2" style="display: inline-block; font-size: 11px;">
                                            Copyright &copy; {{ $publishedYear }}
                                        </span>
                                        
                                    </div>
                                </article>
                            </div>
                        @endforeach

                    </section>

                </div>

            </div>

        </section><!-- /About Section -->

        <!-- Stats Section -->


        @livewire('content.available-books')
        @livewire('content.borrow-book')



    </main>

    <footer id="footer" class="footer dark-background">

        {{-- <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Dewi</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>A108 Adam Street</p>
                        <p>New York, NY 535022</p>
                        <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
                        <p><strong>Email:</strong> <span>info@example.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Graphic Design</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12 footer-newsletter">
                    <h4>Our Newsletter</h4>
                    <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
                    <form action="forms/newsletter.php" method="post" class="php-email-form">
                        <div class="newsletter-form"><input type="email" name="email"><input type="submit"
                                value="Subscribe">
                        </div>
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your subscription request has been sent. Thank you!</div>
                    </form>
                </div>

            </div>
        </div> --}}

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename"></strong>SDO Koronadal City Library <span>All Rights Reserved</span>
            </p>
            <div class="credits">
                Designed by <a href="https://github.com/Brayssz">Brayszz</a> 
            </div>
        </div>


    </footer>



    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- PHP Email Form Validation (No official CDN, keep as is or self-host) -->
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- AOS (Animate on Scroll) -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <!-- GLightbox -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/js/glightbox.min.js"></script>

    <!-- PureCounter -->
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs@1.5.0/dist/purecounter_vanilla.js"></script>

    <!-- Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11.0.5/swiper-bundle.min.js"></script>

    <!-- imagesLoaded -->
    <script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>

    <!-- Isotope Layout -->
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>

    @livewireScripts


    <!-- Main JS File -->
    {{-- <script src="assets/js/main.js"></script> --}}

    @vite(['resources/assets/js/main.js'])
    @stack('scripts')

</body>


</html>
