$(function () {
    Vue.component('languages', {
        template: '#languages-template',
        replace: true,
        data: function () {
            return {};
        }
    });

    var vm = new Vue({
        el: '#languages_widget',
        data:  {
            languages: languages
        },
        methods: {

        }
    });
});