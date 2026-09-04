{{-- 
    Modal Pemilih Bahasa Global (80+ Bahasa Dunia via Google Cloud Translation)
    Mendukung penerjemahan seluruh halaman dan integrasi AI Chatbot BPS Kabupaten Karanganyar
--}}

<div id="bps-language-modal" 
     class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden transition-all duration-300 flex items-center justify-center p-3 sm:p-5"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="lang-modal-title">
    
    {{-- Modal Box Card --}}
    <div class="w-full max-w-2xl bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-in fade-in zoom-in-95 duration-200">
        
        {{-- Modal Header --}}
        <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex items-center justify-between gap-3 shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0 shadow-xs">
                    <span class="iconify text-xl text-slate-700" data-icon="lucide:globe"></span>
                </div>
                <div class="min-w-0">
                    <h3 id="lang-modal-title" class="text-sm sm:text-base font-bold text-slate-900 tracking-tight leading-tight truncate">
                        Pilih Bahasa / Select Language
                    </h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5 truncate">
                        Mendukung 80+ Bahasa Dunia (Google Translate)
                    </p>
                </div>
            </div>
            <button type="button" 
                    onclick="closeLanguageModal()"
                    class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer shrink-0"
                    aria-label="Tutup Modal Bahasa">
                <span class="iconify text-xl" data-icon="lucide:x"></span>
            </button>
        </div>

        {{-- Search Bar --}}
        <div class="p-4 sm:p-6 pb-2 shrink-0">
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 iconify text-lg text-slate-400" data-icon="lucide:search"></span>
                <input type="text" 
                       id="lang-search-input" 
                       oninput="filterLanguages(this.value)"
                       placeholder="Cari bahasa... (English, Arabic, Japanese, Jawa, Sunda, dll)" 
                       class="w-full pl-11 pr-4 py-2.5 sm:py-3 bg-white border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005b9f] focus:border-transparent transition-all shadow-xs">
                <button type="button" 
                        id="lang-clear-search"
                        onclick="clearLanguageSearch()" 
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 hidden">
                    <span class="iconify text-base" data-icon="lucide:x-circle"></span>
                </button>
            </div>
        </div>

        {{-- Scrollable Language List Area --}}
        <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-2 space-y-4">
            
            <div class="flex items-center justify-between">
                <p id="lang-section-label" class="text-[10px] sm:text-[11px] font-extrabold text-slate-400 tracking-wider uppercase">
                    BAHASA POPULER / QUICK SELECT
                </p>
                <span id="lang-count-badge" class="text-[10px] font-bold text-slate-400"></span>
            </div>

            {{-- Grid of Languages --}}
            <div id="languages-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 pb-2">
                {{-- Injected dynamically by JS for smooth real-time filter & instant active-state highlight --}}
            </div>

            {{-- No Results State --}}
            <div id="lang-no-results" class="hidden py-8 text-center space-y-2">
                <span class="iconify text-3xl text-slate-300 mx-auto" data-icon="lucide:search-x"></span>
                <p class="text-xs font-bold text-slate-600">Bahasa tidak ditemukan</p>
                <p class="text-[11px] text-slate-400">Silakan coba kata kunci bahasa lain dalam bahasa Indonesia atau Inggris.</p>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="px-5 py-3.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-center shrink-0">
            <p class="text-[11px] text-slate-500 font-mono flex items-center gap-1.5">
                <span class="text-amber-500">⚡</span>
                <span>Google Cloud Translation</span>
                <span class="text-slate-300">•</span>
                <span>80+ Bahasa Dunia</span>
            </p>
        </div>
    </div>
</div>

{{-- Hidden Google Translate Element --}}
<div id="google_translate_element" style="display:none !important;" aria-hidden="true"></div>

<style>
/* Clean Headless Google Translate - Sembunyikan banner & bar bawaan Google */
.goog-te-banner-frame.skiptranslate,
.goog-te-banner-frame,
#goog-gt-tt,
.goog-te-balloon-frame,
.goog-te-gadget-simple {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
}
body {
    top: 0px !important;
    position: static !important;
}
.skiptranslate:not(.allowed) {
    display: none !important;
}
font[style] {
    background-color: transparent !important;
    box-shadow: none !important;
}
</style>

