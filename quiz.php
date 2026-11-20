<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$result = null;
$answers = [
    'vibe' => (string) ($_POST['vibe'] ?? ''),
    'habit' => (string) ($_POST['habit'] ?? ''),
    'budget' => (string) ($_POST['budget'] ?? ''),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('error', 'Сессия устарела. Обновите страницу.');
        redirect('quiz.php');
    }

    $score = ['PlayStation' => 0, 'Xbox' => 0, 'PC' => 0];
    $map = [
        'vibe' => ['exclusive' => 'PlayStation', 'friends' => 'Xbox', 'deals' => 'PC'],
        'habit' => ['sofa' => 'PlayStation', 'party' => 'Xbox', 'desk' => 'PC'],
        'budget' => ['big' => 'PlayStation', 'mid' => 'Xbox', 'flex' => 'PC'],
    ];

    foreach ($map as $key => $options) {
        $choice = $answers[$key];
        if (isset($options[$choice])) {
            $score[$options[$choice]] += 2;
        }
    }

    arsort($score);
    $winner = array_key_first($score) ?: 'PC';
    $amounts = ['PlayStation' => 2500, 'Xbox' => 1500, 'PC' => 1000];
    $blurbs = [
        'PlayStation' => 'По ответам ближе PlayStation — пополнение PSN.',
        'Xbox' => 'По ответам ближе Xbox — пополнение баланса Microsoft.',
        'PC' => 'По ответам ближе ПК — пополнение Steam Wallet.',
    ];

    $designStmt = db()->prepare('SELECT * FROM designs WHERE is_active = 1 AND category = ? ORDER BY sort_order ASC LIMIT 1');
    $designStmt->execute([$winner]);
    $design = $designStmt->fetch() ?: null;

    $result = [
        'platform' => $winner,
        'amount' => $amounts[$winner],
        'blurb' => $blurbs[$winner],
        'design' => $design,
        'score' => $score,
    ];
}

$pageTitle = 'Подбор кошелька — PlayGift';
$activeNav = 'quiz';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <span class="eyebrow">Квиз</span>
        <h1>Какой кошелёк подарить?</h1>
        <p>Три вопроса. Сайт предложит платформу и сумму.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container quiz-layout">
        <form class="panel quiz-card stagger-form" method="post" action="quiz.php" data-quiz-form>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

            <fieldset class="quiz-step">
                <legend>1. Что ему ближе?</legend>
                <div class="choice-grid">
                    <label class="choice-tile">
                        <input type="radio" name="vibe" value="exclusive" <?= $answers['vibe'] === 'exclusive' ? 'checked' : '' ?> required>
                        <span>Эксклюзивы и трофеи</span>
                    </label>
                    <label class="choice-tile">
                        <input type="radio" name="vibe" value="friends" <?= $answers['vibe'] === 'friends' ? 'checked' : '' ?>>
                        <span>Игры с друзьями</span>
                    </label>
                    <label class="choice-tile">
                        <input type="radio" name="vibe" value="deals" <?= $answers['vibe'] === 'deals' ? 'checked' : '' ?>>
                        <span>Распродажи и вишлист</span>
                    </label>
                </div>
            </fieldset>

            <fieldset class="quiz-step">
                <legend>2. Где обычно играет?</legend>
                <div class="choice-grid">
                    <label class="choice-tile">
                        <input type="radio" name="habit" value="sofa" <?= $answers['habit'] === 'sofa' ? 'checked' : '' ?> required>
                        <span>Диван + ТВ</span>
                    </label>
                    <label class="choice-tile">
                        <input type="radio" name="habit" value="party" <?= $answers['habit'] === 'party' ? 'checked' : '' ?>>
                        <span>Общая тусовка / party</span>
                    </label>
                    <label class="choice-tile">
                        <input type="radio" name="habit" value="desk" <?= $answers['habit'] === 'desk' ? 'checked' : '' ?>>
                        <span>За ПК</span>
                    </label>
                </div>
            </fieldset>

            <fieldset class="quiz-step">
                <legend>3. Какой бюджет подарка?</legend>
                <div class="choice-grid">
                    <label class="choice-tile">
                        <input type="radio" name="budget" value="big" <?= $answers['budget'] === 'big' ? 'checked' : '' ?> required>
                        <span>Крупный номинал</span>
                    </label>
                    <label class="choice-tile">
                        <input type="radio" name="budget" value="mid" <?= $answers['budget'] === 'mid' ? 'checked' : '' ?>>
                        <span>Средний номинал</span>
                    </label>
                    <label class="choice-tile">
                        <input type="radio" name="budget" value="flex" <?= $answers['budget'] === 'flex' ? 'checked' : '' ?>>
                        <span>Небольшой номинал</span>
                    </label>
                </div>
            </fieldset>

            <button class="btn btn-primary" type="submit">Показать результат</button>
        </form>

        <?php if ($result): ?>
            <aside class="panel quiz-result reveal is-visible" data-confetti-on-show>
                <?php render_platform_icon($result['platform'], 'platform-icon platform-icon-lg'); ?>
                <?php render_platform_chip($result['platform']); ?>
                <h2><?= e(platform_label($result['platform'])) ?></h2>
                <p class="muted"><?= e($result['blurb']) ?></p>
                <div class="stat-pill">Рекомендуемый номинал · <?= e(format_money((float) $result['amount'])) ?></div>
                <?php if ($result['design']): ?>
                    <div class="quiz-design">
                        <?php render_media($result['design']['image_path'], $result['design']['title'], 'design-image', 'Оформление'); ?>
                        <strong><?= e($result['design']['title']) ?></strong>
                    </div>
                    <div class="cta-row">
                        <a class="btn btn-primary" href="order.php?design=<?= (int) $result['design']['id'] ?>&amount=<?= (int) $result['amount'] ?>">Оформить этот вариант</a>
                        <a class="btn btn-secondary" href="catalog.php?category=<?= urlencode($result['platform']) ?>">Смотреть каталог</a>
                    </div>
                <?php endif; ?>
            </aside>
        <?php else: ?>
            <aside class="panel quiz-side">
                <h2>Как это работает</h2>
                <p class="muted">Если неясно, PS, Xbox или Steam нужнее — ответьте на вопросы. Ключи не продаём, только пополнение кошелька.</p>
                <ul class="value-list">
                    <li><strong>3 вопроса.</strong> Регистрация не нужна.</li>
                    <li><strong>Результат.</strong> Платформа, сумма и дизайн.</li>
                    <li><strong>Дальше.</strong> Можно сразу открыть оформление.</li>
                </ul>
            </aside>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
