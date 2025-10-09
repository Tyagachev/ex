import axios from '../../service/api.js'

const state = {
    votes: null
}

const mutations = {
    SET_VOTE(state, posts) {
        state.posts = posts
    }
}

const actions = {
    fetchVotes({commit}, posts) {
        commit('SET_VOTE', posts)
    }
}

const getters = {
    getVotes: state => state.votes
}

export default {
    namespaced: true, // важно для модулей
    state,
    mutations,
    actions,
    getters
}
