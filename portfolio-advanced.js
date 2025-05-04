
document.addEventListener('DOMContentLoaded', function() {
    // Preloader
    setTimeout(function() {
        document.querySelector('.preloader').style.opacity = '0';
        setTimeout(function() {
            document.querySelector('.preloader').style.display = 'none';
        }, 500);
    }, 1500);

    // Custom cursor
    try {
        const cursor = document.querySelector('.cursor');
        const cursorFollower = document.querySelector('.cursor-follower');
        
        if (cursor && cursorFollower) {
            // Check if we're on a touch device
            const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
            
            if (!isTouchDevice) {
                // Hide default cursor
                document.body.style.cursor = 'none';
                
                // Show custom cursor
                cursor.style.display = 'block';
                cursorFollower.style.display = 'block';
                
                document.addEventListener('mousemove', function(e) {
                    cursor.style.left = e.clientX + 'px';
                    cursor.style.top = e.clientY + 'px';
                    
                    setTimeout(function() {
                        cursorFollower.style.left = e.clientX + 'px';
                        cursorFollower.style.top = e.clientY + 'px';
                    }, 80);
                });
        
                document.addEventListener('mousedown', function() {
                    cursor.style.transform = 'translate(-50%, -50%) scale(0.7)';
                    cursorFollower.style.transform = 'translate(-50%, -50%) scale(0.7)';
                });
        
                document.addEventListener('mouseup', function() {
                    cursor.style.transform = 'translate(-50%, -50%) scale(1)';
                    cursorFollower.style.transform = 'translate(-50%, -50%) scale(1)';
                });
        
                // Add hover effect to links and buttons
                const hoverElements = document.querySelectorAll('a, button, .skill-card, .project-card, .certification-card');
                
                hoverElements.forEach(element => {
                    element.addEventListener('mouseenter', function() {
                        cursor.style.transform = 'translate(-50%, -50%) scale(1.5)';
                        cursor.style.backgroundColor = 'transparent';
                        cursor.style.border = '1px solid var(--primary-color)';
                        cursorFollower.style.opacity = '0.3';
                    });
                    
                    element.addEventListener('mouseleave', function() {
                        cursor.style.transform = 'translate(-50%, -50%) scale(1)';
                        cursor.style.backgroundColor = 'var(--primary-color)';
                        cursor.style.border = 'none';
                        cursorFollower.style.opacity = '1';
                    });
                });
            } else {
                // Hide custom cursor on touch devices
                cursor.style.display = 'none';
                cursorFollower.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Error initializing custom cursor:', error);
    }

    // Enhanced Mobile menu functionality
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileClose = document.querySelector('.mobile-close');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    const body = document.body;
    
    // Function to toggle mobile menu
    function toggleMobileMenu(show) {
        if (show) {
            mobileMenu.classList.add('active');
            hamburger.classList.add('active');
            mobileMenu.style.right = '0';
            mobileMenu.setAttribute('aria-hidden', 'false');
            body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
        } else {
            mobileMenu.classList.remove('active');
            hamburger.classList.remove('active');
            mobileMenu.style.right = '-100%';
            mobileMenu.setAttribute('aria-hidden', 'true');
            body.style.overflow = ''; // Restore scrolling
        }
    }
    
    // Toggle menu when hamburger is clicked
    if (hamburger) {
        hamburger.addEventListener('click', function() {
            const isActive = mobileMenu.classList.contains('active');
            toggleMobileMenu(!isActive);
        });
    }
    
    // Close menu when close button is clicked
    if (mobileClose) {
        mobileClose.addEventListener('click', function() {
            toggleMobileMenu(false);
        });
    }
    
    // Close menu when a nav link is clicked
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            toggleMobileMenu(false);
        });
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        if (mobileMenu.classList.contains('active') && 
            !mobileMenu.contains(event.target) && 
            !hamburger.contains(event.target)) {
            toggleMobileMenu(false);
        }
    });
    
    // Close menu on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && mobileMenu.classList.contains('active')) {
            toggleMobileMenu(false);
        }
    });

    // Theme toggle functionality is now handled in theme-toggle.js

    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            window.scrollTo({
                top: targetSection.offsetTop - 80,
                behavior: 'smooth'
            });
            
            // Close mobile menu if open
            if (mobileMenu.classList.contains('active')) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });
    });

    // Active navigation link on scroll
    const sections = document.querySelectorAll('section');
    
    window.addEventListener('scroll', function() {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (pageYOffset >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });

    // Project filtering
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            projectCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 100);
                } else if (card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 100);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Enhanced progress bar animation with staggered loading and visual effects
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('[data-width]');
        
        // Reset all bars to 0% width first
        progressBars.forEach(bar => {
            bar.style.width = '0%';
        });
        
        // Animate each bar with a staggered delay
        progressBars.forEach((bar, index) => {
            const width = bar.getAttribute('data-width');
            const delay = 300 + (index * 100); // Staggered delay for each bar
            
            setTimeout(() => {
                // Animate to the target width
                bar.style.width = width;
                
                // Add a subtle pulse effect after the bar reaches its full width
                setTimeout(() => {
                    bar.classList.add('pulse-once');
                    
                    // Remove the pulse class after animation completes
                    setTimeout(() => {
                        bar.classList.remove('pulse-once');
                    }, 1000);
                }, 700);
            }, delay);
        });
    }

    // Initialize progress bar animation when skills section is in view
    const skillsSection = document.querySelector('#skills');
    const techProficiency = document.querySelector('.technical-proficiency');
    
    const observeSkills = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Delay the animation slightly for better user experience
                setTimeout(animateProgressBars, 400);
                
                // Don't unobserve to allow re-animation when scrolling back to section
                // This creates a better user experience
            }
        });
    }, { 
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px' // Trigger slightly before the element is fully in view
    });
    
    // Observe both the skills section and the technical proficiency section
    if (skillsSection) {
        observeSkills.observe(skillsSection);
    }
    
    if (techProficiency) {
        observeSkills.observe(techProficiency);
    }
    
    // Add CSS for the skill bar animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse-once {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(1.05); }
        }
        .pulse-once {
            animation: pulse-once 0.6s ease-in-out;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) skewX(-30deg); }
            100% { transform: translateX(200%) skewX(-30deg); }
        }
        
        .skill-bar-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50px;
            height: 100%;
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
            animation: shine 2s ease-in-out infinite;
            transform: skewX(-30deg);
        }
    `;
    document.head.appendChild(style);
    
    // Add dark mode class to html element for Tailwind dark mode to work
    if (document.body.classList.contains('dark-theme')) {
        document.documentElement.classList.add('dark');
    }

    // Scroll reveal animation
    const sr = ScrollReveal({
        origin: 'bottom',
        distance: '60px',
        duration: 1000,
        delay: 200,
        reset: false
    });

    // Reveal animations for different sections
    sr.reveal('.hero-text', {});
    sr.reveal('.hero-image', { delay: 400 });
    sr.reveal('.scroll-indicator', { delay: 600 });
    sr.reveal('.section-header', {});
    sr.reveal('.about-image', { origin: 'left' });
    sr.reveal('.about-text', { origin: 'right', delay: 300 });
    sr.reveal('.skills-text', { origin: 'left' });
    sr.reveal('.skill-card', { interval: 100 });
    sr.reveal('.progress-item', { interval: 100 });
    sr.reveal('.filter-btn', { interval: 50 });
    sr.reveal('.project-card', { interval: 100 });
    sr.reveal('.achievement-card', { interval: 100 });
    sr.reveal('.testimonial-card', { interval: 100 });
    sr.reveal('.contact-info', { origin: 'left' });
    sr.reveal('.contact-form', { origin: 'right', delay: 300 });

    // Optimized Particles background animation for mobile and desktop
    try {
        if (typeof particlesJS !== 'undefined') {
            // Check if device is mobile
            const isMobile = window.innerWidth < 768;
            
            // Configure particles based on device type
            particlesJS('particles-js', {
                particles: {
                    number: {
                        value: isMobile ? 30 : 80, // Fewer particles on mobile
                        density: {
                            enable: true,
                            value_area: isMobile ? 600 : 800
                        }
                    },
                    color: {
                        value: '#6c63ff'
                    },
                    shape: {
                        type: 'circle',
                        stroke: {
                            width: 0,
                            color: '#000000'
                        },
                        polygon: {
                            nb_sides: 5
                        }
                    },
                    opacity: {
                        value: 0.5,
                        random: false,
                        anim: {
                            enable: false,
                            speed: 1,
                            opacity_min: 0.1,
                            sync: false
                        }
                    },
                    size: {
                        value: isMobile ? 2 : 3, // Smaller particles on mobile
                        random: true,
                        anim: {
                            enable: false,
                            speed: 40,
                            size_min: 0.1,
                            sync: false
                        }
                    },
                    line_linked: {
                        enable: true,
                        distance: isMobile ? 100 : 150, // Shorter connections on mobile
                        color: '#6c63ff',
                        opacity: 0.4,
                        width: isMobile ? 0.8 : 1 // Thinner lines on mobile
                    },
                    move: {
                        enable: true,
                        speed: isMobile ? 3 : 6, // Slower movement on mobile for better performance
                        direction: 'none',
                        random: false,
                        straight: false,
                        out_mode: 'out',
                        bounce: false,
                        attract: {
                            enable: false,
                            rotateX: 600,
                            rotateY: 1200
                        }
                    }
                },
                interactivity: {
                    detect_on: 'canvas',
                    events: {
                        onhover: {
                            enable: !isMobile, // Disable hover effect on mobile
                            mode: 'grab'
                        },
                        onclick: {
                            enable: true, // Keep click interaction on all devices
                            mode: 'push'
                        },
                        resize: true
                    },
                    modes: {
                        grab: {
                            distance: isMobile ? 100 : 140, // Shorter grab distance on mobile
                            line_linked: {
                                opacity: 1
                            }
                        },
                        bubble: {
                            distance: 400,
                            size: 40,
                            duration: 2,
                            opacity: 8,
                            speed: 3
                        },
                        repulse: {
                            distance: 200,
                            duration: 0.4
                        },
                        push: {
                            particles_nb: 4
                        },
                        remove: {
                            particles_nb: 2
                        }
                    }
                },
                retina_detect: true
            });
        } else {
            console.warn('particlesJS is not defined. Make sure the library is loaded correctly.');
            // Add a simple fallback background effect
            const particlesContainer = document.getElementById('particles-js');
            if (particlesContainer) {
                particlesContainer.style.backgroundColor = 'rgba(108, 99, 255, 0.05)';
            }
        }
    } catch (error) {
        console.error('Error initializing particles:', error);
    }

    // Typing animation for hero section
    try {
        const typed = new Typed('.typed-text', {
            strings: ['Freelance Web Developer', 'PHP Developer', 'Laravel Developer', 'React.js Developer', 'Full Stack Developer'],
            typeSpeed: 80,
            backSpeed: 60,
            backDelay: 2000,
            loop: true
        });
    } catch (error) {
        console.error('Typed.js error:', error);
        document.querySelector('.typed-text').textContent = 'Software Developer';
    }

    // 3D tilt effect for cards
    try {
        if (typeof VanillaTilt !== 'undefined') {
            VanillaTilt.init(document.querySelectorAll('.skill-card, .project-card, .achievement-card'), {
                max: 15,
                speed: 400,
                glare: true,
                'max-glare': 0.3
            });
        } else {
            console.warn('VanillaTilt is not defined. Make sure the library is loaded correctly.');
            // Add a simple hover effect as fallback
            const tiltElements = document.querySelectorAll('.skill-card, .project-card, .achievement-card');
            tiltElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                    this.style.transition = 'transform 0.3s ease';
                });
                element.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        }
    } catch (error) {
        console.error('Error initializing VanillaTilt:', error);
    }
    
    // Initialize AOS animations
    try {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1000,
                once: false,
                mirror: true
            });
        }
    } catch (error) {
        console.error('Error initializing AOS:', error);
    }
});

// Contact form submission
function submitContactForm(event) {
    event.preventDefault();
    
    const form = document.getElementById('contactForm');
    const statusDiv = document.getElementById('formStatus');
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Store original button text
    const originalButtonText = submitButton.innerHTML;
    
    // Disable the submit button and show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';
    
    // Clear previous status
    if (window.showFormStatus) {
        // Use the new status display function if available
        statusDiv.classList.add('hidden');
    } else {
        // Fallback to original behavior
        statusDiv.innerHTML = '';
        statusDiv.className = 'form-status';
    }
    
    // Get form data
    const formData = new FormData(form);
    
    // Backend URL - change this to your PHP backend when deployed
    const backendUrl = window.location.hostname === 'localhost' 
        ? 'process_contact.php' 
        : 'https://your-backend-url.com/process_contact.php';
    
    // Send data to server using fetch API
    fetch(backendUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Form submission successful
            if (window.showFormStatus) {
                window.showFormStatus(data.message, true);
            } else {
                statusDiv.innerHTML = data.message;
                statusDiv.className = 'form-status success';
            }
            form.reset();
        } else {
            // Form submission failed
            if (window.showFormStatus) {
                window.showFormStatus(data.message, false);
            } else {
                statusDiv.innerHTML = data.message;
                statusDiv.className = 'form-status error';
            }
        }
        
        // Re-enable the submit button
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
        
        // Clear the status message after 5 seconds
        setTimeout(() => {
            if (window.showFormStatus) {
                statusDiv.classList.add('hidden');
            } else {
                statusDiv.innerHTML = '';
                statusDiv.className = 'form-status';
            }
        }, 5000);
    })
    .catch(error => {
        console.error('Error:', error);
        
        if (window.showFormStatus) {
            window.showFormStatus('An error occurred. Please try again later.', false);
        } else {
            statusDiv.innerHTML = 'An error occurred. Please try again later.';
            statusDiv.className = 'form-status error';
        }
        
        // Re-enable the submit button
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
    
    return false;
}