<script>
// =============================================================
// DATABASE 80+ BAHASA DUNIA LENGKAP
// =============================================================
const BPS_LANGUAGES = [
    // Bahasa Populer (Quick Select)
    { code: 'id', badge: 'ID', name: 'Bahasa Indonesia', sub: 'Indonesian', keywords: 'indonesia indonesia indonesian melayu nasional' },
    { code: 'en', badge: 'GB', name: 'English', sub: 'English', keywords: 'english inggris united kingdom usa britain american' },
    { code: 'ar', badge: 'SA', name: 'العربية', sub: 'Arabic', keywords: 'arab arabic saudi arabia timur tengah' },
    { code: 'ja', badge: 'JP', name: '日本語', sub: 'Japanese', keywords: 'jepang jepun japanese nihongo tokyo' },
    { code: 'zh-CN', badge: 'CN', name: '简体中文', sub: 'Chinese (Simplified)', keywords: 'china tiongkok mandarin simplified cina tionghoa' },
    { code: 'zh-TW', badge: 'TW', name: '繁體中文', sub: 'Chinese (Traditional)', keywords: 'taiwan mandarin traditional hongkong cina tionghoa' },
    { code: 'de', badge: 'DE', name: 'Deutsch', sub: 'German', keywords: 'jerman german deutschland berlin' },
    { code: 'fr', badge: 'FR', name: 'Français', sub: 'French', keywords: 'prancis french france paris' },
    { code: 'es', badge: 'ES', name: 'Español', sub: 'Spanish', keywords: 'spanyol spanish espana madrid' },
    { code: 'ko', badge: 'KR', name: '한국어', sub: 'Korean', keywords: 'korea korean seoul hangul' },
    { code: 'ru', badge: 'RU', name: 'Русский', sub: 'Russian', keywords: 'rusia russian moscow cyrillic' },
    { code: 'nl', badge: 'NL', name: 'Nederlands', sub: 'Dutch', keywords: 'belanda dutch amsterdam holland' },
    { code: 'tr', badge: 'TR', name: 'Türkçe', sub: 'Turkish', keywords: 'turki turkish istanbul ankara' },
    { code: 'pt', badge: 'PT', name: 'Português', sub: 'Portuguese', keywords: 'portugis portuguese brazil lisbon' },
    { code: 'it', badge: 'IT', name: 'Italiano', sub: 'Italian', keywords: 'italia italian roma milan' },
    { code: 'vi', badge: 'VN', name: 'Tiếng Việt', sub: 'Vietnamese', keywords: 'vietnam vietnamese hanoi' },
    { code: 'th', badge: 'TH', name: 'ภาษาไทย', sub: 'Thai', keywords: 'thailand thai bangkok siam' },
    { code: 'ms', badge: 'MY', name: 'Bahasa Melayu', sub: 'Malay', keywords: 'malaysia melayu kuala lumpur' },
    { code: 'jw', badge: 'JW', name: 'Basa Jawa', sub: 'Javanese', keywords: 'jawa javanese krama ngoko solo karanganyar jogja' },
    { code: 'su', badge: 'SU', name: 'Basa Sunda', sub: 'Sundanese', keywords: 'sunda sundanese bandung priangan jawa barat' },

    // Bahasa Dunia Tambahan (Searchable 80+ Bahasa)
    { code: 'hi', badge: 'IN', name: 'हिन्दी', sub: 'Hindi', keywords: 'india hindi new delhi bollywood' },
    { code: 'tl', badge: 'PH', name: 'Tagalog', sub: 'Filipino', keywords: 'filipina philippines tagalog manila' },
    { code: 'pl', badge: 'PL', name: 'Polski', sub: 'Polish', keywords: 'polandia polish warsaw' },
    { code: 'uk', badge: 'UA', name: 'Українська', sub: 'Ukrainian', keywords: 'ukraina ukrainian kyiv' },
    { code: 'sv', badge: 'SE', name: 'Svenska', sub: 'Swedish', keywords: 'swedia swedish stockholm' },
    { code: 'cs', badge: 'CZ', name: 'Čeština', sub: 'Czech', keywords: 'ceko czech prague' },
    { code: 'el', badge: 'GR', name: 'Ελληνικά', sub: 'Greek', keywords: 'yunani greek athens' },
    { code: 'hu', badge: 'HU', name: 'Magyar', sub: 'Hungarian', keywords: 'hungaria hungarian budapest' },
    { code: 'ro', badge: 'RO', name: 'Română', sub: 'Romanian', keywords: 'rumania romanian bucharest' },
    { code: 'da', badge: 'DK', name: 'Dansk', sub: 'Danish', keywords: 'denmark danish copenhagen' },
    { code: 'fi', badge: 'FI', name: 'Suomi', sub: 'Finnish', keywords: 'finlandia finnish helsinki' },
    { code: 'no', badge: 'NO', name: 'Norsk', sub: 'Norwegian', keywords: 'norwegia norwegian oslo' },
    { code: 'he', badge: 'IL', name: 'עברית', sub: 'Hebrew', keywords: 'ibrani hebrew israel' },
    { code: 'fa', badge: 'IR', name: 'فارسی', sub: 'Persian', keywords: 'persia farsi iran tehran' },
    { code: 'ur', badge: 'PK', name: 'اردو', sub: 'Urdu', keywords: 'urdu pakistan islamabad' },
    { code: 'bn', badge: 'BD', name: 'বাংলা', sub: 'Bengali', keywords: 'bengali bangladesh dhaka' },
    { code: 'ta', badge: 'TA', name: 'தமிழ்', sub: 'Tamil', keywords: 'tamil india srilanka chennai' },
    { code: 'te', badge: 'TE', name: 'తెలుగు', sub: 'Telugu', keywords: 'telugu india hyderabad' },
    { code: 'my', badge: 'MM', name: 'မြန်မာဘာသာ', sub: 'Burmese', keywords: 'myanmar burma burmese yangon' },
    { code: 'km', badge: 'KH', name: 'ភាសាខ្មែរ', sub: 'Khmer', keywords: 'kamboja cambodia khmer phnom penh' },
    { code: 'lo', badge: 'LA', name: 'ພາສາລາວ', sub: 'Lao', keywords: 'laos lao vientiane' },
    { code: 'ne', badge: 'NP', name: 'नेपाली', sub: 'Nepali', keywords: 'nepal nepali kathmandu' },
    { code: 'si', badge: 'LK', name: 'සිංහල', sub: 'Sinhala', keywords: 'sri lanka sinhala colombo' },
    { code: 'sw', badge: 'KE', name: 'Kiswahili', sub: 'Swahili', keywords: 'kenya tanzania swahili afrika' },
    { code: 'af', badge: 'ZA', name: 'Afrikaans', sub: 'Afrikaans', keywords: 'afrika selatan afrikaans south africa' },
    { code: 'hr', badge: 'HR', name: 'Hrvatski', sub: 'Croatian', keywords: 'kroasia croatian zagreb' },
    { code: 'sk', badge: 'SK', name: 'Slovenčina', sub: 'Slovak', keywords: 'slowakia slovak bratislava' },
    { code: 'bg', badge: 'BG', name: 'Български', sub: 'Bulgarian', keywords: 'bulgaria bulgarian sofia' },
    { code: 'sr', badge: 'RS', name: 'Српски', sub: 'Serbian', keywords: 'serbia serbian belgrade' }
];

