Vue.component('listado-eventos', {
    template: `
        <div class="col-10">
            <h2 class="text-center mt-5">Eventos</h2>
            <div class="card border-0 mt-4">
                <div class="card-body">
                    <div v-for="evento in EventosOrdenados"
                         :key="evento.id">
                         
                        <evento :ev="evento"></evento>
                    </div>
                </div>
            </div>    
        </div>
    `,


    data: function () {
        return {
            store,
            auth
        }
    },

    computed: {
        EventosOrdenados: function () {
            return this.store.eventos.reverse();
        }
    },

    mounted() {
        let url = 'api/mvc/public/eventos/' + auth.usuario.id;

        fetch(url)
            .then(rta => rta.json())
            .then(rta => {

                this.store.eventos = rta.data;
            })
    },

    props: {
        mios: {
            type: Boolean
        }
    }
});