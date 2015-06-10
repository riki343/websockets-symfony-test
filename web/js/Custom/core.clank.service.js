(function () {
    angular.module('answer').factory('clankService', clankService);

    clankService.$inject = [];

    function clankService() {
        var self = this;
        this.clank = null;
        this.session = null;
        this.channels = [];
        var factory = {
            'clank': self.clank,
            'subscribe': subscribe,
            'publish': publish,
            'unsubscribe': unsubscribe
        };

        connect();
        return factory;

        function connect() {
            if (angular.isDefined(Clank)) {
                self.clank = Clank.connect('ws://localhost:8080');
                self.clank.on("socket/connect", function (session) {
                    self.session = session;
                });
                self.clank.on("socket/disconnect", function (error) {
                    console.log("Disconnected for " + error.reason + " with code " + error.code);
                    self.clank = null;
                    self.session = null;
                });
            } else {
                console.log('This module requires Clank.js');
            }
        }

        function subscribe(channel, onNewMessageHandler) {
            if (self.session && self.channels.indexOf(channel) == -1) {
                self.channels.push(channel);
                self.session.subscribe(channel, function(uri, payload) {
                    if (angular.isDefined(payload.msg)) {
                        onNewMessageHandler(payload.msg);
                    } else if (angular.isDefined(payload.event)) {
                        onNewMessageHandler(payload.event.msg);
                    }
                });
            }
        }

        function unsubscribe(channel) {
            var index = self.channels.indexOf(channel);
            if (self.session && index != -1) {
                self.session.unsubscribe(channel);
                self.channels.splice(index, 1);
            }
        }

        function publish(channel, msg) {
            if (self.session && self.channels.indexOf(channel) != -1) {
                self.session.publish(channel, { 'msg': msg });
            }
        }
    }
})();