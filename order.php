<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = require_login();
$designs = db()->query('SELECT * FROM designs WHERE is_active = 1 ORDER BY sort_order ASC')->fetchAll();
$designMap = [];
foreach ($designs as $design) {
    $designMap[(int) $design['id']] = $design;
}

$selectedDesignId = (int) ($_GET['design'] ?? $_POST['design_id'] ?? ($designs[0]['id'] ?? 0));
$errors = [];

$form = [
    'design_id' => $selectedDesignId,
    'amount' => (string) ($_POST['amount'] ?? '1500'),
    'recipient_name' => trim((string) ($_POST['recipient_name'] ?? '')),
    'recipient_email' => trim((string) ($_POST['recipient_email'] ?? '')),
    'occasion' => (string) ($_POST['occasion'] ?? 'birthday'),
    'message' => trim((string) ($_POST['message'] ?? '')),
    'accent_color' => (string) ($_POST['accent_color'] ?? 'ps_blue'),
    'delivery_method' => (string) ($_POST['delivery_method'] ?? 'email'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Сессия устарела. Обновите страницу и попробуйте снова.';
    }

    $designId = (int) $form['design_id'];
    $amount = (float) $form['amount'];
    $allowedAmounts = amount_options();
    $allowedOccasions = ['birthday', 'new_release', 'tournament', 'friend_gift', 'thankyou', 'justbecause'];
    $allowedAccents = ['ps_blue', 'xbox_green', 'pc_cyan', 'neon', 'carbon'];
    $allowedDelivery = ['email', 'print'];

    if (!isset($designMap[$designId])) {
        $errors[] = 'Выберите дизайн сертификата.';
    }
    if (!in_array((int) $amount, $allowedAmounts, true)) {
        $errors[] = 'Выберите сумму из списка.';
    }
    if ($form['recipient_name'] === '' || mb_strlen($form['recipient_name']) < 2) {
        $errors[] = 'Укажите имя получателя.';
    }
    if ($form['recipient_email'] !== '' && !filter_var($form['recipient_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email получателя указан неверно.';
    }
    if (!in_array($form['occasion'], $allowedOccasions, true)) {
        $errors[] = 'Выберите повод.';
    }
    if (!in_array($form['accent_color'], $allowedAccents, true)) {
        $errors[] = 'Выберите цветовой акцент.';
    }
    if (!in_array($form['delivery_method'], $allowedDelivery, true)) {
        $errors[] = 'Выберите способ получения.';
    }
    if (mb_strlen($form['message']) > 500) {
        $errors[] = 'Сообщение слишком длинное (максимум 500 символов).';
    }

    if (!$errors) {
        $design = $designMap[$designId];
        $total = $amount + (float) $design['price_base'];
        $code = generate_certificate_code();

        $stmt = db()->prepare(
            'INSERT INTO certificates
            (user_id, design_id, amount, recipient_name, recipient_email, occasion, message, accent_color, delivery_method, status, certificate_code)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            (int) $user['id'],
            $designId,
            $total,
            $form['recipient_name'],
            $form['recipient_email'] !== '' ? $form['recipient_email'] : null,
            $form['occasion'],
            $form['message'] !== '' ? $form['message'] : null,
            $form['accent_color'],
            $form['delivery_method'],
            'paid',
            $code,
        ]);

        flash('success', 'Сертификат оформлен. Код: ' . $code);
        redirect('certificates.php');
    }
}

$currentDesign = $designMap[$form['design_id']] ?? $designs[0] ?? null;

if ($currentDesign) {
    remember_recent_design((int) $currentDesign['id']);
}

$pageTitle = 'Оформить пополнение — PlayGift';
$activeNav = 'order';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <h1>Пополнение кошелька</h1>
        <p>Сертификат только на баланс платформы. Выберите кошелёк, сумму, повод и получателя — справа живой предпросмотр.</p>
    </div>
</section>

<section class="container order-layout stagger-layout">
    <div class="panel">
        <?php if ($errors): ?>
            <div class="flash flash-error" style="margin-bottom: 1rem;">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="form-grid stagger-form" method="post" action="order.php" data-order-form>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

            <div class="form-row">
                <label>Кошелёк / оформление</label>
                <div class="choice-grid">
                    <?php foreach ($designs as $design): ?>
                        <div class="design-choice">
                            <input
                                type="radio"
                                name="design_id"
                                id="design_<?= (int) $design['id'] ?>"
                                value="<?= (int) $design['id'] ?>"
                                <?= (int) $form['design_id'] === (int) $design['id'] ? 'checked' : '' ?>
                            >
                            <label for="design_<?= (int) $design['id'] ?>">
                                <div class="thumb" data-design-path="<?= e($design['image_path']) ?>">
                                    <?php render_media($design['image_path'], $design['title'], 'design-image', 'Картинка'); ?>
                                </div>
                                <div class="caption">
                                    <?php render_platform_icon($design['category'], 'platform-icon platform-icon-xs'); ?>
                                    <span><?= e($design['title']) ?></span>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-row">
                <label>Сумма пополнения</label>
                <div class="choice-grid">
                    <?php foreach (amount_options() as $amount): ?>
                        <div class="choice">
                            <input type="radio" name="amount" id="amount_<?= $amount ?>" value="<?= $amount ?>" <?= (int) $form['amount'] === $amount ? 'checked' : '' ?>>
                            <label for="amount_<?= $amount ?>"><?= format_money((float) $amount) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-row two">
                <div class="form-row">
                    <label for="recipient_name">Имя получателя</label>
                    <input id="recipient_name" name="recipient_name" type="text" required value="<?= e($form['recipient_name']) ?>" placeholder="Анна">
                </div>
                <div class="form-row">
                    <label for="recipient_email">Email получателя</label>
                    <input id="recipient_email" name="recipient_email" type="email" value="<?= e($form['recipient_email']) ?>" placeholder="anna@example.com">
                </div>
            </div>

            <div class="form-row two">
                <div class="form-row">
                    <label for="occasion">Повод</label>
                    <select id="occasion" name="occasion">
                        <?php foreach (['birthday', 'new_release', 'tournament', 'friend_gift', 'thankyou', 'justbecause'] as $key): ?>
                            <option value="<?= $key ?>" <?= $form['occasion'] === $key ? 'selected' : '' ?>><?= e(occasion_label($key)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-row">
                    <label for="accent_color">Стиль платформы</label>
                    <select id="accent_color" name="accent_color">
                        <?php foreach (['ps_blue', 'xbox_green', 'pc_cyan', 'neon', 'carbon'] as $key): ?>
                            <option value="<?= $key ?>" <?= $form['accent_color'] === $key ? 'selected' : '' ?>><?= e(accent_label($key)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <label>Способ получения</label>
                <div class="choice-grid">
                    <div class="choice">
                        <input type="radio" name="delivery_method" id="delivery_email" value="email" <?= $form['delivery_method'] === 'email' ? 'checked' : '' ?>>
                        <label for="delivery_email">На email</label>
                    </div>
                    <div class="choice">
                        <input type="radio" name="delivery_method" id="delivery_print" value="print" <?= $form['delivery_method'] === 'print' ? 'checked' : '' ?>>
                        <label for="delivery_print">Для печати</label>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <label for="message">Личное сообщение</label>
                <textarea id="message" name="message" maxlength="500" placeholder="Пусть баланс пригодится на новую игру!"><?= e($form['message']) ?></textarea>
            </div>

            <button class="btn btn-primary" type="submit">Купить сертификат на кошелёк</button>
        </form>
    </div>

    <aside class="order-preview">
        <h2>Предпросмотр</h2>
        <div class="preview-frame" data-preview-frame>
            <?php render_media($currentDesign['image_path'] ?? null, 'Предпросмотр дизайна', 'design-image', 'Место для картинки'); ?>
        </div>
        <ul class="preview-list">
            <li><span>Дизайн</span><strong data-preview-design><?= e($currentDesign['title'] ?? '—') ?></strong></li>
            <li><span>Сумма</span><strong data-preview-amount><?= format_money((float) $form['amount']) ?></strong></li>
            <li><span>Повод</span><strong data-preview-occasion><?= e(occasion_label($form['occasion'])) ?></strong></li>
            <li><span>Получатель</span><strong data-preview-recipient><?= e($form['recipient_name'] !== '' ? $form['recipient_name'] : '—') ?></strong></li>
            <li><span>Стиль</span><strong data-preview-accent><?= e(accent_label($form['accent_color'])) ?></strong></li>
            <li><span>Доставка</span><strong data-preview-delivery><?= $form['delivery_method'] === 'print' ? 'Для печати' : 'На email' ?></strong></li>
        </ul>
    </aside>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
