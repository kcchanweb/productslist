const PrevNext = {
    props: ['label'],
    template: '<div>{{ $route.params.label }}</div>'
}

const routes = [
    { path: '/products/page/:page', component: PrevNext, props: true }
];

const router = new VueRouter({
    routes // short for `routes: routes`
});

const app = new Vue({
    el: '#app',
    data () {
        return {
            products: null
        }
    },
    router,
    mounted () {
        axios.get(`https://local.loopreturns.com/api/product-metrics`)
             .then(response => {
                 this.products = response.data.data
             });
    }
}).$mount('#app');