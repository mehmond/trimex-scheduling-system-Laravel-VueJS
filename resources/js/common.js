import { mapGetters } from 'vuex'
export default{
    data(){
        return{

        }
    },
    methods: {
        async callApi(method, url, dataObj){
            try {
               return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                  });
            } catch (error) {
                return error.response
            } 
        },

        info (desc, title="Hey" ) {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        success (desc, title="Success") {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        warning (desc, title="Ooops!") {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        error (desc, title="Oops!") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        swr (desc="Something went wrong! Please Try again", title="Oops!") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        checkUserPermission(key){
            if(!this.userPermission) return true
            let isPermitted = false;
            for(let d of this.userPermission){
                if(this.$route.name==d.name){
                    if(d[key]){
                        isPermitted = true
                        break;
                    }else{
                        break
                    }
                }
                
            }
            return isPermitted
        }

    },
    computed: {
        ...mapGetters({
            'userPermission' : 'getUserPermission'
        }),
        isReadPermitted(){
           return this.checkUserPermission('read')
        },
        isWritePermitted(){
            return this.checkUserPermission('write')
        },
        isUpdatePermitted(){
            return this.checkUserPermission('update')
        },
        isDeletePermitted(){
            return this.checkUserPermission('delete')
        },
    },
}