<template>
  <q-page class="row items-center justify-evenly">
    <div style="min-width: 250px;">
      <q-input v-model="filter" label="Search" dense></q-input>
      <div v-if="!root && !filter" class="fa-2x text-center">
        <i class="fas fa-spinner fa-spin"></i>
      </div>
      <div v-if="!root && filter" class="fa-2x text-center">
        Not found
      </div>
      <div v-if="!!root" style="border: 1px solid #888; padding: 8px;">
        <TreeNode :root="root" />
      </div>
    </div>
  </q-page>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
import { keyBy, uniqBy } from 'lodash'
import TreeNode from 'src/components/TreeNode.vue'
import NestedSet from 'src/store/models/NestedSet'
import { Collection } from '@vuex-orm/core'

@Component({
  components: {
    TreeNode
  }
})
export default class PageIndex extends Vue {
  public filter: string | null = ''
  public found = 0

  public get root () {
    if (this.filter) {
      var filtered = NestedSet.query().where('title', this.filter).with('children.children').all()
      const parentsCache: Record<number, NestedSet | null> = keyBy(filtered, 'id')
      // eslint-disable-next-line camelcase
      while (filtered?.[0]?.parent_id || filtered.length > 1) {
        const parents: Collection<NestedSet> = []
        for (const item of filtered) {
          item.expanded = true
          if (item.parent_id) {
            let parent = parentsCache[item.parent_id]
            if (!parent) {
              parent = NestedSet.query().whereId(item.parent_id).first()
              parentsCache[item.parent_id] = parent
            }
            if (parent) {
              parents.push(parent)
              parent.children = parent.children ?? []
              parent.children.push(item)
              parent.children = uniqBy(parent.children, 'id')
              parent.expanded = true
            }
          } else {
            item.expanded = true
            parents.push(item)
          }
        }
        filtered = uniqBy(parents, 'id')
      }
      return filtered[0]
    }
    const root = NestedSet.query().where('parent_id', null).with('children').first()
    if (root) {
      root.expanded = true
    }
    return root
  }

  async mounted () {
    await NestedSet.api().get('nested-set')
  }
}
</script>
