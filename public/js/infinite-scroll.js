// Infinite Scroll Pagination
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-infinite-scroll]');
    if (!container) return;

    let loading = false;
    let page = 1;
    const nextPageUrl = container.dataset.nextPage;
    
    if (!nextPageUrl) return;

    // Create loading indicator
    const loadingIndicator = document.createElement('div');
    loadingIndicator.className = 'text-center py-4 hidden';
    loadingIndicator.id = 'loading-indicator';
    loadingIndicator.innerHTML = `
        <svg class="animate-spin h-8 w-8 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-600 mt-2">Loading more...</p>
    `;
    container.parentElement.appendChild(loadingIndicator);

    // Detect scroll near bottom
    function handleScroll() {
        if (loading) return;

        const scrollPosition = window.innerHeight + window.scrollY;
        const threshold = document.documentElement.scrollHeight - 500;

        if (scrollPosition >= threshold) {
            loadMore();
        }
    }

    function loadMore() {
        if (loading) return;
        
        loading = true;
        loadingIndicator.classList.remove('hidden');
        page++;

        const url = new URL(window.location.href);
        url.searchParams.set('page', page);

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newItems = doc.querySelectorAll('[data-infinite-scroll] > *');
            
            if (newItems.length > 0) {
                newItems.forEach(item => {
                    container.appendChild(item);
                });
                
                // Check if there's a next page
                const nextPage = doc.querySelector('[data-infinite-scroll]')?.dataset.nextPage;
                if (!nextPage) {
                    window.removeEventListener('scroll', handleScroll);
                    loadingIndicator.innerHTML = '<p class="text-gray-500">No more records</p>';
                    setTimeout(() => loadingIndicator.classList.add('hidden'), 2000);
                } else {
                    container.dataset.nextPage = nextPage;
                }
            } else {
                window.removeEventListener('scroll', handleScroll);
                loadingIndicator.innerHTML = '<p class="text-gray-500">No more records</p>';
                setTimeout(() => loadingIndicator.classList.add('hidden'), 2000);
            }
        })
        .catch(error => {
            console.error('Error loading more items:', error);
            loadingIndicator.innerHTML = '<p class="text-red-500">Error loading more records</p>';
            setTimeout(() => loadingIndicator.classList.add('hidden'), 2000);
        })
        .finally(() => {
            loading = false;
            setTimeout(() => loadingIndicator.classList.add('hidden'), 500);
        });
    }

    // Attach scroll event
    window.addEventListener('scroll', handleScroll);
});
