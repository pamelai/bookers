Vue.component('comentar', {
    template: `
    <div class="media shadow-textarea">
        <div class="media-body mt-2">
            <form @submit.prevent="publicarComentario()">
                <div class="input-group">
                    <textarea v-model="nuevoCom.comentario" rows="3" placeholder="Escribe tu comentario" class="form-control"></textarea>
                    <div class="input-group-prepend">
                        <button class="btn btn-info rounded " type="submit" :disabled='nuevoCom.comentario == null || nuevoCom.comentario == ""'>Comentar</button>              
                    </div>
                </div>
            </form>        
        </div>
    </div>`,

    data: function () {
        return {
            nuevoCom: {
                usuarios_id: auth.usuario.id,
                novedades_id: this.idNovedad,
                comentario: null
            },

            respuesta: {
                mensaje: null,
                tipo: "success"
            },

            store,
            auth

        }
    },

    props: {
        idNovedad: {
            type: Number,
            required: true

        },

        usuarioNov: {
            type: Number,
            required: true

        }
    },

    methods: {
        publicarComentario: function () {
            if (this.nuevoCom.comentario == null) {
                return;
            }

            fetch('api/mvc/public/novedades/comentarios', {
                method: 'post',
                body: JSON.stringify(this.nuevoCom),
                headers: {
                    'Content-Type': 'application/json',
                }
            }).then(rta => rta.json())
                .then(rta => {

                    if (rta.estado) {
                        this.store.setNotificacion({
                            mensaje: rta.mensaje,
                            tipo: 'success',
                        });

                        if(this.nuevoCom.usuarios_id != this.usuarioNov){
                            let noti={
                                'Usuarios_id_recibe':  this.usuarioNov,
                                'Usuarios_id_envia': this.nuevoCom.usuarios_id,
                                'Novedades_id': this.nuevoCom.novedades_id,
                                'notificacion': 'Tienes un nuevo comentario'
                            };

                            fetch('api/mvc/public/notificacion/crear',{
                                method: 'post',
                                body: JSON.stringify(noti),
                                headers: {
                                    'Content-Type': 'application/json',
                                }
                            })
                                .then(rta => rta.json())
                                .then(rta => {})
                        }

                        fetch('api/mvc/public/novedadesListado/' + auth.usuario.id)
                            .then(rta => rta.json())
                            .then(rta => {

                                this.store.novedades = rta.data;
                                this.nuevoCom.comentario = null;
                                this.$parent.comentariosVisibles = true;

                                if(this.$parent.nove){
                                    for (let i = 0; i < this.store.novedades.length; i++) {
                                        if (this.$parent.nove.id == this.store.novedades[i].id) {
                                            this.$parent.nove = this.store.novedades[i];
                                            break;
                                        }
                                    }
                                }

                            })

                    } else {
                        this.store.setNotificacion({
                            mensaje: rta.mensaje,
                            tipo: 'danger',
                        });
                    }
                });
        }
    }
})