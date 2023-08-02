<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sebastian Kostecki</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&family=Wix+Madefor+Display:wght@400;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div class="vh-100">
    <header class="header h-100">
        <div class="container h-100 position-relative">
            <div class="w-100 position-absolute top-50 start-50 translate-middle">
                <h1 class="title">SEBASTIAN KOSTECKI</h1>
                <h2 class="subtitle">Webdeveloper | PHP | Laravel</h2>
            </div>
            <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                <ul class="list-unstyled d-flex">
                    <li class="me-3 fs-5">
                        <a class="link-light link-offset-2 link-underline-opacity-10 link-underline-opacity-100-hover"
                           href="#about-me">O mnie</a>
                    </li>
                    <li class="me-2 fs-5">
                        <a class="link-light link-offset-2 link-underline-opacity-10 link-underline-opacity-100-hover"
                           href="#skills">Umiejętności</a>
                    </li>
                    <li class="me-2 fs-5">
                        <a class="link-light link-offset-2 link-underline-opacity-10 link-underline-opacity-100-hover"
                           href="#projects">Moje Projekty</a>
                    </li>
                    <li class="me-2 fs-5">
                        <a class="link-light link-offset-2 link-underline-opacity-10 link-underline-opacity-100-hover"
                           href="#contact">Kontakt</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <main>
        <div class="container about-me py-5">
            <h1 id="about-me" class="about-me-header mb-4">O mnie</h1>
            <div class="row about-me-content">
                <div class="col">
                    <p>Cześć! Jestem pasjonatem tworzenia stron internetowych. Specjalizuję się w frameworku Laravel,
                        który
                        pozwala mi tworzyć niezwykle wydajne i niezawodne aplikacje. Moje umiejętności obejmują głównie
                        back-end, ale rozwijam również swoją wiedzę z zakresu front-endu.</p>
                </div>
                <div class="col">
                    <p>Z pasją podchodzę do swoich zadań. Staram się w pełni zrozumieć oczekiwania klienta, aby
                        następnie
                        tworzyć wysokiej jakości produkty końcowe. Poza pracą skupiam się głównie na czasie z rodziną
                        oraz
                        rozwoju swoich umiejętności w IT.</p>
                </div>
            </div>
        </div>
        <div class="bg-black text-white">
            <div class="container py-5">
                <h1 id="skills" class="skills-header pb-3">Umiejętności</h1>
                <div>
                    @foreach($skills as $skill)
                        <div class="row border-bottom border-secondary pt-4">
                            <div class="col-6 d-flex">
                                @foreach($skill['item'] as $item)
                                    <div class="d-flex flex-column me-5">
                                        <img height="64" src="{{ asset('/assets/icons/' . $item['icon'] . '.svg') }}"
                                             alt="{{ $item['icon'] }}"/>
                                        <p class="text-center fs-6 mt-2">{{ $item['name'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-6 d-flex align-items-center">
                                <p>{{ $skill['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="container">
                <div class="bg-black text-white">
                    <div class="container py-5">
                        <h1 id="projects" class="skills-header pb-4">Moje Projekty</h1>
                        <div>
                            <div class="row row-cols-1 row-cols-md-2 g-4">
                                @foreach($projects as $project)
                                    <div class="col">
                                        <div class="card">
                                            <img src="{{ asset('assets/img/' . $project['img']) }}" class="card-img-top"
                                                 alt="{{ $project['img'] }}">
                                            <div class="card-body">
                                                <h5 class="card-title text-white fw-bold">{{ $project['title'] }}</h5>
                                                <p class="card-text text-white">{{ $project['subtitle'] }}</p>
                                                @foreach($project['links'] as $link)
                                                    <a href="{{ $link['link'] }}"
                                                       target="_blank"
                                                       class="btn btn-danger me-2">{{ $link['title'] }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-black text-white">
                    <div class="container pt-5 pb-2">
                        <h1 id="contact" class="skills-header text-center">Kontakt ze mną</h1>
                        <h2 class="text-center fs-6">Chcesz współpracować? To świetnie! Oto moje dane kontaktowe:</h2>
                    </div>
                    <div class="row py-5">
                        <div class="col-4">
                            <div
                                class="d-flex flex-column contact-card rounded rounded-3 mb-3 p-4 d-flex align-items-center">
                                <div class="rounded-circle bg-secondary contact-card-icon position-relative">
                                    <img height="48" src="{{ asset('/assets/icons/envelope.svg') }}"
                                         class="position-absolute top-50 start-50 translate-middle"/>
                                </div>
                                <p class="fw-bold mt-3 mb-1">E-MAIL</p>
                                <p>
                                    <a class="link-light text-decoration-none"
                                       href="mailto:sebastian.kostecki@gmail.com">
                                        sebastian.kostecki@gmail.com</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div
                                class="d-flex flex-column contact-card rounded rounded-3 mb-3 p-4 d-flex align-items-center">
                                <div class="rounded-circle bg-secondary contact-card-icon position-relative">
                                    <img height="48" src="{{ asset('/assets/icons/phone.svg') }}"
                                         class="position-absolute top-50 start-50 translate-middle"/>

                                </div>
                                <p class="fw-bold mt-3 mb-1">TELEFON</p>
                                <p>+48 502 321 934</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div
                                class="d-flex flex-column contact-card rounded rounded-3 mb-3 p-4 d-flex align-items-center">
                                <div class="rounded-circle bg-secondary contact-card-icon position-relative">
                                    <img height="48" src="{{ asset('/assets/icons/linkedin.svg') }}"
                                         class="position-absolute top-50 start-50 translate-middle"/>
                                </div>
                                <p class="fw-bold mt-3 mb-1">LINKEDIN</p>
                                <p>
                                    <a class="link-light text-decoration-none" target="_blank"
                                       href="https://www.linkedin.com/in/kostecki-sebastian">
                                        in/kostecki-sebastian</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="py-5">
                        <h1 class="skills-header mb-4">Czekasz na idealnego developera?</h1>
                        <a class="btn btn-danger me-3" href="#contact" role="button">Skontaktuj się ze mną</a>
                        <a class="btn btn-danger" href="#skills" role="button">Sprawdź moje umiejętności</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-black text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <p>Usługi</p>
                    <ul class="list-unstyled">
                        <li>Tworzenie stron i aplikacji internetowych</li>
                        <li>Back-end dla aplikacji internetowych</li>
                    </ul>
                </div>
                <div class="col-4">
                    <p>Media</p>
                    <ul class="list-unstyled">
                        <li>
                            <a class="link-light text-decoration-none"
                               href="https://www.linkedin.com/in/kostecki-sebastian">LinkedIn</a>
                        </li>
                        <li>
                            <a class="link-light text-decoration-none" href="https://github.com/sebastian-kostecki">GitHub</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <p>Wszystkie prawa zastrzeżone &copy; 2023</p>
                <div>
                    <a class="me-2" href="https://www.linkedin.com/in/kostecki-sebastian">
                        <img src="{{ asset('assets/icons/linkedin.svg') }}" alt="linkedin" height="20">
                    </a>
                    <a href="https://github.com/sebastian-kostecki">
                        <img src="{{ asset('assets/icons/github.svg') }}" alt="linkedin" height="20">
                    </a>
                </div>
            </div>
        </div>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>
</html>
