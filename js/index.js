 document.addEventListener('DOMContentLoaded', function() {
            // Role selection
            const establishmentBtn = document.getElementById('establishmentBtn');
            const adminBtn = document.getElementById('adminBtn');
            const establishmentLogin = document.getElementById('establishmentLogin');
            const adminLogin = document.getElementById('adminLogin');
            
            establishmentBtn.addEventListener('click', function() {
                establishmentBtn.classList.add('active');
                adminBtn.classList.remove('active');
                establishmentLogin.style.display = 'block';
                adminLogin.style.display = 'none';
            });
            
            adminBtn.addEventListener('click', function() {
                adminBtn.classList.add('active');
                establishmentBtn.classList.remove('active');
                adminLogin.style.display = 'block';
                establishmentLogin.style.display = 'none';
            });
        });