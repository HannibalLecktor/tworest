//WEB_SOCKET_DEBUG = true;
WEB_SOCKET_SWF_LOCATION = "/WebSocketMain.swf";
WEB_SOCKET_LOGGER = {
    log: function (msg) {
    }, error: function (msg) {
        alert(msg)
    }
};
$(function () {
    moment.locale(locale);
    $(".fancybox").fancybox();
    vm = new Vue({
        el: '#chat_widget',
        data: {
            socket: null,
            id: chat_id,
            users: users,
            user_id: user_id,
            messages: [],
            currentOffset: messagesOnPage,
            messagesOnPage: messagesOnPage,
            messagesTotalCount: 0,
            smiles: smiles,
            form: {
                text: '',
                image_id: null,
                thumbnailUrl: null
            }
        },
        methods: {
            sendMessage: function (e) {
                if (e) e.preventDefault();
                var message = {
                    type: 'message',
                    message: this.form.text.toString(),
                    image_id: this.form.image_id,
                    chat_id: this.id,
                    private_chat: private_chat,
                    sessid: Cookies.get('_tworest_session')
                };
                this.socket.send(JSON.stringify(message));
                this.form.text = '';
                this.form.image_id = null;
                $(".emoji-wysiwyg-editor").html("")
            },
            sendAuthRequest: function () {
                this.socket.send(JSON.stringify({
                    type: 'auth',
                    chat_id: this.id,
                    private_chat: private_chat,
                    sessid: Cookies.get('_tworest_session')
                }));
            },
            scrollToEnd: function () {
                var messageBox = document.getElementById('message-box');
                messageBox.scrollTop = messageBox.scrollHeight;
            },
            handleScroll: function (e) {
                e.preventDefault();
                if (
                    e.target.scrollTop < 3
                    && this.currentOffset < this.messagesTotalCount
                ) {
                    this.loadMessages(this.currentOffset);
                    this.currentOffset = parseInt(this.currentOffset) + parseInt(this.messagesOnPage);
                    Vue.nextTick(function () {
                        e.target.scrollTop = 50;
                    })
                }
            },
            loadMessages: function (offset) {
                this.socket.send(JSON.stringify({
                    type: 'history',
                    chat_id: this.id,
                    offset: offset,
                    private_chat: private_chat,
                    sessid: Cookies.get('_tworest_session')
                }));
            },
            acceptInviteToPrivateChat: function (user_id) {
                this.socket.send(JSON.stringify({
                    type: 'acceptPrivateChat',
                    user_id: user_id,
                    sessid: Cookies.get('_tworest_session')
                }));
            },
            declineInviteToPrivateChat: function (user_id) {
                this.socket.send(JSON.stringify({
                    type: 'declinePrivateChat',
                    user_id: user_id,
                    sessid: Cookies.get('_tworest_session')
                }));
            }
        },

        created: function () {
            var self = this;

            try {
                this.socket = new WebSocket("ws://" + window.location.hostname + ":8080");

                this.socket.onmessage = function (e) {
                    response = JSON.parse(e.data);
                    //console.log(response.data);
                    switch (response.type) {
                        case 'message':
                            self.messages.push({
                                user_id: response.data.user.id,
                                text: response.data.message.text,
                                image: response.data.message.image,
                                created_at: new Date
                            });
                            if (response.data.alone && response.data.private_chat) {
                                alert('Собеседник отсутствует в чате');
                            }
                            Vue.nextTick(function () {
                                self.scrollToEnd();
                            });
                            break;
                        case 'auth':
                            $.extend(self.users, response.data.users);

                            var messages = response.data.messages.map(function (val) {
                                val.created_at = new Date(val.created_at * 1000);
                                return val;
                            });

                            self.$set('messages', messages.concat(self.messages));
                            if (response.data.hasOwnProperty('messagesTotalCount')) {
                                self.$set('messagesTotalCount', response.data.messagesTotalCount);
                            }
                            Vue.nextTick(function () {
                                self.scrollToEnd();
                            });
                            break;
                        case 'newuser':
                            self.users.$add(response.data.user.id, response.data.user);
                            break;
                        case 'history':
                            var messages = response.data.messages.map(function (val) {
                                val.created_at = new Date(val.created_at * 1000);
                                return val;
                            });
                            self.$set('messages', messages.concat(self.messages));
                            break;
                        case 'privateChat':
                            self.$.invite_form.show(response.data.user);
                            break;
                        case 'inviteToPrivateChatSended':
                            //if (response.data.hasOwnProperty('result')) {
                            if (response.data.hasOwnProperty('result') && response.data.result) {
                                $("#inviteSendedModal").modal();
                            } else {
                                $("#inviteFailedModal").modal();
                            }
                            break;
                        case 'acceptPrivateChat':
                            self.$.invite_form.hide();
                            if (response.data.user.id != self.user_id) {
                                self.$.invite_accepted_modal.show(response.data.user, response.data.chat);
                            } else {
                                self.$.invite_accepted_modal.show(self.users[response.data.owner_id], response.data.chat);
                            }
                            break;
                        case 'declinePrivateChat':
                            $('#inviteDeclinedModal').modal();
                            self.$.invite_form.hide();
                            break;
                    }
                };

                this.socket.onerror = function (e) {
                    alert('Не удалось установить соединение, попробуйте обновить страницу через несколько минут');
                };

                this.socket.onopen = function (e) {
                    self.sendAuthRequest();
                };
            } catch (e) {
                alert("Инициализация чата не удалась \n Возможно ваш браузер устарел \n  Обновите ваш браузер и Adode Flash Player");
            }
        }
    });
});

