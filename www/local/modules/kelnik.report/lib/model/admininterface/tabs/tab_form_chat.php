<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
$fields = [
    'NAME', 'NAME_SEZ'
];

$fieldTitle = \Bitrix\Main\Localization\Loc::getMessage('KELNIK_REPORT_FIELD_' . $field);
?>
<section class="b-report-block" id="chat">
    <div>
        <textarea name="chatMessage" id="chatMessage" placeholder="Сообщение" cols="60" rows="8"></textarea>            
    </div>
    <div>
        <a href="" class="adm-btn adm-btn-save" title="" id="chatMessageSend">Отправить</a>
    </div>
</section>

<section class="b-report-block" id="messages">
    <div class="b-report-block__header" style="">
        <h3 class="b-report-block__title">Список сообщений</h3>
    </div>
    <?php if($this->messagesChat) { ?>
        <br><br>       
        <div class="b-report-block__body-grouped">
            <div class="b-input-group">
                <ul class="b-input-group__messages">
                    <?php foreach ($this->messagesChat as $message) { ?>
                        <li class="b-input-group__message">
                            <span 
                            	class="b-input-group__header-id" 
                            	title="Нажмите для ответа на это сообщение" 
                            	onclick="copyChat(this, '#chatMessage')"
                            	data-id="#<?php echo $message['ID']; ?>#"
                            >
                                Ответить
                            </span>
                            <span class="b-input-group__message-date"><?php echo $message['DATE_CREATED']->format("d.m.Y H:i");?></span>
                            <span class="b-input-group__message-author">
                                <?php echo $message['USER_NAME'] . ': '; ?>                                
                            </span>
                            <?php if (($message['PARENT_ID'] || $message['FIELD_ID']) && isset($message['PARENT_MESSAGE'])) {?>
                                <div class="b-input-group__message-parent">
                                    <?php echo $message['PARENT_MESSAGE'];?>
                                </div>
                            <?php } ?>
                            <div class="b-input-group__message-text">
                                <?php echo $message['TEXT'];?>
                            </div>

                            <?php if (isset($message['CHILDREN'])) { ?>
                            	<ul class="b-input-group__messages b-input-group__messages-children">
                            		<?php foreach ($message['CHILDREN'] as $messageChild) { ?>
                            			<li class="b-input-group__message">				                            
				                            <span class="b-input-group__message-date"><?php echo $messageChild['DATE_CREATED']->format("d.m.Y H:i");?></span>
				                            <span class="b-input-group__message-author">
				                                <?php echo $messageChild['USER_NAME'] . ': '; ?>                                
				                            </span>
				                            <?php if (($messageChild['PARENT_ID'] || $messageChild['FIELD_ID']) && isset($messageChild['PARENT_MESSAGE'])) {?>
				                                <div class="b-input-group__message-parent">
				                                    <?php echo $messageChild['PARENT_MESSAGE'];?>
				                                </div>
				                            <?php } ?>
				                            <div class="b-input-group__message-text">
				                                <?php echo $messageChild['TEXT'];?>
				                            </div>				                            
				                        </li>
                            		<?php } ?>
                            	</ul>
                            <?php } ?>
                        </li>

                    <? } ?>
                </ul>
            </div>
        </div>
    <?php } ?>
</section>

<script>
    document.querySelector('#chatMessageSend').onclick = function(){
        let chatMessage = document.querySelector('#chatMessage').value;
        let params = [];
        params.msg = chatMessage;
        sendRequest('chatMessage='+chatMessage, location.href, 'POST', updatePage, params);
        return false;
    }

    sendRequest = function(data, url, method, callback, params = []){
        var xhr = new XMLHttpRequest();             
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(data);
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                callback(this, params);
            }
        }
    }

    updatePage = function(r, params){
        let block = document.createElement('div');
        block.classList.add('adm-info-message');
        block.innerHTML = 'Обновите страницу для вывода сообщений';
        document.querySelector('#messages .b-report-block__header').append(block);
    }

    copyChat = function(el, targetIns){
        let _this = this;
        let text = el.dataset.id;
        let targetText = document.querySelector(targetIns).value;
        let newTargetText = text + ' ' + targetText;

        document.querySelector(targetIns).value = newTargetText;
    }
</script>
