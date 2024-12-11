<div>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="customSwitch1">
        <label class="form-check-label" for="customSwitch1">Tema</label>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const switchInput = document.getElementById('customSwitch1');
            const linkElement = document.querySelector('link[href*="sb-admin-2"]');

            if (!switchInput || !linkElement) return;

            function toggleMode(isDarkMode) {
                if (isDarkMode) {
                    linkElement.href = linkElement.href.replace('sb-admin-2.css', 'sb-admin-2-dt.css');
                } else {
                    linkElement.href = linkElement.href.replace('sb-admin-2-dt.css', 'sb-admin-2.css');
                }
                localStorage.setItem('darkMode', isDarkMode);
            }

            const darkModeState = localStorage.getItem('darkMode');
            if (darkModeState !== null) {
                toggleMode(JSON.parse(darkModeState));
                switchInput.checked = JSON.parse(darkModeState);
            }

            switchInput.addEventListener('change', function () {
                toggleMode(switchInput.checked);
            });
        });
    </script>
</div>
