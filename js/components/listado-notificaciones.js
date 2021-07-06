Vue.component('listado-notificaciones', {
    template: `
    <div class="dropdown">
        <button class="btn bg-transparent nav-link dropdown-toggle" type="button" id="notificaciones" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @click="mostrarNoti()">
            Notificaciones <span v-show="notiNoLeidas" class="badge badge-light">{{notiNoLeidas}}</span>
        </button>
        
        <ul class="dropdown-menu" aria-labelledby="notificaciones">
            <li v-if="store.notificaciones.length == 0" class="dropdown-item">No tienes notificaciones</li>
            <li v-else class="dropdown-item" v-for="noti in notiOrdenadas" 
                :key="noti.id">
                <router-link :to="{ name: 'novedad', params: {id: noti.novId } }">{{noti.notificacion}}</router-link>
            </li>
            <li @click="vaciar()" class="text-danger dropdown-item" v-if="store.notificaciones.length">Vaciar notificaciones</li>
        </ul>
    </div>
    `,


    data: function () {
        return {
            store,
            auth,
            notiVisibles: false,
            notiNoLeidas: 0
        }
    },

    computed: {
        notiOrdenadas: function () {
            return this.store.notificaciones.reverse();
        }
    },

    mounted: async function () {

        let urlNoti = 'api/mvc/public/notificacion/' + auth.usuario.id;
        const request = await fetch(urlNoti);
        const rta = await request.json();

        this.store.notificaciones = rta.data;

        let cant = 0;
        this.store.notificaciones.forEach(function (noti) {
            if (noti.leida == 0) {
                cant++;
            }
        })
        this.notiNoLeidas = cant;
    },

    methods: {
        mostrarNoti: function () {
            this.notiVisibles = !this.notiVisibles;

            if (this.notiVisibles) {
                this.notiNoLeidas = 0;
                this.store.notificaciones.forEach(function (noti) {
                    if (noti.leida == 0) {
                        fetch("api/mvc/public/notificacion/lectura", {
                            method: 'PUT',
                            body: JSON.stringify({id: noti.id, leida: 1}),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        }).then(rta => rta.json())
                            .then(rta => {
                                if (rta.estado) {

                                    this.notiNoLeidas = 0;

                                } else {
                                    this.respuesta = {
                                        mensaje: rta.mensaje,
                                        tipo: 'danger'
                                    };
                                }
                            });
                    }
                })
            }
        },

        vaciar: function () {
            fetch("api/mvc/public/notificacion/vaciar", {
                method: 'PUT',
                body: JSON.stringify({user: this.store.usuario.id}),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(rta => rta.json())
                .then(rta => {
                    if (rta.estado) {
                        this.store.setNotificacion({
                            mensaje: rta.mensaje,
                            tipo: 'success'
                        });

                        this.store.notificaciones=[];

                    } else {
                        this.store.setNotificacion({
                            mensaje: rta.mensaje,
                            tipo: 'danger'
                        });
                    }
                });
        }
    }
});