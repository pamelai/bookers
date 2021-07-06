Vue.component('listado-novedades', {
    template: `
    <div class="col-12">
        <h2 class="text-center mt-5">Últimas noticias</h2>
        <div class="card border-0 mt-4">
            <div class="card-body">
                <form class="form-inline justify-content-center" @submit.prevent="search()" v-if="!mias">
                    <input class="form-control mr-sm-2" type="search" placeholder="Buscar publicacion" v-model="buscar">
                    <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Buscar  <i class="far fa-search"></i></button>
                </form>
                
                <section v-if="buscador">
                
                    <div v-for="novedad in buscado"
                         :key="novedad.id">
                         
                        <novedad :nov="novedad"></novedad>
                    </div>
                
                </section>
                
                <section v-else>
                    <div v-if="!mias" v-for="novedad in novedadesOrdenadas"
                         :key="novedad.id">
                         
                        <novedad :nov="novedad"></novedad>
                    </div>
                    
                    <div v-if="mias" class="accordion" id="novedades">
                        <div class="card text-center">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="collapse" href="#todas" role="button" aria-expanded="true" aria-controls="todas">Todas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link collapsed" data-toggle="collapse" href="#favs" role="button" aria-expanded="false" aria-controls="favs">Favoritos</a>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="collapse show" id="todas" data-parent="#novedades">
                                <div class="card card-body">
                                    <div v-for="novedad in novedadesOrdenadas"
                                         :key="novedad.id">
                                         
                                        <novedad :nov="novedad"></novedad>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="collapse" id="favs" data-parent="#novedades">
                                <div class="card card-body">
                                    <div v-for="fav in novedadesFavoritas">
                                         
                                        <novedad :nov="fav"></novedad>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>    
        </div>
    </div>
    `,


    data: function () {
        return {
            store,
            auth,
            comentariosVisibles: false,
            comment: false,
            buscar: '',
            buscado: []
        }
    },

    computed: {
        novedadesOrdenadas: function () {
            if (this.mias) {
                return this.store.novedadesMias.reverse();
            } else {
                return this.store.novedades.reverse();

            }
        },

        novedadesFavoritas: function () {
            let ff = [];

            this.store.novedades.forEach(function (novedad) {
                if (novedad.favorito != '0') {
                    ff.push(novedad)
                }
            })

            return ff;
        },

        buscador: function () {
            if (this.buscar == '') {
                this.buscado = [];
                return false
            } else
                return this.buscado.length
        }
    },

    props: {
        mias: {
            type: Boolean
        }
    },

    mounted: async function () {


        let url = 'api/mvc/public/novedadesListado/' + auth.usuario.id;
        if (this.store.novedades.length == 0) {

            fetch(url)
                .then(rta => rta.json())
                .then(rta => {
                    this.store.novedades = rta.data
                })
        }

        if (this.mias) {
            url += "/" + auth.usuario.id;
        }

        fetch(url)
            .then(rta => rta.json())
            .then(rta => {
                if (this.mias) {
                    this.store.novedadesMias = rta.data
                } else {
                    this.store.novedades = rta.data
                }
            })
    },

    methods: {
        search: async function () {
                if (this.buscar.trim().length == 0) {
                    return false;
                }

            let urlS = 'api/mvc/public/novedades/tag/' + this.buscar;
            const request = await fetch(urlS);
            const rta = await request.json();

            let aBusque = [];
            this.store.novedades.forEach(function (nov) {
                rta.data.forEach(function (s) {
                    if (s.Novedades_id == nov.id) {
                        aBusque.push(nov);
                    }
                })
            })

            if (aBusque.length)
                this.buscado = aBusque;
            else {
                this.store.setNotificacion({
                    mensaje: 'No hay coincidencias',
                    tipo: 'danger',
                });
            }
        }
    }
});