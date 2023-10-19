define([
    'uiComponent',
    'ko'
], function (Component, ko) {
    return Component.extend({
        selectedColor : ko.observable(""),
        initialize: function (config) {
            this.getSelectedColor()
            let options = this.getColorOptions(config)
            this.colorOptions = ko.observableArray(options)
            this._super();
        },
        getColorOptions: function (config) {
            let entries = Object.entries(config.colorSwitchComponent);
            entries = entries.map(function (item) {
                return item[1]
            })
            entries.push({color_value: '#ffffff', color_label: 'default'})
            return entries;

        },
        updateColor: function (object, event) {
            console.log(event.target.value)
            document.body.style.backgroundColor = event.target.value
            console.log(document.body.style.backgroundColor)
            localStorage.setItem('background-color', event.target.value)
            this.selectedColor(bgColor)
        },
        getSelectedColor: function () {
            let bgColor = localStorage.getItem('background-color')
            document.body.style.backgroundColor = bgColor;
            this.selectedColor(bgColor)
        }
    });
});