Vue.filter('replaceSmiles', function (msg) {
    var smileCodes = Object.keys(smiles);
    var strRegex = '(' + smileCodes.join("|").replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$]/g, "\\$&") + ')';
    return msg.replace(new RegExp(strRegex, 'g'), function (m) {
        return '<img src="' + smiles[m] + '" class="emoji">' || m;
    });
});

Vue.component('invite_form', {
    template: '#inviteFormComponentTemplate',
    replace: true,
    data: function () {
        return {
            user: {},
            visible: false
        }
    },
    methods: {
        show: function (user) {
            var self = this;
            self.$set('user', user);
            Vue.nextTick(function () {
                $("#" + self.$el.id).modal();
            });
        },
        hide: function () {
            var self = this;
            self.$set('user', {});
            Vue.nextTick(function () {
                $("#" + self.$el.id).modal('hide');
            });
        },
        acceptInvite: function (user_id) {
            this.$parent.acceptInviteToPrivateChat(user_id);
        },
        declineInvite: function (user_id) {
            this.hide();
            this.$parent.declineInviteToPrivateChat(user_id);
        }
    }
});

Vue.component('invite_accepted_modal', {
    template: '#inviteAcceptedModalComponentTemplate',
    replace: true,
    data: function () {
        return {
            user: {},
            chat: {}
        }
    },
    methods: {
        show: function (user, chat) {
            var self = this;
            $('#inviteSendedModal').modal('hide');
            self.$set('user', user);
            self.$set('chat', chat);
            Vue.nextTick(function () {
                $("#" + self.$el.id).modal();
            });
        },
        hide: function () {
            var self = this;
            self.$set('user', {});
            self.$set('chat', {});
            Vue.nextTick(function () {
                $("#" + self.$el.id).modal('hide');
            });
        }
    }
});

Vue.component('messages', {
    template: '#messages-template',
    replace: true,
    props: ['users', 'messages', 'user_id'],
    data: function () {
        return {};
    },
    methods: {
        sendInviteToPrivateChat: function (user_id) {
            vm.socket.send(JSON.stringify({
                type: 'privateChat',
                user_id: user_id,
                chat_id: chat_id,
                sessid: Cookies.get('_tworest_session')
            }));
        },
        showDetailInfo: function (user) {
            vm.$.invite_detail_info_modal.show(user);
        }
    }
});

Vue.component('invite_detail_info_modal', {
    template: '#inviteDetailInfoModalComponentTemplate',
    replace: true,
    data: function () {
        return {
            user: {}
        }
    },
    methods: {
        show: function (user) {
            var self = this;
            self.$set('user', user);
            Vue.nextTick(function () {
                $("#" + self.$el.id).modal();
            });
        },
        hide: function () {
            var self = this;
            self.$set('user', {});
            Vue.nextTick(function () {
                $("#" + self.$el.id).modal('hide');
            });
        }
    }
});

Vue.directive('emojiarea', {
    bind: function () {
        $.emojiarea.icons = smiles;
        $(this.el).emojiarea({
            button: '#smiles_btn'
        });
    }
});