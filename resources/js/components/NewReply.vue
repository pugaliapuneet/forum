<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <wysiwyg name="body" 
                        v-model="body" 
                        placeholder="Have something to say?" 
                        ref="trix"
                        :shouldClear="completed"
                        ></wysiwyg>
            </div>

            <button class="btn btn-default" 
                    type="submit" 
                    @click="addReply">Post</button>
        </div>
        <p class="text-center" v-else>Please <a href="/login">sign in</a> to participate in this discussion.</p>
    </div>
</template>

<script>
import 'at.js';
import 'jquery.caret';

export default {
    data() {
        return {
            body: '', 
            completed: false,
        }
    },

    mounted() {
        $("#body").atwho({
            at: "@", 
            delay: 750,
            callbacks: {
                remoteFilter: function(query, callback) {
                    console.log("Called");
                    $.getJSON("/api/users", {name: query}, function(usernames){
                        callback(usernames)
                    })
                }
            },
            // data: 'http://localhost:8887/research/users.php',
        })
    }, 

    methods: {
        addReply() {
            axios.post(location.pathname+'/replies', {body: this.body})
                .catch(error => {
                    flash(error.response.data, 'danger');
                })
                .then(({data}) => {
                    this.body = '';
                    this.completed = true;

                    flash('Your reply has been posted.');

                    this.$emit('created', data);
                })
        }
    }
}
</script>