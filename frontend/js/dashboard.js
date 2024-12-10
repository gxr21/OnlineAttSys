function showTab(tabId) {
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active', 'fade-in');
    });
    const selectedTab = document.getElementById(tabId);
    selectedTab.classList.add('active', 'fade-in');
}
