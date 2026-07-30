/**
 * Smart Dynamic German Translator Engine (DE 🇩🇪)
 */
const germanDictionary = {
    // Navigation & Sidebar
    "Home": "Startseite",
    "About": "Über mich",
    "Chat Portal": "Chat-Portal",
    "Sandika": "Sandika Portal",
    "Blog": "Blog & Artikel",
    "Projects": "Projekte",
    "Publications": "Publikationen",
    "Support": "Unterstützung",
    "Contact": "Kontakt",
    "Search": "Suchen...",
    "Books": "Bücher",
    "VPN Server": "VPN-Server",
    "Fun Zone": "Spaß-Zone",

    // Header & User Actions
    "Log In": "Anmelden",
    "Sign Up": "Registrieren",
    "Logout": "Abmelden",
    "My Profile": "Mein Profil",
    "Settings": "Einstellungen",
    "Theme": "Design",
    "Light": "Hell",
    "Dark": "Dunkel",
    "German Language": "Deutsche Sprache",
    "English Language": "Englische Sprache",

    // Sandika Portal
    "OPERATIONAL HUB": "BETRIEBSZENTRALE",
    "Contribution Points (CP), Agent Ranks, Cipher Vault & Lexicon Network": "Beitragspunkte (CP), Agenten-Ränge, Chiffren-Tresor & Lexikon-Netzwerk",
    "Rules & Ranks": "Regeln & Ränge",
    "Stories Hub": "Geschichten-Zentrale",
    "Lexicon Dictionary": "Lexikon-Wörterbuch",
    "Git Insights": "Git-Einblicke",
    "Tactical Tools & ROT13": "Taktische Werkzeuge & ROT13",
    "Rookie": "Anfänger",
    "Soldier": "Soldat",
    "Captain": "Hauptmann",
    "Sergeant": "Feldwebel",
    "Lieutenant": "Leutnant",
    "Admiral": "Admiral",
    "Bossman": "Chef",

    // Blog & Articles
    "PARSABE BLOG & RESEARCH CHRONICLES": "PARSABE BLOG & FORSCHUNGSCHRONIK",
    "Rich Text Publishing, Technical AI Insights & Articles": "Rich-Text-Veröffentlichung, Technische KI-Einblicke & Artikel",
    "Write a Blog": "Blog schreiben",
    "Publish Article": "Artikel veröffentlichen",
    "Article Title": "Artikeltitel",
    "Article Content": "Artikelinhalt",
    "Formatting Tools": "Formatierungswerkzeuge",
    "Bullet List": "Aufzählungsliste",
    "Insert Table": "Tabelle einfügen",
    "Link": "Link",
    "Cover Image (Optional)": "Titelbild (Optional)",
    "Read Full Article": "Vollständigen Artikel lesen",
    "Back to Blog": "Zurück zum Blog",
    "Publication Article": "Publikationsartikel",

    // Chat Portal & Social Features
    "PARSABE ONLINE CHAT PORTAL": "PARSABE ONLINE CHAT-PORTAL",
    "Online Members": "Online-Mitglieder",
    "Direct Contacts": "Direktkontakte",
    "Type a message...": "Nachricht schreiben...",
    "Post Sandika Story": "Sandika-Story veröffentlichen",
    "What's happening?": "Was gibt's Neues?",
    "Community Timeline & Quick Post": "Community-Zeitleiste & Schnellbeitrag",
    "Post ➔": "Veröffentlichen ➔",
    "Likes": "Gefällt mir",
    "Comments": "Kommentare",
    "Repost": "Teilen",
    "Bookmark": "Lesezeichen",
    "Share": "Teilen",
    "Write a comment...": "Kommentar schreiben...",
    "Follow": "Folgen",
    "Following": "Gefolgt",
    "Followers": "Follower",
    "Posts": "Beiträge",
    "My Cover Headers Gallery": "Meine Galerie für Titelbanner",
    "My Profile Avatars Gallery": "Meine Profilbild-Galerie",
    "Account Privacy & Content Settings": "Konto-Datenschutz & Inhaltseinstellungen",
    "Delete My Account Completely": "Mein Konto vollständig löschen",

    // Admin Dashboard
    "PARSABE EXECUTIVE ANALYTICS CORE": "PARSABE EXECUTIVE ANALYSE-KERN",
    "Real-Time Traffic, Member Management, Analytics & Governance Platform": "Echtzeit-Verkehr, Mitgliederverwaltung, Analysen & Governance-Plattform",
    "Total Impressions": "Gesamte Impressionen",
    "Registered Members": "Registrierte Mitglieder",
    "Published Articles": "Veröffentlichte Artikel",
    "CS Feedback Score": "CS-Feedback-Bewertung",
    "Read Article": "Artikel lesen",
    "Delete & Notify Author": "Löschen & Autor benachrichtigen",

    // Common Buttons & Messages
    "Cancel": "Abbrechen",
    "Close": "Schließen",
    "Submit": "Absenden",
    "Save Changes": "Änderungen speichern",
    "Confirm": "Bestätigen",
    "Success": "Erfolg",
    "Error": "Fehler",
    "Notification": "Benachrichtigung"
};

function translateDOMNode(node) {
    if (node.nodeType === Node.TEXT_NODE) {
        const text = node.nodeValue.trim();
        if (text && germanDictionary[text]) {
            node.nodeValue = node.nodeValue.replace(text, germanDictionary[text]);
        } else if (text) {
            // Partial matching for key phrases
            for (const [key, value] of Object.entries(germanDictionary)) {
                if (key.length > 3 && text.includes(key)) {
                    node.nodeValue = node.nodeValue.replace(key, value);
                }
            }
        }
    } else if (node.nodeType === Node.ELEMENT_NODE) {
        // Translate placeholders & titles
        const placeholder = node.getAttribute('placeholder');
        if (placeholder && germanDictionary[placeholder]) {
            node.setAttribute('placeholder', germanDictionary[placeholder]);
        }
        const title = node.getAttribute('title');
        if (title && germanDictionary[title]) {
            node.setAttribute('title', germanDictionary[title]);
        }

        // Recursively translate children
        if (node.childNodes && !['SCRIPT', 'STYLE', 'TEXTAREA'].includes(node.tagName)) {
            node.childNodes.forEach(translateDOMNode);
        }
    }
}

export function applySmartGermanTranslation() {
    const locale = window.APP_LOCALE || (document.documentElement.lang === 'de' ? 'de' : 'en');
    if (locale !== 'de') return;

    // Set page HTML lang attribute
    document.documentElement.setAttribute('lang', 'de');

    // Translate DOM tree
    translateDOMNode(document.body);

    // Set up MutationObserver to translate dynamically rendered elements (e.g. Chat, Feed, Modals)
    const observer = new MutationObserver((mutations) => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                translateDOMNode(node);
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
}

document.addEventListener('DOMContentLoaded', () => {
    applySmartGermanTranslation();
});

window.applySmartGermanTranslation = applySmartGermanTranslation;
