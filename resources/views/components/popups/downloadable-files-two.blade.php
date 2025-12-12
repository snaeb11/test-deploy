<div id="downloadable-files-popup-two"
    class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
    <div class="relative max-h-[90vh] w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-2xl">
        <!-- Header with gradient background -->
        <div class="bg-gradient-to-r from-[#9D3E3E] to-[#7a2f2f] px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white sm:text-2xl">
                    R&DD MOA Forms, Samples, and References
                </h2>
                <button id="close-popup-two"
                    class="rounded-full p-1 text-white transition-colors duration-200 hover:bg-white/20 hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Content area with scroll -->
        <div class="max-h-[calc(90vh-120px)] overflow-y-auto p-6">
            <!-- Loading State -->
            <div id="moa-forms-loading" class="py-8 text-center">
                <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent motion-reduce:animate-[spin_1.5s_linear_infinite]"
                    role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2 text-gray-600">Loading forms...</p>
            </div>

            <!-- Grid Links with improved styling -->
            <div id="moa-forms-grid" class="grid hidden grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Forms will be loaded here dynamically -->
            </div>

            <!-- Empty State -->
            <div id="moa-forms-empty" class="hidden py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No forms available</h3>
                <p class="mt-1 text-sm text-gray-500">No MOA forms are currently available.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('downloadable-files-popup-two');
        const loadingDiv = document.getElementById('moa-forms-loading');
        const gridDiv = document.getElementById('moa-forms-grid');
        const emptyDiv = document.getElementById('moa-forms-empty');

        // Load forms when popup is shown
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const target = mutation.target;
                    if (!target.classList.contains('hidden')) {
                        loadMoaForms();
                    }
                }
            });
        });

        observer.observe(popup, {
            attributes: true
        });

        async function loadMoaForms() {
            try {
                loadingDiv.classList.remove('hidden');
                gridDiv.classList.add('hidden');
                emptyDiv.classList.add('hidden');

                const response = await fetch('/downloadable-forms/moa_forms');
                if (!response.ok) {
                    throw new Error('Failed to load forms');
                }

                const forms = await response.json();

                loadingDiv.classList.add('hidden');

                if (forms.length === 0) {
                    emptyDiv.classList.remove('hidden');
                    return;
                }

                gridDiv.innerHTML = forms.map(form => `
                    <a href="${escapeHtml(form.url)}" target="_blank"
                        class="group flex items-center rounded-xl border border-gray-200 p-4 transition-all duration-200 hover:border-[#9D3E3E] hover:bg-[#9D3E3E]/5">
                        <div class="mr-3 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-[#9D3E3E] group-hover:text-[#7a2f2f]" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="${form.icon_path}" />
                            </svg>
                        </div>
                        <span class="font-medium text-[#9D3E3E] transition-colors group-hover:text-[#7a2f2f]">${escapeHtml(form.title)}</span>
                    </a>
                `).join('');

                gridDiv.classList.remove('hidden');
            } catch (error) {
                console.error('Error loading MOA forms:', error);
                loadingDiv.classList.add('hidden');
                emptyDiv.classList.remove('hidden');
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    });
</script>
