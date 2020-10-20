import { store } from 'quasar/wrappers'
import { axios } from 'src/boot/axios'
import Vuex from 'vuex'
import VuexORM from '@vuex-orm/core'
import VuexORMAxios from '@vuex-orm/plugin-axios'
import NestedSet from './models/NestedSet'

export interface StateInterface {
  example: unknown;
}

export default store(function ({ Vue }) {
  Vue.use(Vuex)
  VuexORM.use(VuexORMAxios, { axios })
  const database = new VuexORM.Database()
  database.register(NestedSet)

  const Store = new Vuex.Store<StateInterface>({
    plugins: [VuexORM.install(database)],
    modules: {
      // example
    },

    strict: !!process.env.DEV
  })

  return Store
})
