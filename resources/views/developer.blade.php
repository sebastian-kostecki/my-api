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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
                <h1 id="skills" class="skills-header pb-5">Umiejętności</h1>
                <div class="row border-bottom border-secondary">
                    <div class="col-2 d-flex flex-column">
                        <svg xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 0 640 512">
                            <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <style>svg {
                                    fill: #ffffff
                                }</style>
                            <path
                                d="M320 104.5c171.4 0 303.2 72.2 303.2 151.5S491.3 407.5 320 407.5c-171.4 0-303.2-72.2-303.2-151.5S148.7 104.5 320 104.5m0-16.8C143.3 87.7 0 163 0 256s143.3 168.3 320 168.3S640 349 640 256 496.7 87.7 320 87.7zM218.2 242.5c-7.9 40.5-35.8 36.3-70.1 36.3l13.7-70.6c38 0 63.8-4.1 56.4 34.3zM97.4 350.3h36.7l8.7-44.8c41.1 0 66.6 3 90.2-19.1 26.1-24 32.9-66.7 14.3-88.1-9.7-11.2-25.3-16.7-46.5-16.7h-70.7L97.4 350.3zm185.7-213.6h36.5l-8.7 44.8c31.5 0 60.7-2.3 74.8 10.7 14.8 13.6 7.7 31-8.3 113.1h-37c15.4-79.4 18.3-86 12.7-92-5.4-5.8-17.7-4.6-47.4-4.6l-18.8 96.6h-36.5l32.7-168.6zM505 242.5c-8 41.1-36.7 36.3-70.1 36.3l13.7-70.6c38.2 0 63.8-4.1 56.4 34.3zM384.2 350.3H421l8.7-44.8c43.2 0 67.1 2.5 90.2-19.1 26.1-24 32.9-66.7 14.3-88.1-9.7-11.2-25.3-16.7-46.5-16.7H417l-32.8 168.7z"/>
                        </svg>
                        <p class="text-center fs-6">PHP 8.2</p>
                    </div>
                    <div class="col-2 d-flex flex-column">
                        <svg xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 0 512 512">
                            <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <style>svg {
                                    fill: #ffffff
                                }</style>
                            <path
                                d="M504.4,115.83a5.72,5.72,0,0,0-.28-.68,8.52,8.52,0,0,0-.53-1.25,6,6,0,0,0-.54-.71,9.36,9.36,0,0,0-.72-.94c-.23-.22-.52-.4-.77-.6a8.84,8.84,0,0,0-.9-.68L404.4,55.55a8,8,0,0,0-8,0L300.12,111h0a8.07,8.07,0,0,0-.88.69,7.68,7.68,0,0,0-.78.6,8.23,8.23,0,0,0-.72.93c-.17.24-.39.45-.54.71a9.7,9.7,0,0,0-.52,1.25c-.08.23-.21.44-.28.68a8.08,8.08,0,0,0-.28,2.08V223.18l-80.22,46.19V63.44a7.8,7.8,0,0,0-.28-2.09c-.06-.24-.2-.45-.28-.68a8.35,8.35,0,0,0-.52-1.24c-.14-.26-.37-.47-.54-.72a9.36,9.36,0,0,0-.72-.94,9.46,9.46,0,0,0-.78-.6,9.8,9.8,0,0,0-.88-.68h0L115.61,1.07a8,8,0,0,0-8,0L11.34,56.49h0a6.52,6.52,0,0,0-.88.69,7.81,7.81,0,0,0-.79.6,8.15,8.15,0,0,0-.71.93c-.18.25-.4.46-.55.72a7.88,7.88,0,0,0-.51,1.24,6.46,6.46,0,0,0-.29.67,8.18,8.18,0,0,0-.28,2.1v329.7a8,8,0,0,0,4,6.95l192.5,110.84a8.83,8.83,0,0,0,1.33.54c.21.08.41.2.63.26a7.92,7.92,0,0,0,4.1,0c.2-.05.37-.16.55-.22a8.6,8.6,0,0,0,1.4-.58L404.4,400.09a8,8,0,0,0,4-6.95V287.88l92.24-53.11a8,8,0,0,0,4-7V117.92A8.63,8.63,0,0,0,504.4,115.83ZM111.6,17.28h0l80.19,46.15-80.2,46.18L31.41,63.44Zm88.25,60V278.6l-46.53,26.79-33.69,19.4V123.5l46.53-26.79Zm0,412.78L23.37,388.5V77.32L57.06,96.7l46.52,26.8V338.68a6.94,6.94,0,0,0,.12.9,8,8,0,0,0,.16,1.18h0a5.92,5.92,0,0,0,.38.9,6.38,6.38,0,0,0,.42,1v0a8.54,8.54,0,0,0,.6.78,7.62,7.62,0,0,0,.66.84l0,0c.23.22.52.38.77.58a8.93,8.93,0,0,0,.86.66l0,0,0,0,92.19,52.18Zm8-106.17-80.06-45.32,84.09-48.41,92.26-53.11,80.13,46.13-58.8,33.56Zm184.52,4.57L215.88,490.11V397.8L346.6,323.2l45.77-26.15Zm0-119.13L358.68,250l-46.53-26.79V131.79l33.69,19.4L392.37,178Zm8-105.28-80.2-46.17,80.2-46.16,80.18,46.15Zm8,105.28V178L455,151.19l33.68-19.4v91.39h0Z"/>
                        </svg>
                        <p class="text-center fs-6">Laravel 10</p>
                    </div>
                    <div class="col-2 d-flex flex-column">
                    </div>
                    <div class="col-6 d-flex align-items-center">
                        <p>Pracuję głównie w języku <strong>PHP</strong> korzystając z frameworku
                            <strong>Laravel</strong>.
                            W przyszłości planuję naukę Symfony.</p>
                    </div>
                </div>
                <div class="row border-bottom border-secondary pt-3">
                    <div class="col-2 d-flex flex-column">
                        <svg xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 0 448 512">
                            <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <style>svg {
                                    fill: #ffffff
                                }</style>
                            <path
                                d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM243.8 381.4c0 43.6-25.6 63.5-62.9 63.5-33.7 0-53.2-17.4-63.2-38.5l34.3-20.7c6.6 11.7 12.6 21.6 27.1 21.6 13.8 0 22.6-5.4 22.6-26.5V237.7h42.1v143.7zm99.6 63.5c-39.1 0-64.4-18.6-76.7-43l34.3-19.8c9 14.7 20.8 25.6 41.5 25.6 17.4 0 28.6-8.7 28.6-20.8 0-14.4-11.4-19.5-30.7-28l-10.5-4.5c-30.4-12.9-50.5-29.2-50.5-63.5 0-31.6 24.1-55.6 61.6-55.6 26.8 0 46 9.3 59.8 33.7L368 290c-7.2-12.9-15-18-27.1-18-12.3 0-20.1 7.8-20.1 18 0 12.6 7.8 17.7 25.9 25.6l10.5 4.5c35.8 15.3 55.9 31 55.9 66.2 0 37.8-29.8 58.6-69.7 58.6z"/>
                        </svg>
                        <p class="text-center fs-6">Java Script</p>
                    </div>
                    <div class="col-2 d-flex flex-column">
                        <svg xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 0 448 512">
                            <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <style>svg {
                                    fill: #ffffff
                                }</style>
                            <path
                                d="M356.9 64.3H280l-56 88.6-48-88.6H0L224 448 448 64.3h-91.1zm-301.2 32h53.8L224 294.5 338.4 96.3h53.8L224 384.5 55.7 96.3z"/>
                        </svg>
                        <p class="text-center fs-6">Vue.js 2 & 3</p>
                    </div>
                    <div class="col-2 d-flex flex-column">
                        <svg xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 0 122.89 122.89">
                            <style>svg {
                                    fill: #ffffff
                                }</style>

                            <path class="st0"
                                  d="M61.44,33.47c-15.45,0-28,12.54-28,28c0,15.47,12.54,28.01,28,28.01c15.47,0,28.01-12.54,28.01-28.01 C89.44,46.01,76.9,33.48,61.44,33.47L61.44,33.47L61.44,33.47z M117.37,36.01l-0.54-1.17l-0.84,0.98c-2.09,2.37-4.74,4.2-7.7,5.3 l-0.84,0.3l0.33,0.84c2.54,6.11,3.85,12.68,3.85,19.28c0,27.73-22.49,50.24-50.23,50.24S11.18,89.27,11.18,61.53 s22.49-50.23,50.23-50.23c7.46,0,14.81,1.66,21.55,4.86l0.78,0.37l0.33-0.84c1.25-2.89,3.21-5.45,5.68-7.42l0.99-0.84l-1.13-0.59 C80.97,2.35,71.34,0,61.58,0c-0.03,0-0.1,0-0.16,0C27.51,0,0,27.51,0,61.44s27.53,61.44,61.44,61.44 c33.94,0,61.44-27.51,61.44-61.44c0-8.8-1.88-17.51-5.54-25.5L117.37,36.01L117.37,36.01z M114.4,23.19 c0-7.04-5.7-12.75-12.75-12.75c-7.04,0-12.75,5.71-12.75,12.75c0,7.06,5.71,12.77,12.75,12.77 C108.71,35.96,114.4,30.24,114.4,23.19L114.4,23.19z"/>
                        </svg>
                        <p class="text-center fs-6">Ionic</p>
                    </div>
                    <div class="col-6 d-flex align-items-center">
                        <p>W pracy wykorzystuję framework <strong>Vue.js</strong> w wersji 2. Obecnie poznaję wersję 3.
                            Tworząc aplikację mobilną wykorzystywałem framework <strong>Ionic</strong>.
                        </p>
                    </div>
                </div>
                <div class="row border-bottom border-secondary pt-3">
                    <div class="col-2 d-flex flex-column">
                        <svg xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 0 448 512">
                            <style>svg {
                                    fill: #ffffff
                                }</style>
                            <path
                                d="M448 80v48c0 44.2-100.3 80-224 80S0 172.2 0 128V80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6V288c0 44.2-100.3 80-224 80S0 332.2 0 288V186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6V432c0 44.2-100.3 80-224 80S0 476.2 0 432V346.1z"/>
                        </svg>
                        <p class="text-center fs-6">MySQL</p>
                    </div>
                    <div class="col-2 d-flex flex-column">
                    </div>
                    <div class="col-2 d-flex flex-column">
                    </div>
                    <div class="col-6 d-flex align-items-center">
                        <p>Pracuję na bazie danych <strong>MySQL.</strong></p>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-2 d-flex flex-column">
                        <svg xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 0 448 512">
                            <style>svg {
                                    fill: #ffffff
                                }</style>
                            <path
                                d="M439.55 236.05L244 40.45a28.87 28.87 0 0 0-40.81 0l-40.66 40.63 51.52 51.52c27.06-9.14 52.68 16.77 43.39 43.68l49.66 49.66c34.23-11.8 61.18 31 35.47 56.69-26.49 26.49-70.21-2.87-56-37.34L240.22 199v121.85c25.3 12.54 22.26 41.85 9.08 55a34.34 34.34 0 0 1-48.55 0c-17.57-17.6-11.07-46.91 11.25-56v-123c-20.8-8.51-24.6-30.74-18.64-45L142.57 101 8.45 235.14a28.86 28.86 0 0 0 0 40.81l195.61 195.6a28.86 28.86 0 0 0 40.8 0l194.69-194.69a28.86 28.86 0 0 0 0-40.81z"/>
                        </svg>
                        <p class="text-center fs-6">GIT</p>
                    </div>
                    <div class="col-2 d-flex flex-column">
                        <svg role="img" viewBox="0 0 24 24" height="64" xmlns="http://www.w3.org/2000/svg"><title>
                                PhpStorm</title>
                            <path
                                d="M7.833 6.611v-.055c0-1-.667-1.5-1.778-1.5H4.389v3.056h1.722c1.111-.001 1.722-.668 1.722-1.501zM0 0v24h24V0H0zm2.167 3.111h4.056c2.389 0 3.833 1.389 3.833 3.445v.055c0 2.333-1.778 3.5-4.056 3.5H4.333v3H2.167v-10zM11.278 21h-9v-1.5h9V21zM18.5 10.222c0 2-1.5 3.111-3.667 3.111-1.5-.056-3-.611-4.222-1.667l1.278-1.556c.89.722 1.833 1.222 3 1.222.889 0 1.444-.333 1.444-.944v-.056c0-.555-.333-.833-2-1.277C12.333 8.555 11 8 11 6v-.056c0-1.833 1.444-3 3.5-3 1.444 0 2.723.444 3.723 1.278l-1.167 1.667c-.889-.611-1.777-1-2.611-1-.833 0-1.278.389-1.278.889v.056c0 .667.445.889 2.167 1.333 2 .556 3.167 1.278 3.167 3v.055z"/>
                        </svg>
                        <p class="text-center fs-6">PHP Storm</p>
                    </div>
                    <div class="col-2 d-flex flex-column">
                    </div>
                    <div class="col-6 d-flex align-items-center">
                        <p>Posługuję się systemem kontroli wersji <strong>GIT</strong> oraz moim środowiskiem
                            programistycznym jest <strong>PHP Storm</strong>.</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="bg-black text-white">
                    <div class="container py-5">
                        <h1 id="projects" class="skills-header pb-4">Moje Projekty</h1>
                        <div class="row">
                            <div class="col-6">
                                <div class="card">
                                    <img src="{{ asset('/img/keep-wallet.png') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title text-white fw-bold">Ogarniam Portfel</h5>
                                        <p class="card-text text-white">Aplikacja internetowa do zarządzania domowym
                                            budżetem.</p>
                                        <a href="https://budget.sebastian-kostecki.profesjonalnyprogramista.pl/"
                                           target="_blank" class="btn btn-danger me-2">Przejdź do strony</a>
                                        <a href="https://github.com/sebastian-kostecki/keep-wallet" target="_blank"
                                           class="btn btn-danger">Przejdź do GitHub</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card">
                                    <img src="{{ asset('/img/o-nia-o-niego.jpg') }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title text-white fw-bold">O Nią & O Niego</h5>
                                        <p class="card-text text-white">Aplikacja mobilna pozwalająca użytkownikowi odmawiać odpowiednią tajemnicę różańcową.</p>
                                        <a href="https://play.google.com/store/apps/details?id=kostecki.sebastian.oniaoniego"
                                           target="_blank" class="btn btn-danger me-2">Przejdź do aplikacji</a>
                                        <a href="https://github.com/sebastian-kostecki/o-nia-o-niego" target="_blank"
                                           class="btn btn-danger">Przejdź do GitHub</a>
                                    </div>
                                </div>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 0 512 512"
                                         class="position-absolute top-50 start-50 translate-middle">
                                        <style>svg {
                                                fill: #ffffff
                                            }</style>
                                        <path
                                            d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/>
                                    </svg>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 0 512 512"
                                         class="position-absolute top-50 start-50 translate-middle">
                                        <style>svg {
                                                fill: #ffffff
                                            }</style>
                                        <path
                                            d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/>
                                    </svg>
                                </div>
                                <p class="fw-bold mt-3 mb-1">TELEFON</p>
                                <p>+48 502 321 934</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div
                                class="d-flex flex-column contact-card rounded rounded-3 mb-3 p-4 d-flex align-items-center">
                                <div class="rounded-circle bg-secondary contact-card-icon position-relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 0 448 512"
                                         class="position-absolute top-50 start-50 translate-middle">
                                        <style>svg {
                                                fill: #ffffff
                                            }</style>
                                        <path
                                            d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/>
                                    </svg>
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
                    <div class="py-5s">
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
            <div class="d-flex justify-content-between mt-5">
                <p>Wszystkie prawa zastrzeżone &copy; 2023</p>
                <div>
                    <a class="me-2" href="https://www.linkedin.com/in/kostecki-sebastian">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 448 512">
                            <style>svg {
                                    fill: #ffffff
                                }</style>
                            <path
                                d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/>
                        </svg>
                    </a>
                    <a href="https://github.com/sebastian-kostecki">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 496 512">
                            <style>svg {
                                    fill: #ffffff
                                }</style>
                            <path
                                d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/>
                        </svg>
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
