// Part 11.1 — Trendship API Wrapper
const API_BASE_URL = '/api'; // Relative to the site root as specified

const TrendshipAPI = {
    async request(endpoint, method = 'GET', data = null) {
        const options = {
            method,
            headers: { 'Content-Type': 'application/json' }
        };
        if (data) options.body = JSON.stringify(data);

        try {
            const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
            const result = await response.json();
            
            if (!response.ok) {
                const errorMsg = result.error || (result.errors && result.errors[0].msg) || 'Something went wrong.';
                window.showToast(errorMsg);
                return { success: false, error: errorMsg };
            }
            
            return result;
        } catch (error) {
            console.error(`API Error (${endpoint}):`, error);
            window.showToast('You appear to be offline. Please check your connection.');
            return { success: false, error: 'Network error' };
        }
    },

    submitContact(formData) { return this.request('/contact', 'POST', formData); },
    subscribeNewsletter(email, tag = null) { return this.request('/newsletter', 'POST', { email, tag }); },
    downloadReport(email) { return this.request('/report/download', 'POST', { email }); },
    bookExhibition(bookingData) { return this.request('/booking', 'POST', bookingData); },
    generateMoodboard(items, name) { return this.request('/moodboard/generate', 'POST', { items, name }); },
    getSpecUrl(materialId) { return `${API_BASE_URL}/spec/${materialId}`; }
};

window.TrendshipAPI = TrendshipAPI;
