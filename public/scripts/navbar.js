document.addEventListener('DOMContentLoaded', function () {
    const btn     = document.getElementById('mobile-menu-btn');
    const menu    = document.getElementById('mobile-menu-options');

    if (btn && menu) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.toggle('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!menu.contains(e.target) && e.target !== btn) {
                menu.classList.add('hidden');
            }
        });
    }
});
