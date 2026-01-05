// Minimal Responsive Navbar
(function() {
    function updateTime() {
        const now = new Date();
        const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        const date = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        
        document.querySelectorAll('#time, #currentTime, #currentTimeSmall, #adminTimeVal, #realtimeTime').forEach(el => {
            if (el) el.textContent = time;
        });
        
        document.querySelectorAll('#date, #currentDate, #currentDateSmall, #adminDate, #realtimeDate').forEach(el => {
            if (el) el.textContent = date;
        });
    }
    
    function adjustNavbar() {
        const w = window.innerWidth;
        
        // Logo
        const logoSize = w < 400 ? '20px' : w < 576 ? '24px' : w < 768 ? '26px' : '30px';
        document.querySelectorAll('#navLogo, #adminLogo, #welcomeLogo').forEach(el => {
            if (el) el.style.height = logoSize;
        });
        
        // Titles
        document.querySelectorAll('#navTitle, #adminTitleText, .navbar-brand').forEach(el => {
            if (!el) return;
            if (w < 576) {
                el.style.display = 'none';
            } else {
                el.style.display = 'inline';
                el.style.fontSize = w < 768 ? '0.65rem' : '0.8rem';
            }
        });
        
        // Date
        document.querySelectorAll('#date, #adminDate').forEach(el => {
            if (el) el.style.display = w < 400 ? 'none' : 'block';
        });
        
        // Time containers
        document.querySelectorAll('#adminTime').forEach(el => {
            if (el) el.style.fontSize = w < 576 ? '0.6rem' : '0.7rem';
        });
        
        // User names - keep visible on all screens
        document.querySelectorAll('#adminUser').forEach(el => {
            if (el) el.style.display = w < 768 ? 'none' : 'inline';
        });
        
        // Support text
        document.querySelectorAll('#adminSupport').forEach(el => {
            if (el) el.style.display = w < 576 ? 'none' : 'inline';
        });
        
        // Buttons
        document.querySelectorAll('.navbar .btn').forEach(el => {
            if (!el) return;
            const padding = w < 400 ? '0.1rem 0.25rem' : w < 576 ? '0.15rem 0.3rem' : '0.2rem 0.4rem';
            const fontSize = w < 400 ? '0.65rem' : w < 576 ? '0.7rem' : '0.8rem';
            el.style.padding = padding;
            el.style.fontSize = fontSize;
        });
        
        // Icons
        document.querySelectorAll('.navbar i').forEach(el => {
            if (el) el.style.fontSize = w < 576 ? '0.75rem' : '0.9rem';
        });
        
        // Container gaps
        document.querySelectorAll('.navbar .container-fluid').forEach(el => {
            if (el) el.style.gap = w < 576 ? '0.25rem' : '0.5rem';
        });
    }
    
    updateTime();
    setInterval(updateTime, 1000);
    adjustNavbar();
    window.addEventListener('resize', adjustNavbar);
})();
