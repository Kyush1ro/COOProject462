<div class="position-fixed bottom-0 start-0 p-4" style="z-index: 1050;">
    <!-- Accessibility Toggle Button -->
    <div class="dropup">
        <button class="btn btn-dark rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
                style="width: 50px; height: 50px;"
                type="button" 
                data-coreui-toggle="dropdown" 
                data-coreui-auto-close="outside"
                aria-expanded="false">
            <i class="fas fa-universal-access fa-lg text-white"></i>
        </button>

        <!-- Accessibility Menu -->
        <ul class="dropdown-menu shadow-lg p-3 border-0" style="width: 250px; margin-bottom: 10px;" onclick="event.stopPropagation()">
            <li class="mb-2">
                <h6 class="dropdown-header text-uppercase fw-bold px-0">Accessibility Tools</h6>
            </li>
            
            <!-- Text Size -->
            <li class="mb-3">
                <label class="small text-muted mb-1 d-block">Text Size</label>
                <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="Accessibility.resetText()">A</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="Accessibility.increaseText()">A+</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="Accessibility.decreaseText()">A-</button>
                </div>
            </li>

            <!-- Contrast -->
            <li class="mb-3">
                <label class="small text-muted mb-1 d-block">Contrast</label>
                <button class="btn btn-outline-dark w-100 btn-sm d-flex align-items-center justify-content-between" onclick="Accessibility.toggleContrast()">
                    <span>High Contrast</span>
                    <i class="fas fa-adjust"></i>
                </button>
            </li>

            <!-- Dyslexia Font -->
            <li class="mb-3">
                <label class="small text-muted mb-1 d-block">Readability</label>
                <button class="btn btn-outline-primary w-100 btn-sm d-flex align-items-center justify-content-between" onclick="Accessibility.toggleDyslexiaFont()">
                    <span>Dyslexia Friendly</span>
                    <i class="fas fa-font"></i>
                </button>
            </li>
            
            <!-- Reset -->
            <li>
                <hr class="dropdown-divider">
                <button class="btn btn-link text-danger w-100 btn-sm text-decoration-none" onclick="Accessibility.resetAll()">
                    Reset All Settings
                </button>
            </li>
        </ul>
    </div>
</div>

<style>
    /* High Contrast Mode */
    body.high-contrast {
        background-color: #000 !important;
        color: #fff !important;
    }
    body.high-contrast .card, 
    body.high-contrast .sidebar, 
    body.high-contrast .header,
    body.high-contrast .footer,
    body.high-contrast .dropdown-menu {
        background-color: #1a1a1a !important;
        color: #fff !important;
        border: 1px solid #fff !important;
    }
    body.high-contrast a {
        color: #ffff00 !important;
        text-decoration: underline !important;
    }
    body.high-contrast .btn-primary {
        background-color: #ffff00 !important;
        color: #000 !important;
        border-color: #ffff00 !important;
    }
    
    /* Dyslexia Font */
    body.dyslexia-font {
        font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif !important;
        line-height: 1.6 !important;
        letter-spacing: 0.05em !important;
    }
</style>

<script>
const Accessibility = {
    zoom: 100,

    increaseText: function() {
        this.zoom += 10;
        document.body.style.fontSize = this.zoom + '%';
    },

    decreaseText: function() {
        if (this.zoom > 70) {
            this.zoom -= 10;
            document.body.style.fontSize = this.zoom + '%';
        }
    },

    resetText: function() {
        this.zoom = 100;
        document.body.style.fontSize = '';
    },

    toggleContrast: function() {
        document.body.classList.toggle('high-contrast');
    },

    toggleDyslexiaFont: function() {
        document.body.classList.toggle('dyslexia-font');
    },

    resetAll: function() {
        this.resetText();
        document.body.classList.remove('high-contrast', 'dyslexia-font');
    }
};
</script>
