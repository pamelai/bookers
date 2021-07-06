const auth = {
    estado: false,
    usuario: {
        id: null,
        nombre: null,
        apellido: null,
        email: null,
        usuario: null,
        imagen: null
    },

    login: function(email, password) {
        return fetch('api/mvc/public/login', {
            body: JSON.stringify({email: email, password: password}),
            method: 'post',
            headers: {
                'Content-Type': 'application/json; charset=utf-8'
            }
        }).then(res => res.json())
            .then(res => {
                if(res.estado) {
                    this.setUsuario(res.data);
                    this.setEstado(true);

                    localStorage.setItem('auth.usuario', JSON.stringify(this.usuario));
                }
                return res;
            });
    },

    logout: function() {
        return fetch('api/mvc/public/logout', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
        }).then(rta => rta.json())
            .then(rta => {
                localStorage.removeItem('auth.usuario');
                this.setUsuario({
                    id: null,
                    email: null,
                    usuario: null,
                    imagen: null,
                    nombre: null,
                    apellido: null,
                });
                this.setEstado(false);

                return rta;
            });
    },

    getUsuario: function() {
        return this.usuario;
    },

    setUsuario: function(usr) {
        this.usuario = {
            id: usr.id,
            email: usr.email,
            usuario: usr.usuario,
            imagen: usr.imagen,
            nombre: usr.nombre,
            apellido: usr.apellido,
        };
    },

    logueado: function() {
        return this.estado;
    },

    setEstado: function(estado) {
        this.estado = estado;
    },

    registro:function (nombre, apellido, usuario, password, password_conf, email, email_conf) {
        return fetch('api/mvc/public/registrarse', {
            body: JSON.stringify({
                nombre: nombre,
                apellido:apellido,
                usuario:usuario,
                password: password,
                password_conf: password_conf,
                email: email,
                email_conf: email_conf
            }),
            method: 'post',
            headers: {
                'Content-Type': 'application/json; charset=utf-8'
            }
        }).then(res => res.json())
            .then(res => {
                return res;
            });
    }
};

if(localStorage.getItem('auth.usuario') !== null) {
    auth.setUsuario(JSON.parse(localStorage.getItem('auth.usuario')));
    auth.setEstado(true);

}