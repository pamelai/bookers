Vue.component('form-login', {
    template: `
    <section>
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card rounded-2 mt-4">
                    <div class="card-header">
                        <h2 class="mb-0">Iniciar sesión</h2>
                    </div>
                    <div class="card-body">
                        <form v-on:submit.prevent="login()" action="">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="email" type="email" class="form-control form-control-lg rounded-0" name="email" id="email" placeholder="ingrese su correo electrónico">
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
                                <label for="password">Contraseña</label>
                                <input v-model="password" type="password" class="form-control form-control-lg rounded-0" id="password" placeholder="*********">
                            </div>
                            <div 
                                class="alert"
                                :class="['alert-' + respuesta.tipo]"
                                v-if="respuesta.mensaje != null && respuesta.mensaje.password != null"
                            >
                                {{ respuesta.mensaje.password[0] }}
                            </div>
                    
                            <button type="submit" class="btn btn-success btn-lg float-right">Ingresar</button>
                        </form>
                        <div class="mt-4">
                            <div class="d-flex justify-content-center links">
                                ¿No tienes una cuenta? <router-link class="ml-2" to="/registro">Registrate</router-link>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div> 
    </section>
    `,
    data: function () {
        return {
            email: null,
            password: null,

            respuesta: {
                mensaje: null,
                tipo: "success"
            },

            store
        }
    },

    methods: {
        login: function () {
            auth.login(this.email, this.password).then(ok => {

                if (ok.estado) {
                    this.store.setNotificacion({
                        mensaje: ok.mensaje,
                        tipo: 'success'
                    });

                    this.store.auth.logged = auth.logueado();
                    this.store.usuario = auth.getUsuario();
                    this.$router.push("/novedades");

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