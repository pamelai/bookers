Vue.component('perfil-usuario', {
    template: `
    <section class="row justify-content-center mt-4 ">
        <div class="col-md-4 h-100">
            <div class="card">
                <div class="card-body">
                    <img :src="store.usuario.imagen" width="300" height="300" class="img-fluid mx-auto d-block rounded shadow-sm" :alt="store.usuario.usuario">
                    <h3 class="font-weight-bold mb-3 mt-5">@{{store.usuario.usuario}}</h3>
                    <h4 class="mt-4">{{store.usuario.nombre}} {{store.usuario.apellido}}</h4>
                    <h5 class="mt-4">Intereses</h5>
                    <ul class="pl-4">
                        <li v-for="interes in store.intereses">{{interes.interes}}</li>
                    </ul>
                    <div class="card-body">
                        <router-link class="btn btn-light mt-3 border-secondary btn-block" to="/perfil/editar">Editar perfil <i class="far fa-edit"></i></router-link>
                    </div>
                </div>
            </div>
            <div class="card border-0 mt-4" v-if="eventos.length > 0">
                <button class="btn btn-outline-info" data-toggle="collapse" data-target="#eventoCollapse">Eventos a los que asistiré  <i class="fa-calendar-check fal"></i></button>
                <div class="card-body border collapse" id="eventoCollapse">
                    <div v-for="evento in eventos">
                        <evento :ev="evento" :mias="true"></evento>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <publicar></publicar>
            <listado-novedades :mias="true"></listado-novedades>
        </div>
    </section>`,

    data: function () {
        return {
            store,
            auth,
            eventos: []
        }
    },

    mounted: async function(){
        let urlInt = 'api/mvc/public/usuario/intereses/' + auth.usuario.id;
        const request = await fetch(urlInt);
        const rta = await request.json();
        this.store.intereses = rta.data;

        fetch("api/mvc/public/eventos/perfil/" + auth.usuario.id)
            .then(rta => rta.json())
            .then(rta => {

                this.eventos = rta.data;
            })
    }
})


