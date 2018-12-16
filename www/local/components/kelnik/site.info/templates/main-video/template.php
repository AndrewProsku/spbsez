<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
if ($arResult['NO_VIDEO']) {
    return;
}
?>
<div class="l-home__main-screen-video-wrapper">
    <video class="l-home__main-screen-video" width="100%" height="auto" preload="auto" autoplay="autoplay"
           loop="loop">
        <source src="<?= $arResult['MAIN_VIDEO_MP4_PATH']; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <source src="<?= $arResult['MAIN_VIDEO_OGV_PATH']; ?>" type='video/ogg; codecs="theora, vorbis"'>
        <source src="<?= $arResult['MAIN_VIDEO_WEBM_PATH']; ?>" type='video/webm; codecs="vp8, vorbis"'>
    </video>
</div>