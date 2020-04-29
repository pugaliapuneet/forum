<template>
    <div id="'reply-'+id" class="card" :class="isBest ? 'border-success' : ''">
        <div class="card-header">
            <div class="level">
                <h6 class="flex">
                    <a href="'/profiles/'+data.owner.name" v-text="reply.owner.name"></a> 
                    said <span v-text="ago"></span>
                </h6>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <wysiwyg v-model="body"></wysiwyg>
                    </div>

                    <button class="btn btn-sm btn-primary">Update</button>
                    <button class="btn btn-sm btn-link" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>
        
        <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-secondary btn-sm mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm mr-1" @click="destroy">Delete</button>
            </div>
            <button v-if="authorize('owns', reply.thread)" class="btn btn-light btn-sm ml-a" @click="markBestReply">Best Reply?</button>
        </div>
    </div>
</template>

<script>

import Favorite from './Favorite.vue'
import moment from 'moment';

export default {
    props: ['reply'], 
    components: {Favorite}, 
    data() {
        return {
            editing: false, 
            id: this.reply.id,
            body: this.reply.body,
            isBest: this.reply.isBest,
        }
    }, 

    computed: {
        ago() {
            return moment(this.reply.created_at).fromNow() + "...";
        }, 
    },

    created() {
        window.events.$on('best-reply-selected', id => {
            this.isBest = (id === this.id)
        })
    }, 

    methods: {
        update() {
            axios.patch('/replies/' + this.reply.id, {
                body: this.body
            }).catch(error => {
                flash(error.response.data, 'danger');
            });

            this.editing = false;

            flash('Updated!');
        }, 

        destroy() {
            axios.delete('/replies/' + this.reply.id)

            this.$emit('deleted', this.reply.id);
        }, 

        markBestReply() {
            this.isBest = true;

            axios.post('/replies/'+this.reply.id+'/best');

            window.events.$emit('best-reply-selected', this.reply.id);
        },
    }
}
</script>