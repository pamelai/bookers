Vue.component('form-registro', {
    template: `
    <section>
        <div class="container-fluid col-lg-6">
            <div class="card rounded-2">
                <div class="card-header">
                    <h2 class="mb-0">Registrarse</h2>
                </div>
                <div class="card-body">
                    <form class="form" role="form" autocomplete="off" novalidate="" @submit.prevent="registro()">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" v-model="nombre" class="form-control form-control-lg rounded-0" name="nombre" id="nombre" placeholder="Ingrese su nombre">
                        </div>
                        
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" v-model="apellido" class="form-control form-control-lg rounded-0" id="apellido" placeholder="Ingrese su apellido" name="apellido">
                        </div>
                        
                        <div class="form-group">
                            <label for="usuario">Usuario</label>
                            <input type="text" v-model="usuario" class="form-control form-control-lg rounded-0" id="usuario" placeholder="Ingrese su nombre de usuario" name="usuario">
                        </div>
                        <div 
                            class="alert"
                            :class="['alert-' + respuesta.tipo]"
                            v-if="respuesta.mensaje != null && respuesta.mensaje.usuario != null"
                        >
                            {{ respuesta.mensaje.usuario[0] }}
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password"  v-model="password" class="form-control form-control-lg rounded-0" id="password" placeholder="Ingrese su contraseña" name="password">
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
                            <label for="email">Email</label>
                            <input type="email" v-model="email" class="form-control form-control-lg rounded-0" id="email" placeholder="Ingrese su email" name="email">
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
            
                        <button type="submit" class="btn btn-success btn-lg float-right" id="btnRegistro">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </section>`,

    data: function () {
        return {
            store,
            nombre: null,
            apellido: null,
            usuario: null,
            password: null,
            email: null,
            respuesta: {
                mensaje: null,
                tipo: "success"
            }
        };
    },

    methods: {
        registro: function () {
            auth.registro(this.nombre, this.apellido, this.usuario, this.password, this.password_conf, this.email, this.email_conf).then(ok => {
                if (ok.estado) {
                    this.store.setNotificacion({
                        mensaje: ok.mensaje,
                        tipo: 'success'
                    });
                    this.$router.push('/');

                } else {
                    if (ok.mensaje != null && typeof ok.mensaje !== 'object') {
                        this.store.setNotificacion({
                            mensaje: ok.mensaje,
                            tipo: 'danger'
                        });

                    } else {
                        this.respuesta = {
                            mensaje: ok.mensaje,
                            tipo: 'danger'
                        };
                    }
                }
            })
        }
    }
});