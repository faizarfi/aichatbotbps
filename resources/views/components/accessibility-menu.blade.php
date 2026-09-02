{{-- 
    Komponen Menu Aksesibilitas Resmi BPS Kabupaten Karanganyar
    Memenuhi standar aksesibilitas ramah difabel dan disabilitas (WCAG 2.1)
--}}

{{-- Reading Guide Ruler Element --}}
<div id="a11y-reading-ruler" aria-hidden="true"></div>

{{-- Floating Accessibility Trigger Button (Kiri Bawah) --}}
<div class="fixed bottom-6 left-4 sm:left-6 z-40 group">
    <button type="button"
            id="a11y-toggle-btn"
            onclick="toggleA11yMenu()"
            class="w-12 h-12 rounded-2xl bg-[#04325e] hover:bg-[#005b9f] text-white shadow-lg shadow-blue-900/30 border-2 border-[#f7941d] flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95 cursor-pointer focus:outline-none focus:ring-4 focus:ring-blue-300"
            aria-label="Buka Menu Aksesibilitas Web BPS"
            title="Menu Aksesibilitas">
        <span class="iconify text-2xl text-white group-hover:rotate-12 transition-transform" data-icon="lucide:accessibility"></span>
    </button>
    <div class="hidden sm:block absolute left-14 top-1/2 -translate-y-1/2 px-2.5 py-1 rounded-lg bg-slate-900 text-white text-[11px] font-bold whitespace-nowrap pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity shadow-md">
        Aksesibilitas Web
    </div>
</div>

