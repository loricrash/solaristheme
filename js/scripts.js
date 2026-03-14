const vortix = document.querySelector('.vortix');
const container = document.querySelector('.logoheader');

let angle = 0;
let speed = 1; // Velocità minima
let targetSpeed = 1;
const acceleration = 0.1; // Quanto velocemente accelera

function animatevortix() {
    // Avvicina la velocità attuale a quella desiderata (smooth)
    if (speed < targetSpeed) {
        speed += acceleration;
    } else if (speed > targetSpeed) {
        speed -= acceleration;
    }

    // Incrementa l'angolo (sempre negativo per girare in senso antiorario come nel tuo codice)
    angle -= speed;
    
    // Applica la variabile al CSS
    vortix.style.setProperty('--vortix-angle', `${angle}deg`);

    requestAnimationFrame(animatevortix);
}

// Gestione eventi
container.addEventListener('mouseenter', () => {
    targetSpeed = 10; // Velocità massima in hover
});

container.addEventListener('mouseleave', () => {
    targetSpeed = 1; // Torna alla velocità base
});

animatevortix();



//funzione per far funzionare l'hamburger e il banner 

let phonebannertot = document.querySelector(".phonebannertot");  // var per contenere il banner completo

let hamburger = document.querySelector(".hamburger"); //var per l'icona hamburger
let closebanner = document.querySelector(".closebanner"); //var per il pulsante X

hamburger.onclick = function () { //cliccando l'icona si attiva questa funzione
    phonebannertot.classList.add("showbanner"); //aggiungi la classe "showbanner" al banner per mostrarlo
    console.log('mostra banner') //console.log = scrivi nella console
};

closebanner.onclick = function () {
    phonebannertot.classList.remove("showbanner");
    console.log('nascondi banner')
};


phonebannertot.addEventListener('click', function (event) { // per far chiudere il banner anche quando si clicca nello sfondo
    if (event.target === this) { // Controlla se il target dell'evento è il div stesso
        console.log('backgraund banner');
        phonebannertot.classList.remove("showbanner");
        console.log('nascondi banner')
    }
});


// per lo sfondo dell'header
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    header.classList.toggle('scrolled', window.scrollY > 75);
});

