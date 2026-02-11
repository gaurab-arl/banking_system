// Navigation toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

if (hamburger && navMenu) {
    // Toggle menu open/close
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        hamburger.classList.toggle('active');
    });

    // Close menu on link click
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });
}

// Setup database on Get Started button click
document.addEventListener('DOMContentLoaded', function () {
    const getStartedBtn = document.querySelector('a[href="pages/login.html"].btn-primary');

    if (getStartedBtn) {
        getStartedBtn.addEventListener('click', async function (e) {
            // Only run setup if not already done
            if (!localStorage.getItem('dbSetupDone')) {
                e.preventDefault(); // Prevent immediate navigation

                try {
                    // Run setup
                    console.log('Setting up database...');
                    const response = await fetch('php/setup.php');
                    const result = await response.text();
                    console.log('Setup result:', result);

                    // Mark setup as done
                    localStorage.setItem('dbSetupDone', 'true');

                    // Navigate to login page after setup
                    setTimeout(() => {
                        window.location.href = '../pages/login.html';
                    }, 500);

                } catch (error) {
                    console.error('Setup error:', error);
                    // Still allow navigation even if setup fails
                    window.location.href = '../index.html';
                }
            }
        });
    }

    // Run setup automatically on first visit (optional)
    if (!localStorage.getItem('dbSetupDone')) {
        console.log('First visit, running setup...');
        fetch('php/setup.php')
            .then(res => res.text())
            .then(result => {
                console.log('Auto-setup result:', result);
                localStorage.setItem('dbSetupDone', 'true');
            })
            .catch(err => console.error('Auto-setup error:', err));
    }
});