{{-- Accessibility Drawer Modal --}}
<div id="a11y-modal" 
     class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden transition-opacity duration-300 flex items-end sm:items-center justify-start sm:p-6"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="a11y-modal-title">
    
    {{-- Drawer Panel --}}
    <div class="w-full sm:w-96 max-h-[90vh] bg-white rounded-t-3xl sm:rounded-3xl border border-slate-200 shadow-2xl overflow-hidden flex flex-col transform transition-transform duration-300">
        {{-- Header BPS Navy --}}
        <div class="px-5 py-4 bg-[#04325e] text-white border-b-2 border-[#f7941d] flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
                    <span class="iconify text-lg text-[#f7941d]" data-icon="lucide:accessibility"></span>
                </div>
                <div>
                    <h3 id="a11y-modal-title" class="text-xs sm:text-sm font-black text-white tracking-tight uppercase">
                        Menu Aksesibilitas
                    </h3>
                    <p class="text-[10px] text-blue-200 font-medium">Layanan Inklusif BPS Karanganyar</p>
                </div>
            </div>
            <button type="button" 
                    onclick="toggleA11yMenu()"
                    class="p-1.5 rounded-xl hover:bg-white/10 text-white/80 hover:text-white transition-colors cursor-pointer"
                    aria-label="Tutup Menu Aksesibilitas">
                <span class="iconify text-xl" data-icon="lucide:x"></span>
            </button>
        </div>

        {{-- Scrollable Settings Body --}}
        <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-5 text-xs text-slate-700 divide-y divide-slate-100">
            
            {{-- 1. Ukuran Teks --}}
            <div class="space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="font-extrabold text-slate-900 flex items-center gap-1.5">
                        <span class="iconify text-base text-[#005b9f]" data-icon="lucide:type"></span>
                        <span>Ukuran Teks</span>
                    </span>
                    <span id="a11y-font-label" class="text-[11px] font-bold text-[#005b9f] bg-blue-50 px-2 py-0.5 rounded-md">Normal (100%)</span>
                </div>
                <div class="grid grid-cols-4 gap-1.5">
                    <button type="button" onclick="setA11yFontSize('sm')" id="btn-font-sm" class="a11y-font-btn py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 font-bold text-center transition-all cursor-pointer">
                        A- (85%)
                    </button>
                    <button type="button" onclick="setA11yFontSize('md')" id="btn-font-md" class="a11y-font-btn py-2 rounded-xl border-2 border-[#005b9f] bg-blue-50 text-[#005b9f] font-black text-center transition-all cursor-pointer">
                        A (100%)
                    </button>
                    <button type="button" onclick="setA11yFontSize('lg')" id="btn-font-lg" class="a11y-font-btn py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 font-bold text-center transition-all cursor-pointer">
                        A+ (115%)
                    </button>
                    <button type="button" onclick="setA11yFontSize('xl')" id="btn-font-xl" class="a11y-font-btn py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 font-bold text-center transition-all cursor-pointer">
                        A++ (130%)
                    </button>
                </div>
            </div>

            {{-- 2. Kontras Tampilan --}}
            <div class="pt-4 space-y-2.5">
                <span class="font-extrabold text-slate-900 flex items-center gap-1.5">
                    <span class="iconify text-base text-[#005b9f]" data-icon="lucide:sun-medium"></span>
                    <span>Kontras Tampilan</span>
                </span>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="setA11yContrast('normal')" id="btn-contrast-normal" class="a11y-contrast-btn p-2.5 rounded-xl border-2 border-[#005b9f] bg-blue-50 text-[#005b9f] font-bold text-left transition-all flex items-center gap-2 cursor-pointer">
                        <span class="iconify text-base" data-icon="lucide:monitor"></span>
                        <span>Standar BPS</span>
                    </button>
                    <button type="button" onclick="setA11yContrast('high')" id="btn-contrast-high" class="a11y-contrast-btn p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 font-bold text-left transition-all flex items-center gap-2 cursor-pointer">
                        <span class="iconify text-base text-amber-500" data-icon="lucide:contrast"></span>
                        <span>Kontras Tinggi</span>
                    </button>
                    <button type="button" onclick="setA11yContrast('grayscale')" id="btn-contrast-grayscale" class="a11y-contrast-btn p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 font-bold text-left transition-all flex items-center gap-2 cursor-pointer">
                        <span class="iconify text-base text-slate-500" data-icon="lucide:circle-dot"></span>
                        <span>Hitam Putih</span>
                    </button>
                    <button type="button" onclick="setA11yContrast('invert')" id="btn-contrast-invert" class="a11y-contrast-btn p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 font-bold text-left transition-all flex items-center gap-2 cursor-pointer">
                        <span class="iconify text-base text-indigo-500" data-icon="lucide:blend"></span>
                        <span>Balik Warna</span>
                    </button>
                </div>
            </div>

            {{-- 3. Keterbacaan Huruf --}}
            <div class="pt-4 space-y-2.5">
                <span class="font-extrabold text-slate-900 flex items-center gap-1.5">
                    <span class="iconify text-base text-[#005b9f]" data-icon="lucide:book-open"></span>
                    <span>Keterbacaan & Disleksia</span>
                </span>
                <div class="space-y-1.5">
                    {{-- Font Ramah Keterbacaan --}}
                    <label class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer border border-slate-200">
                        <span class="font-bold flex items-center gap-2">
                            <span class="iconify text-base text-slate-500" data-icon="lucide:spell-check"></span>
                            <span>Font Ramah Disleksia</span>
                        </span>
                        <input type="checkbox" id="chk-font-readable" onchange="toggleA11yClass('a11y-font-readable', this.checked)" class="h-4 w-4 rounded border-slate-300 text-[#005b9f] focus:ring-[#005b9f] cursor-pointer">
                    </label>

                    {{-- Spasi Baris Lebar --}}
                    <label class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer border border-slate-200">
                        <span class="font-bold flex items-center gap-2">
                            <span class="iconify text-base text-slate-500" data-icon="lucide:align-justify"></span>
                            <span>Spasi Baris Lebar</span>
                        </span>
                        <input type="checkbox" id="chk-line-height" onchange="toggleA11yClass('a11y-line-height-lg', this.checked)" class="h-4 w-4 rounded border-slate-300 text-[#005b9f] focus:ring-[#005b9f] cursor-pointer">
                    </label>

                    {{-- Spasi Huruf Renggang --}}
                    <label class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer border border-slate-200">
                        <span class="font-bold flex items-center gap-2">
                            <span class="iconify text-base text-slate-500" data-icon="lucide:move-horizontal"></span>
                            <span>Spasi Huruf Renggang</span>
                        </span>
                        <input type="checkbox" id="chk-letter-spacing" onchange="toggleA11yClass('a11y-letter-spacing-lg', this.checked)" class="h-4 w-4 rounded border-slate-300 text-[#005b9f] focus:ring-[#005b9f] cursor-pointer">
                    </label>
                </div>
            </div>

            {{-- 4. Bantuan Navigasi & Membaca --}}
            <div class="pt-4 space-y-2.5">
                <span class="font-extrabold text-slate-900 flex items-center gap-1.5">
                    <span class="iconify text-base text-[#005b9f]" data-icon="lucide:navigation"></span>
                    <span>Bantuan Visual & Navigasi</span>
                </span>
                <div class="space-y-1.5">
                    {{-- Sorot & Garis Bawah Link --}}
                    <label class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer border border-slate-200">
                        <span class="font-bold flex items-center gap-2">
                            <span class="iconify text-base text-slate-500" data-icon="lucide:link"></span>
                            <span>Sorot & Garis Bawah Tautan</span>
                        </span>
                        <input type="checkbox" id="chk-highlight-links" onchange="toggleA11yClass('a11y-highlight-links', this.checked)" class="h-4 w-4 rounded border-slate-300 text-[#005b9f] focus:ring-[#005b9f] cursor-pointer">
                    </label>

                    {{-- Kursor Besar --}}
                    <label class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer border border-slate-200">
                        <span class="font-bold flex items-center gap-2">
                            <span class="iconify text-base text-slate-500" data-icon="lucide:mouse-pointer"></span>
                            <span>Kursor Lebih Besar</span>
                        </span>
                        <input type="checkbox" id="chk-big-cursor" onchange="toggleA11yClass('a11y-big-cursor', this.checked)" class="h-4 w-4 rounded border-slate-300 text-[#005b9f] focus:ring-[#005b9f] cursor-pointer">
                    </label>

                    {{-- Penggaris Baca --}}
                    <label class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer border border-slate-200">
                        <span class="font-bold flex items-center gap-2">
                            <span class="iconify text-base text-slate-500" data-icon="lucide:ruler"></span>
                            <span>Garis Panduan Membaca</span>
                        </span>
                        <input type="checkbox" id="chk-reading-ruler" onchange="toggleReadingRuler(this.checked)" class="h-4 w-4 rounded border-slate-300 text-[#005b9f] focus:ring-[#005b9f] cursor-pointer">
                    </label>

                    {{-- Pembaca Teks Suara (Speech) --}}
                    <label class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer border border-slate-200">
                        <span class="font-bold flex items-center gap-2">
                            <span class="iconify text-base text-slate-500" data-icon="lucide:volume-2"></span>
                            <span>Bantuan Pembaca Suara Teks</span>
                        </span>
                        <input type="checkbox" id="chk-speech-reader" onchange="toggleSpeechReader(this.checked)" class="h-4 w-4 rounded border-slate-300 text-[#005b9f] focus:ring-[#005b9f] cursor-pointer">
                    </label>
                </div>
            </div>

        </div>

        {{-- Footer: Reset Pengaturan --}}
        <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3">
            <button type="button" 
                    onclick="resetA11ySettings()"
                    class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                <span class="iconify text-sm" data-icon="lucide:rotate-ccw"></span>
                <span>Reset Standar</span>
            </button>
            <button type="button" 
                    onclick="toggleA11yMenu()"
                    class="px-5 py-2 rounded-xl bg-[#005b9f] hover:bg-[#04325e] text-white font-bold text-xs transition-colors cursor-pointer shadow-xs">
                Selesai
            </button>
        </div>
    </div>