// per il colore dinamico
document.addEventListener('DOMContentLoaded', function() {
    const bridge = document.getElementById('acf-color-bridge');
    if (!bridge) return;

    // Default fisici (RGB puri)
    const DEFAULT_PRIMARY = [35, 31, 32];
    const DEFAULT_ACCENT  = [166, 215, 28];

    const data = bridge.dataset;
    console.log("Dati ricevuti dal bridge:", data);

    if (data.attiva === 'true') {
        
        // NUOVA LOGICA: Sfondo Neutro
        // Se sfondo-neutro è attivo, il primario resta grigio default e il custom diventa l'accento
        if (data.sfondoneutro === 'true' && data.custom) {
            console.log("Modalità: Sfondo Neutro Attivo");
            let customRgb = data.custom.includes('rgb') ? 
                            data.custom.match(/\d+/g).map(Number) : 
                            hexToRgb(data.custom);
            
            applyColors(DEFAULT_PRIMARY, false, customRgb);
        }
        // PRIORITÀ 1: Automatica (Immagine)
        else if (data.auto === 'true' && data.img && data.img.trim() !== "") {
            console.log("Priorità: Automatica (Immagine)");
            extractColorFromImage(data.img);
        } 
        // PRIORITÀ 2: Personalizzata (Manuale)
        else if (data.custom && data.custom.trim() !== "") {
            console.log("Priorità: Personalizzata");
            let rgb = data.custom.includes('rgb') ? 
                      data.custom.match(/\d+/g).map(Number) : 
                      hexToRgb(data.custom);
            
            applyColors(rgb, true);
        }
        else {
            applyColors(DEFAULT_PRIMARY, false, DEFAULT_ACCENT);
        }

    } else {
        applyColors(DEFAULT_PRIMARY, false, DEFAULT_ACCENT);
    }

    // --- FUNZIONI DI SUPPORTO ---

    function hexToRgb(hex) {
        let cleanHex = hex.replace('#', '').trim();
        if (cleanHex.length === 3) {
            cleanHex = cleanHex.split('').map(char => char + char).join('');
        }
        if (cleanHex.length !== 6) return DEFAULT_PRIMARY;

        const r = parseInt(cleanHex.substring(0, 2), 16);
        const g = parseInt(cleanHex.substring(2, 4), 16);
        const b = parseInt(cleanHex.substring(4, 6), 16);
        return [r, g, b];
    }

    function extractColorFromImage(url) {
        const img = new Image();
        img.crossOrigin = "Anonymous";
        img.src = url;
        img.onload = function() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = 1; canvas.height = 1;
            ctx.drawImage(img, 0, 0, 1, 1);
            const p = ctx.getImageData(0, 0, 1, 1).data;
            applyColors([p[0], p[1], p[2]], true);
        };
        img.onerror = function() {
            applyColors(DEFAULT_PRIMARY, true);
        };
    }

    function applyColors(primaryArray, shouldDerive, manualAccent = null) {
        const p = primaryArray;
        let a = manualAccent || DEFAULT_ACCENT;

        if (shouldDerive && !manualAccent) {
            // Conversione in HSB (HSV)
            const hsb = rgbToHsb(p[0], p[1], p[2]);
            
            // Logica richiesta: H*1, S*2 (max 100), B = 100
            const newH = hsb.h;
            const newS = Math.min(100, hsb.s * 2);
            const newB = 100;

            a = hsbToRgb(newH, newS, newB);
        }
        
        document.documentElement.style.setProperty('--color-primary', `rgb(${p[0]}, ${p[1]}, ${p[2]})`);
        document.documentElement.style.setProperty('--color-secondary', `rgb(${a[0]}, ${a[1]}, ${a[2]})`);
        
        console.log("Variabili impostate:", { primary: p, accent: a });
    }

    // --- NUOVI CONVERTITORI HSB (HSV) ---

    function rgbToHsb(r, g, b) {
        r /= 255; g /= 255; b /= 255;
        const max = Math.max(r, g, b), min = Math.min(r, g, b);
        const d = max - min;
        let h;
        const s = max === 0 ? 0 : (d / max) * 100;
        const v = max * 100;

        if (max === min) {
            h = 0;
        } else {
            switch (max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }
            h /= 6;
        }
        return { h: h * 360, s: s, b: v };
    }

    function hsbToRgb(h, s, b) {
        s /= 100; b /= 100;
        let r, g, bl;
        const i = Math.floor(h / 60);
        const f = h / 60 - i;
        const p = b * (1 - s);
        const q = b * (1 - f * s);
        const t = b * (1 - (1 - f) * s);

        switch (i % 6) {
            case 0: r = b; g = t; bl = p; break;
            case 1: r = q; g = b; bl = p; break;
            case 2: r = p; g = b; bl = t; break;
            case 3: r = p; g = q; bl = b; break;
            case 4: r = t; g = p; bl = b; break;
            case 5: r = b; g = p; bl = q; break;
        }
        return [Math.round(r * 255), Math.round(g * 255), Math.round(bl * 255)];
    }
});

// per ingrandire l'immagine
function openFullImage(src) {
    console.log("hai cliccato un immagine")
    document.getElementById('fullImage').src = src;
    document.getElementById('fullImageOverlay').style.display = 'flex';
}

// per le card nell hero dell homepage
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.cardhero');
    const slides = document.querySelectorAll('.hp-slide');
    const containerCards = document.querySelector('.cardhero-container');

    if (!cards.length || !slides.length) return;

    function switchSlide(targetIndex) {
        // 1. Update classi Active sulle Card
        cards.forEach(card => {
            card.classList.toggle('active', card.getAttribute('data-target') === targetIndex);
        });

        // 2. Update classi Active sulle Slide
        slides.forEach(slide => {
            const isTarget = slide.getAttribute('data-slide') === targetIndex;
            
            if (isTarget) {
                slide.classList.add('active');
                
                // Spostiamo fisicamente le card nella slide attiva per non farle sparire
                const targetLayout = slide.querySelector('.updividerhero');
                if (targetLayout && containerCards) {
                    targetLayout.appendChild(containerCards);
                }
            } else {
                slide.classList.remove('active');
            }
        });
    }

    cards.forEach(card => {
        card.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            switchSlide(target);
        });
    });
});