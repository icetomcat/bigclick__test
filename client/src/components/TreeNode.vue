<template>
  <div draggable v-on:drop="onDrop" @dragover.prevent @dragstart="onDragStart" @dragend="onDragEnd" @dragenter="onDragEnter" @dragleave="onDragLeave">
    <div class="node-tree" v-on:click="toggle" v-bind:class="{'node-tree_pointer': hasChildren}">
      <div class="node-tree__expander">
        <div v-if="hasChildren" class="node-tree__expander-icon">
          <i v-if="expanded" class="far fa-folder-open"></i>
          <i v-else class="far fa-folder"></i>
        </div>
        <div v-else>
          <i class="fas fa-file"></i>
        </div>
      </div>
      <span class="label">{{ root.title }}</span>
    </div>
    <div class="node-tree__children" :class="{'node-tree__children_expanded': expanded, 'node-tree__children_folded': !expanded}">
      <div v-if="root.children.length || expanded">
        <node v-for="child of rootChildren" :root="child" :key="child.id" :droppable="!drag && droppable"></node>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import NestedSet from 'src/store/models/NestedSet'
import { Vue, Component, Prop, Watch } from 'vue-property-decorator'

@Component({
  name: 'node'
})
export default class TreeNode extends Vue {
  @Prop({ type: Object, required: true }) readonly root!: NestedSet
  @Prop({ type: Boolean, default: true }) readonly droppable!: boolean
  _expanded = false
  drag = false

  @Watch('root', { deep: true })
  onRootChanged (root: NestedSet, old: NestedSet) {
    this.expanded = root.expanded !== old.expanded ? root.expanded : this.expanded
  }

  get hasChildren () {
    return this.root.hasChildren()
  }

  get rootChildren () {
    if (this.root.children.length) {
      return this.root.children
    }
    return this.root.loadChildren()
  }

  get expanded () {
    return this.$data._expanded as boolean
  }

  set expanded (value: boolean) {
    this.$data._expanded = value
  }

  mounted () {
    this.expanded = this.root.expanded ?? false
  }

  onDragStart (event: DragEvent) {
    event.stopPropagation()
    event.dataTransfer && event.dataTransfer.setData('itemId', this.root.id.toString())
    this.drag = true
  }

  onDragEnd (event: DragEvent) {
    event.stopPropagation()
    this.drag = false
  }

  onDragEnter (event: DragEvent) {
    event.stopPropagation()
  }

  onDragLeave (event: DragEvent) {
    event.preventDefault()
  }

  async onDrop (event: DragEvent) {
    event.stopPropagation()
    if (!this.droppable) {
      return
    }

    const itemId = event.dataTransfer && +event.dataTransfer.getData('itemId')

    if (itemId === this.root.id || !itemId) {
      return
    }

    const item = NestedSet.getById(itemId)

    if (item && item.parent_id === this.root.id) {
      return
    }

    try {
      await NestedSet.api().put(`nested-set/${itemId}`, {
        ...item,
        parent_id: this.root.id
      })
      this.$q.notify({
        type: 'positive',
        message: 'Done',
        timeout: 3000
      })
    } catch (e) {
      let message = ''
      if (e instanceof Error) {
        message = e.message
      }
      this.$q.notify({
        type: 'negative',
        message: message,
        timeout: 3000
      })
    }
    this.expanded = true
  }

  toggle () {
    this.expanded = !this.expanded
    if (this.expanded && !this.root.children.length) {
      this.root.loadChildren()
    }
  }
}
</script>

<style lang="scss" scoped>
.node-tree {
  padding: 0 4px;
  cursor: default;
}

.node-tree__dragover {
  background-color: #dba8a8;
}

.node-tree__expander-icon {
  transition: 0.25s transform;
}

.node-tree__expander-icon_90 {
  transform: rotate(90deg);
}

.node-tree__expander {
  display: inline-block;
  text-align: center;
  width: 16px;
}

.node-tree_pointer {
  cursor: pointer;
  transition: 0.5s background-color;
  border-radius: 4px;
  &:hover {
    background-color: #eeeeee;
  }
}

.node-tree__children {
  overflow: hidden;
  transition: 0.5s all;
  padding-left: 16px;
}

.node-tree__children_folded {
  height: 0px;
  opacity: 0;
}

.node-tree__children_expanded {
  height: auto;
  opacity: 1;
}
</style>
