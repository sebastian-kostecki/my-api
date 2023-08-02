<?php

namespace App\Http\Controllers\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HomepageController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = [
            'skills' => [
                'php' => [
                    'description' => 'Pracuję głównie w języku PHP korzystając z frameworku Laravel. W przyszłości planuję naukę Symfony.',
                    'item' => [
                        [
                            'name' => 'PHP 8.2',
                            'icon' => 'php'
                        ],
                        [
                            'name' => 'Laravel 10',
                            'icon' => 'laravel'
                        ]
                    ]
                ],
                'javascript' => [
                    'description' => 'W pracy wykorzystuję framework Vue.js w wersji 2. Obecnie poznaję wersję 3. Tworząc aplikację mobilną wykorzystywałem framework Ionic',
                    'item' => [
                        [
                            'name' => 'JavaScript',
                            'icon' => 'javascript'
                        ],
                        [
                            'name' => 'Vue.js 2 & 3',
                            'icon' => 'vue'
                        ],
                        [
                            'name' => 'Ionic',
                            'icon' => 'ionic'
                        ],
                    ]
                ],
                'database' => [
                    'description' => 'Pracuję na bazie danych MySQL.',
                    'item' => [
                        [
                            'name' => 'MySQL',
                            'icon' => 'mysql'
                        ],
                    ]
                ],
                'tools' => [
                    'description' => 'Posługuję się systemem kontroli wersji GIT oraz moim środowiskiem programistycznym jest PHP Storm',
                    'item' => [
                        [
                            'name' => 'GIT',
                            'icon' => 'git'
                        ],
                        [
                            'name' => 'PHP Storm',
                            'icon' => 'phpstorm'
                        ]
                    ]
                ]
            ],
            'projects' => [
                [
                    'img' => 'keep-wallet.png',
                    'title' => 'Ogarniam Portfel',
                    'subtitle' => 'Aplikacja internetowa do zarządzania domowym budżetem.',
                    'links' => [
                        [
                            'title' => 'Przejdź do strony',
                            'link' => 'https://budget.sebastian-kostecki.profesjonalnyprogramista.pl/'
                        ],
                        [
                            'title' => 'Przejdź do GitHub',
                            'link' => 'https://github.com/sebastian-kostecki/keep-wallet'
                        ]
                    ]
                ],
                [
                    'img' => 'o-nia-o-niego.jpg',
                    'title' => 'O Nią & O Niego',
                    'subtitle' => 'Aplikacja mobilna pomagająca użytkownikowi odmawiać odpowiednią tajemnicę różańcową.',
                    'links' => [
                        [
                            'title' => 'Przejdź do aplikacji',
                            'link' => 'https://play.google.com/store/apps/details?id=kostecki.sebastian.oniaoniego'
                        ],
                        [
                            'title' => 'Przejdź do GitHub',
                            'link' => 'https://github.com/sebastian-kostecki/o-nia-o-niego'
                        ]
                    ]
                ]
            ]
        ];

        return view('homepage.developer', $data);
    }
}
