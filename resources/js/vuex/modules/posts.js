import axios from '../../service/api.js'

const state = {
    posts: null
}

const mutations = {
    SET_POSTS(state, posts) {
        state.posts = posts
    }
}

const actions = {
    fetchPosts({commit}, posts) {
        commit('SET_POSTS', posts)
    }
}

const getters = {
    getPosts: state => state.posts
}

export default {
    namespaced: true, // важно для модулей
    state,
    mutations,
    actions,
    getters
}
