/**
 * Club Interactive ESM Module
 */
export function u() {
    "use strict";
    const theLastExperience = noWorkers => {
        "use strict";
        const struct = {
            points: [{
                    x: 0,
                    y: -4,
                    f(s, d) {
                        this.y -= 0.01 * s * ts;
                    }
                },
                {
                    x: 0,
                    y: -16,
                    f(s, d) {
                        this.y -= 0.02 * s * d * ts;
                    }
                },
                {
                    x: 0,
                    y: 12,
                    f(s, d) {
                        this.y += 0.02 * s * d * ts;
                    }
                },
                { x: -12, y: 0 },
                { x: 12, y: 0 },
                { x: -24, y: 0 },
                { x: 24, y: 0 },
                { x: -12, y: 24 },
                { x: 12, y: 24 },
                {
                    x: -12,
                    y: 48,
                    f(s, d) {
                        this.x += 0.02 * s * d * ts;
                    }
                },
                {
                    x: 12,
                    y: 48,
                    f(s, d) {
                        this.x += 0.02 * s * d * ts;
                    }
                }
            ],
            links: [
                { p0: 0, p1: 1, p: 0.1 },
                { p0: 0, p1: 2, p: 0.1 },
                { p0: 0, p1: 3, p: 0.1 },
                { p0: 0, p1: 4, p: 0.1 },
                { p0: 1, p1: 3, p: 0.1 },
                { p0: 1, p1: 4, p: 0.1 },
                { p0: 3, p1: 5, p: 0.1 },
                { p0: 4, p1: 6, p: 0.1 },
                { p0: 2, p1: 7, p: 0.1 },
                { p0: 2, p1: 8, p: 0.1 },
                { p0: 7, p1: 9, p: 0.1 },
                { p0: 8, p1: 10, p: 0.1 }
            ]
        };

        const canvas = {
            init() {
                this.elem = document.querySelector("canvas");
                this.ctx = this.elem.getContext("2d");
                this.resize();
                window.addEventListener("resize", () => this.resize(), false);
                return this.ctx;
            },
            resize() {
                this.width = this.elem.width = this.elem.offsetWidth;
                this.height = this.elem.height = this.elem.offsetHeight;
            }
        };

        const ctx = canvas.init();
        let ts = 0;

        const pointer = {
            x: 0,
            y: 0,
            down(e) {
                this.x = e.clientX || (e.touches && e.touches[0].clientX);
                this.y = e.clientY || (e.touches && e.touches[0].clientY);
            },
            up(e) {}
        };

        window.addEventListener("mousemove", e => pointer.down(e), false);
        window.addEventListener("touchmove", e => pointer.down(e), false);
        window.addEventListener("mousedown", e => pointer.down(e), false);
        window.addEventListener("touchstart", e => pointer.down(e), false);
    };

    theLastExperience(true);
}

export function p() {
    var q = document.getElementById('audio');
    var t = document.getElementById('lo');
    var fr = document.getElementById('fr');
    if (!q) return false;
    if (q.paused) {
        q.play();
        if (t) t.style.display = "block";
        if (fr) fr.style.display = "none";
        return false;
    } else {
        q.pause();
        if (t) t.style.display = "none";
        if (fr) fr.style.display = "block";
        return false;
    }
}

export function o() {
    var audio = document.getElementById('audio');
    if (!audio) return;
    var playPromise = audio.play();
    if (playPromise !== undefined) {
        playPromise.then(_ => {}).catch(error => {});
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (typeof mdc !== 'undefined' && mdc.ripple && document.querySelector('.foo-button')) {
        mdc.ripple.MDCRipple.attachTo(document.querySelector('.foo-button'));
    }
});

// Bind to window for inline HTML onclick/onload attributes
window.u = u;
window.p = p;
window.o = o;