</div>

<script>
/**
 * Sistem Manajemen Aksesibilitas Web BPS Karanganyar
 * Menyimpan preferensi di localStorage sehingga otomatis berlaku di seluruh halaman.
 */
const A11Y_STORAGE_KEY = 'bps_accessibility_prefs';

let a11yPrefs = {
    fontSize: 'md',
    contrast: 'normal',
    readableFont: false,
    lineHeight: false,
    letterSpacing: false,
    highlightLinks: false,
    bigCursor: false,
    readingRuler: false,
    speechReader: false
};

function loadA11yPrefs() {
    try {
        const stored = localStorage.getItem(A11Y_STORAGE_KEY);
        if (stored) {
            a11yPrefs = Object.assign(a11yPrefs, JSON.parse(stored));
        }
    } catch (e) {
        console.warn('Gagal memuat preferensi aksesibilitas:', e);
    }
    applyA11yPrefs();
}

function saveA11yPrefs() {
    try {
        localStorage.setItem(A11Y_STORAGE_KEY, JSON.stringify(a11yPrefs));
    } catch (e) {
        console.warn('Gagal menyimpan preferensi aksesibilitas:', e);
    }
}

function applyA11yPrefs() {
    const html = document.documentElement;

    // 1. Font Size
    html.classList.remove('a11y-font-sm', 'a11y-font-md', 'a11y-font-lg', 'a11y-font-xl');
    html.classList.add('a11y-font-' + a11yPrefs.fontSize);
    updateFontSizeUI();

    // 2. Contrast
    html.classList.remove('a11y-contrast-high', 'a11y-contrast-grayscale', 'a11y-contrast-invert');
    if (a11yPrefs.contrast !== 'normal') {
        html.classList.add('a11y-contrast-' + a11yPrefs.contrast);
    }
    updateContrastUI();

    // 3. Toggles
    html.classList.toggle('a11y-font-readable', !!a11yPrefs.readableFont);
    const chkFont = document.getElementById('chk-font-readable');
    if (chkFont) chkFont.checked = !!a11yPrefs.readableFont;

    html.classList.toggle('a11y-line-height-lg', !!a11yPrefs.lineHeight);
    const chkLine = document.getElementById('chk-line-height');
    if (chkLine) chkLine.checked = !!a11yPrefs.lineHeight;

    html.classList.toggle('a11y-letter-spacing-lg', !!a11yPrefs.letterSpacing);
    const chkLetter = document.getElementById('chk-letter-spacing');
    if (chkLetter) chkLetter.checked = !!a11yPrefs.letterSpacing;

    html.classList.toggle('a11y-highlight-links', !!a11yPrefs.highlightLinks);
    const chkLinks = document.getElementById('chk-highlight-links');
    if (chkLinks) chkLinks.checked = !!a11yPrefs.highlightLinks;

    html.classList.toggle('a11y-big-cursor', !!a11yPrefs.bigCursor);
    const chkCursor = document.getElementById('chk-big-cursor');
    if (chkCursor) chkCursor.checked = !!a11yPrefs.bigCursor;

    // Reading Ruler
    const ruler = document.getElementById('a11y-reading-ruler');
    if (ruler) {
        ruler.style.display = a11yPrefs.readingRuler ? 'block' : 'none';
    }
    const chkRuler = document.getElementById('chk-reading-ruler');
    if (chkRuler) chkRuler.checked = !!a11yPrefs.readingRuler;

    // Speech Reader Checkbox
    const chkSpeech = document.getElementById('chk-speech-reader');
    if (chkSpeech) chkSpeech.checked = !!a11yPrefs.speechReader;
}

