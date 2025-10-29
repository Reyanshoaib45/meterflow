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
        submitBtn.html('<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...');
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
    
    // Show notification
    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-50 border-green-200 text-green-800',
            error: 'bg-red-50 border-red-200 text-red-800',
            warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };
        
        const notification = $(`
            <div class="fixed top-4 right-4 z-50 animate-fade-in-up max-w-sm">
                <div class="${colors[type]} border px-4 py-3 rounded-lg shadow-lg">
                    <span class="font-medium">${message}</span>
                </div>
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // Expose globally
    window.showNotification = showNotification;
    
    
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
