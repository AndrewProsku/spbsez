<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php if(empty($arResult['QUESTIONS'])): return; endif; ?>

<div class="faq-list">
	<?php foreach ($arResult['QUESTIONS_TYPES'] as $k => $type): ?>
    	<div class="faq-group">	    		
    		<div class="faq-group__title">
                <div class="faq-group-open faq-group-open-trigger"></div>
                <h3 class="faq-group-open-trigger"><?=$type['NAME']?></h3>
            </div>
    		<div class="faq-group__items faq-group__items_close">
    			<?php foreach ($arResult['QUESTIONS_BY_TYPES'][$k] as $n => $question): ?>
	    			<div class="faq-group__item">
                        <!--<div class="faq-group__item-open"></div>-->
	    				<div class="faq-group__item-title"><span><?=$n+1?></span><?=$question['NAME']?></div>
	    				<div class="faq-group__item-content faq-group__item-content_close">
                            <p class="_marked"></p>
                            <?=$question['TEXT']?>                                
                        </div>
	    			</div>
    			<?php endforeach;?>
    		</div>
    	</div>
	<?php endforeach;?>
</div>