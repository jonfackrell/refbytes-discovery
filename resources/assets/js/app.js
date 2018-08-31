
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.config.ignoredElements = ['highlight', 'searchlink', 'relatesto'];

const app = new Vue({
    el: '#app',
    mounted: function(){

        var behaviourSlider = document.getElementById('date-range-slider');

        noUiSlider.create(behaviourSlider, {
            start: [ 20, 40 ],
            animate: true,
            tooltips: [true, true],
            step: 1,
            behaviour: 'tap-drag',
            connect: true,
            range: {
                'min':  [0],
                'max':  [100]
            }
        });

    }
});