let currentBpsLang = localStorage.getItem('bps_selected_lang') || getCookieLanguage() || 'id';

function getCookieLanguage() {
    const match = document.cookie.match(/googtrans=\/id\/([^;]+)/);
    return match ? match[1] : null;
}

// -------------------------------------------------------------
// RENDER DAFTAR BAHASA KE MODAL
// -------------------------------------------------------------
function renderLanguagesList(filterQuery = '') {
    const grid = document.getElementById('languages-grid');
    const noResults = document.getElementById('lang-no-results');
    const sectionLabel = document.getElementById('lang-section-label');
    const countBadge = document.getElementById('lang-count-badge');
    if (!grid) return;

    const query = filterQuery.trim().toLowerCase();
    const filtered = query 
        ? BPS_LANGUAGES.filter(l => l.name.toLowerCase().includes(query) || l.sub.toLowerCase().includes(query) || l.code.toLowerCase().includes(query) || l.keywords.includes(query))
        : BPS_LANGUAGES;

    if (query) {
        sectionLabel.textContent = `HASIL PENCARIAN (${filtered.length})`;
        countBadge.textContent = `${filtered.length} bahasa`;
    } else {
        sectionLabel.textContent = 'BAHASA POPULER / QUICK SELECT';
        countBadge.textContent = `${BPS_LANGUAGES.length}+ bahasa`;
    }

    if (filtered.length === 0) {
        grid.innerHTML = '';
        noResults.classList.remove('hidden');
        return;
    }

    noResults.classList.add('hidden');
    grid.innerHTML = filtered.map(lang => {
        const isActive = (currentBpsLang === lang.code) || (currentBpsLang === 'id' && lang.code === 'id');
        
        if (isActive) {
            return `
                <button type="button" 
                        onclick="selectBpsLanguage('${lang.code}')"
                        class="p-3 sm:p-3.5 rounded-2xl bg-[#0f172a] text-white border border-slate-900 shadow-md flex items-center justify-between text-left transition-all cursor-pointer group active:scale-98">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="w-8 h-8 rounded-xl bg-white/15 text-white font-black text-xs flex items-center justify-center shrink-0 border border-white/20">
                            ${lang.badge}
                        </span>
                        <div class="min-w-0">
                            <span class="text-xs sm:text-sm font-bold text-white block leading-tight truncate">${lang.name}</span>
                            <span class="text-[10px] sm:text-[11px] text-slate-300 block truncate mt-0.5">${lang.sub}</span>
                        </div>
                    </div>
                    <span class="iconify text-lg text-emerald-400 shrink-0 ml-2" data-icon="lucide:check"></span>
                </button>
            `;
        }

        return `
            <button type="button" 
                    onclick="selectBpsLanguage('${lang.code}')"
                    class="p-3 sm:p-3.5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/90 hover:border-slate-300 text-slate-800 shadow-xs flex items-center justify-between text-left transition-all cursor-pointer group active:scale-98">
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-blue-50 text-slate-700 group-hover:text-[#005b9f] font-black text-xs flex items-center justify-center shrink-0 border border-slate-200 transition-colors">
                        ${lang.badge}
                    </span>
                    <div class="min-w-0">
                        <span class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-[#005b9f] block leading-tight truncate transition-colors">${lang.name}</span>
                        <span class="text-[10px] sm:text-[11px] text-slate-400 block truncate mt-0.5">${lang.sub}</span>
                    </div>
                </div>
            </button>
        `;
    }).join('');

    if (window.Iconify && typeof window.Iconify.scan === 'function') {
        window.Iconify.scan(grid);
    }
}

