Vue.component('publicar', {
    template: `
    <div class="col-12">
        <h2 class="text-center mt-4 mb-4">Hola, {{store.usuario.nombre}}, ¿Qué hay de nuevo hoy?</h2>
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="publicarNovedad()">
                    <textarea v-model="nuevaNov.cuerpo" class="form-control" id="message" rows="3" placeholder="¿Qué hay de nuevo hoy?"></textarea>
                    
                    <button type="submit" :disabled='nuevaNov.cuerpo == null || nuevaNov.cuerpo == ""' class="btn btn-primary mt-3 float-right">Compartir</button>
                    
                </form>
           </div>
        </div>
    </div>`,

    data: function () {
        return {
            nuevaNov: {
                usuarios_id: auth.usuario.id,
                cuerpo: null
            },
            respuesta: {
                mensaje: null,
                tipo: "success"
            },

            store,
            auth
        }
    },

    props: {},

    methods: {
        publicarNovedad: function () {
            if (this.nuevaNov.cuerpo == null) {
                return;
            }

            fetch('api/mvc/public/novedades/publicar', {
                method: 'post',
                body: JSON.stringify(this.nuevaNov),
                headers: {
                    'Content-Type': 'application/json',
                }
            }).then(rta => rta.json())
                .then(rta => {

                    if (rta.estado) {
                        this.store.setNotificacion({
                            mensaje: rta.mensaje,
                            tipo: 'success'
                        });

                        if (this.nuevaNov.cuerpo.indexOf('#') != -1) {
                            let pos = this.nuevaNov.cuerpo.indexOf('#');
                            let cuerpo = this.nuevaNov.cuerpo.slice(pos);

                            let aTags = cuerpo.split('#');

                            aTags = aTags.filter(Boolean);
                            aTags = aTags.map(function (tag) {

                                if (tag.indexOf(' ') != -1)
                                    return tag.split(' ')[0];
                                else
                                    return tag;
                            });

                            aTags.forEach(function (tag) {

                                fetch('api/mvc/public/novedades/tag/crear', {
                                    method: 'post',
                                    body: JSON.stringify({Novedades_id: rta.data.id, tag: tag}),
                                    headers: {
                                        'Content-Type': 'application/json',
                                    }
                                }).then(rta => rta.json())
                                    .then(rta => {});
                            })
                        }

                        this.nuevaNov.cuerpo = null;

                        let url = 'api/mvc/public/novedadesListado/' + auth.usuario.id;
                        if (this.$route.path == '/perfil') {
                            url += "/" + auth.usuario.id;
                        }

                        fetch(url)
                            .then(rta => rta.json())
                            .then(rta => {

                                if (this.$route.path == '/perfil') {
                                    this.store.novedadesMias = rta.data;
                                }else{
                                    this.store.novedades = rta.data;
                                }
                            })


                    } else {
                        this.store.setNotificacion({
                            mensaje: rta.mensaje,
                            tipo: 'danger'
                        });
                    }
                });
        }
    }
})

