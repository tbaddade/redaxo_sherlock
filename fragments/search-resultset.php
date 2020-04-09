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
                    <?php if($result->getTitle()): ?>
                        <strong><?= $result->getTitle() ?></strong>
                    <?php endif; ?>

                    <?= $result->getText() ?>

                    <?php if($result->getUrl()): ?>
                        <a class="search-result-link" href="<?= $result->getUrl() ?>"><?= $result->getUrl() ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
