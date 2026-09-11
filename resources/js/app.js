import './bootstrap';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;


/* =========================================================
   AUTOCARE UI
   ========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function () {
        initRevealAnimations();
        initRippleEffects();
        initScrollTopButton();
        initTiltEffects();
    }
);


/* =========================================================
   REVEAL ON SCROLL
   ========================================================= */

function initRevealAnimations() {
    const elements =
        document.querySelectorAll(
            '[data-reveal]'
        );

    if (!elements.length) {
        return;
    }


    if (
        !('IntersectionObserver' in window)
    ) {
        elements.forEach(
            function (element) {
                element.classList.add(
                    'is-visible'
                );
            }
        );

        return;
    }


    const observer =
        new IntersectionObserver(
            function (entries) {
                entries.forEach(
                    function (entry) {
                        if (
                            entry.isIntersecting
                        ) {
                            entry.target
                                .classList
                                .add(
                                    'is-visible'
                                );

                            observer.unobserve(
                                entry.target
                            );
                        }
                    }
                );
            },
            {
                threshold: 0.08,
                rootMargin:
                    '0px 0px -30px 0px',
            }
        );


    elements.forEach(
        function (element, index) {
            element.style
                .transitionDelay =
                `${Math.min(
                    index * 35,
                    220
                )}ms`;

            observer.observe(
                element
            );
        }
    );
}


/* =========================================================
   BUTTON RIPPLE
   ========================================================= */

function initRippleEffects() {
    const buttons =
        document.querySelectorAll(
            '.btn, [data-autocare-ripple]'
        );


    buttons.forEach(
        function (button) {
            button.addEventListener(
                'click',
                function (event) {
                    if (
                        button.disabled
                        ||
                        button.classList
                            .contains(
                                'disabled'
                            )
                    ) {
                        return;
                    }


                    const rect =
                        button
                            .getBoundingClientRect();


                    const size =
                        Math.max(
                            rect.width,
                            rect.height
                        );


                    const ripple =
                        document
                            .createElement(
                                'span'
                            );


                    ripple.className =
                        'autocare-ripple';


                    ripple.style.width =
                        `${size}px`;

                    ripple.style.height =
                        `${size}px`;


                    ripple.style.left =
                        `${
                            event.clientX
                            - rect.left
                            - size / 2
                        }px`;


                    ripple.style.top =
                        `${
                            event.clientY
                            - rect.top
                            - size / 2
                        }px`;


                    button.appendChild(
                        ripple
                    );


                    window.setTimeout(
                        function () {
                            ripple.remove();
                        },
                        600
                    );
                }
            );
        }
    );
}


/* =========================================================
   SCROLL TO TOP
   ========================================================= */

function initScrollTopButton() {
    const button =
        document.getElementById(
            'scrollTopButton'
        );


    if (!button) {
        return;
    }


    function updateButton() {
        if (
            window.scrollY > 350
        ) {
            button.classList.add(
                'show'
            );
        } else {
            button.classList.remove(
                'show'
            );
        }
    }


    window.addEventListener(
        'scroll',
        updateButton,
        {
            passive: true,
        }
    );


    button.addEventListener(
        'click',
        function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });
        }
    );


    updateButton();
}


/* =========================================================
   LIGHT 3D TILT
   ========================================================= */

function initTiltEffects() {
    const elements =
        document.querySelectorAll(
            '[data-tilt]'
        );


    elements.forEach(
        function (element) {
            element.addEventListener(
                'mousemove',
                function (event) {
                    if (
                        window.innerWidth
                        < 992
                    ) {
                        return;
                    }


                    const rect =
                        element
                            .getBoundingClientRect();


                    const x =
                        event.clientX
                        - rect.left;


                    const y =
                        event.clientY
                        - rect.top;


                    const centerX =
                        rect.width / 2;


                    const centerY =
                        rect.height / 2;


                    const rotateX =
                        (
                            centerY - y
                        )
                        / 35;


                    const rotateY =
                        (
                            x - centerX
                        )
                        / 35;


                    element.style.transform =
                        `
                            perspective(900px)
                            rotateX(${rotateX}deg)
                            rotateY(${rotateY}deg)
                            translateY(-4px)
                        `;
                }
            );


            element.addEventListener(
                'mouseleave',
                function () {
                    element.style.transform =
                        '';
                }
            );
        }
    );
}