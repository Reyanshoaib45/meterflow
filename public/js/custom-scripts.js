/**
 * ===================================
 * Custom JavaScript & jQuery
 * Meter Flow Nation (mepco)
 * ===================================
 */

$(document).ready(function() {
    
    // ===================================
    // Smooth Page Load Effect
    // ===================================
    
    $('body').css('opacity', '0').animate({ opacity: 1 }, 600);
    
    // ===================================
    // Select2 Initialization with Animations
    // ===================================
    
    // Initialize all select elements with select2
    $('select:not(.no-select2)').select2({
        theme: 'default',
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder') || 'Select an option';
        },
        allowClear: true,
        dropdownCssClass: 'select2-dropdown-animated'
    }).on('select2:open', function() {
        $('.select2-dropdown').hide().fadeIn(200);
    });
    
    // Initialize multi-select with special styling
    $('select[multiple]').select2({
        theme: 'default',
        width: '100%',
        placeholder: 'Select multiple options',
        allowClear: true,
        closeOnSelect: false
    });
    
    // ===================================
    // Flash Messages Auto-Hide
    // ===================================
    
    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        const flashMessages = document.querySelectorAll('[class*="fixed top-4 right-4"]');
        flashMessages.forEach(message => {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.5s ease-out';
            setTimeout(() => message.remove(), 500);
        });
    }, 5000);
    
    // ===================================
    // Smooth Scroll for Anchor Links
    // ===================================
    
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 80
            }, 800);
        }
    });
    
    // ===================================
    // Form Validation Helpers
    // ===================================
    
    // Add visual feedback for form inputs on focus
    $('input, textarea, select').on('focus', function() {
        $(this).closest('.form-group, .mb-4, .mb-6').addClass('focused');
    }).on('blur', function() {
        $(this).closest('.form-group, .mb-4, .mb-6').removeClass('focused');
    });
    
    // ===================================
    // Image Loading Optimization
    // ===================================
    
    // Lazy load images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img.lazy').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // ===================================
    // Print Functionality
    // ===================================
    
    // Handle print button clicks
    $('.print-btn, [data-action="print"]').on('click', function(e) {
        e.preventDefault();
        window.print();
    });
    
    // ===================================
    // Tooltips Enhancement
    // ===================================
    
    // Add title attributes as tooltips
    $('[data-tooltip]').each(function() {
        $(this).attr('title', $(this).data('tooltip'));
    });
    
    // ===================================
    // Copy to Clipboard
    // ===================================
    
    $('.copy-btn, [data-action="copy"]').on('click', function(e) {
        e.preventDefault();
        const textToCopy = $(this).data('copy-text') || $(this).prev('input, textarea').val();
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(textToCopy).then(() => {
                showNotification('Copied to clipboard!', 'success');
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }
    });
    
    // ===================================
    // Loading Indicator
    // ===================================
    
    // Show loading on form submit
    $('form[data-loading="true"]').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<svg class="animate-spin h-5 w-5 mr-2 inline text-blue-600 dark:text-blue-400" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...');
    });
    
    // ===================================
    // Accordion Functionality
    // ===================================
    
    $('.accordion-header').on('click', function() {
        const $accordion = $(this).closest('.accordion-item');
        const $content = $accordion.find('.accordion-content');
        
        // Toggle current accordion
        $content.slideToggle(300);
        $accordion.toggleClass('active');
        
        // Close other accordions if data-single="true"
        if ($(this).closest('.accordion').data('single') === true) {
            $('.accordion-item').not($accordion).removeClass('active')
                .find('.accordion-content').slideUp(300);
        }
    });
    
    // ===================================
    // Tab Functionality
    // ===================================
    
    $('.tab-link').on('click', function(e) {
        e.preventDefault();
        const tabId = $(this).data('tab');
        
        // Update active tab link
        $(this).closest('.tabs').find('.tab-link').removeClass('active');
        $(this).addClass('active');
        
        // Show corresponding tab content
        $('.tab-content').removeClass('active').hide();
        $('#' + tabId).addClass('active').fadeIn(300);
    });
    
    // ===================================
    // Modal Functionality
    // ===================================
    
    // Open modal
    $('[data-modal-target]').on('click', function(e) {
        e.preventDefault();
        const modalId = $(this).data('modal-target');
        $('#' + modalId).fadeIn(200).css('display', 'flex');
        $('body').addClass('overflow-hidden');
    });
    
    // Close modal
    $('.modal-close, [data-modal-close]').on('click', function() {
        $(this).closest('.modal').fadeOut(200);
        $('body').removeClass('overflow-hidden');
    });
    
    // Close modal on outside click
    $('.modal').on('click', function(e) {
        if ($(e.target).is('.modal')) {
            $(this).fadeOut(200);
            $('body').removeClass('overflow-hidden');
        }
    });
    
    // ===================================
    // Number Counter Animation (Enhanced)
    // ===================================
    
    function animateCounter(element) {
        const raw = element.dataset.count;
        const target = Number(raw);
        if (!Number.isFinite(target) || target < 0) {
            return; // Do not animate non-numeric targets
        }
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.textContent = Number(target).toLocaleString();
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    }
    
    // Trigger counter animation when in viewport
    if ('IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    animateCounter(entry.target);
                    entry.target.classList.add('counted');
                }
            });
        }, {
            threshold: 0.5
        });
        
        // Only animate explicit counters marked with data-count
        document.querySelectorAll('[data-count]').forEach(counter => {
            counterObserver.observe(counter);
        });
    }
    
    // ===================================
    // Button Ripple Effect
    // ===================================
    
    document.querySelectorAll('button, .btn, a[class*="bg-"]').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // ===================================
    // Back to Top Button
    // ===================================
    
    // Show/hide back to top button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn(200);
        } else {
            $('.back-to-top').fadeOut(200);
        }
    });
    
    $('.back-to-top').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 800);
    });
    
    // ===================================
    // Helper Functions
    // ===================================
    
    // Enhanced notification system
    function showNotification(message, type = 'info', duration = 4000) {
        const colors = {
            success: {
                bg: 'bg-green-50 dark:bg-green-900/30',
                border: 'border-green-200 dark:border-green-700',
                text: 'text-green-800 dark:text-green-200',
                icon: 'text-green-600 dark:text-green-400'
            },
            error: {
                bg: 'bg-red-50 dark:bg-red-900/30',
                border: 'border-red-200 dark:border-red-700',
                text: 'text-red-800 dark:text-red-200',
                icon: 'text-red-600 dark:text-red-400'
            },
            warning: {
                bg: 'bg-yellow-50 dark:bg-yellow-900/30',
                border: 'border-yellow-200 dark:border-yellow-700',
                text: 'text-yellow-800 dark:text-yellow-200',
                icon: 'text-yellow-600 dark:text-yellow-400'
            },
            info: {
                bg: 'bg-blue-50 dark:bg-blue-900/30',
                border: 'border-blue-200 dark:border-blue-700',
                text: 'text-blue-800 dark:text-blue-200',
                icon: 'text-blue-600 dark:text-blue-400'
            }
        };
        
        const icons = {
            success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
            error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
            warning: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
            info: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
        };
        
        const colorScheme = colors[type];
        const icon = icons[type];
        
        // Create notification container
        const notificationId = 'notification-' + Date.now();
        const notification = $(`
            <div id="${notificationId}" class="fixed top-4 right-4 z-50 max-w-sm transform translate-x-full opacity-0 transition-all duration-300 ease-out">
                <div class="${colorScheme.bg} ${colorScheme.border} border px-4 py-3 rounded-lg shadow-xl backdrop-blur-sm flex items-center space-x-3">
                    <div class="${colorScheme.icon} flex-shrink-0">
                        ${icon}
                    </div>
                    <span class="${colorScheme.text} font-medium flex-1">${message}</span>
                    <button onclick="closeNotification('${notificationId}')" class="${colorScheme.text} hover:opacity-70 transition-opacity flex-shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        `);
        
        $('body').append(notification);
        
        // Animate in
        setTimeout(() => {
            $(`#${notificationId}`).removeClass('translate-x-full opacity-0').addClass('translate-x-0 opacity-100');
        }, 10);
        
        // Auto-remove after duration
        setTimeout(() => {
            closeNotification(notificationId);
        }, duration);
    }
    
    // Close notification function
    function closeNotification(id) {
        const notification = $(`#${id}`);
        notification.removeClass('translate-x-0 opacity-100').addClass('translate-x-full opacity-0');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
    
    // Expose globally
    window.showNotification = showNotification;
    window.closeNotification = closeNotification;
    
    
    // ===================================
    // Smooth Scroll Reveal
    // ===================================
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.reveal-on-scroll').forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)';
        observer.observe(element);
    });
    
});

// ===================================
// Page Load Complete
// ===================================

window.addEventListener('load', function() {
    // Remove any loading overlays
    $('.page-loader, .loading-overlay').fadeOut(300);
    
    // Add loaded class to body
    document.body.classList.add('page-loaded');
    
    // Smooth entrance for all cards
    $('.card-hover, .hover-lift').each(function(index) {
        $(this).css({
            opacity: 0,
            transform: 'translateY(20px)'
        }).delay(index * 100).animate({
            opacity: 1
        }, 600, function() {
            $(this).css('transform', 'translateY(0)');
        });
    });
});

// ===================================
// Console Branding
// ===================================

console.log('%c Meter Flow Nation (mepco) ', 'background: linear-gradient(to right, #3b82f6, #8b5cf6); color: white; font-size: 16px; padding: 10px; border-radius: 5px;');
console.log('%c Developed by Reyan Shoaib ', 'color: #3b82f6; font-size: 12px;');
