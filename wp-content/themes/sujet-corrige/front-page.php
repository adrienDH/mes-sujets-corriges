<?php
$theme_colors = svt_theme_colors();

/* === Requêtes légères (IDs + taxonomies, sans ACF) === */
$querySujetService  = new QuerySujetDefaultProvider();
$sujetIds           = $querySujetService->getDefault();
$allTaxonomyService = new QueryAllTaxonomyProvider();
$years              = $allTaxonomyService->get('year-tax');
$typesEx1           = $allTaxonomyService->get('type-exe-1-tax');
$typesEx2           = $allTaxonomyService->get('type-exe-2-tax');
$centers            = $allTaxonomyService->get('center-tax');
$totalSujets        = count($sujetIds);
$yearCount          = count($years);
?>
<?php get_header() ?>
<?php
/* === Calcul des données ACF (après get_header pour que ACF soit initialisé) === */
$cards        = [];
$withCorrCount = 0;
$type2Counts  = [];

foreach ($sujetIds as $postId) {
    $yearT   = get_the_terms($postId, 'year-tax')       ?: [];
    $t1T     = get_the_terms($postId, 'type-exe-1-tax') ?: [];
    $t2T     = get_the_terms($postId, 'type-exe-2-tax') ?: [];
    $centerT = get_the_terms($postId, 'center-tax')     ?: [];

    /* Récupération des champs ACF via get_post_meta (contourne le bug acf_init) */
    $promptId  = get_post_meta($postId, 'prompt', true);
    $promptUrl = $promptId ? wp_get_attachment_url((int) $promptId) : null;

    $corrCount   = (int) get_post_meta($postId, 'corrections', true);
    $corrections = [];
    for ($i = 0; $i < $corrCount; $i++) {
        $corrAttachId = get_post_meta($postId, "corrections_{$i}_correction", true);
        $corrName     = get_post_meta($postId, "corrections_{$i}_name", true) ?: '';
        if ($corrAttachId) {
            $corrUrl = wp_get_attachment_url((int) $corrAttachId);
            if ($corrUrl) {
                $corrections[] = ['url' => $corrUrl, 'name' => $corrName];
            }
        }
    }
    $hasCorr = !empty($corrections);

    if ($hasCorr) $withCorrCount++;
    foreach ($t2T as $term) {
        $type2Counts[$term->term_id] = ($type2Counts[$term->term_id] ?? 0) + 1;
    }

    $cards[] = [
        'id'          => $postId,
        'promptUrl'   => $promptUrl,
        'corrections' => $corrections,
        'yearT'       => $yearT,
        't1T'         => $t1T,
        't2T'         => $t2T,
        'centerT'     => $centerT,
        'hasCorr'     => $hasCorr,
    ];
}
?>