// -------------------------------------------------------------
// FILTER & SEARCH REAL-TIME
// -------------------------------------------------------------
function filterLanguages(val) {
    const clearBtn = document.getElementById('lang-clear-search');
    if (clearBtn) {
        if (val) clearBtn.classList.remove('hidden');
        else clearBtn.classList.add('hidden');
    }
    renderLanguagesList(val);
}

function clearLanguageSearch() {
    const input = document.getElementById('lang-search-input');
    if (input) {
        input.value = '';
        filterLanguages('');
        input.focus();
    }
}

// -------------------------------------------------------------
// KONTROL MODAL BUKA / TUTUP
// -------------------------------------------------------------
function openLanguageModal() {
    const modal = document.getElementById('bps-language-modal');
    if (!modal) return;
    renderLanguagesList();
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => {
        const input = document.getElementById('lang-search-input');
        if (input) input.focus();
    }, 100);
}

function closeLanguageModal() {
    const modal = document.getElementById('bps-language-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close on backdrop click & ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLanguageModal();
});
document.addEventListener('click', function(e) {
    const modal = document.getElementById('bps-language-modal');
    if (modal && !modal.classList.contains('hidden') && e.target === modal) {
        closeLanguageModal();
    }
});

// -------------------------------------------------------------
// LOGIKA PILIH BAHASA & TRANSLATE ENGINE
// -------------------------------------------------------------
function selectBpsLanguage(code) {
    currentBpsLang = code;
    localStorage.setItem('bps_selected_lang', code);

    // Update cookie googtrans
    if (code === 'id') {
        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + window.location.hostname;
    } else {
        document.cookie = "googtrans=/id/" + code + "; path=/;";
        document.cookie = "googtrans=/id/" + code + "; path=/; domain=" + window.location.hostname;
    }

    // Update badge di seluruh halaman
    updateLanguageBadges(code);
    closeLanguageModal();

    // Trigger translate widget Google
    const select = document.querySelector('#google_translate_element select');
    if (select) {
        select.value = code;
        select.dispatchEvent(new Event('change'));
    }

    // Pancarkan event agar komponen lain (seperti Chatbot) tahu bahasa berubah
    window.dispatchEvent(new CustomEvent('bps-language-changed', {
        detail: { lang: code }
    }));

    // Jika select belum siap atau beralih dari/ke id, reload untuk memastikan translasi 100% mulus
    setTimeout(() => {
        window.location.reload();
    }, 300);
}

function updateLanguageBadges(code) {
    const langObj = BPS_LANGUAGES.find(l => l.code === code) || BPS_LANGUAGES[0];
    
    // Navbar badges
    const navCode = document.getElementById('current-lang-code');
    if (navCode) navCode.textContent = langObj.badge || 'ID';

    const navCodeMobile = document.getElementById('current-lang-code-mobile');
    if (navCodeMobile) navCodeMobile.textContent = langObj.badge || 'ID';

    const topBarLabel = document.getElementById('current-lang-topbar');
    if (topBarLabel) topBarLabel.textContent = langObj.name || 'Bahasa Indonesia';

    // Chatbot badges
    const chatBadge = document.getElementById('chat-lang-label');
    if (chatBadge) chatBadge.textContent = langObj.badge || 'ID';

    const chatInputBadge = document.getElementById('chat-input-lang-badge');
    if (chatInputBadge) chatInputBadge.textContent = langObj.badge || 'ID';
}

// -------------------------------------------------------------
// GOOGLE TRANSLATE SDK INITIALIZATION
// -------------------------------------------------------------
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'id',
        includedLanguages: BPS_LANGUAGES.map(l => l.code).join(','),
        autoDisplay: false
    }, 'google_translate_element');
}

// Inisialisasi saat DOM siap
document.addEventListener('DOMContentLoaded', function() {
    updateLanguageBadges(currentBpsLang);
});
</script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>
