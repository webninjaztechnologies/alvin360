<?php

return [
    'Elements' => [
        'general' => [
            'dependency' => [
                'css' => [
                    [
                        'name' => 'general',
                        'src' => 'assets/css/general.css',
                        'is_external'   => false
                    ]
                ]
            ]
        ],
        // Social Media icons
        'iqonic_social_icons' => [
            'class' => 'Iqonic\Elementor\Elements\Social_Icons\Widget',
            'dependency' => [
                'css' => [
                    [
                        'name' => 'socials-icon',
                        'src' => 'assets/css/social-icons.css',
                        'is_external'   => false
                    ]
                ],
            ]
        ],

        // button
        'iqonic_button' => [
            'class' => 'Iqonic\Elementor\Elements\Button\Widget'
        ],
        // Section Title
        'iqonic_title' => [
            'class' => 'Iqonic\Elementor\Elements\Title\Widget',
            'dependency' => [
                'css' => [
                    [
                        'name' => 'title',
                        'src' => 'assets/css/title.css',
                        'is_external'   => false
                    ]
                ],
            ]
        ],
        // Accordion
        'iqonic_accordion' => [
            'class' => 'Iqonic\Elementor\Elements\Accordion\Widget',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'accordions',
                        'src' => 'assets/js/accordion.js',
                        'is_external'   => false
                    ]
                ],
                'css' => [
                    [
                        'name' => 'accordion',
                        'src' => 'assets/css/accordion.css',
                        'is_external'   => false
                    ]
                ],
            ]
        ],

        // Blog
        'iqonic_blog' => [
            'class' => 'Iqonic\Elementor\Elements\Blog\Widget',
            'dependency' => [
                'css' => [
                    [
                        'name' => 'blog',
                        'src' => 'assets/css/blog.css',
                        'is_external'   => false
                    ]
                ]
            ]
        ],

        // Blog Slider
        'iqonic_blogs_slider' => [
            'class' => 'Iqonic\Elementor\Elements\BlogSlider\Widget',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'swiper-slider',
                        'src' => 'assets/js/swiper.min.js',
                        'is_external'=> true
                    ],
                    [
                        'name' => 'swiper-general',
                        'src' => 'assets/js/swiper-general.js',
                        'is_external'=> false
                    ]
                ],
                'css' => [
                    [
                        'name' => 'swiper-slider',
                        'src' => 'assets/css/swiper-bundle.min.css',
                        'is_external'=> true
                    ],
                    [
                        'name' => 'blog',
                        'src' => 'assets/css/blog.css',
                        'is_external'=> false
                    ]
                ]
            ]
        ],

        // Testimonial
        'iqonic_testimonial' => [
            'class' => 'Iqonic\Elementor\Elements\Testimonial\Widget',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'swiper-slider',
                        'src' => 'assets/js/swiper.min.js',
                        'is_external'   => true
                    ],
                    [
                        'name' => 'thumbs-swiper',
                        'src' => 'assets/js/thumbs-swiper.js',
                        'is_external'   => false
                    ]
                ],
                'css' => [
                    [
                        'name' => 'swiper-slider',
                        'src' => 'assets/css/swiper-bundle.min.css',
                        'is_external'   => true
                    ],
                    [
                        'name' => 'testimonial',
                        'src' => 'assets/css/testimonial.css',
                        'is_external'   => false
                    ]
                ],
            ]
        ],

        // Service
        'iqonic_iconbox' => [
            'class' => 'Iqonic\Elementor\Elements\IconBox\Widget',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'jquery',
                        'src' => 'assets/js/jquery.min.js',
                        'is_external'   => true
                    ]
                ],
                'css' => [
                    [
                        'name' => 'IconBox',
                        'src' => 'assets/css/iconbox.css',
                        'is_external'   => false
                    ]
                ],
            ]
        ],

        // Timer
        'iqonic_timer' => [
            'class' => 'Iqonic\Elementor\Elements\Timer\Widget',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'countdowntimer',
                        'src' => 'assets/js/jQuery.countdownTimer.min.js',
                        'is_external'   => false
                    ],
                    [
                        'name' => 'timer',
                        'src' => 'assets/js/timer.js',
                        'is_external'=> true
                    ]
                ],
                'css' => [
                    [
                        'name' => 'timer',
                        'src' => 'assets/css/timer.css',
                        'is_external'=> false
                    ]
                ]
            ]
        ],

        /* Counter */
        'iqonic_counter' => [
            'class' => 'Iqonic\Elementor\Elements\Counter\Widget',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'jquery.countTo',
                        'src' => 'assets/js/jquery.countTo.js',
                        'is_external'   => true
                    ],
                    [
                        'name' => 'counter',
                        'src' => 'assets/js/counter.js',
                        'is_external'   => false
                    ]
                ],
                'css' => [
                    [
                        'name' => 'counter',
                        'src' => 'assets/css/counter.css',
                        'is_external'   => false
                    ]
                ],
            ]
        ],

        /* Lists */
        'iqonic_lists' => [
            'class' => 'Iqonic\Elementor\Elements\Lists\Widget',
            'dependency' => [
                'css' => [
                    [
                        'name' => 'list',
                        'src' => 'assets/css/list.css',
                        'is_external'=> false
                    ]
                ],
            ]
        ],

        'iqonic_imageBox' => [
            'class' => 'Iqonic\Elementor\Elements\ImageBox\Widget',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'swiper-slider',
                        'src' => 'assets/js/swiper.min.js',
                        'is_external'   => true
                    ],
                    [
                        'name' => 'swiper-general',
                        'src' => 'assets/js/swiper-general.js',
                        'is_external'   => false
                    ]
                ],
                'css' => [
                    [
                        'name' => 'swiper-slider',
                        'src' => 'assets/css/swiper-bundle.min.css',
                        'is_external'   => true
                    ],
                    [
                        'name' => 'imagebox',
                        'src' => 'assets/css/imagebox.css',
                        'is_external'   => false
                    ]
                ]
            ]
        ],
        /* Before After Image */
        'iqonic_before_after_image' => [
            'class' => 'Iqonic\Elementor\Elements\Before_After_Image\Widget',
            'dependency' => [
                'js' => [
                    [
                        'name' => 'iqonic-event-move',
                        'src' => 'assets/js/jquery.event.move.js',
                        'is_external'   => true
                    ],
                    [
                        'name' => 'jquery-twentytwenty.',
                        'src' => 'assets/js/jquery.twentytwenty.js',
                        'is_external'   => true
                    ],
                    [
                        'name' => 'before-after-img',
                        'src' => 'assets/js/before-after-img.js',
                        'is_external'   => false
                    ]
                ],
                'css' => [
                    [
                        'name' => 'before-after',
                        'src' => 'assets/css/before-after.css',
                        'is_external'   => false
                    ],
                ],
            ]
        ],
        /* pmp pricing plan */
        'iqonic_pmpro_pricing' => [
            'class' => 'Iqonic\Elementor\Elements\PMP_Pricing\Widget',
            'dependency' => [
                'css' => [
                    [
                        'name' => 'pmpro-pricing',
                        'src' => 'assets/css/pmpro-pricing.css',
                        'is_external'   => false
                    ]
                ],
            ]
        ]

    ]
];
