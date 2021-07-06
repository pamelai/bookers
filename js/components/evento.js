Vue.component('evento', {
    template: ` 
    <article  class="my-2 p-3" id="ev">
        <h3 class="h4 px-1 py-2 text-center border-bottom border-warning">{{ event.nombre }}</h3>
        <ul class="pt-2">
        <li class="text-left text-secondary d-block"><i class="fal fa-calendar-day fa-1x"></i>  {{ event.fecha }}</li>
        <li class="text-left text-secondary d-block"><i class="fal fa-clock"></i>  {{ event.hora }}</li>
        <li class="text-left text-secondary d-block"><i class="fal fa-map-marker-alt"></i> {{ event.lugar }}</li>
        </ul>
        <p class="pt-2 ml-4">{{ event.descripcion }}</p>
        <div class="d-flex mb-2" v-if="!mias">
            <div class="btn-group btn-group-sm mt-4 ml-auto">
                
                  <button class="btn bg-transparent" @click="asistir(event.id,1)">

                    <span v-show="event.estado == 1"><i class="fa-calendar-check text-success fa-2x fad"></i></span>
                    <span v-show="event.estado != 1"><i class="fa-calendar-check text-success fa-2x fal"></i></span>
<!--                    <i :class="['fa-calendar-check text-success fa-2x', event.estado == 1 ? 'fad' : 'fal']"></i>-->
<!--                    <i :class="calendarCheckStyles" ref="lala"></i>-->
                </button>
                
                <button class="btn bg-transparent" @click="asistir(event.id,2)">
                    
                    
                    <span v-show="event.estado == 2"><i class="fa-calendar-minus text-warning fa-2x fad"></i></span>
                    <span v-show="event.estado != 2"><i class="fa-calendar-minus text-warning fa-2x fal"></i></span>
                   <!-- <i :class="['fa-calendar-minus text-warning fa-2x', event.estado == 2 ? 'fad' : 'fal']"></i>-->
                   
                </button>
                
                <button class="btn bg-transparent" @click="asistir(event.id,3)">
                    
                    <span v-show="event.estado == 3"><i class="fa-calendar-times text-danger fa-2x fad"></i></span>
                    <span v-show="event.estado != 3"><i class="fa-calendar-times text-danger fa-2x fal"></i></span>
                    <!-- <i :class="['fa-calendar-times text-danger fa-2x', event.estado == 3 ? 'fad' : 'fal']"></i>-->
                </button>
              
            </div>
        </div>
        <div class="p-2 mr-2 border-top border-warning" v-if="event.asistentes != null">
            
            <small  v-for="(asistente, pos) in event.asistentes"  class="text-secondary d-block"><b>{{ asistente.usuario }}</b> {{ getEstado(asistente.estado) }}</small>
            
        </div>
    </article>`,

    data: function () {
        return {
            store,
            auth,
            event: this.ev
        }
    },
    computed: {},
    props: {
        ev: {
            type: Object
        },
        mias:{
            type: Boolean,
            default: false
        }
    },

    mounted() {
    },

    methods: {
        getEstado: function(estado){
            let est;

            switch (estado) {
                case "1": est = "asistirá";
                break;
                case "2": est =  "tal vez asista";
                break;
                case "3": est = "no asistirá";
                break;
            }

            return est;
        },
        asistir: function (id,estado) {
            fetch("api/mvc/public/eventos/asistir", {
                method: 'POST',
                body: JSON.stringify({eventos_id: id, usuarios_id: this.auth.usuario.id,estado}),
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

                        this.event.estado = estado;
                    }
                })
        },
    }
});
