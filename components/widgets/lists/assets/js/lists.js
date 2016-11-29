$(function () {
    Vue.component('list', {
        template: '#list-template',
        replace: true,
        data: function () {
            return {};
        }
    });

    var vm = new Vue({
        el: '#list_widget',
        data:  {
            list: list
        },
        methods: {

        }
    });
});