<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="chat-messages">
    <?php 
    if (!$arResult['MESSAGES']) {
        echo "Сообщений нет";
    } else {
        foreach ($arResult['MESSAGES'] as $message) {
            ?>
            <div class="chat-message">
                <div class="chat-message__header">
                    <span 
                        class="chat-message__header-id" 
                        title="Нажмите для ответа на это сообщение" 
                        onclick="sezApp.copyChat(this, '#chat_answer')"
                        data-id="#<?php echo $message["ID"]?>#"
                    >
                        Ответить
                    </span>
                    <span class="chat-message__header-author">
                        <?php echo $message['USER_NAME'];?>
                    </span>
                    <span class="chat-message__header-date"><?php echo $message['DATE_CREATED']->format("d.m.Y H:i");?></span>
                </div>                
                <?php if (($message['PARENT_ID'] || $message['FIELD_ID']) && isset($message['PARENT_MESSAGE'])) { ?>
                    <div class="chat-message__theme">
                        <?php echo $message['PARENT_MESSAGE']?>
                    </div>
                <?php } ?>
                <div class="chat-message__text">
                    <?php echo $message["TEXT"]?>
                </div>

                <?php if (isset($message['CHILDREN'])) { ?>
                    <?php foreach ($message['CHILDREN'] as $messageChild) { ?>
                        <div class="chat-message chat-message-children">
                            <div class="chat-message__header">                                
                                <span class="chat-message__header-author">
                                    <?php echo $messageChild['USER_NAME'];?>
                                </span>
                                <span class="chat-message__header-date"><?php echo $messageChild['DATE_CREATED']->format("d.m.Y H:i");?></span>
                            </div>                
                            <?php if (($messageChild['PARENT_ID'] || $messageChild['FIELD_ID']) && isset($messageChild['PARENT_MESSAGE'])) { ?>
                                <div class="chat-message__theme">
                                    <?php echo $messageChild['PARENT_MESSAGE']?>
                                </div>
                            <?php } ?>
                            <div class="chat-message__text">
                                <?php echo $messageChild["TEXT"]?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <?php
        }
    }
    ?>
</div>

<div class="chat-response">
    <textarea id="chat_answer"></textarea>
    <span class="b-reports-button" onclick="sezApp.answerChat(<?= $arResult['REPORT_ID']?>)">Отправить</span>
</div>

