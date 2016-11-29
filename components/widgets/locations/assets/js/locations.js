$(function () {
    var vm = new Vue({
        el: '#locations_widget',
        data: {
            locations: locations,
            selected_country: "",
            selected_city: "",
            selected_district: "",
            search_district: ""
        },
        methods: {
            //setSelectedCity: function () {
            //    console.log('set selected city');
            //    this.$set(
            //        'selected_city',
            //        Object.keys(this.locations[this.selected_country].cities)[0]
            //    );
            //    this.setSelectedDistrict();
            //},
            //
            //setSelectedDistrict: function () {
            //    console.log('set selected_district');
            //    this.$set(
            //        'selected_district',
            //        Object.keys(this.locations[this.selected_country].cities[this.selected_city].districts)[0]
            //    );
            //},

            joinChat: function (id, e) {
                e.preventDefault();
                //console.log(id);
                location.replace('/chats/' + id);
            }
        },

        compiled: function () {
            $(document).on("click", '.select2-container--disabled span[aria-labelledby="select2-selected_city-container"]', function () {
                $('span[aria-labelledby="select2-selected_country-container"]').css({'box-shadow': '1px -1px 3px 2px red'});
                setTimeout(function () {
                    $('span[aria-labelledby="select2-selected_country-container"]').css({'box-shadow': 'none'});
                }, 2000)
            });

            $(document).on("click", '.select2-container--disabled span[aria-labelledby="select2-selected_district-container"]', function () {
                $('span[aria-labelledby="select2-selected_city-container"]').css({'box-shadow': '1px -1px 3px 2px red'});
                setTimeout(function () {
                    $('span[aria-labelledby="select2-selected_city-container"]').css({'box-shadow': 'none'});
                }, 2000)
            });
        }
    })
});

Vue.directive('select', {
    twoWay: true,

    bind: function () {
        var optionsData;
        var optionsExpression = this.el.getAttribute('options');
        if (optionsExpression) {
            optionsData = this.vm.$eval(optionsExpression);
        }

        var self = this;
        $(this.el)
            .select2({
                data: optionsData
            }).on('change', function () {
                self.set(this.value);
            })
    },

    update: function (value) {
        $(this.el).val(value).trigger('change');
        //$(this.el).select2('destroy');
    },

    unbind: function () {
        $(this.el).off().select2('destroy')
    }
});