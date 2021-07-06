Vue.component('form-perfil', {
    template: `
    <section>
        <div class="row justify-content-center my-3">
            <div class="col-12 col-lg-6">
                <form @submit.prevent="editar()">
                    <div class="form-group preview text-center">
                        <img class="preview-img img-fluid" :src="store.usuario.imagen" :alt="store.usuario.usuario"/>
                        <div class="browse-button">
                            <i class="fa fa-pencil-alt"></i>
                            <input class="browse-input" type="file" name="imagen" @change="readFile($event)" id="imagen"/>
                        </div>
                    </div>
                    <div 
                        class="alert"
                        :class="['alert-' + respuesta.tipo]"
                        v-if="respuesta.mensaje != null && respuesta.mensaje.imagen != null"
                    >
                        {{ respuesta.mensaje.imagen[0] }}
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese su nombre" v-model="store.usuario.nombre">
                    </div>
                    <div 
                        class="alert"
                        :class="['alert-' + respuesta.tipo]"
                        v-if="respuesta.mensaje != null && respuesta.mensaje.nombre != null"
                    >
                        {{ respuesta.mensaje.nombre[0] }}
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Ingrese su apellido" v-model="store.usuario.apellido">
                    </div>
                    <div 
                        class="alert"
                        :class="['alert-' + respuesta.tipo]"
                        v-if="respuesta.mensaje != null && respuesta.mensaje.apellido != null"
                    >
                        {{ respuesta.mensaje.apellido[0] }}
                    </div>
                    
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Ingrese su usuario" v-model="store.usuario.usuario">
                    </div>
                    <div 
                        class="alert"
                        :class="['alert-' + respuesta.tipo]"
                        v-if="respuesta.mensaje != null && respuesta.mensaje.usuario != null"
                    >
                        {{ respuesta.mensaje.usuario[0] }}
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Ingrese su email" v-model="store.usuario.email">
                    </div>
                    <div 
                        class="alert"
                        :class="['alert-' + respuesta.tipo]"
                        v-if="respuesta.mensaje != null && respuesta.mensaje.email != null"
                    >
                        <ul v-if="respuesta.mensaje.email.length > 1">
                            <li v-for="error in respuesta.mensaje.email">{{error}}</li>
                        </ul>
                        
                        <p v-else >{{ respuesta.mensaje.email[0] }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" v-model="store.usuario.password" value="***********" placeholder="***********">
                    </div>
                    <div 
                        class="alert"
                        :class="['alert-' + respuesta.tipo]"
                        v-if="respuesta.mensaje != null && respuesta.mensaje.password != null"
                    >
                        <ul v-if="respuesta.mensaje.password.length > 1">
                            <li v-for="error in respuesta.mensaje.password">{{error}}</li>
                        </ul>
                        
                        <p v-else >{{ respuesta.mensaje.password[0] }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="intereses">Intereses</label>
                        <textarea v-model="intereses" class="form-control" id="intereses" rows="3" placeholder="Ingresa tus intereses separados por una coma. Ej.: 'Libros, Terror, Música'"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
                
                <div>
                    <h3 class="text-center mt-4 mb-4">Eliminar cuenta</h3>
                    <div class="text-center">
                        <label class="mb-3">Estás a punto de desatar una acción irrevesible</label>
                        <button type="button" class="btn btn-danger d-block m-auto" data-toggle="modal" data-target="#confirmacion">
                            Eliminar cuenta
                        </button>
                    </div>
                </div>
                
                <div class="modal fade" id="confirmacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">Eliminar cuenta</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            ¿Estás seguro de que quieres eliminar tu cuenta?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                <button type="button" class="btn btn-danger" @click="eliminar()" data-dismiss="modal">Si</button>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </section>`,

    data: function () {
        return {
            respuesta: {
                mensaje: null,
                tipo: "success"
            },

            store,
            intereses: null
        };
    },

    mounted() {
        let res = '';
        this.store.intereses.forEach(function (int) {
            res += int.interes + ', ';

        });

        this.intereses = res.slice(0, -2);
    },

    methods: {
        readFile: function (event) {
            const image = event.target.files[0];

            const fr = new FileReader;
            fr.addEventListener('load', function () {
                const img64 = fr.result;

                store.usuario.imagen = img64;
            });
            fr.readAsDataURL(image);

        },

        editar: function () {
            fetch('api/mvc/public/perfil/editar', {
                method: 'put',
                body: JSON.stringify(this.store.usuario),
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

                        localStorage.setItem('auth.usuario', JSON.stringify(rta.data));
                    } else {
                        if (rta.mensaje != null && typeof rta.mensaje !== 'object') {
                            this.store.setNotificacion({
                                mensaje: rta.mensaje,
                                tipo: 'danger'
                            });

                        } else {
                            this.respuesta = {
                                mensaje: rta.mensaje,
                                tipo: 'danger'
                            };
                        }
                    }
                });

            //Veo que habia antes y en relacion al resultado hago un crear, eliminar o ambos de los intereses
            let intAnt = '';
            this.store.intereses.forEach(function (int) {

                intAnt += int.interes + ', ';

            });
            intAnt = intAnt.slice(0, -2);
            let intEli = [];
            let intAgre = [];
            let agrego = {};

            if (intAnt !== this.intereses) {

                let intAct = this.intereses.split(', ');

                this.store.intereses.forEach(function (int) {
                    //Si en lo actual no hay alguno de los ant, lo elimino
                    if (!intAct.includes(int.interes)) {
                        intEli.push(int.id);

                    } else if (intEli.indexOf(int.id) != -1) {
                        //Si lo saco sin querer y lo vuelve a escribir, lo saco de eliminados
                        intEli.splice(intEli.indexOf(int.id), 1);

                    }
                })

                for (let i = 0; i < intAct.length; i++) {
                    //Si en lo ant, no hay alguno de los actuales lo agrego
                    if (!intAnt.includes(intAct[i])) {
                        agrego = {
                            'Usuarios_id': store.usuario.id,
                            'interes': intAct[i]
                        }
                        intAgre.push(agrego)
                        agrego = {};
                    }
                }
            }

            if (intEli.length) {
                intEli.forEach(function (id) {
                    fetch('api/mvc/public/usuario/intereses/eliminar', {
                        method: 'DELETE',
                        body: JSON.stringify({'id': id}),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(rta => rta.json())
                        .then(rta => {

                            if (rta.estado) {

                                store.setNotificacion({
                                    mensaje: rta.mensaje,
                                    tipo: 'success',
                                });

                                respuesta = {
                                    mensaje: null,
                                    tipo: 'success'
                                };

                                let urlInt = 'api/mvc/public/usuario/intereses/' + auth.usuario.id;
                                fetch(urlInt)
                                    .then(rta => rta.json())
                                    .then(rta => {

                                        store.intereses = rta.data;

                                        let res = '';
                                        store.intereses.forEach(function (int) {

                                            res += int.interes + ', ';

                                        });
                                        intereses = res.slice(0, -2);
                                    })

                            } else {
                                respuesta = {
                                    mensaje: rta.mensaje,
                                    tipo: 'danger'
                                }
                            }
                        });
                })
            }


            if (intAgre.length) {
                intAgre.forEach(function (interes) {
                    fetch('api/mvc/public/usuario/intereses/crear', {
                        method: 'POST',
                        body: JSON.stringify(interes),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(rta => rta.json())
                        .then(rta => {

                            if (rta.estado) {

                                store.setNotificacion({
                                    mensaje: rta.mensaje,
                                    tipo: 'success',
                                });

                                respuesta = {
                                    mensaje: null,
                                    tipo: 'success'
                                };

                                let urlInt = 'api/mvc/public/usuario/intereses/' + auth.usuario.id;
                                fetch(urlInt)
                                    .then(rta => rta.json())
                                    .then(rta => {

                                        store.intereses = rta.data;

                                        let res = '';
                                        store.intereses.forEach(function (int) {

                                            res += int.interes + ', ';

                                        });
                                        intereses = res.slice(0, -2);
                                    })

                            } else {
                                respuesta = {
                                    mensaje: rta.mensaje,
                                    tipo: 'danger'
                                }
                            }
                        });
                })
            }

        },


        eliminar: function () {
            fetch('api/mvc/public/perfil/eliminar', {
                method: 'delete',
                body: JSON.stringify(this.store.usuario.id),
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
                        localStorage.removeItem('auth.usuario');
                        auth.setUsuario({
                            id: null,
                            email: null,
                            usuario: null,
                            imagen: null,
                            nombre: null,
                            apellido: null,
                        });

                        auth.setEstado(false);
                        this.store.usuario = {
                            id: null,
                            email: null,
                            usuario: null,
                            imagen: null,
                            nombre: null,
                            apellido: null
                        };
                        this.store.auth.logged = false;

                        this.$router.push('/');
                    } else {
                        this.respuesta = {
                            mensaje: rta.mensaje,
                            tipo: 'danger'
                        }
                    }
                });
        }
    }
});