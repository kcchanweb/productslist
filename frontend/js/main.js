new Vue({
    el: '#app',
    data () {
        return {
            info: null
        }
    },
    mounted () {
        axios
            .get('')
            .then(response => (this.info = response))
    }
})