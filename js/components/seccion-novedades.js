Vue.component('seccion-novedades', {
    template: `
<section class="row justify-content-center mt-4 ">
        
        <div class="col-md-8 justify-content-center mt-4">
        <publicar></publicar>      
        <listado-novedades></listado-novedades>
        </div>
    <div class="col-md-4 card border-left mt-4" id="prox"> 
            <h2 class="text-center mt-4">Próximos eventos</h2>                           
            <div class="mt-2" v-for="evento in eventos" v-if="eventos.length > 0">
                <evento :ev="evento" :mias="true"></evento>
            </div>
        <div class="card-body">
                        <router-link class="btn btn-outline-primary mt-2 btn-block" to="/eventos">Ver todos</router-link>
                    </div>
        </div>
    </section>
`,
    data: function () {
        return {
            store,
            auth,
            eventos: []
        }
    },

    mounted: function(){

        fetch("api/mvc/public/eventos/proximos")
            .then(rta => rta.json())
            .then(rta => {
                this.eventos = rta.data;

            })

    }
});