(function () {
    angular.module('answer').controller('CoreController', CoreController);

    CoreController.$inject = [
        '$scope',
        'clankService'
    ];

    function CoreController($scope, clank) {
        var self = this;
        this.messages = [];
        this.message = '';

        this.subscribe = subscribe;
        this.disconnect = disconnect;
        this.addMessage = addMessage;

        function subscribe() {
            clank.subscribe('app/channel', onNewMessage);
        }

        function disconnect() {
            clank.unsubscribe('app/channel');
            self.messages = [];
        }

        function addMessage(message) {
            if (parseCommand(message)) {
                clank.publish('app/channel', message);
            } else {
                self.message = '';
            }
        }

        function onNewMessage(msg) {
            $scope.$apply(function () {
                self.messages.push(msg);
                self.message = '';
            });
        }

        function parseCommand(cmd) {
            switch (cmd) {
                case '/time':
                    var date = new Date();
                    self.messages.push(date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds());
                    return false;
                default: return true;
            }
        }
    }
})();