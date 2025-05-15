<?php
    if(!empty($_POST) && isset($_POST['is_registration'])) {
        $userService = new UserProvider();
        $isUserCreated = $userService->create($_POST);
    }

    if(!empty($_POST) && !isset($_POST['is_registration'])) {
        $querySearchService = new QuerySearchProvider();
        $sujets = $querySearchService->search($_POST);
    } else {
        $querySujetService = new QuerySujetDefaultProvider();
        $sujets = $querySujetService->getDefault();
    }
    
    $allTaxonomyService = new QueryAllTaxonomyProvider();
    $years = $allTaxonomyService->get('year-tax');
    $typesEx1 = $allTaxonomyService->get('type-exe-1-tax');
    $typesEx2 = $allTaxonomyService->get('type-exe-2-tax');

    $isLogged = is_user_logged_in();
?>

<?php get_header() ?>

<div class="container">
    <div class="row mb-5">
        <div class="col-12">
            <p>Vous souhaitez contribuer ? Pour vous inscrire cliqué : <a href="<?= home_url('/').'inscription' ?>">ici</a></p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form method="post" action="<?= home_url() ?>" class="input-group">
                <select name="year-tax-name" class="form-select" aria-label="Années">
                    <option
                        <?php if(empty($_POST['year-tax-name'])): ?>
                            <?= 'selected' ?>
                        <?php endif; ?>
                            value=""
                    >
                        Choisir une année
                    </option>
                    <?php foreach ($years as $year): ?>
                        <option
                                value="<?= $year->term_id ?>"
                            <?php if(!empty($_POST['year-tax-name']) && $_POST['year-tax-name'] == $year->term_id): ?>
                                <?= 'selected' ?>
                            <?php endif; ?>
                        >
                            <?= $year->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="type-exe-1-tax-name" class="form-select" aria-label="Types de l'exercice 1">
                    <option
                        <?php if(empty($_POST['type-exe-1-tax-name'])): ?>
                            <?= 'selected' ?>
                        <?php endif; ?>
                            value=""
                    >
                        Choisir le type de l'exercice 1
                    </option>
                    <?php foreach ($typesEx1 as $type): ?>
                        <option
                                value="<?= $type->term_id ?>"
                            <?php if(!empty($_POST['type-exe-1-tax-name']) && $_POST['type-exe-1-tax-name'] == $type->term_id): ?>
                                <?= 'selected' ?>
                            <?php endif; ?>
                        >
                            <?= $type->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="type-exe-2-tax-name" class="form-select" aria-label="Types de l'exercice 2">
                    <option
                        <?php if(empty($_POST['type-exe-2-tax-name'])): ?>
                            <?= 'selected' ?>
                        <?php endif; ?>
                         value=""
                    >
                        Choisir le type de l'exercice 2
                    </option>
                    <?php foreach ($typesEx2 as $type): ?>
                        <option
                            value="<?= $type->term_id ?>"
                            <?php if(!empty($_POST['type-exe-2-tax-name']) && $_POST['type-exe-2-tax-name'] == $type->term_id): ?>
                                <?= 'selected' ?>
                            <?php endif; ?>
                        >
                            <?= $type->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input
                    type="text"
                    name="search-name"
                    placeholder="Titre du sujet"
                    class="form-control"
                    aria-label="Titre"
                    value="<?= !empty($_POST['search-name']) ? $_POST['search-name'] : '' ?>"
                >
                <button class="btn btn-outline-secondary" type="submit">Rechercher</button>
            </form>
            <div class="table-responsive">
                <div class="table-wrapper mt-5">
                    <img src="<?= get_template_directory_uri(); ?>/assets/images/fleur.png" alt="Fleur" class="fleur1">
                    <img src="<?= get_template_directory_uri(); ?>/assets/images/cellule.png" alt="Fleur" class="cellule">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Titre</th>
                            <th scope="col">Année</th>
                            <th scope="col">Type de l'exercice 1</th>
                            <th scope="col">Type de l'exercice 2</th>
                            <th scope="col" class="text-center">Ficher d'énnoncé</th>
                            <th scope="col" class="text-center">Fichier de correction</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($sujets as $sujetId): ?>
                            <?php
                                $acfFields = get_fields($sujetId);
                                $year = get_the_terms($sujetId, 'year-tax');
                                $typeEx1 = get_the_terms($sujetId, 'type-exe-1-tax');
                                $typeEx2 = get_the_terms($sujetId, 'type-exe-2-tax');
                            ?>
                            <tr>
                                <th scope="row" class="align-middle"><?= get_the_title($sujetId) ?></th>
                                <td class="align-middle">
                                    <?php if(!empty($year[0])): ?>
                                        <?= $year[0]->name ?>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <?php if(!empty($typeEx1[0])): ?>
                                        <?= $typeEx1[0]->name ?>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <?php if(!empty($typeEx2[0])): ?>
                                        <?= $typeEx2[0]->name ?>
                                    <?php endif; ?>
                                </td>
                                <?php if(!empty($acfFields['prompt'])): ?>
                                    <td class="text-center">
                                        <a href="<?= $acfFields['prompt']['url'] ?>" target="_blank" class="btn btn-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"></path>
                                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"></path>
                                            </svg>
                                            Sujet
                                        </a>
                                    </td>
                                <?php endif; ?>
                                <?php if(!empty($acfFields['corrections'])): ?>
                                    <td class="text-center">
                                        <?php foreach ($acfFields['corrections'] as $key => $correction): ?>
                                            <?php if(!empty($correction['correction'])): ?>
                                                <a
                                                    <?php if($isLogged): ?>
                                                        href="<?= $correction['correction']['url'] ?>"
                                                        target="_blank"
                                                    <?php endif; ?>
                                                    class="btn  <?= $isLogged ? 'btn-success' : 'btn-outline-secondary' ?>"
                                                    <?php if(!$isLogged): ?>
                                                        data-bs-toggle="modal" data-bs-target="#modal_connection"
                                                    <?php endif; ?>
                                                >
                                                    <span class="align-middle">Exercice <?= ++$key ?></span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"></path>
                                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"></path>
                                                    </svg>

                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div
        class="row mt-5 contact-form-wrapper"
        data-nonce="<?= wp_create_nonce('security-svt-sujet-corrige-nonce') ?>"
        data-ajaxurl="<?= admin_url('admin-ajax.php') ?>"
        data-action="svt_contact"
    >
        <div class="col-12">
            <div class="container">
                <div class="row justify-content-lg-center">
                    <div class="col-12 col-lg-9">
                        <div class=" shadow-sm overflow-hidden bg-green-svt">
                            <div class="row gy-4 gy-xl-3 p-4 p-xl-5">
                                <div class="col-12">
                                    <h2>Contact</h2>
                                    <p>Pour toute information, n'hésitez pas à nous contacter, nous vous répondrons dès que possible.</p>
                                    <p class="success d-none fw-bold text-success">Merci pour votre message !</p>
                                    <p class="error d-none fw-bold text-danger">Certains champs sont manquants.</p>
                                </div>
                                <div class="col-12 form-fields">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="email">
                                    </div>
                                </div>
                                <div class="col-12 form-fields">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" rows="5"></textarea>
                                </div>
                                <div class="col-4 offset-4 form-fields">
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-success" id="submit_contact">Envoyer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_connection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="connection" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="staticBackdropLabel">Merci de vous connecter pour accèder aux documents de correction</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if(!empty($_GET['login']) && $_GET['login'] === 'failed'): ?>
                    <div class="text-danger text-center">Identifiant ou votre mot de passe est incorrect</div>
                <?php endif; ?>
                <div class="container d-flex justify-content-center">
                    <div class="login-form-container">
                        <?= wp_login_form([
                            'redirect' => home_url()
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php get_footer() ?>