Vue.component('novedad', {
    template: `
    <article  class="my-2 p-2" id="nov" v-if="novedad">
        <div class="media">
            <img :src="novedad.usuarios[0].imagen" class="mr-3 img-fluid" :alt="novedad.usuarios[0].usuario" width="60px" height="60px">
            <div class="media-body">
                <div class="row justify-content-between" v-if="Array.isArray(novedad.usuarios)">
                    <div class="col-auto">
                        <h3 class="h4 mb-0 p-0">{{ novedad.usuarios[0].usuario }}</h3>
                        <h4 class="h5 mt-0 p-0"><small v-if="novedad.compartido" class="text-left text-info">Compartido desde <b>@{{ novedad.compartido }}</b></small></h4>
                    </div>
                    <div class="btn-group col-auto align-self-start">
        
                        <button class="btn bg-transparent mr-3" data-toggle="dropdown" >
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                            
                          <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li v-if="novedad.usuarios[0].id === auth.usuario.id" >
                                <a @click="eliminarNov(novedad.id)" class="ml-2 text-danger"> Eliminar novedad </a>
                            </li>
                            <li v-else>
                                <p class="ml-2"> No tienes acciones disponibles </p>
                            </li>
                          </ul>
                    </div>
        
                </div>
                <small class="text-left text-secondary d-block">{{ novedad.date }}</small>
            </div>
        </div>
        <p class="text-secondary text-left mt-3">{{ novedad.descripcion }}</p>
        
        <div class="d-flex">
            <div class="btn-group btn-group-sm mt-4 ml-auto">
                <button class="btn bg-transparent" @click="compartir(novedad.id)">
                    <i class="fas fa-retweet-alt text-success"></i>
                </button>
                
                <button v-show="novedad.favorito != '0'" class="btn bg-transparent" @click="eliminarFav(novedad())">
                    <i class="fas fa-heart text-danger"></i>
                </button>
                
                <button v-show="novedad.favorito == '0'" class="btn bg-transparent" @click="agregarFav(novedad())">
                    <i class="far fa-heart text-danger"></i>
                </button>
                
                <button class="btn bg-transparent" @click="mostrarComentarios()">
                     <i class="fa fa-comment"></i> {{ comentarios.length }}
                </button>
                 <button class="btn btn-info rounded" @click="comenta(novedad.id)">
                    Comentar
                </button>
                
            </div>
        </div>
        <section>
            <comentar :idNovedad="parseInt(novedad.id)" :usuarioNov="parseInt(novedad.usuarios[0].id)" v-if="comment" ></comentar>
            <novedad-comentarios :id="parseInt(novedad.id)" :comentarios="comentarios" v-show="comentariosVisibles"></novedad-comentarios>
        </section>
        
    </article>`,

    data: function () {
        return {
            store,
            auth,
            comentariosVisibles: false,
            comment: false,
            borrarNovedad: {
                id: null
            },
            fav: false,
            nove: null
        }
    },

    props: {
        nov: {
            type: Object
        }
    },

    mounted: async function () {
        if (this.$route.params.id) {
            this.nove = this.nov;
            let id = this.$route.params.id;
            let novedad;
            if (this.store.novedades.length)
                novedad = this.store.novedades;
            else {
                let url = 'api/mvc/public/novedadesListado/' + auth.usuario.id;
                const request = await fetch(url);
                const rta = await request.json();
                novedad = rta.data
            }

            for (let i = 0; i < novedad.length; i++) {
                if (id == novedad[i].id) {
                    this.nove = novedad[i];
                    this.comentariosVisibles = true;
                    break;
                }
            }

        } else if (this.$route.fullPath != '/novedades') {
            this.$router.push('/novedades');
            this.nove = null;
        }


    },

    computed: {
        novedad() {
            return this.nove ? this.nove : this.nov
        },

        comentarios() {
            return Array.isArray(this.novedad.comentarios) ? this.novedad.comentarios : [];
        }

    },

    methods: {

        compartir: function (id) {
            fetch("api/mvc/public/novedades/compartir", {
                method: 'POST',
                body: JSON.stringify({Novedades_id: id, Usuarios_id: this.auth.usuario.id}),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(rta => rta.json())
                .then(rta => {
                    if (rta.estado) {
                        this.store.setNotificacion({
                            mensaje: rta.mensaje,
                            tipo: 'success',
                        });
                    }


                    let url = 'api/mvc/public/novedadesListado/' + auth.usuario.id;

                    if (this.$route.path == '/perfil') {
                        url += "/" + auth.usuario.id;
                    }

                    fetch(url)
                        .then(rta => rta.json())
                        .then(rta => {

                            if (this.$route.path == '/perfil') {
                                this.store.novedadesMias = rta.data
                            } else {
                                this.store.novedades = rta.data
                            }
                            ;
                        })


                })

        },

        agregarFav: async function (nov) {

            const request = await fetch("api/mvc/public/novedades/favorito", {
                method: 'POST',
                body: JSON.stringify({Novedades_id: nov.id, Usuarios_id: this.auth.usuario.id}),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const rta = await request.json();

            if (rta.estado) {
                nov.favorito = '1';

                this.store.setNotificacion({
                    mensaje: rta.mensaje,
                    tipo: 'success',
                });
                this.fav = rta.datos.id;
                this.nov.fav_id = this.fav;
                this.store.favoritos.push(this.nov)

            } else {
                this.respuesta = {
                    mensaje: rta.mensaje,
                    tipo: 'danger'
                };
            }
        },

        eliminarFav: async function (nov) {

            const request = await fetch("api/mvc/public/novedades/favorito", {
                method: 'DELETE',
                body: JSON.stringify({id: nov.favorito}),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            const rta = await request.json();

            if (rta.estado) {
                this.store.setNotificacion({
                    mensaje: rta.mensaje,
                    tipo: 'success',
                });

                this.fav = false;
                this.store.favoritos.splice(this.store.favoritos.indexOf(this.nov), 1)

                nov.favorito = '0';

            } else {
                this.store.setNotificacion({
                    mensaje: rta.mensaje,
                    tipo: 'danger',
                });
            }
        },

        mostrarComentarios: function () {
            this.comentariosVisibles = !this.comentariosVisibles;
        },

        comenta: function (id) {
            this.comment = !this.comment;
        },

        eliminarNov: function (id) {
            this.borrarNovedad.id = id;

            fetch("api/mvc/public/novedades/eliminar", {
                method: 'DELETE',
                body: JSON.stringify(this.borrarNovedad),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(rta => rta.json())
                .then(rta => {
                    if (rta.estado) {
                        this.store.setNotificacion({
                            mensaje: rta.mensaje,
                            tipo: 'success',
                        });

                        let url = 'api/mvc/public/novedadesListado/' + auth.usuario.id;

                        if (this.$route.path == '/perfil') {
                            url += "/" + auth.usuario.id;
                        }

                        fetch(url)
                            .then(rta => rta.json())
                            .then(rta => {

                                if (this.$route.path == '/perfil') {
                                    this.store.novedadesMias = rta.data
                                } else {
                                    this.store.novedades = rta.data
                                }
                            })

                    } else {
                        this.respuesta = {
                            mensaje: rta.mensaje,
                            tipo: 'danger'
                        };
                    }
                });
        }
    }
});