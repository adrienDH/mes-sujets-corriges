document.addEventListener("DOMContentLoaded", function () {

    // --- Login modal auto-ouverture si échec ---
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("login") && urlParams.get("login") === "failed") {
        const modalEl = document.getElementById("modal_connection");
        if (modalEl && window.bootstrap) {
            new bootstrap.Modal(modalEl).show();
        }
    }

    // --- Contact AJAX ---
    const submitBtn = document.getElementById("submit_contact");
    if (submitBtn) {
        submitBtn.addEventListener("click", function () {
            const wrapper = document.querySelector(".contact-form-wrapper");
            const ajaxUrl = wrapper.dataset.ajaxurl;
            const data = {
                action: wrapper.dataset.action,
                nonce: wrapper.dataset.nonce,
                email: document.getElementById("email").value,
                message: document.getElementById("message").value,
            };
            fetch(ajaxUrl, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams(data),
            })
                .then(r => r.json())
                .then(function (res) {
                    const success = wrapper.querySelector(".success");
                    const error = wrapper.querySelector(".error");
                    if (res.success) {
                        success.style.display = "block";
                        error.style.display = "none";
                        wrapper.querySelectorAll(".form-fields").forEach(f => f.style.display = "none");
                    } else {
                        error.style.display = "block";
                    }
                });
        });
    }

    // --- Filtre client-side (uniquement sur la page d'accueil) ---
    const cardGrid = document.getElementById("card-grid");
    if (!cardGrid) return;

    /* State */
    const state = { q: "", year: "", center: "", type1: "", type2: [], withCorr: false, sort: "default" };

    /* Éléments de contrôle */
    const elSearch   = document.getElementById("f-search");
    const elCenter   = document.getElementById("f-center");
    const elWithCorr = document.getElementById("f-withcorr");
    const elSort     = document.getElementById("f-sort");
    const elCount    = document.getElementById("sujet-count");
    const elChips    = document.getElementById("active-chips");
    const elEmpty    = document.getElementById("no-results");
    const elReset    = document.getElementById("reset-all");
    const elYearPills   = document.querySelectorAll(".ypill");
    const elSegBtns     = document.querySelectorAll(".seg-btn");
    const elThemeItems  = document.querySelectorAll(".theme-item");
    const elClearYear   = document.getElementById("clear-year");
    const elClearType1  = document.getElementById("clear-type1");
    const elClearThemes = document.getElementById("clear-themes");

    /* Ordre initial des cards (pour le tri "default") */
    const allCards = Array.from(cardGrid.querySelectorAll(".card"));
    const defaultOrder = allCards.slice();

    /* ---- Binders ---- */

    if (elSearch) {
        elSearch.addEventListener("input", function () {
            state.q = this.value.trim();
            applyAll();
        });
    }

    elYearPills.forEach(function (btn) {
        btn.addEventListener("click", function () {
            const val = this.dataset.year;
            state.year = (state.year === val) ? "" : val;
            elYearPills.forEach(b => b.classList.toggle("on", b.dataset.year === state.year && state.year !== ""));
            if (elClearYear) elClearYear.style.display = state.year ? "" : "none";
            applyAll();
        });
    });
    if (elClearYear) {
        elClearYear.addEventListener("click", function () {
            state.year = "";
            elYearPills.forEach(b => b.classList.remove("on"));
            this.style.display = "none";
            applyAll();
        });
    }

    if (elCenter) {
        elCenter.addEventListener("change", function () {
            state.center = this.value;
            applyAll();
        });
    }

    elSegBtns.forEach(function (btn) {
        btn.addEventListener("click", function () {
            const val = this.dataset.type1;
            state.type1 = (state.type1 === val) ? "" : val;
            elSegBtns.forEach(b => b.classList.toggle("on", b.dataset.type1 === state.type1 && state.type1 !== ""));
            if (elClearType1) elClearType1.style.display = state.type1 ? "" : "none";
            applyAll();
        });
    });
    if (elClearType1) {
        elClearType1.addEventListener("click", function () {
            state.type1 = "";
            elSegBtns.forEach(b => b.classList.remove("on"));
            this.style.display = "none";
            applyAll();
        });
    }

    if (elWithCorr) {
        elWithCorr.addEventListener("click", function () {
            state.withCorr = !state.withCorr;
            this.classList.toggle("on", state.withCorr);
            applyAll();
        });
    }

    elThemeItems.forEach(function (btn) {
        btn.addEventListener("click", function () {
            const val = this.dataset.type2;
            const idx = state.type2.indexOf(val);
            if (idx === -1) { state.type2.push(val); }
            else { state.type2.splice(idx, 1); }
            this.classList.toggle("on", state.type2.includes(val));
            if (elClearThemes) elClearThemes.style.display = state.type2.length ? "" : "none";
            applyAll();
        });
    });
    if (elClearThemes) {
        elClearThemes.addEventListener("click", function () {
            state.type2 = [];
            elThemeItems.forEach(b => b.classList.remove("on"));
            this.style.display = "none";
            applyAll();
        });
    }

    if (elSort) {
        elSort.addEventListener("change", function () {
            state.sort = this.value;
            applyAll();
        });
    }

    if (elReset) {
        elReset.addEventListener("click", resetAll);
    }

    /* ---- Filter + sort ---- */

    function matchesCard(card) {
        if (state.year && card.dataset.year !== state.year) return false;
        if (state.center && card.dataset.center !== state.center) return false;
        if (state.type1) {
            const ids = (card.dataset.type1 || "").split(",").filter(Boolean);
            if (!ids.includes(state.type1)) return false;
        }
        if (state.type2.length) {
            const ids = (card.dataset.type2 || "").split(",").filter(Boolean);
            if (!state.type2.every(t => ids.includes(t))) return false;
        }
        if (state.withCorr && card.dataset.hasCorr !== "1") return false;
        if (state.q) {
            const haystack = [
                card.dataset.title,
                card.dataset.yearName,
                card.dataset.centerName,
                card.dataset.t1Names,
                card.dataset.t2Names,
            ].join(" ").toLowerCase();
            if (!haystack.includes(state.q.toLowerCase())) return false;
        }
        return true;
    }

    function applyAll() {
        /* Tri */
        let ordered = defaultOrder.slice();
        if (state.sort === "az") {
            ordered.sort(function (a, b) {
                return (a.dataset.title || "").localeCompare(b.dataset.title || "", "fr");
            });
        } else if (state.sort === "corr") {
            ordered.sort(function (a, b) {
                return (b.dataset.hasCorr === "1" ? 1 : 0) - (a.dataset.hasCorr === "1" ? 1 : 0);
            });
        }
        ordered.forEach(c => cardGrid.appendChild(c));

        /* Filtre + comptage */
        let visible = 0;
        ordered.forEach(function (card) {
            const show = matchesCard(card);
            card.style.display = show ? "" : "none";
            if (show) visible++;
        });

        /* Mettre à jour le compteur */
        if (elCount) {
            elCount.textContent = visible;
        }

        /* Message "aucun résultat" */
        if (elEmpty) {
            elEmpty.classList.toggle("show", visible === 0);
        }

        /* Bouton reset */
        const hasFilters = state.q || state.year || state.center || state.type1 || state.type2.length || state.withCorr;
        if (elReset) elReset.disabled = !hasFilters;

        /* Chips */
        renderChips();
    }

    /* ---- Chips filtres actifs ---- */

    function chipDef(label, clearFn) { return { label: label, clear: clearFn }; }

    function renderChips() {
        if (!elChips) return;
        const chips = [];

        if (state.year) {
            const btn = Array.from(elYearPills).find(b => b.dataset.year === state.year);
            chips.push(chipDef("Année " + (btn ? btn.textContent : state.year), function () {
                state.year = "";
                elYearPills.forEach(b => b.classList.remove("on"));
                if (elClearYear) elClearYear.style.display = "none";
                applyAll();
            }));
        }
        if (state.center && elCenter) {
            const opt = elCenter.querySelector('option[value="' + state.center + '"]');
            chips.push(chipDef(opt ? opt.textContent : state.center, function () {
                state.center = "";
                elCenter.value = "";
                applyAll();
            }));
        }
        if (state.type1) {
            const btn = Array.from(elSegBtns).find(b => b.dataset.type1 === state.type1);
            chips.push(chipDef(btn ? btn.textContent : state.type1, function () {
                state.type1 = "";
                elSegBtns.forEach(b => b.classList.remove("on"));
                if (elClearType1) elClearType1.style.display = "none";
                applyAll();
            }));
        }
        if (state.withCorr) {
            chips.push(chipDef("Avec corrigé", function () {
                state.withCorr = false;
                if (elWithCorr) elWithCorr.classList.remove("on");
                applyAll();
            }));
        }
        state.type2.forEach(function (val) {
            const btn = Array.from(elThemeItems).find(b => b.dataset.type2 === val);
            const label = btn ? btn.dataset.themeName : val;
            chips.push(chipDef(label, function () {
                const idx = state.type2.indexOf(val);
                if (idx !== -1) state.type2.splice(idx, 1);
                elThemeItems.forEach(b => { if (b.dataset.type2 === val) b.classList.remove("on"); });
                if (elClearThemes) elClearThemes.style.display = state.type2.length ? "" : "none";
                applyAll();
            }));
        });

        if (chips.length === 0) {
            elChips.style.display = "none";
            return;
        }
        elChips.style.display = "";
        elChips.innerHTML = '<span class="active-lbl">Filtres actifs :</span>';
        chips.forEach(function (chip) {
            const span = document.createElement("span");
            span.className = "active-chip";
            span.textContent = chip.label;
            const xBtn = document.createElement("button");
            xBtn.className = "chip-x";
            xBtn.setAttribute("aria-label", "Retirer");
            xBtn.innerHTML = '<svg viewBox="0 0 24 24" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>';
            xBtn.addEventListener("click", chip.clear);
            span.appendChild(xBtn);
            elChips.appendChild(span);
        });
    }

    /* ---- Reset all ---- */

    function resetAll() {
        state.q = ""; state.year = ""; state.center = ""; state.type1 = ""; state.type2 = []; state.withCorr = false; state.sort = "default";
        if (elSearch)   elSearch.value = "";
        if (elCenter)   elCenter.value = "";
        if (elSort)     elSort.value = "default";
        if (elWithCorr) elWithCorr.classList.remove("on");
        elYearPills.forEach(b => b.classList.remove("on"));
        elSegBtns.forEach(b => b.classList.remove("on"));
        elThemeItems.forEach(b => b.classList.remove("on"));
        [elClearYear, elClearType1, elClearThemes].forEach(el => { if (el) el.style.display = "none"; });
        applyAll();
    }

    /* ---- Toast ---- */

    const toastEl  = document.getElementById("toast");
    const toastMsg = document.getElementById("toast-msg");
    let toastTimer;
    window.showToast = function (msg) {
        if (!toastEl) return;
        if (toastMsg) toastMsg.textContent = msg;
        toastEl.classList.add("show");
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { toastEl.classList.remove("show"); }, 2600);
    };
});