function toggleA11yMenu() {
    const modal = document.getElementById('a11y-modal');
    if (!modal) return;
    const isHidden = modal.classList.contains('hidden');
    if (isHidden) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    } else {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function setA11yFontSize(size) {
    a11yPrefs.fontSize = size;
    saveA11yPrefs();
    applyA11yPrefs();
}

function updateFontSizeUI() {
    const label = document.getElementById('a11y-font-label');
    const labels = {
        sm: 'Kecil (85%)',
        md: 'Normal (100%)',
        lg: 'Besar (115%)',
        xl: 'Ekstra (130%)'
    };
    if (label) label.textContent = labels[a11yPrefs.fontSize] || 'Normal (100%)';

    ['sm', 'md', 'lg', 'xl'].forEach(s => {
        const btn = document.getElementById('btn-font-' + s);
        if (btn) {
            if (s === a11yPrefs.fontSize) {
                btn.className = 'a11y-font-btn py-2 rounded-xl border-2 border-[#005b9f] bg-blue-50 text-[#005b9f] font-black text-center transition-all cursor-pointer';
            } else {
                btn.className = 'a11y-font-btn py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 font-bold text-center transition-all cursor-pointer text-slate-700';
            }
        }
    });
}

function setA11yContrast(contrast) {
    a11yPrefs.contrast = contrast;
    saveA11yPrefs();
    applyA11yPrefs();
}

function updateContrastUI() {
    ['normal', 'high', 'grayscale', 'invert'].forEach(c => {
        const btn = document.getElementById('btn-contrast-' + c);
        if (btn) {
            if (c === a11yPrefs.contrast) {
                btn.className = 'a11y-contrast-btn p-2.5 rounded-xl border-2 border-[#005b9f] bg-blue-50 text-[#005b9f] font-bold text-left transition-all flex items-center gap-2 cursor-pointer';
            } else {
                btn.className = 'a11y-contrast-btn p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 font-bold text-left transition-all flex items-center gap-2 cursor-pointer text-slate-700';
            }
        }
    });
}

function toggleA11yClass(className, isEnabled) {
    if (className === 'a11y-font-readable') a11yPrefs.readableFont = isEnabled;
    if (className === 'a11y-line-height-lg') a11yPrefs.lineHeight = isEnabled;
    if (className === 'a11y-letter-spacing-lg') a11yPrefs.letterSpacing = isEnabled;
    if (className === 'a11y-highlight-links') a11yPrefs.highlightLinks = isEnabled;
    if (className === 'a11y-big-cursor') a11yPrefs.bigCursor = isEnabled;
    saveA11yPrefs();
    applyA11yPrefs();
}

function toggleReadingRuler(isEnabled) {
    a11yPrefs.readingRuler = isEnabled;
    saveA11yPrefs();
    applyA11yPrefs();
}

function toggleSpeechReader(isEnabled) {
    a11yPrefs.speechReader = isEnabled;
    saveA11yPrefs();
    applyA11yPrefs();
    if (isEnabled) {
        if ('speechSynthesis' in window) {
            const utter = new SpeechSynthesisUtterance('Bantuan pembaca suara teks aktif. Sorot atau klik teks untuk mendengarkan.');
            utter.lang = 'id-ID';
            window.speechSynthesis.speak(utter);
        }
    }
}

function resetA11ySettings() {
    a11yPrefs = {
        fontSize: 'md',
        contrast: 'normal',
        readableFont: false,
        lineHeight: false,
        letterSpacing: false,
        highlightLinks: false,
        bigCursor: false,
        readingRuler: false,
        speechReader: false
    };
    saveA11yPrefs();
    applyA11yPrefs();
}

// Event listener untuk reading ruler yang mengikuti pergerakan kursor
document.addEventListener('mousemove', function(e) {
    if (!a11yPrefs.readingRuler) return;
    const ruler = document.getElementById('a11y-reading-ruler');
    if (ruler) {
        ruler.style.top = e.clientY + 'px';
    }
});

// Event listener untuk pembaca teks yang dipilih
document.addEventListener('mouseup', function() {
    if (!a11yPrefs.speechReader || !('speechSynthesis' in window)) return;
    const selected = window.getSelection().toString().trim();
    if (selected && selected.length > 2 && selected.length < 500) {
        window.speechSynthesis.cancel();
        const utter = new SpeechSynthesisUtterance(selected);
        utter.lang = 'id-ID';
        window.speechSynthesis.speak(utter);
    }
});

// Jalankan saat DOM siap
document.addEventListener('DOMContentLoaded', loadA11yPrefs);
</script>
