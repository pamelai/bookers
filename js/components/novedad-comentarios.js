Vue.component('novedad-comentarios', {
    template: `
    <article class="row mt-3">
        <div class="col-12" v-for="comentario in comentarios">
            <div class="media border-top p-3">
                <img :src="comentario.usuarios[0].imagen" class="mr-3 img-fluid" :alt="comentario.usuarios[0].usuario" width="40px" height="40px">
                <div class="media-body">
                    <h5 class="font-weight-light">{{ comentario.usuarios.length > 0 ? comentario.usuarios[0].usuario : "" }}</h5>
                    {{ comentario.comentario }}
                </div>
            </div>
        </div>
    </article>`,

    data: function(){
        return {
            comentariosVisibles: false
        }
    },

    props:{
        id:{
            type: Number,
            required: true

        },
        comentarios:{
            type: Array,
            required: true
        }
    }
});
