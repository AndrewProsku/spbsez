<?php if (CSite::InDir('/visual/')): ?>
    <?php include('inc_footer_visual.php'); ?>
<?php else: ?>
    <?php include('inc_footer.php'); ?>
<?php endif; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/img/sprite/sprite.svg'; ?>
<? $APPLICATION->ShowHeadStrings(); ?>
<? $APPLICATION->ShowHeadScripts(); ?>
</body>
</html>
