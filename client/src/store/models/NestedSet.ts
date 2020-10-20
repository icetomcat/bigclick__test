import { Collection, Model } from '@vuex-orm/core'
import { BelongsToField, AttrField, OrmModel, StringField, PrimaryKey, HasManyField } from 'vuex-orm-decorators'

@OrmModel('nestedSet')
export default class NestedSet extends Model {
    @PrimaryKey() @AttrField() id!: number;
    // eslint-disable-next-line camelcase
    @AttrField() parent_id!: number | null;
    @AttrField() depth?: number;
    @StringField() title!: string;
    @BelongsToField(NestedSet, 'parent_id') parent!: NestedSet | null;
    @HasManyField(NestedSet, 'parent_id') children!: Collection<NestedSet>;
    public expanded = false

    static getById (id: string | number) {
      return NestedSet.query().whereId(id).first()
    }

    static getRootNode () {
      return NestedSet.query().where('parent_id', null).withAllRecursive().first()
    }

    hasChildren () {
      return this.children?.length || !!NestedSet.query().whereFk('parent_id', this.id).first()
    }

    loadChildren () {
      this.children = NestedSet.query().whereFk('parent_id', this.id).all()
      return this.children
    }
}