<div class="shell">

    <!-- ====== SIDEBAR ====== -->
    <aside class="sidebar">

        <a class="sidebar-brand" href="<?= esc_url(home_url('/')) ?>">
            <span class="brand-mark">
                <svg viewBox="0 0 40 40" width="25" height="25" fill="none">
                    <path d="M20 6c7 0 12 4 12 11 0 5-4 8-8 8-1.5 0-3-.4-4-1.2" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>
                    <path d="M20 6c-2.5 4-3 9-1 13.5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" opacity=".75"/>
                    <path d="M8 30h24M11 35h18" stroke="#E6C79B" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="brand-text">
                <span class="brand-title">Mes sujets corrigés</span>
                <span class="brand-sub">Bac SVT</span>
            </span>
        </a>

        <div class="sidebar-scroll">

            <div class="s-search">
                <?= svt_icon('search', 1.8, 17) ?>
                <input type="text" id="f-search" placeholder="Rechercher…" autocomplete="off">
            </div>

            <!-- Années -->
            <div class="fgroup">
                <div class="fgroup-head">
                    <span class="fgroup-title"><?= svt_icon('calendar', 1.8, 14) ?> Année</span>
                    <button class="fgroup-clear" id="clear-year" style="display:none">Effacer</button>
                </div>
                <div class="year-pills">
                    <?php foreach ($years as $y): ?>
                        <button class="ypill" data-year="<?= $y->term_id ?>"><?= esc_html($y->name) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Centre d'examen -->
            <div class="fgroup">
                <div class="fgroup-head">
                    <span class="fgroup-title"><?= svt_icon('pin', 1.8, 14) ?> Centre d'examen</span>
                </div>
                <div class="s-select">
                    <select id="f-center">
                        <option value="">Tous les centres</option>
                        <?php foreach ($centers as $c): ?>
                            <option value="<?= $c->term_id ?>"><?= esc_html($c->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Session / Type exercice 1 -->
            <?php if (!empty($typesEx1)): ?>
            <div class="fgroup">
                <div class="fgroup-head">
                    <span class="fgroup-title"><?= svt_icon('tag', 1.8, 14) ?> Type d'exercice 1</span>
                    <button class="fgroup-clear" id="clear-type1" style="display:none">Effacer</button>
                </div>
                <div class="segment">
                    <?php foreach ($typesEx1 as $t): ?>
                        <button class="seg-btn" data-type1="<?= $t->term_id ?>"><?= esc_html($t->name) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Avec corrigé -->
            <div class="fgroup">
                <button class="s-toggle" id="f-withcorr" type="button">
                    <span class="switch"></span>
                    <?= svt_icon('checkCircle', 1.8, 17) ?>
                    Avec corrigé
                    <span class="tg-meta"><?= $withCorrCount ?></span>
                </button>
            </div>

            <!-- Thèmes (type-exe-2-tax) -->
            <?php if (!empty($typesEx2)): ?>
            <div class="fgroup">
                <div class="fgroup-head">
                    <span class="fgroup-title"><?= svt_icon('tag', 1.8, 14) ?> Thèmes</span>
                    <button class="fgroup-clear" id="clear-themes" style="display:none">Effacer</button>
                </div>
                <div class="theme-list">
                    <?php foreach ($typesEx2 as $t):
                        $col   = $theme_colors[$t->name] ?? ['fg' => '#45524A', 'bg' => '#F2EFE6'];
                        $count = $type2Counts[$t->term_id] ?? 0;
                    ?>
                        <button class="theme-item" data-type2="<?= $t->term_id ?>" data-theme-name="<?= esc_attr($t->name) ?>" type="button">
                            <span class="t-ic" style="color:<?= $col['fg'] ?>;background:<?= $col['bg'] ?>"><?= svt_theme_icon($t->name, 14) ?></span>
                            <span class="t-name"><?= esc_html($t->name) ?></span>
                            <span class="t-count"><?= $count ?></span>
                            <span class="t-check"><?= svt_icon('check', 2.6, 12) ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- /sidebar-scroll -->

        <div class="sidebar-foot">
            <button class="reset-all" id="reset-all" type="button" disabled>
                <?= svt_icon('reset', 1.8, 15) ?> Réinitialiser les filtres
            </button>
            <?php if (!is_user_logged_in()): ?>
                <p class="sidebar-contribute">Contribuer ? <a href="<?= esc_url(home_url('/inscription')) ?>">S'inscrire ici</a></p>
            <?php endif; ?>
        </div>

    </aside><!-- /sidebar -->

    <!-- ====== MAIN ====== -->
    <div class="main">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h1><span class="h1-ic"><?= svt_icon('book', 1.8, 19) ?></span>Sujets du baccalauréat</h1>
                <span class="topbar-sub"><b id="sujet-count"><?= $totalSujets ?></b> <?= $totalSujets > 1 ? 'sujets' : 'sujet' ?> au total</span>
            </div>
            <span class="topbar-spacer"></span>
            <div class="sortsel">
                <select id="f-sort" aria-label="Trier par">
                    <option value="default">Plus récents</option>
                    <option value="az">Centre A→Z</option>
                    <option value="corr">Avec corrigé</option>
                </select>
            </div>
            <?php if (is_user_logged_in()): ?>
                <a href="<?= esc_url(admin_url()) ?>" class="btn btn-outline"><?= svt_icon('user', 1.8, 16) ?> Administration</a>
            <?php else: ?>
                <button type="button" class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#modal_connection"><?= svt_icon('user', 1.8, 16) ?> Se connecter</button>
            <?php endif; ?>
        </div>

        <!-- Bannière intro -->
        <div class="intro">
            <div class="intro-txt">
                <h2>Tous les sujets, <em>classés et corrigés.</em></h2>
                <p>Bibliothèque de sujets de spécialité SVT, filtrable par année, centre et thème du programme.</p>
            </div>
            <div class="intro-stats">
                <div class="istat">
                    <span class="istat-ic"><?= svt_icon('layersHist', 1.7, 18) ?></span>
                    <div class="n"><?= $totalSujets ?></div><div class="l">sujets</div>
                </div>
                <div class="istat">
                    <span class="istat-ic"><?= svt_icon('doc', 1.7, 18) ?></span>
                    <div class="n"><?= $withCorrCount ?></div><div class="l">corrigés</div>
                </div>
                <div class="istat">
                    <span class="istat-ic"><?= svt_icon('calendar', 1.7, 18) ?></span>
                    <div class="n"><?= $yearCount ?></div><div class="l">années</div>
                </div>
            </div>
        </div>

        <!-- Chips filtres actifs -->
        <div class="active-row" id="active-chips" style="display:none"></div>

        <!-- Grille de cartes -->
        <main class="results">
            <div class="card-grid" id="card-grid">

                <?php foreach ($cards as $card):
                    $postId      = $card['id'];
                    $promptUrl   = $card['promptUrl'];
                    $corrections = $card['corrections'];
                    $yearT       = $card['yearT'];
                    $t1T         = $card['t1T'];
                    $t2T         = $card['t2T'];
                    $centerT     = $card['centerT'];
                    $hasCorr     = $card['hasCorr'];

                    $yearId    = $yearT[0]->term_id  ?? '';
                    $yearName  = $yearT[0]->name     ?? '';
                    $centerId  = $centerT[0]->term_id ?? '';
                    $centerName = $centerT[0]->name  ?? '';
                    $t1Ids     = implode(',', array_column($t1T, 'term_id'));
                    $t1Names   = implode(',', array_column($t1T, 'name'));
                    $t2Ids     = implode(',', array_column($t2T, 'term_id'));
                    $t2Names   = implode(',', array_column($t2T, 'name'));
                    $postTitle = get_the_title($postId);
                    $canAccess = current_user_can('administrator') || current_user_can('editor');
                ?>
                <div class="card"
                    data-year="<?= esc_attr($yearId) ?>"
                    data-center="<?= esc_attr($centerId) ?>"
                    data-type1="<?= esc_attr($t1Ids) ?>"
                    data-type2="<?= esc_attr($t2Ids) ?>"
                    data-has-corr="<?= $hasCorr ? '1' : '0' ?>"
                    data-year-name="<?= esc_attr($yearName) ?>"
                    data-center-name="<?= esc_attr($centerName) ?>"
                    data-t1-names="<?= esc_attr($t1Names) ?>"
                    data-t2-names="<?= esc_attr($t2Names) ?>"
                    data-title="<?= esc_attr($postTitle) ?>"
                >
                    <div class="card-top">
                        <span class="card-marker">
                            <span class="card-cal"><?= svt_icon('calendar', 1.7, 15) ?></span>
                            <?php if ($yearName): ?>
                                <span class="card-year"><?= esc_html($yearName) ?></span>
                            <?php endif; ?>
                            <?php foreach ($t1T as $t1): ?>
                                <span class="card-session"><?= esc_html($t1->name) ?></span>
                            <?php endforeach; ?>
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="card-titleline">
                            <span class="card-pin"><?= svt_icon('pin', 1.8, 16) ?></span>
                            <span class="card-title"><?= esc_html($postTitle) ?></span>
                        </div>
                        <?php if (!empty($t2T)): ?>
                            <div class="card-tags">
                                <?php foreach ($t2T as $t2):
                                    $col = $theme_colors[$t2->name] ?? ['fg' => '#45524A', 'bg' => '#F2EFE6'];
                                ?>
                                    <span class="tag" style="color:<?= $col['fg'] ?>;background:<?= $col['bg'] ?>">
                                        <?= svt_theme_icon($t2->name, 13) ?>
                                        <?= esc_html($t2->name) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-actions">
                        <?php if ($promptUrl): ?>
                            <a href="<?= esc_url($promptUrl) ?>" target="_blank" class="act act-subject">
                                <?= svt_icon('download', 1.8, 15) ?> Télécharger le sujet
                            </a>
                        <?php endif; ?>

                        <?php if ($hasCorr): ?>
                            <?php foreach ($corrections as $corr): ?>
                                <?php if ($canAccess): ?>
                                    <a href="<?= esc_url($corr['url']) ?>" target="_blank" class="act act-corr">
                                        <?= svt_icon('doc', 1.7, 15) ?>
                                        <?= $corr['name'] ? esc_html($corr['name']) : 'Corrigé' ?>
                                    </a>
                                <?php else: ?>
                                    <button type="button" class="act act-corr act-locked" data-bs-toggle="modal" data-bs-target="#modal_connection">
                                        <?= svt_icon('doc', 1.7, 15) ?>
                                        <?= $corr['name'] ? esc_html($corr['name']) : 'Corrigé' ?>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="no-corr">Corrigé à venir</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div><!-- /card-grid -->

            <div class="empty" id="no-results">
                <h3>Aucun sujet ne correspond</h3>
                <p>Essayez d'élargir vos critères ou réinitialisez les filtres.</p>
            </div>
        </main>

    </div><!-- /main -->

</div><!-- /shell -->

<!-- ====== CONTACT ====== -->
<section class="contact-section">
    <div class="contact-inner">
        <h2>Contact</h2>
        <p>Pour toute information, n'hésitez pas à nous contacter, nous vous répondrons dès que possible.</p>
        <div
            class="contact-form-wrapper"
            data-nonce="<?= wp_create_nonce('security-svt-sujet-corrige-nonce') ?>"
            data-ajaxurl="<?= esc_url(admin_url('admin-ajax.php')) ?>"
            data-action="svt_contact"
        >
            <p class="success">Merci pour votre message !</p>
            <p class="error">Certains champs sont manquants.</p>
            <div class="form-fields">
                <label for="email">Email <span style="color:#c0392b">*</span></label>
                <input type="email" id="email" autocomplete="email">
            </div>
            <div class="form-fields">
                <label for="message">Message <span style="color:#c0392b">*</span></label>
                <textarea id="message" rows="5"></textarea>
            </div>
            <div class="form-fields">
                <button id="submit_contact" type="button">Envoyer</button>
            </div>
        </div>
    </div>
</section>

<!-- Toast -->
<div class="toast" id="toast" role="status" aria-live="polite">
    <?= svt_icon('download', 1.8, 17) ?>
    <span id="toast-msg"></span>
</div>

<?php get_footer() ?>
