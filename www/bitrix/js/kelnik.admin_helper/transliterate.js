function Translitirate($from, $to)
{
    var self = this;
    self.interval = 250;
    self.unlinkImage = '/bitrix/themes/.default/icons/iblock/unlink.gif';
    self.linkImage = '/bitrix/themes/.default/icons/iblock/link.gif';
    self.$from = $from;
    self.$fromImage = $(document.createElement('img'));
    self.$to = $to;
    self.$toImage  = $(document.createElement('img'));
    self.linked = false;
    self.oldValue = '';


    function createImages()
    {
        self.$fromImage
            .addClass('linked')
            .attr('src', self.linked ? self.linkImage : self.unlinkImage)
            .click(function () {
                self.setLinked();
            });
        self.$toImage
            .addClass('linked')
            .attr('src', self.linked ? self.linkImage : self.unlinkImage)
            .click(function () {
                self.setLinked();
            });
        self.$from.parent().append(self.$fromImage);
        self.$to.parent().append(self.$toImage);
    }
    createImages();
    self.translit();
}

Translitirate.prototype.setLinked = function () {

    var self = this;
    self.linked = !self.linked;
    self.$fromImage.attr('src', self.linked ? self.linkImage : self.unlinkImage);
    self.$toImage.attr('src', self.linked ? self.linkImage : self.unlinkImage);
    self.translit();
    /*var linked_state = document.getElementById('linked_state');
    if(linked_state)    {
        if(linked)            {
            linked_state.value='Y'; 
        }        else            {
            linked_state.value='N';
        }
        s_lodgiei
    }*/
};

Translitirate.prototype.translit = function () {
    var self = this;
    if (self.linked) {
        if (self.oldValue != self.$from.val()) {
            BX.translit(self.$from.val(), {
                'max_len'              : 100,
                'change_case'          : 'L',
                'replace_space'        : '-',
                'replace_other'        : '-',
                'delete_repeat_replace': true,
                'use_google'           : false,
                'callback'             : function (result) {
                    self.$to.val(result);
                    setTimeout(function () {
                        self.translit();
                    }, self.interval);
                }
            });
            self.oldValue = self.$from.val();
        } else {
            setTimeout(function () {
                self.translit();
            }, self.interval);
        }
    } else {
        setTimeout(function () {
            self.translit();
        }, self.interval);
    }
};
