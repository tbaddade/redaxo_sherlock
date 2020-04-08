<section class="search-result-section">
    <?php if ($this->getVar('title', '') != ''): ?>
    <header class="search-result-header">
        <h2 class="search-result-heading"><?= $this->getVar('title') ?></h2>
    </header>
    <?php endif ?>
    <div class="search-result-body">
        <ul class="search-result-list">
            <?php foreach ($this->getVar('results', []) as $result): ?>
            <li>
                <?php if ($result->getRaw()): ?>
                    <?= $result->getRaw() ?>
                <?php else: ?>
                    <strong><?= $result->getTitle() ?></strong> <?= $result->getText() ?><?= Yakme\Html::getUrlLink($result->getUrl(), ['class' => 'search-result-link']) ?>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
