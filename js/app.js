const ViewLogin = {
    template: `<div>
                    <form-login></form-login>
                 </div>`
};

const ViewRegistro = {
    template: `<form-registro></form-registro>`
};

const ViewNovedades = {
    template: `<seccion-novedades></seccion-novedades>`
};

const ViewNovedad = {
    template: `<novedad></novedad>`
};

const ViewPerfilForm = {
    template: `<form-perfil></form-perfil>`
};

const ViewPerfil = {
    template: `<perfil-usuario></perfil-usuario>`
};

const ViewEventos = {
    template: `<seccion-eventos></seccion-eventos>`
};


const rutas = [
    {path: "/", component: ViewLogin},
    {path: "/registro", component: ViewRegistro},
    {path: "/perfil", component: ViewPerfil, meta: {requiresAuth: true}},
    {path: "/perfil/editar", component: ViewPerfilForm, meta: {requiresAuth: true}},
    {path: "/novedades", component: ViewNovedades, meta: {requiresAuth: true}},
    {path: "/novedades/:id", name: 'novedad', component: ViewNovedad, meta: {requiresAuth: true}},
    {path: "/eventos", component: ViewEventos, meta: {requiresAuth: true}}
];

const router = new VueRouter({
    routes: rutas
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(ruta => ruta.meta.requiresAuth)) {
        if (!auth.logueado()) {
            next('/');
        } else {
            next();
        }
    } else {
        if (auth.logueado()) {
            next('/novedades');
        } else {
            next();
        }
    }
});

const store = {
    usuario: {},
    favoritos: [],
    auth: {
        logged: false
    },
    notificacion: {
        tipo: 'success',
        mensaje: null
    },
    setNotificacion(msj) {
        this.notificacion = msj;
    },
    novedades: [],
    novedadesMias: [],
    intereses: [],
    notificaciones: [],
    eventos: []

};

const app = new Vue({
    el: "#app",
    router,
    data: {
        store
    },

    mounted: async function () {
        this.store.auth.logged = auth.logueado();
        if (this.store.auth.logged) {
            this.store.usuario = auth.getUsuario();

            /*let url = 'api/mvc/public/novedades';
            if (this.mias) {
                url += "/" + auth.usuario.id;
            }
            const requestNov = await fetch(url);
            const rtaNov = await requestNov.json();

            let urlFav = 'api/mvc/public/novedades/favoritos/' + auth.usuario.id;
            const request = await fetch(urlFav);
            const rta = await request.json();

            rtaNov.data.forEach(function (nov) {
                rta.data.forEach(function (fav) {
                    if (fav.Novedades_id == nov.id) {
                        nov.fav_id = fav.id
                        store.favoritos.push(nov);
                    }
                })
            })*/
        }
    },

    methods: {
        logout: function () {
            auth.logout().then((rta) => {
                if (rta.estado) {
                    this.store.auth.logged = false;

                    this.store.setNotificacion({
                        mensaje: rta.mensaje,
                        tipo: 'success',
                    });
                    this.$router.push('/');
                }
            })
        },

        cerrar: function () {
            this.store.notificacion = {
                type: 'success',
                text: null
            }
        }
    }
});