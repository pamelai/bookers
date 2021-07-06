Vue.component('notificacion', {
    template:`
    <div 
        class="alert fade show"
        :class="['alert-' + tipo]"
        role="alert"
        id="noti"
    >
       {{ mensaje }}
    </div>`,

    props:{
        tipo:{
            type:String,
            default:'success'
        },
        mensaje:{
            type:String,
            required:true
        },
        autoCierre: {
            type: Boolean,
            default: true
        },
        autoCirreTiempo: {
            type: Number,
            default: 3500
        }
    },

    mounted(){
        if(this.autoCierre) {
            setTimeout(() => {
                this.$emit('closed');
            }, this.autoCirreTiempo);
        }
    }
});